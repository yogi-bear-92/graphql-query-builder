# GitHub Issues Template Reference

This document provides ready-to-use templates for creating the 8 specific issues outlined in the updated PROJECT_PLAN.md.

**Important**: Most core features from the original plan are already implemented. These templates focus on the remaining work for code quality, documentation, and release preparation.

## ✅ Already Implemented (No Issues Needed)
- Variable definition support with `defineVariable()`
- Nested fragments and safe replacement  
- Fragment file imports with `loadFragmentFromFile()`
- Mutation and alias handling with fluent API
- Comprehensive fluent query builder methods
- Multiple operation support (query, mutation, subscription)
- Directive support (@include, @skip)
- Comprehensive test coverage (44 tests passing)

## 🔄 Remaining Work (Issues to Create)

## Milestone 1 - Code Quality and Standards

### Issue #1: Introduce PSR-12 Coding Standards

**Title**: [Quality] Introduce PSR-12 Coding Standards

**Labels**: enhancement, quality, milestone-1

**Body**:
```markdown
## Problem Description
Currently there is no automated code style checking, which can lead to inconsistent code formatting and style across the project.

## Proposed Solution
Add PHP CodeSniffer or PHP CS Fixer to enforce PSR-12 coding standards automatically.

## Acceptance Criteria
- [ ] Add coding standard tool to composer.json dev dependencies
- [ ] Configure PSR-12 ruleset
- [ ] Fix any existing style violations in current code
- [ ] Add composer scripts: `composer check-style` and `composer fix-style`
- [ ] Update CONTRIBUTING.md with style guidelines

## Implementation Notes
- Choose between `squizlabs/php_codesniffer` or `friendsofphp/php-cs-fixer`
- Configure to check `src/` and `tests/` directories
- Add phpcs.xml or .php-cs-fixer.php configuration file

## Testing Requirements
- [ ] Verify all existing code passes style checks
- [ ] Document style check process in README
- [ ] Ensure CI will run style checks (when implemented)

## Documentation Updates
- [ ] Add style checking instructions to README
- [ ] Update development workflow documentation
```

---

### Issue #2: Add Static Analysis Tools

**Title**: [Quality] Add Static Analysis Tools  

**Labels**: enhancement, quality, milestone-1

**Body**:
```markdown
## Problem Description
No static analysis is performed on the codebase, which could miss type-related errors and potential bugs before runtime.

## Proposed Solution
Integrate PHPStan or Psalm for static analysis to catch type errors and potential issues early.

## Acceptance Criteria
- [ ] Add PHPStan to dev dependencies
- [ ] Configure appropriate analysis level (start with level 6+)
- [ ] Fix any detected issues in current codebase
- [ ] Add composer script: `composer analyse`
- [ ] Configure phpstan.neon with project-specific rules

## Implementation Notes
- Start with reasonable analysis level, can be increased over time
- Focus on type coverage and potential null pointer issues
- Consider adding psalm as alternative/additional tool

## Testing Requirements
- [ ] All current code passes static analysis
- [ ] Document analysis process for contributors
- [ ] Add baseline file if needed for gradual adoption

## Documentation Updates
- [ ] Add static analysis instructions to development docs
- [ ] Document how to handle analysis issues
```

---

## Milestone 2 - Enhanced Documentation

### Issue #3: Expand README with Complete API Documentation

**Title**: [Documentation] Expand README with Complete API Documentation

**Labels**: documentation, milestone-2

**Body**:
```markdown
## Problem Description
Current README only covers basic usage and doesn't document all the implemented features like variable definitions, fragments, aliases, directives, and fluent API.

## Proposed Solution
Create comprehensive API documentation with examples covering all features that are currently implemented.

## Acceptance Criteria
- [ ] Document all public methods with usage examples
- [ ] Add comprehensive installation and quick start guide
- [ ] Include fluent API examples and patterns
- [ ] Document variable definitions with type examples
- [ ] Show fragment usage including file imports and nested fragments
- [ ] Document aliases and directive usage
- [ ] Add troubleshooting section with common issues
- [ ] Add performance considerations and best practices

## Implementation Notes
- Use clear, copy-pasteable examples
- Organize content logically (basic → advanced)
- Include both .gql file and fluent API approaches
- Add table of contents for easy navigation

## Testing Requirements
- [ ] All code examples in README are tested and working
- [ ] Examples cover real-world use cases
- [ ] Documentation matches current API exactly

## Documentation Updates
- [ ] Complete rewrite of README.md
- [ ] Add API reference section
- [ ] Include migration guide if API changes
```

---

### Issue #4: Create Advanced Usage Examples

**Title**: [Documentation] Create Advanced Usage Examples

**Labels**: documentation, examples, milestone-2

**Body**:
```markdown
## Problem Description
Current examples are basic and don't showcase the full capabilities of the library, particularly advanced features like complex mutations, error handling, and real-world patterns.

## Proposed Solution
Add comprehensive example files demonstrating advanced usage patterns and real-world scenarios.

## Acceptance Criteria
- [ ] Add mutation examples with complex input types
- [ ] Show subscription usage patterns  
- [ ] Demonstrate error handling scenarios
- [ ] Add real-world examples (pagination, filtering, sorting)
- [ ] Show integration with popular GraphQL endpoints
- [ ] Add performance optimization examples
- [ ] Include examples for different architectural patterns

## Implementation Notes
- Create separate example files for different scenarios
- Use realistic data structures and operations
- Include both success and error cases
- Add comments explaining decision-making

## Testing Requirements
- [ ] All examples run without errors
- [ ] Examples demonstrate best practices
- [ ] Include examples that show common pitfalls to avoid

## Documentation Updates
- [ ] New example files in examples/ directory
- [ ] Update main example.php with advanced scenarios
- [ ] Document when to use each pattern
```

---

## Milestone 3 - Enhanced Validation and Error Handling

### Issue #5: GraphQL Syntax Validation

**Title**: [Enhancement] GraphQL Syntax Validation

**Labels**: enhancement, validation, milestone-3

**Body**:
```markdown
## Problem Description
Currently no validation of GraphQL syntax when loading queries from files or strings, which can lead to runtime errors when queries are used.

## Proposed Solution
Integrate GraphQL parser for syntax validation with clear error messages.

## Acceptance Criteria
- [ ] Validate GraphQL syntax when loading from files or strings
- [ ] Provide clear error messages for syntax issues
- [ ] Add optional strict mode for enhanced validation
- [ ] Consider integrating `webonyx/graphql-php` for parsing
- [ ] Add tests for various syntax error scenarios

## Implementation Notes
- Add syntax validation as optional feature (enabled by default)
- Provide option to disable for performance if needed
- Focus on parse errors rather than schema validation

## Testing Requirements
- [ ] Test various GraphQL syntax errors
- [ ] Test valid GraphQL queries pass validation
- [ ] Test error message clarity and usefulness

## Documentation Updates
- [ ] Document validation features and options
- [ ] Add troubleshooting guide for common syntax issues
```

---

### Issue #6: Enhanced Error Messages and Logging

**Title**: [Enhancement] Enhanced Error Messages and Logging

**Labels**: enhancement, error-handling, milestone-3

**Body**:
```markdown
## Problem Description
Current error messages could be more helpful for debugging, and there's no logging capability for troubleshooting issues.

## Proposed Solution
Improve exception messages and add optional PSR-3 compatible logging.

## Acceptance Criteria
- [ ] Enhance error messages with context and suggestions
- [ ] Add optional PSR-3 compatible logging support
- [ ] Improve file operation error handling
- [ ] Add debug mode for detailed information
- [ ] Include file names and line numbers where applicable

## Implementation Notes
- Use PSR-3 LoggerInterface as optional dependency
- Add context to all exceptions
- Consider adding debug mode for verbose output

## Testing Requirements
- [ ] Test improved error messages provide useful information
- [ ] Test logging functionality if implemented
- [ ] Test error handling in various failure scenarios

## Documentation Updates
- [ ] Document error handling best practices
- [ ] Add troubleshooting section with common issues
```

---

## Milestone 4 - Release and Distribution

### Issue #7: Finalize Composer Package Metadata

**Title**: [Release] Finalize Composer Package Metadata

**Labels**: release, packaging, milestone-4

**Body**:
```markdown
## Problem Description
Current composer.json needs enhancement for professional package distribution on Packagist.

## Proposed Solution
Add complete metadata and prepare package for publication.

## Acceptance Criteria
- [ ] Add comprehensive description and keywords
- [ ] Add author information and homepage URL
- [ ] Configure proper version constraints for dependencies
- [ ] Add suggest section for optional dependencies
- [ ] Prepare CHANGELOG.md with version history
- [ ] Set up proper semantic versioning

## Implementation Notes
- Use semantic versioning (start with 1.0.0)
- Add appropriate keywords for discoverability
- Include links to documentation and repository

## Testing Requirements
- [ ] Verify composer.json is valid
- [ ] Test package installation from local path
- [ ] Ensure all metadata is accurate

## Documentation Updates
- [ ] Create CHANGELOG.md
- [ ] Update README with installation instructions
- [ ] Add badges for version, tests, etc.
```

---

### Issue #8: Continuous Integration Setup

**Title**: [Infrastructure] Continuous Integration Setup

**Labels**: ci, infrastructure, milestone-4

**Body**:
```markdown
## Problem Description
No automated testing on multiple PHP versions or automated quality checks.

## Proposed Solution
Create GitHub Actions workflow for testing and quality assurance.

## Acceptance Criteria
- [ ] Create workflow testing PHP 8.0, 8.1, 8.2, 8.3
- [ ] Run PHPUnit tests on all supported versions
- [ ] Run coding standards checks (when implemented)
- [ ] Run static analysis (when implemented)
- [ ] Add status badges to README
- [ ] Set up proper branch protection rules

## Implementation Notes
- Use GitHub Actions for CI/CD
- Test on multiple operating systems if needed
- Cache dependencies for faster builds

## Testing Requirements
- [ ] All tests pass on all PHP versions
- [ ] CI workflow completes successfully
- [ ] Status badges reflect actual build status

## Documentation Updates
- [ ] Add CI status badges to README
- [ ] Document contribution workflow including CI requirements
```

---

## Usage Instructions

To create these issues in GitHub:

1. Go to your repository's Issues section
2. Click "New Issue"
3. Copy the title and body content from above
4. Add the specified labels
5. Assign to appropriate milestone (create milestones first)
6. Submit the issue

Each issue focuses on realistic remaining work rather than features that are already implemented.