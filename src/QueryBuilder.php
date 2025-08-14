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
     * Load a fragment from a file
     */
    public function loadFragmentFromFile(string $name, string $filePath): self
    {
        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException("Fragment file not found: {$filePath}");
        }

        $content = file_get_contents($filePath);
        if ($content === false) {
            throw new \RuntimeException("Failed to read fragment file: {$filePath}");
        }

        $this->fragments[$name] = trim($content);
        return $this;
    }

    /**
     * Build the final query with variables and fragments
     */
    public function build(): array
    {
        $finalQuery = $this->query;

        // Replace fragment placeholders safely
        if (!empty($this->fragments)) {
            $finalQuery = $this->replaceFragmentsSafely($finalQuery);
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

    /**
     * Replace fragments safely, avoiding replacement in comments and string literals
     */
    private function replaceFragmentsSafely(string $query): string
    {
        $resolvedFragments = [];
        $maxIterations = 10; // Prevent infinite loops
        $iteration = 0;
        
        do {
            $replacementsMade = false;
            $iteration++;
            
            if ($iteration > $maxIterations) {
                throw new \RuntimeException('Circular fragment dependency detected or too many nested fragments');
            }
            
            foreach ($this->fragments as $name => $fragment) {
                $pattern = '/\.\.\.' . preg_quote($name, '/') . '\b/';
                $newQuery = $this->replaceFragmentPattern($query, $pattern, $fragment);
                
                if ($newQuery !== $query) {
                    $replacementsMade = true;
                    $query = $newQuery;
                }
            }
        } while ($replacementsMade);
        
        return $query;
    }

    /**
     * Replace fragment pattern while avoiding comments and string literals
     */
    private function replaceFragmentPattern(string $query, string $pattern, string $replacement): string
    {
        $result = '';
        $inString = false;
        $inComment = false;
        $stringChar = null;
        $i = 0;
        $length = strlen($query);
        
        while ($i < $length) {
            $char = $query[$i];
            $nextChar = ($i + 1 < $length) ? $query[$i + 1] : null;
            
            // Handle string literals
            if (!$inComment && ($char === '"' || $char === "'")) {
                if (!$inString) {
                    $inString = true;
                    $stringChar = $char;
                } elseif ($char === $stringChar && ($i === 0 || $query[$i - 1] !== '\\')) {
                    $inString = false;
                    $stringChar = null;
                }
                $result .= $char;
                $i++;
                continue;
            }
            
            // Handle comments
            if (!$inString && $char === '#') {
                $inComment = true;
                $result .= $char;
                $i++;
                continue;
            }
            
            // End of comment at newline
            if ($inComment && ($char === "\n" || $char === "\r")) {
                $inComment = false;
                $result .= $char;
                $i++;
                continue;
            }
            
            // If we're in a string or comment, just copy the character
            if ($inString || $inComment) {
                $result .= $char;
                $i++;
                continue;
            }
            
            // Check for fragment pattern at this position
            $remainingQuery = substr($query, $i);
            if (preg_match($pattern, $remainingQuery, $matches, PREG_OFFSET_CAPTURE)) {
                $matchStart = $matches[0][1];
                $matchLength = strlen($matches[0][0]);
                
                if ($matchStart === 0) {
                    // Found a match at the current position
                    $result .= $replacement;
                    $i += $matchLength;
                    continue;
                }
            }
            
            // No match, copy the character
            $result .= $char;
            $i++;
        }
        
        return $result;
    }
}