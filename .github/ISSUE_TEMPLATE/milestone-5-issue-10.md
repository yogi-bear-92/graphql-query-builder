---
name: 🧪 Increase PHPUnit Coverage
about: Write comprehensive tests for all features to ensure reliability and easier refactoring
title: '[Testing] Increase PHPUnit Coverage'
labels: ['testing', 'quality', 'milestone-5']
assignees: ''
---

## Problem Description
Tests only cover basic functionality. Need comprehensive testing for all features to ensure reliability.

## Proposed Solution
Write comprehensive tests for all features with goal of 90%+ code coverage.

## Acceptance Criteria
- [ ] Test all new features (variable definitions, nested fragments, mutations)
- [ ] Add error case testing for all methods
- [ ] Achieve 90%+ code coverage
- [ ] Add integration tests with real GraphQL queries
- [ ] Test performance with large queries

## Test Categories to Add
1. **Unit Tests**
   - All public methods
   - Edge cases and error conditions
   - Variable handling and validation
   - Fragment processing
   
2. **Integration Tests**
   - Complete query building workflows
   - File loading and processing
   - Multi-operation scenarios
   
3. **Performance Tests**
   - Large query handling
   - Memory usage validation
   - Speed benchmarks

## Implementation Notes
- Use PHPUnit data providers for multiple test cases
- Mock file system operations where appropriate
- Test with various PHP versions in CI
- Consider using test doubles for external dependencies

## Testing Requirements
- [ ] All existing functionality covered
- [ ] New features have full test coverage
- [ ] Error scenarios tested
- [ ] Performance impact measured

## Documentation Updates
- [ ] Document testing approach
- [ ] Add contributing guidelines for tests
- [ ] Document how to run specific test suites

## Definition of Done
- [ ] All acceptance criteria met
- [ ] Code coverage target achieved
- [ ] All tests passing consistently
- [ ] Test documentation complete