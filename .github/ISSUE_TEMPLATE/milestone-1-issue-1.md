---
name: 🔧 Add Variable Definition Support
about: Add support for GraphQL variable definitions and injection into query strings
title: '[Core] Add Variable Definition Support'
labels: ['enhancement', 'core', 'milestone-1']
assignees: ''
---

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

## Definition of Done
- [ ] All acceptance criteria met
- [ ] All tests passing
- [ ] Documentation updated
- [ ] Code reviewed and approved
- [ ] Examples demonstrate the feature