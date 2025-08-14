---
name: 📚 Add Sample .gql Files and Scripts
about: Provide comprehensive example set demonstrating all library features
title: '[Documentation] Add Sample .gql Files and Scripts'
labels: ['documentation', 'examples', 'milestone-4']
assignees: ''
---

## Problem Description
Limited examples showing library capabilities make it difficult for users to understand how to use advanced features.

## Proposed Solution
Provide comprehensive example set with .gql files and demonstration scripts for all features.

## Acceptance Criteria
- [ ] Add mutation examples: `examples/create-user.gql`
- [ ] Show directive usage: `examples/conditional-fields.gql`
- [ ] Demonstrate nested fragments: `examples/complex-user.gql`
- [ ] Update example.php to show all new features
- [ ] Add separate script files for different use cases

## Example Files to Create
- `examples/mutations/create-user.gql` - User creation mutation
- `examples/mutations/update-post.gql` - Post update with variables
- `examples/fragments/user-fields.gql` - Reusable user fragment
- `examples/fragments/nested-comments.gql` - Nested fragment example
- `examples/directives/conditional-fields.gql` - @include/@skip usage
- `examples/complex/multi-operation.gql` - Multiple operations
- `examples/scripts/mutation-example.php` - Mutation demonstration
- `examples/scripts/fragment-example.php` - Fragment usage
- `examples/scripts/fluent-api-example.php` - Programmatic building

## Implementation Notes
- Use realistic, production-like examples
- Include comments explaining complex parts
- Show both file-based and programmatic approaches
- Demonstrate error handling

## Testing Requirements
- [ ] All example scripts must run without errors
- [ ] Verify .gql files have valid syntax
- [ ] Test examples with different PHP versions

## Documentation Updates
- [ ] Reference examples in README
- [ ] Add examples index file
- [ ] Document example file organization

## Definition of Done
- [ ] All acceptance criteria met
- [ ] All examples tested and working
- [ ] Examples documented and indexed
- [ ] Code reviewed and approved