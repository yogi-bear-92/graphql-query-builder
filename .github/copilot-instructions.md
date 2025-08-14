# GraphQL Query Builder for PHP

GraphQL Query Builder is a PHP library for programmatically building GraphQL queries from .gql files with support for variables and fragments. The repository contains a working PHP library with basic functionality.

**Always reference these instructions first and fallback to search or bash commands only when you encounter unexpected information that does not match the info here.**

## Working Effectively

### Current Repository State
The repository contains a functional PHP GraphQL query builder library with:
- Core `QueryBuilder` class in `src/QueryBuilder.php`
- PHPUnit tests in `tests/QueryBuilderTest.php`
- Example usage in `examples/` directory
- Composer configuration for PHP 8.0+ with PHPUnit

### Project Setup (For Development)
- Prerequisites verification:
  - `php --version` -- should return PHP 8.0+ (currently PHP 8.3.6 available)
  - `composer --version` -- should return Composer v2+ for dependency management
  - `git --version` -- should be available

- Initialize/setup the project:
  - `composer install` -- installs PHPUnit and other dev dependencies (~30-60 seconds)
  - `composer test` -- runs PHPUnit tests (~1-2 seconds)
  - `php examples/example.php` -- runs example usage script

### Build and Test Setup
- Running tests:
  - `composer test` or `vendor/bin/phpunit` -- runs all PHPUnit tests (~1-2 seconds)
  - Tests cover basic functionality: loading from string/file, variables, fragments
  - For development: `vendor/bin/phpunit --testdox` for verbose test output

- Code validation:
  - Basic PHP syntax: `php -l src/QueryBuilder.php` -- validates syntax
  - Run examples: `php examples/example.php` -- validates functionality

### Linting and Code Quality
- PHP CodeSniffer (when added):
  - `composer require --dev squizlabs/php_codesniffer` -- for PSR-12 code standards
  - `vendor/bin/phpcs --standard=PSR12 src/` -- check coding standards
  - `vendor/bin/phpcbf --standard=PSR12 src/` -- auto-fix coding standards

- Static Analysis (when added):
  - `composer require --dev phpstan/phpstan` -- for static analysis
  - `vendor/bin/phpstan analyse src/` -- check for type errors

## Validation

### Manual Testing Scenarios
Since this is a GraphQL query builder library for PHP, always test these scenarios after making changes:

1. **Basic Query Building**:
   - Test loading from .gql files: `$builder->loadFromFile('user.gql')`
   - Test loading from strings: `$builder->loadFromString('query { user { id } }')`
   - Test with variables: `$builder->withVariables(['id' => '123'])`

2. **Fragment Handling**:
   - Test simple fragments: `$builder->addFragment('UserFragment', 'id name email')`
   - Test fragment replacement in queries with `...FragmentName` syntax
   - Test multiple fragments in one query

3. **Current Limitations to Address**:
   - Variable definitions are not injected into query strings (only returned separately)
   - Fragment replacement uses simple string replacement (can cause issues in comments)
   - No support for mutations, aliases, or directives yet
   - No nested fragment support

4. **Integration Testing**:
   - Run `php examples/example.php` to verify all examples work
   - Test error cases like missing files or invalid fragment names

### Pre-commit Validation
Always run these commands before committing:
- `php -l src/QueryBuilder.php` -- ensure PHP syntax is valid
- `composer test` -- ensure all PHPUnit tests pass
- `php examples/example.php` -- verify examples work correctly
- Manual testing of at least one complete query building scenario

### CI/CD Considerations
When CI/CD pipelines are added (in .github/workflows/), they will likely include:
- PHP matrix testing (versions 8.0, 8.1, 8.2, 8.3)
- `composer install` for dependency installation
- `composer test` for running PHPUnit tests
- Code coverage reporting with PHPUnit --coverage

## Common Tasks

### Current PHP GraphQL Query Builder Architecture
```
├── src/
│   └── QueryBuilder.php       # Main query builder class
├── tests/
│   └── QueryBuilderTest.php   # PHPUnit tests
├── examples/
│   ├── user.gql              # Example GraphQL query file
│   ├── users.gql             # Example with fragments
│   └── example.php           # Usage examples
├── composer.json             # PHP dependencies and scripts
├── phpunit.xml              # PHPUnit configuration
└── readme.md                # Basic usage documentation
```

### Expected Composer.json Scripts
```json
{
  "scripts": {
    "test": "phpunit",
    "test-coverage": "phpunit --coverage-html coverage",
    "check-style": "phpcs --standard=PSR12 src/",
    "fix-style": "phpcbf --standard=PSR12 src/",
    "analyse": "phpstan analyse src/"
  }
}
```

### Development Workflow
1. `composer install` -- install dependencies
2. Make changes to source files in `src/`
3. Add/update tests in `tests/`
4. `composer test` -- run tests
5. `php examples/example.php` -- verify examples work
6. Run style checks before committing

## Troubleshooting

### Common Issues
- **PHP syntax errors**: Check with `php -l src/QueryBuilder.php`
- **Test failures**: Run `composer test` to see detailed PHPUnit output
- **Missing dependencies**: Run `composer install` to install PHPUnit
- **GraphQL syntax errors**: Validate .gql files manually or add validation to the builder

### Performance Considerations
- PHP class loading is generally fast
- File operations for .gql files are minimal overhead
- PHPUnit tests should run quickly (under 5 seconds)
- Fragment replacement is currently simple string replacement (may need optimization)

### Dependencies to Consider
Core dependencies that may be added:
- `webonyx/graphql-php` (for GraphQL parsing and validation)
- `symfony/finder` (for advanced file handling)

Dev dependencies commonly used:
- `phpunit/phpunit` (already included)
- `squizlabs/php_codesniffer` (for PSR-12 code standards)
- `phpstan/phpstan` (for static analysis)
- `friendsofphp/php-cs-fixer` (alternative to PHP_CodeSniffer)

## Repository Standards

### Code Style
- Use PHP 8.0+ features appropriately
- Follow PSR-12 coding standards
- Write comprehensive PHPUnit tests
- Include PHPDoc comments for public APIs
- Keep code simple and readable

### File Naming
- Use PascalCase for class names: `QueryBuilder`
- Use camelCase for method and variable names
- Test files should end with `Test.php`: `QueryBuilderTest.php`
- GraphQL files use `.gql` extension

### Git Workflow
- Create feature branches for new functionality
- Write descriptive commit messages
- Include tests for all new features
- Ensure all validation steps pass before creating PRs

---

**REMINDERS**:
- Always run `composer test` after making changes
- Verify examples still work with `php examples/example.php`
- This is a PHP project, not JavaScript/TypeScript
- Focus on simplicity and ease of use for PHP developers