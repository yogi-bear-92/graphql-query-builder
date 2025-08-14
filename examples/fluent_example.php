<?php

require_once __DIR__ . '/../src/QueryBuilder.php';

use GraphQLQueryBuilder\QueryBuilder;

echo "GraphQL Fluent Query Builder Examples\n";
echo "=====================================\n\n";

// Example 1: Basic fluent query
$builder = new QueryBuilder();
$result = $builder
    ->query('GetUsers')
    ->field('id')
    ->field('name')
    ->field('email')
    ->build();

echo "Example 1 - Basic fluent query:\n";
echo "Query: " . $result['query'] . "\n\n";

// Example 2: Query with arguments and variables
$builder->reset();
$result = $builder
    ->query('GetUser')
    ->defineVariable('userId', 'ID!')
    ->field('user', ['id' => '$userId'])
    ->withVariables(['userId' => '123'])
    ->build();

echo "Example 2 - Query with arguments and variables:\n";
echo "Query: " . $result['query'] . "\n";
echo "Variables: " . json_encode($result['variables']) . "\n\n";

// Example 3: Nested objects with fluent API
$builder->reset();
$result = $builder
    ->query('GetUserProfile')
    ->defineVariable('id', 'ID!')
    ->object('user', ['id' => '$id'])
        ->field('id')
        ->field('name')
        ->field('email')
        ->object('profile')
            ->field('avatar')
            ->field('bio')
            ->field('website')
        ->end()
        ->object('preferences')
            ->field('theme')
            ->field('language')
            ->field('notifications')
        ->end()
    ->end()
    ->withVariables(['id' => 'user-456'])
    ->build();

echo "Example 3 - Nested objects:\n";
echo "Query: " . $result['query'] . "\n";
echo "Variables: " . json_encode($result['variables']) . "\n\n";

// Example 4: Mutation with fluent API
$builder->reset();
$result = $builder
    ->mutation('CreateUser')
    ->defineVariable('input', 'CreateUserInput!')
    ->field('createUser', ['input' => '$input'])
    ->withVariables([
        'input' => [
            'name' => 'John Doe',
            'email' => 'john@example.com'
        ]
    ])
    ->build();

echo "Example 4 - Mutation:\n";
echo "Query: " . $result['query'] . "\n";
echo "Variables: " . json_encode($result['variables']) . "\n\n";

// Example 5: Subscription with fluent API
$builder->reset();
$result = $builder
    ->subscription('UserUpdates')
    ->defineVariable('userId', 'ID!')
    ->object('userUpdated', ['id' => '$userId'])
        ->field('id')
        ->field('name')
        ->field('status')
    ->end()
    ->withVariables(['userId' => '789'])
    ->build();

echo "Example 5 - Subscription:\n";
echo "Query: " . $result['query'] . "\n";
echo "Variables: " . json_encode($result['variables']) . "\n\n";

// Example 6: Using aliases with fluent API
$builder->reset();
$result = $builder
    ->query('GetAdminUsers')
    ->field('user', ['role' => 'ADMIN'], 'adminUser')
    ->field('user', ['role' => 'MODERATOR'], 'moderatorUser')
    ->build();

echo "Example 6 - Aliases:\n";
echo "Query: " . $result['query'] . "\n\n";

// Example 7: Complex nested structure
$builder->reset();
$result = $builder
    ->query('GetUserPosts')
    ->defineVariable('userId', 'ID!')
    ->defineVariable('limit', 'Int', 10)
    ->object('user', ['id' => '$userId'])
        ->field('id')
        ->field('name')
        ->object('posts', ['limit' => '$limit'])
            ->field('id')
            ->field('title')
            ->field('content')
            ->object('author')
                ->field('id')
                ->field('name')
            ->end()
            ->object('comments', ['limit' => 5])
                ->field('id')
                ->field('text')
                ->object('author')
                    ->field('id')
                    ->field('name')
                ->end()
            ->end()
        ->end()
    ->end()
    ->withVariables(['userId' => 'user-123', 'limit' => 5])
    ->build();

echo "Example 7 - Complex nested structure:\n";
echo "Query: " . $result['query'] . "\n";
echo "Variables: " . json_encode($result['variables']) . "\n\n";

// Example 8: Mixing fluent API with existing fragments
$builder->reset();
$builder->addFragment('UserFragment', 'id name email');
$result = $builder
    ->query('GetUsersWithFragment')
    ->object('users')
        ->field('...UserFragment') // Using existing fragment syntax
        ->field('status')
    ->end()
    ->build();

echo "Example 8 - Mixing with fragments:\n";
echo "Query: " . $result['query'] . "\n\n";

echo "All fluent API examples completed successfully!\n";