---
name: 🔧 Support Nested Fragments and Imports
about: Replace string replacement with proper GraphQL parsing for safe fragment handling
title: '[Core] Support Nested Fragments and Imports'
labels: ['enhancement', 'core', 'milestone-1']
assignees: ''
---

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

## Definition of Done
- [ ] All acceptance criteria met
- [ ] All tests passing
- [ ] Documentation updated
- [ ] Code reviewed and approved
- [ ] Examples demonstrate the feature