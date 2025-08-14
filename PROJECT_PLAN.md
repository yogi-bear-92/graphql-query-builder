# GraphQL Query Builder for PHP - Project Plan

## Current State

The GraphQL Query Builder for PHP is a functional library that provides basic GraphQL query building capabilities:

- **Core functionality**: Load GraphQL queries from `.gql` files or strings
- **Variable support**: Merge variables and return them alongside queries
- **Fragment support**: Simple fragment replacement using string substitution
- **API design**: Fluent interface with method chaining
- **Testing**: Basic PHPUnit test coverage
- **Requirements**: PHP 8.0+ with Composer

### Current Architecture

```
src/QueryBuilder.php      # Main class with core functionality
tests/QueryBuilderTest.php # PHPUnit tests covering basic scenarios
examples/                 # Sample .gql files and usage scripts
├── user.gql             # Basic query with variables
├── users.gql            # Query with fragment placeholder
└── example.php          # Usage demonstration
```

## Development Roadmap

This plan outlines 6 milestones with 13 specific issues to complete the GraphQL Query Builder project.

---

## Milestone 1 – Core Enhancements

**Goal**: Complete the essential query-building features

### Issue #1: Add Variable Definition Support
- **Problem**: Variables are only returned separately, not injected into query strings
- **Solution**: Enhance the builder to accept GraphQL variable definitions (types and default values) and inject them into the query's operation definition
- **Impact**: Enables complete query generation with proper variable declarations
- **Acceptance Criteria**:
  - Add method to define variable types: `$builder->defineVariable('id', 'ID!')`
  - Inject variable definitions into query string: `query($id: ID!) { ... }`
  - Maintain backward compatibility with current variable handling

### Issue #2: Support Nested Fragments and Imports
- **Problem**: Current fragment replacement uses simple string replacement which can cause issues
- **Solution**: Replace simple string replacement with proper GraphQL parsing for fragment handling
- **Impact**: Safer and more robust fragment replacement, support for nested fragments
- **Acceptance Criteria**:
  - Parse GraphQL to identify fragment placeholders safely
  - Support fragments that contain other fragments
  - Add method to load fragments from external files
  - Avoid accidental replacement in comments or string literals

### Issue #3: Implement Mutation and Alias Handling
- **Problem**: Library only supports queries, no mutation or alias support
- **Solution**: Extend the API to build mutations and allow aliasing fields and directives
- **Impact**: Complete GraphQL operation support beyond just queries
- **Acceptance Criteria**:
  - Support building mutations: `mutation { createUser(...) { id } }`
  - Add alias support: `admin: user(role: ADMIN) { name }`
  - Support basic directives: `@include(if: $condition)`
  - Add examples demonstrating new features

---

## Milestone 2 – Advanced Builder API

**Goal**: Provide a fluent API for programmatic query construction

### Issue #4: Design Fluent Query Builder Methods
- **Problem**: Can only load complete query strings, no programmatic building
- **Solution**: Add methods to incrementally add fields, arguments, and nested selections
- **Impact**: Enables building queries entirely in PHP without external .gql files
- **Acceptance Criteria**:
  - Add field selection methods: `$builder->addField('name')->addField('email')`
  - Support nested selections: `$builder->addObject('user')->addField('id')`
  - Add argument support: `$builder->addField('user', ['id' => '$userId'])`
  - Maintain fluent interface design

### Issue #5: Support Multiple Operations per Request
- **Problem**: Can only build one operation at a time
- **Solution**: Allow building and returning multiple operations in one call
- **Impact**: Support for complex GraphQL requests with multiple operations
- **Acceptance Criteria**:
  - Enable multiple named operations in one query string
  - Return array of operations when multiple are defined
  - Support mixing queries and mutations
  - Add examples showing multiple operation usage

---

## Milestone 3 – Validation and Error Handling

**Goal**: Improve robustness and developer experience

### Issue #6: GraphQL Syntax Validation
- **Problem**: No validation of GraphQL syntax when loading queries
- **Solution**: Integrate GraphQL parser/validator for syntax checking
- **Impact**: Better error messages and early detection of syntax issues
- **Acceptance Criteria**:
  - Add dependency on `webonyx/graphql-php` for parsing
  - Validate syntax when loading from string or file
  - Provide clear error messages for syntax issues
  - Add tests for various syntax error scenarios

### Issue #7: Enhanced Exception Messages
- **Problem**: Basic error messages for missing files and other issues
- **Solution**: Improve error messages for better developer experience
- **Impact**: Easier debugging and development
- **Acceptance Criteria**:
  - Detailed messages for file not found errors
  - Clear messages for unresolved fragment references
  - Helpful suggestions for common mistakes
  - Context information in all exceptions

---

## Milestone 4 – Documentation and Examples

**Goal**: Make the library easy to adopt

### Issue #8: Expand README with Advanced Examples
- **Problem**: Basic README doesn't cover advanced features
- **Solution**: Document all features with comprehensive examples
- **Impact**: Better adoption and easier onboarding for new users
- **Acceptance Criteria**:
  - Document variable definitions and type support
  - Show nested fragment examples
  - Demonstrate mutation building
  - Add installation and setup instructions
  - Include API reference for all public methods

### Issue #9: Add Sample .gql Files and Scripts
- **Problem**: Limited examples showing library capabilities
- **Solution**: Provide comprehensive example set
- **Impact**: Clear demonstration of all features
- **Acceptance Criteria**:
  - Add mutation examples: `examples/create-user.gql`
  - Show directive usage: `examples/conditional-fields.gql`
  - Demonstrate nested fragments: `examples/complex-user.gql`
  - Update example.php to show all new features

---

## Milestone 5 – Testing and Quality

**Goal**: Ensure code quality and stability

### Issue #10: Increase PHPUnit Coverage
- **Problem**: Tests only cover basic functionality
- **Solution**: Write comprehensive tests for all features
- **Impact**: Better reliability and easier refactoring
- **Acceptance Criteria**:
  - Test all new features (variable definitions, nested fragments, mutations)
  - Add error case testing
  - Achieve 90%+ code coverage
  - Add integration tests with real GraphQL queries

### Issue #11: Introduce Coding Standard Tooling
- **Problem**: No automated code style checking
- **Solution**: Add PHP_CodeSniffer for PSR-12 compliance
- **Impact**: Consistent code style and better maintainability
- **Acceptance Criteria**:
  - Add `squizlabs/php_codesniffer` dependency
  - Configure for PSR-12 standards
  - Add composer scripts for checking and fixing style
  - Fix any existing style issues

---

## Milestone 6 – Release Preparation

**Goal**: Prepare for distribution via Composer

### Issue #12: Finalize composer.json Metadata
- **Problem**: Basic composer.json missing important metadata
- **Solution**: Complete package information for Packagist publication
- **Impact**: Professional package ready for public use
- **Acceptance Criteria**:
  - Add complete description and keywords
  - Set appropriate license and author information
  - Add homepage and repository URLs
  - Set stable version number (1.0.0)

### Issue #13: Tag Stable Release and Publish on Packagist
- **Problem**: No tagged releases or public availability
- **Solution**: Create stable release and publish to Packagist
- **Impact**: Easy installation via Composer for all PHP developers
- **Acceptance Criteria**:
  - Create git tag for v1.0.0
  - Verify all tests pass
  - Submit to Packagist
  - Update installation instructions

---

## Implementation Guidelines

### Development Process
1. Each issue should be implemented in a feature branch
2. All changes must include appropriate tests
3. Run `composer test` and examples before committing
4. Update documentation as features are added
5. Maintain backward compatibility where possible

### Quality Standards
- Follow PSR-12 coding standards
- Maintain PHPUnit test coverage above 90%
- All public methods must have PHPDoc comments
- Examples must demonstrate real-world usage
- Error messages must be helpful and actionable

### Dependencies
- **Core**: `php: >=8.0`
- **Validation**: `webonyx/graphql-php` (for GraphQL parsing)
- **Development**: `phpunit/phpunit`, `squizlabs/php_codesniffer`
- **Optional**: `phpstan/phpstan` (static analysis)

---

## Timeline Estimate

- **Milestone 1**: 2-3 weeks (core functionality enhancement)
- **Milestone 2**: 2-3 weeks (advanced API development)
- **Milestone 3**: 1-2 weeks (validation and error handling)
- **Milestone 4**: 1-2 weeks (documentation and examples)
- **Milestone 5**: 1-2 weeks (testing and quality)
- **Milestone 6**: 1 week (release preparation)

**Total estimated time**: 8-13 weeks

This plan transforms the existing prototype into a robust, feature-complete GraphQL query builder for PHP suitable for production use.