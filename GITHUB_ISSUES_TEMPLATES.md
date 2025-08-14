# GitHub Issues Template Reference

This document provides ready-to-use templates for creating the 13 specific issues outlined in the PROJECT_PLAN.md.

## Milestone 1 - Core Enhancements

### Issue #1: Add Variable Definition Support

**Title**: [Core] Add Variable Definition Support

**Labels**: enhancement, core, milestone-1

**Body**:
```markdown
## Problem Description
Currently, variables are only returned separately in the build() result array, not injected into the query string itself. This means the generated queries are incomplete and require manual variable definition.

## Proposed Solution
Enhance the builder to accept GraphQL variable definitions (types and default values) and inject them into the query's operation definition.

## Acceptance Criteria
- [ ] Add `defineVariable($name, $type, $defaultValue = null)` method
- [ ] Inject variable definitions into query string: `query($id: ID!) { ... }`
- [ ] Support multiple variable definitions
- [ ] Maintain backward compatibility with current variable handling
- [ ] Support both nullable and non-nullable types

## Implementation Notes
- Parse existing query to identify operation type (query/mutation)
- Inject variable definitions after operation name but before selection set
- Handle cases where query already has variable definitions

## Testing Requirements
- [ ] Unit tests for defineVariable method
- [ ] Test variable injection into various query types
- [ ] Test backward compatibility with existing withVariables usage
- [ ] Test edge cases (empty variables, duplicate names)

## Documentation Updates
- [ ] PHPDoc comments for new methods
- [ ] README examples showing variable definition usage
- [ ] Update examples/example.php with variable definitions
```

---

### Issue #2: Support Nested Fragments and Imports

**Title**: [Core] Support Nested Fragments and Imports  

**Labels**: enhancement, core, milestone-1

**Body**:
```markdown
## Problem Description
Current fragment replacement uses simple string replacement (`str_replace`) which can cause issues with fragments in comments, string literals, or nested fragment scenarios.

## Proposed Solution
Replace simple string replacement with proper GraphQL parsing for safe fragment handling and support for nested fragments.

## Acceptance Criteria
- [ ] Parse GraphQL to identify fragment placeholders safely
- [ ] Support fragments that contain other fragment references
- [ ] Add `loadFragmentFromFile($name, $filePath)` method
- [ ] Avoid accidental replacement in comments or string literals
- [ ] Support fragment imports from external files

## Implementation Notes
- Consider using `webonyx/graphql-php` for parsing
- Implement recursive fragment resolution
- Handle circular fragment dependencies gracefully

## Testing Requirements
- [ ] Test nested fragment scenarios
- [ ] Test fragments in comments (should not be replaced)
- [ ] Test fragment imports from files
- [ ] Test circular dependency detection

## Documentation Updates
- [ ] Examples showing nested fragment usage
- [ ] Documentation for fragment import methods
- [ ] Best practices for fragment organization
```

---

### Issue #3: Implement Mutation and Alias Handling

**Title**: [Core] Implement Mutation and Alias Handling

**Labels**: enhancement, core, milestone-1

**Body**:
```markdown
## Problem Description
Library currently only supports query operations. No support for mutations, field aliases, or directives.

## Proposed Solution
Extend the API to build mutations and support aliasing fields and basic directives.

## Acceptance Criteria
- [ ] Support building mutations: `mutation { createUser(...) { id } }`
- [ ] Add alias support: `admin: user(role: ADMIN) { name }`
- [ ] Support basic directives: `@include(if: $condition)`, `@skip(if: $condition)`
- [ ] Detect operation type automatically
- [ ] Add examples demonstrating new features

## Implementation Notes
- Extend current query detection to handle mutations
- Add methods for alias specification
- Consider directive syntax and validation

## Testing Requirements
- [ ] Test mutation building
- [ ] Test field aliasing
- [ ] Test directive usage
- [ ] Test mixed query/mutation scenarios

## Documentation Updates
- [ ] Mutation examples in README
- [ ] Alias usage documentation
- [ ] Directive usage examples
```

## Milestone 2 - Advanced Builder API

### Issue #4: Design Fluent Query Builder Methods

**Title**: [API] Design Fluent Query Builder Methods

**Labels**: enhancement, api, milestone-2

**Body**:
```markdown
## Problem Description
Currently can only load complete query strings from files or strings. No way to build queries programmatically in PHP code.

## Proposed API Design
Add methods to incrementally build GraphQL queries using a fluent interface.

## Acceptance Criteria
- [ ] Add field selection: `$builder->field('name')->field('email')`
- [ ] Support nested selections: `$builder->object('user')->field('id')->end()`
- [ ] Add arguments: `$builder->field('user', ['id' => '$userId'])`
- [ ] Support aliases: `$builder->field('name', [], 'userName')`
- [ ] Maintain fluent interface design
- [ ] Allow mixing with current file/string loading

## API Examples
```php
$builder = new QueryBuilder();
$result = $builder
    ->query('GetUser')
    ->field('user', ['id' => '$userId'])
        ->field('id')
        ->field('name')
        ->field('email')
        ->object('profile')
            ->field('avatar')
            ->field('bio')
        ->end()
    ->end()
    ->defineVariable('userId', 'ID!')
    ->build();
```

## Implementation Notes
- Use builder pattern with method chaining
- Track nesting level for proper query structure
- Consider performance implications of method chaining

## Testing Requirements
- [ ] Test all new API methods
- [ ] Test nested object building
- [ ] Test integration with existing functionality
- [ ] Test complex query scenarios

## Documentation Updates
- [ ] Comprehensive API reference
- [ ] Migration guide from file-based to API-based building
- [ ] Best practices for query construction
```

---

### Issue #5: Support Multiple Operations per Request

**Title**: [API] Support Multiple Operations per Request

**Labels**: enhancement, api, milestone-2

**Body**:
```markdown
## Problem Description
Can only build one operation at a time. GraphQL supports multiple named operations in a single request.

## Proposed Solution
Allow building and returning multiple operations in one GraphQL document.

## Acceptance Criteria
- [ ] Support multiple named operations: `query GetUser {...} mutation CreatePost {...}`
- [ ] Return appropriate structure for multiple operations
- [ ] Support mixing queries and mutations
- [ ] Add method to specify operation name
- [ ] Provide examples showing usage

## API Examples
```php
$builder = new QueryBuilder();
$result = $builder
    ->operation('query', 'GetUser')
    ->field('user', ['id' => '$userId'])
        ->field('id')
        ->field('name')
    ->end()
    ->operation('mutation', 'CreatePost')
    ->field('createPost', ['input' => '$postInput'])
        ->field('id')
        ->field('title')
    ->end()
    ->build();
```

## Implementation Notes
- Modify internal structure to handle multiple operations
- Consider how to handle variables across operations
- Update build() method return format

## Testing Requirements
- [ ] Test multiple query operations
- [ ] Test mixed query/mutation operations
- [ ] Test variable handling across operations
- [ ] Test operation naming

## Documentation Updates
- [ ] Multiple operation examples
- [ ] Use cases for multiple operations
- [ ] Best practices and limitations
```

## Usage Instructions

To create these issues in GitHub:

1. Go to your repository's Issues section
2. Click "New Issue"
3. Select the appropriate template (if templates are configured)
4. Copy the title and body content from above
5. Add the specified labels
6. Assign to appropriate milestone
7. Submit the issue

Each issue follows the established template format and includes all necessary information for implementation.