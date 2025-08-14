---
name: 🚀 Support Multiple Operations per Request
about: Allow building and returning multiple operations in one GraphQL document
title: '[API] Support Multiple Operations per Request'
labels: ['enhancement', 'api', 'milestone-2']
assignees: ''
---

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

## Definition of Done
- [ ] All acceptance criteria met
- [ ] All tests passing
- [ ] Documentation updated
- [ ] Code reviewed and approved
- [ ] Examples demonstrate the feature