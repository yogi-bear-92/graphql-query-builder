<?php

require_once __DIR__ . '/../src/QueryBuilder.php';

use GraphQLQueryBuilder\QueryBuilder;

echo "GraphQL Query Builder Example\n";
echo "=============================\n\n";

// Example 1: Load query from file
$builder = new QueryBuilder();
$result = $builder->loadFromFile(__DIR__ . '/user.gql')
    ->withVariables(['id' => '123'])
    ->build();

echo "Example 1 - Query from file:\n";
echo "Query: " . $result['query'] . "\n";
echo "Variables: " . json_encode($result['variables']) . "\n\n";

// Example 2: Load query from string with fragments
$builder->reset();
$result = $builder->loadFromString('
    query GetUserPosts($userId: ID!) {
      user(id: $userId) {
        ...UserFragment
        posts {
          id
          title
          content
        }
      }
    }
')
    ->addFragment('UserFragment', 'id name email')
    ->withVariables(['userId' => '456'])
    ->build();

echo "Example 2 - Query with fragments:\n";
echo "Query: " . $result['query'] . "\n";
echo "Variables: " . json_encode($result['variables']) . "\n\n";

// Example 3: Reusable fragments
$builder->reset();
$builder->addFragment('UserFragment', 'id name email profile { avatar bio }');

$result = $builder->loadFromFile(__DIR__ . '/users.gql')
    ->withVariables(['first' => 10, 'after' => null])
    ->build();

echo "Example 3 - Reusable fragments:\n";
echo "Query: " . $result['query'] . "\n";
echo "Variables: " . json_encode($result['variables']) . "\n\n";

// Example 4: Variable definitions (NEW FEATURE)
$builder->reset();
$result = $builder->loadFromString('
    query {
      user(id: $userId) {
        id
        name
        posts(limit: $limit) {
          title
        }
      }
    }
')
    ->defineVariable('userId', 'ID!')
    ->defineVariable('limit', 'Int', 5)
    ->withVariables(['userId' => '789', 'limit' => 3])
    ->build();

echo "Example 4 - Variable definitions:\n";
echo "Query: " . $result['query'] . "\n";
echo "Variables: " . json_encode($result['variables']) . "\n\n";

// Example 5: Enhanced fragments with file loading
$builder->reset();

// Create a temporary fragment file to demonstrate
$profileFragmentPath = __DIR__ . '/profile_fragment.gql';
file_put_contents($profileFragmentPath, 'avatar bio website social { twitter github }');

$result = $builder->loadFromString('
    query GetUserWithProfile($id: ID!) {
        user(id: $id) {
            id
            name
            email
            # This comment mentions ...ProfileFragment but should not be replaced
            profile {
                ...ProfileFragment
            }
        }
    }
')
    ->loadFragmentFromFile('ProfileFragment', $profileFragmentPath)
    ->defineVariable('id', 'ID!')
    ->withVariables(['id' => 'user123'])
    ->build();

echo "Example 5 - Enhanced fragments with file loading:\n";
echo "Query: " . $result['query'] . "\n";
echo "Variables: " . json_encode($result['variables']) . "\n\n";

// Clean up
unlink($profileFragmentPath);

// Example 6: Nested fragments
$builder->reset();
$result = $builder->loadFromString('
    query GetUserDetails($userId: ID!) {
        user(id: $userId) {
            ...UserWithProfile
        }
    }
')
    ->addFragment('UserWithProfile', 'id name email ...DetailedProfile')
    ->addFragment('DetailedProfile', 'avatar bio preferences { theme language }')
    ->defineVariable('userId', 'ID!')
    ->withVariables(['userId' => 'nested123'])
    ->build();

echo "Example 6 - Nested fragments:\n";
echo "Query: " . $result['query'] . "\n";
echo "Variables: " . json_encode($result['variables']) . "\n\n";

// Example 7: Basic Aliases
$builder->reset();
$result = $builder->loadFromString('
    query GetUsers {
        user {
            id
            name
            email
        }
    }
')
    ->addAlias('admin', 'user', ['role' => 'ADMIN'])
    ->build();

echo "Example 7 - Basic Aliases:\n";
echo "Query: " . $result['query'] . "\n";
echo "Variables: " . json_encode($result['variables']) . "\n\n";

// Example 8: Basic Directives
$builder->reset();
$result = $builder->loadFromString('
    query GetUser {
        user {
            id
            name
            email
        }
    }
')
    ->addDirective('user', 'include', ['if' => '$showUser'])
    ->addDirective('name', 'skip', ['if' => '$hideName'])
    ->defineVariable('showUser', 'Boolean', true)
    ->defineVariable('hideName', 'Boolean', false)
    ->withVariables([
        'showUser' => true,
        'hideName' => false
    ])
    ->build();

echo "Example 8 - Basic Directives:\n";
echo "Query: " . $result['query'] . "\n";
echo "Variables: " . json_encode($result['variables']) . "\n";