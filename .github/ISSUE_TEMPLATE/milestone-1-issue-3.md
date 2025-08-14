---
name: 🔧 Implement Mutation and Alias Handling
about: Extend API to support mutations, field aliases, and basic directives
title: '[Core] Implement Mutation and Alias Handling'
labels: ['enhancement', 'core', 'milestone-1']
assignees: ''
---

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

## Definition of Done
- [ ] All acceptance criteria met
- [ ] All tests passing
- [ ] Documentation updated
- [ ] Code reviewed and approved
- [ ] Examples demonstrate the feature