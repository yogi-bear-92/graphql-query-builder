# GraphQL Query Builder for PHP

A comprehensive GraphQL query builder for PHP that supports loading queries from `.gql` files, programmatic query building, variable definitions, fragments, mutations, and advanced features like aliases and directives.

## ✅ Features

- **Query Loading**: Load GraphQL queries from `.gql` files or strings
- **Variable Definitions**: Full support for GraphQL variable definitions with types and defaults
- **Fragment Support**: Safe fragment replacement with nested fragments and file imports
- **Fluent API**: Complete programmatic query building without external files
- **Operations**: Support for queries, mutations, and subscriptions
- **Advanced Features**: Field aliases, directives (@include, @skip), complex nested structures
- **Error Handling**: Comprehensive error handling for file operations and query issues

## Installation

```bash
composer require yogi-bear-92/graphql-query-builder
```

## Quick Start

### Basic Usage with .gql Files

```php
use GraphQLQueryBuilder\QueryBuilder;

$builder = new QueryBuilder();
$result = $builder->loadFromFile('queries/user.gql')
    ->withVariables(['id' => '123'])
    ->build();

echo $result['query'];
// Variables: $result['variables']
```

### Variable Definitions

```php
$result = $builder
    ->loadFromString('query { user(id: $userId) { name } }')
    ->defineVariable('userId', 'ID!')
    ->withVariables(['userId' => '123'])
    ->build();

// Output: query($userId: ID!) { user(id: $userId) { name } }
```

### Fragments

```php
$result = $builder
    ->loadFromString('query { user { ...UserFragment } }')
    ->addFragment('UserFragment', 'id name email')
    ->build();

// Fragment replacement with safe parsing
```

### Fluent API

```php
$result = $builder
    ->query('GetUser')
    ->defineVariable('id', 'ID!')
    ->object('user', ['id' => '$id'])
        ->field('id')
        ->field('name')
        ->field('email')
        ->object('profile')
            ->field('avatar')
            ->field('bio')
        ->end()
    ->end()
    ->withVariables(['id' => 'user-123'])
    ->build();
```

### Mutations

```php
$result = $builder
    ->mutation('CreateUser')
    ->defineVariable('input', 'CreateUserInput!')
    ->field('createUser', ['input' => '$input'])
        ->field('id')
        ->field('name')
    ->end()
    ->build();
```

### Aliases and Directives

```php
$result = $builder
    ->loadFromString('query { user { id name email } }')
    ->addAlias('admin', 'user', ['role' => 'ADMIN'])
    ->addDirective('name', 'include', ['if' => '$showName'])
    ->build();
```

## Advanced Features

### Fragment File Imports

```php
$builder->loadFragmentFromFile('UserFragment', 'fragments/user.gql');
```

### Nested Fragments

```php
$builder
    ->addFragment('UserWithProfile', 'id name ...ProfileFragment')
    ->addFragment('ProfileFragment', 'avatar bio website');
```

### Multiple Operations

```php
$result = $builder
    ->query('GetUser')
        ->field('user', ['id' => '$userId'])
            ->field('name')
        ->end()
    ->end()
    ->mutation('UpdateUser')
        ->field('updateUser', ['id' => '$userId', 'input' => '$input'])
            ->field('id')
        ->end()
    ->end()
    ->build();
```

## Testing

This library includes comprehensive test coverage:

```bash
# Run tests
composer test

# Run examples to see all features
php examples/example.php
php examples/fluent_example.php
```

**Test Coverage**: 44 tests, 93 assertions, 100% passing

## API Reference

### Core Methods

- `loadFromFile(string $filePath)` - Load query from .gql file
- `loadFromString(string $query)` - Load query from string
- `withVariables(array $variables)` - Set query variables
- `build()` - Build final query and variables array
- `reset()` - Reset builder for reuse

### Variable Definitions

- `defineVariable(string $name, string $type, $defaultValue = null)` - Define variable with type

### Fragments

- `addFragment(string $name, string $fragment)` - Add fragment
- `loadFragmentFromFile(string $name, string $filePath)` - Load fragment from file

### Advanced Features

- `addAlias(string $alias, string $field, array $arguments = [])` - Add field alias
- `addDirective(string $field, string $directive, array $arguments = [])` - Add directive

### Fluent API

- `query(?string $name)` - Start query operation
- `mutation(?string $name)` - Start mutation operation
- `subscription(?string $name)` - Start subscription operation
- `field(string $name, array $arguments = [])` - Add field
- `object(string $name, array $arguments = [])` - Start nested object
- `end()` - End current nesting level

## Examples

See the `examples/` directory for comprehensive usage examples:

- `examples/example.php` - Basic usage with files, fragments, variables
- `examples/fluent_example.php` - Fluent API examples
- `examples/user.gql` - Sample query file
- `examples/users.gql` - Sample query with fragments

## Requirements

- PHP 8.0 or higher
- Composer for dependency management

## Contributing

1. Fork the repository
2. Create a feature branch
3. Add tests for new functionality
4. Ensure all tests pass: `composer test`
5. Submit a pull request

## Current Status

This library is **feature-complete** and production-ready with:
- ✅ All core GraphQL query building features
- ✅ Advanced features (fragments, variables, mutations, etc.)  
- ✅ Comprehensive test coverage
- ✅ Working examples

Remaining work focuses on code quality, enhanced documentation, and release preparation.

## License

MIT
