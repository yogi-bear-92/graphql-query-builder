---
name: 🚀 Design Fluent Query Builder Methods
about: Add programmatic query building with fluent interface methods
title: '[API] Design Fluent Query Builder Methods'
labels: ['enhancement', 'api', 'milestone-2']
assignees: ''
---

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

## Definition of Done
- [ ] All acceptance criteria met
- [ ] All tests passing
- [ ] Documentation updated
- [ ] Code reviewed and approved
- [ ] Examples demonstrate the feature