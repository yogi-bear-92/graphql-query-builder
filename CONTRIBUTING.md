# Contributing to GraphQL Query Builder for PHP

Thank you for your interest in contributing! This document provides guidelines for contributing to the GraphQL Query Builder project.

## Development Setup

### Prerequisites
- PHP 8.0 or higher
- Composer 2.0 or higher
- Git

### Getting Started
1. Fork the repository
2. Clone your fork: `git clone https://github.com/YOUR_USERNAME/graphql-query-builder.git`
3. Install dependencies: `composer install`
4. Run tests to verify setup: `composer test`
5. Run examples: `php examples/example.php`

## Development Workflow

### Before Making Changes
1. Check existing issues and the [PROJECT_PLAN.md](PROJECT_PLAN.md) to understand planned work
2. Create an issue or comment on existing issues to discuss your proposed changes
3. Create a feature branch: `git checkout -b feature/your-feature-name`

### Making Changes
1. Follow PSR-12 coding standards
2. Add PHPDoc comments for all public methods
3. Write tests for new functionality
4. Update examples if your changes affect usage
5. Update documentation as needed

### Testing Your Changes
```bash
# Run all tests
composer test

# Check for syntax errors
php -l src/QueryBuilder.php

# Test examples still work
php examples/example.php

# Check code style (when configured)
composer check-style
```

### Submitting Changes
1. Ensure all tests pass
2. Commit with descriptive messages
3. Push to your fork
4. Create a pull request with:
   - Clear description of changes
   - Reference to related issues
   - Screenshots if UI/output is affected

## Code Style Guidelines

### PHP Standards
- Follow PSR-12 coding standards
- Use PHP 8.0+ features appropriately
- Keep methods focused and single-purpose
- Use meaningful variable and method names

### Documentation
- Add PHPDoc comments for all public methods
- Include parameter types and return types
- Provide usage examples in docblocks for complex methods
- Update README.md for new features

### Testing
- Write unit tests for all new functionality
- Include both positive and negative test cases
- Test error conditions and edge cases
- Maintain high test coverage (90%+)

## Project Structure

```
src/
├── QueryBuilder.php       # Main class
└── [future classes]       # Additional classes as needed

tests/
├── QueryBuilderTest.php   # Main test class
└── [additional tests]     # Feature-specific tests

examples/
├── *.gql                  # GraphQL query examples
├── example.php            # Basic usage examples
└── [advanced examples]    # Complex usage demonstrations
```

## Issue Guidelines

### Reporting Bugs
- Use the bug report template
- Include PHP version and environment details
- Provide minimal reproduction steps
- Include expected vs actual behavior

### Requesting Features
- Check the [PROJECT_PLAN.md](PROJECT_PLAN.md) for planned features
- Use appropriate issue templates for different milestone categories
- Provide clear use cases and benefits
- Consider implementation complexity

### Working on Issues
- Comment on issues before starting work
- Reference issues in commit messages
- Update issue status as you progress
- Ask questions if requirements are unclear

## Milestone-Based Development

The project follows a structured development plan with 6 milestones:

1. **Core Enhancements** - Essential query-building features
2. **Advanced Builder API** - Fluent API for programmatic construction
3. **Validation and Error Handling** - Robustness improvements
4. **Documentation and Examples** - Comprehensive guides and demos
5. **Testing and Quality** - Code quality and test coverage
6. **Release Preparation** - Packaging and distribution

See [PROJECT_PLAN.md](PROJECT_PLAN.md) for detailed information about each milestone.

## Code Review Process

### For Contributors
- Respond to feedback promptly
- Make requested changes in separate commits
- Keep pull requests focused on single features/fixes
- Be open to suggestions and alternative approaches

### For Reviewers
- Review code for functionality, style, and maintainability
- Test changes locally when possible
- Provide constructive feedback
- Approve when ready or request specific changes

## Community Guidelines

### Communication
- Be respectful and constructive in discussions
- Help newcomers understand the project
- Share knowledge and best practices
- Report inappropriate behavior to maintainers

### Recognition
- Contributors will be acknowledged in release notes
- Significant contributions may result in maintainer status
- All contributors are valued regardless of contribution size

## Development Resources

### GraphQL Resources
- [GraphQL Specification](https://spec.graphql.org/)
- [GraphQL PHP Library](https://github.com/webonyx/graphql-php)
- [GraphQL Query Language](https://graphql.org/learn/queries/)

### PHP Resources
- [PSR-12 Coding Standard](https://www.php-fig.org/psr/psr-12/)
- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [Composer Documentation](https://getcomposer.org/doc/)

### Project-Specific
- [Current Issues](https://github.com/yogi-bear-92/graphql-query-builder/issues)
- [Project Plan](PROJECT_PLAN.md)
- [API Documentation](src/QueryBuilder.php) (PHPDoc comments)

## Questions?

If you have questions about contributing:
- Check existing issues and documentation first
- Create a new issue with the "question" label
- Join discussions on existing issues
- Review the [PROJECT_PLAN.md](PROJECT_PLAN.md) for project direction

Thank you for contributing to GraphQL Query Builder for PHP!