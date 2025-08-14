<?php

namespace GraphQLQueryBuilder;

/**
 * Main GraphQL Query Builder class
 * Loads .gql files and builds queries with reusable parts
 */
class QueryBuilder
{
    private string $query = '';
    private array $variables = [];
    private array $fragments = [];
    private array $variableDefinitions = [];

    /**
     * Load GraphQL query from a .gql file
     */
    public function loadFromFile(string $filePath): self
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("GraphQL file not found: {$filePath}");
        }

        $content = file_get_contents($filePath);
        if ($content === false) {
            throw new \RuntimeException("Failed to read GraphQL file: {$filePath}");
        }

        $this->query = trim($content);
        return $this;
    }

    /**
     * Load GraphQL query from string
     */
    public function loadFromString(string $query): self
    {
        $this->query = trim($query);
        return $this;
    }

    /**
     * Set variables for the query
     */
    public function withVariables(array $variables): self
    {
        $this->variables = array_merge($this->variables, $variables);
        return $this;
    }

    /**
     * Define a variable with its type and optional default value
     * 
     * This method adds variable definitions that will be injected into the query
     * operation definition. For example: query($id: ID!, $limit: Int = 10) { ... }
     * 
     * @param string $name Variable name (without the $ prefix)
     * @param string $type GraphQL type (e.g., 'ID!', 'String', 'Int', '[String!]!')
     * @param mixed $defaultValue Optional default value for the variable
     * @return self Returns this instance for method chaining
     */
    public function defineVariable(string $name, string $type, $defaultValue = null): self
    {
        $this->variableDefinitions[$name] = [
            'type' => $type,
            'defaultValue' => $defaultValue,
            'hasDefaultValue' => func_num_args() >= 3
        ];
        return $this;
    }

    /**
     * Add a reusable fragment
     */
    public function addFragment(string $name, string $fragment): self
    {
        $this->fragments[$name] = $fragment;
        return $this;
    }

    /**
     * Build the final query with variables and fragments
     */
    public function build(): array
    {
        $finalQuery = $this->query;

        // Replace fragment placeholders
        foreach ($this->fragments as $name => $fragment) {
            $finalQuery = str_replace("...{$name}", $fragment, $finalQuery);
        }

        // Inject variable definitions if any are defined and query doesn't already have them
        if (!empty($this->variableDefinitions)) {
            $finalQuery = $this->injectVariableDefinitions($finalQuery);
        }

        return [
            'query' => $finalQuery,
            'variables' => $this->variables
        ];
    }

    /**
     * Get the raw query string
     */
    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * Get the variables
     */
    public function getVariables(): array
    {
        return $this->variables;
    }

    /**
     * Reset the builder state
     */
    public function reset(): self
    {
        $this->query = '';
        $this->variables = [];
        $this->fragments = [];
        $this->variableDefinitions = [];
        return $this;
    }

    /**
     * Inject variable definitions into the query
     */
    private function injectVariableDefinitions(string $query): string
    {
        // Build the variable definitions string
        $definitions = [];
        foreach ($this->variableDefinitions as $name => $definition) {
            $varDef = '$' . $name . ': ' . $definition['type'];
            if ($definition['hasDefaultValue']) {
                $varDef .= ' = ' . $this->formatDefaultValue($definition['defaultValue']);
            }
            $definitions[] = $varDef;
        }
        
        $variableString = '(' . implode(', ', $definitions) . ')';
        
        // Check if query already has variable definitions
        if (preg_match('/^(\s*)(\w+)(\s*\([^)]*\))?\s*{/', $query, $matches)) {
            // Query already has variable definitions or is ready for them
            if (isset($matches[3])) {
                // Already has variable definitions, don't modify
                return $query;
            } else {
                // Insert variable definitions after operation type and name
                return preg_replace(
                    '/^(\s*)(\w+)(\s*)({)/',
                    '$1$2' . $variableString . '$3$4',
                    $query
                );
            }
        }
        
        return $query;
    }

    /**
     * Format default value for GraphQL
     */
    private function formatDefaultValue($value): string
    {
        if (is_string($value)) {
            return '"' . addslashes($value) . '"';
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        } elseif (is_null($value)) {
            return 'null';
        } else {
            return (string) $value;
        }
    }
}