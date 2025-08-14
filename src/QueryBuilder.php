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
        return $this;
    }
}