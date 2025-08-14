---
name: 🧪 Introduce Coding Standard Tooling
about: Add PHP_CodeSniffer for PSR-12 compliance and consistent code style
title: '[Testing] Introduce Coding Standard Tooling'
labels: ['testing', 'quality', 'milestone-5']
assignees: ''
---

## Problem Description
No automated code style checking leads to inconsistent code style and harder maintenance.

## Proposed Solution
Add PHP_CodeSniffer for PSR-12 compliance and create composer scripts for easy usage.

## Acceptance Criteria
- [ ] Add `squizlabs/php_codesniffer` dependency
- [ ] Configure for PSR-12 standards
- [ ] Add composer scripts for checking and fixing style
- [ ] Fix any existing style issues
- [ ] Update CI to check code style

## Implementation Steps
1. Add composer dependency: `composer require --dev squizlabs/php_codesniffer`
2. Create `phpcs.xml` configuration file
3. Add composer scripts:
   ```json
   {
     "scripts": {
       "check-style": "phpcs --standard=PSR12 src/",
       "fix-style": "phpcbf --standard=PSR12 src/"
     }
   }
   ```
4. Fix existing style violations
5. Document usage in README

## Configuration Requirements
- Use PSR-12 coding standard
- Check `src/` and `tests/` directories
- Exclude vendor and other build directories
- Set appropriate error reporting level

## Testing Requirements
- [ ] Verify all existing code passes style check
- [ ] Test that style fixes work correctly
- [ ] Ensure CI integration works

## Documentation Updates
- [ ] Add style checking to README
- [ ] Document coding standards in CONTRIBUTING.md
- [ ] Add pre-commit hook recommendations

## Definition of Done
- [ ] All acceptance criteria met
- [ ] No style violations in codebase
- [ ] CI includes style checking
- [ ] Documentation updated