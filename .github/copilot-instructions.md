# GraphQL Query Builder

GraphQL Query Builder is a JavaScript/TypeScript library for programmatically building GraphQL queries. The repository is currently in early development stage with minimal structure.

**Always reference these instructions first and fallback to search or bash commands only when you encounter unexpected information that does not match the info here.**

## Working Effectively

### Current Repository State
The repository currently contains only a minimal readme.md file. When working on this project, you will need to set up the basic project structure from scratch.

### Project Setup (Required for New Development)
- Prerequisites verification:
  - `node --version` -- should return Node.js v16+ (currently v20.19.4 available)
  - `npm --version` -- should return npm v8+ (currently v10.8.2 available)
  - `git --version` -- should be available

- Initialize the project:
  - `npm init -y` -- takes ~1 second, initializes package.json
  - `npm install graphql` -- takes ~2-3 seconds for core GraphQL dependency
  - `npm install --save-dev typescript @types/node jest @types/jest ts-jest` -- takes ~90 seconds. NEVER CANCEL. Set timeout to 3+ minutes.
  - `npx tsc --init` -- takes ~1 second, creates tsconfig.json

### Build and Test Setup
- TypeScript compilation:
  - `npx tsc` -- takes ~1-2 seconds for small projects. NEVER CANCEL on larger codebases. Set timeout to 2+ minutes.
  - For development: `npx tsc --watch` for continuous compilation

- Testing setup:
  - Create `jest.config.json` with: `{ "preset": "ts-jest", "testEnvironment": "node" }`
  - `npx jest` -- takes ~1-2 seconds for basic tests. NEVER CANCEL for large test suites. Set timeout to 5+ minutes.
  - For development: `npx jest --watch` for continuous testing

### Linting and Code Quality
- ESLint setup:
  - `npm install --save-dev eslint @typescript-eslint/parser @typescript-eslint/eslint-plugin` -- takes ~30 seconds. NEVER CANCEL. Set timeout to 2+ minutes.
  - `npx eslint . --ext .ts,.js` -- takes ~1-2 seconds for small projects. Set timeout to 2+ minutes for large codebases.

## Validation

### Manual Testing Scenarios
Since this is a GraphQL query builder library, always test these scenarios after making changes:

1. **Basic Query Building**:
   - Test simple field selection: `query { user { id name } }`
   - Test nested queries: `query { user { posts { title comments { text } } } }`
   - Test with variables: `query($id: ID!) { user(id: $id) { name } }`

2. **Mutation Building**:
   - Test simple mutations: `mutation { createUser(input: {...}) { id } }`
   - Test with variables and input types

3. **Advanced Features**:
   - Test fragments: `fragment UserInfo on User { id name email }`
   - Test directives: `query { user @include(if: $includeUser) { name } }`
   - Test aliases: `query { admin: user(role: ADMIN) { name } }`

4. **Type Safety** (when using TypeScript):
   - Verify proper type inference for query results
   - Test compile-time errors for invalid queries

### Pre-commit Validation
Always run these commands before committing:
- `npx tsc` -- ensure TypeScript compilation succeeds
- `npx jest` -- ensure all tests pass
- `npx eslint . --ext .ts,.js --fix` -- fix linting issues automatically
- Manual testing of at least one complete query building scenario

### CI/CD Considerations
When CI/CD pipelines are added (in .github/workflows/), they will likely include:
- Node.js matrix testing (versions 16, 18, 20)
- `npm ci` for clean dependency installation
- `npm run build` and `npm run test` commands
- Code coverage reporting with Jest

## Common Tasks

### Typical GraphQL Query Builder Architecture
```
src/
├── index.ts              # Main entry point
├── query-builder.ts      # Core query building logic
├── types.ts             # TypeScript type definitions
├── utils/
│   ├── validation.ts    # Query validation utilities
│   └── formatting.ts    # Query string formatting
└── __tests__/
    ├── query-builder.test.ts
    └── integration.test.ts
```

### Expected Package.json Scripts
```json
{
  "scripts": {
    "build": "tsc",
    "test": "jest",
    "test:watch": "jest --watch",
    "lint": "eslint . --ext .ts,.js",
    "lint:fix": "eslint . --ext .ts,.js --fix",
    "dev": "tsc --watch"
  }
}
```

### Development Workflow
1. `npm run dev` -- start TypeScript compilation in watch mode
2. `npm run test:watch` -- start Jest in watch mode (in separate terminal)
3. Make changes to source files
4. Tests automatically re-run on file changes
5. `npm run lint:fix` -- fix linting issues before committing

## Troubleshooting

### Common Issues
- **TypeScript compilation errors**: Check tsconfig.json configuration, ensure all dependencies have type definitions
- **Jest configuration issues**: Verify jest.config.json preset is set to "ts-jest"
- **Import/export errors**: Ensure proper module configuration in tsconfig.json
- **GraphQL syntax errors**: Use GraphQL schema validation tools during development

### Performance Considerations
- For large query builders, TypeScript compilation may take 2-5 minutes -- always set appropriate timeouts
- Jest test suites can take 5-15 minutes for comprehensive testing -- NEVER CANCEL
- ESLint on large codebases may take 1-3 minutes -- be patient

### Dependencies to Consider
Core dependencies that may be added:
- `graphql` (required)
- `graphql-tag` (for template literals)
- `@graphql-tools/schema` (for schema utilities)

Dev dependencies commonly used:
- `typescript`, `@types/node`
- `jest`, `@types/jest`, `ts-jest`
- `eslint`, `@typescript-eslint/parser`, `@typescript-eslint/eslint-plugin`
- `prettier` (for code formatting)

## Repository Standards

### Code Style
- Use TypeScript for all source code
- Follow ESLint recommended rules
- Use Prettier for consistent formatting
- Write comprehensive unit tests with Jest
- Include JSDoc comments for public APIs

### File Naming
- Use kebab-case for file names: `query-builder.ts`
- Use PascalCase for class names: `QueryBuilder`
- Use camelCase for function and variable names
- Test files should end with `.test.ts` or `.spec.ts`

### Git Workflow
- Create feature branches for new functionality
- Write descriptive commit messages
- Include tests for all new features
- Ensure all validation steps pass before creating PRs

---

**CRITICAL REMINDERS**:
- **NEVER CANCEL** any build, test, or lint commands -- they may take several minutes
- Always set timeouts of 2+ minutes for build commands, 5+ minutes for test suites
- **ALWAYS** validate your changes with manual testing scenarios before committing
- TypeScript compilation times increase with project size -- be patient
- When in doubt, run the complete validation sequence: build → test → lint → manual testing