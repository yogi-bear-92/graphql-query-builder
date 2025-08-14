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
echo "Variables: " . json_encode($result['variables']) . "\n";