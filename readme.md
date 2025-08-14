# GraphQL Query Builder for PHP

A simple GraphQL query builder for PHP that uses `.gql` files to build queries with reusable parts for any entity.

## Features

- Load GraphQL queries from `.gql` files
- Build reusable query fragments
- Support for variables and parameters
- Simple PHP API for query construction

## Installation

```bash
composer require yogi-bear-92/graphql-query-builder
```

## Usage

```php
use GraphQLQueryBuilder\QueryBuilder;

$builder = new QueryBuilder();
$query = $builder->loadFromFile('queries/user.gql')
    ->withVariables(['id' => 123])
    ->build();
```

## Requirements

- PHP 8.0 or higher

## License

MIT
