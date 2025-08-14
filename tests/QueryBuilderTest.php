<?php

namespace GraphQLQueryBuilder\Tests;

use GraphQLQueryBuilder\QueryBuilder;
use PHPUnit\Framework\TestCase;

class QueryBuilderTest extends TestCase
{
    private QueryBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new QueryBuilder();
    }

    public function testLoadFromString(): void
    {
        $query = 'query { user { id name } }';
        $result = $this->builder->loadFromString($query)->build();
        
        $this->assertEquals($query, $result['query']);
        $this->assertEquals([], $result['variables']);
    }

    public function testWithVariables(): void
    {
        $variables = ['id' => '123', 'name' => 'test'];
        $result = $this->builder
            ->loadFromString('query { user { id } }')
            ->withVariables($variables)
            ->build();
        
        $this->assertEquals($variables, $result['variables']);
    }

    public function testAddFragment(): void
    {
        $result = $this->builder
            ->loadFromString('query { user { ...UserFragment } }')
            ->addFragment('UserFragment', 'id name email')
            ->build();
        
        $this->assertStringContainsString('id name email', $result['query']);
        $this->assertStringNotContainsString('...UserFragment', $result['query']);
    }

    public function testMultipleFragments(): void
    {
        $result = $this->builder
            ->loadFromString('query { user { ...UserFragment } profile { ...ProfileFragment } }')
            ->addFragment('UserFragment', 'id name')
            ->addFragment('ProfileFragment', 'avatar bio')
            ->build();
        
        $this->assertStringContainsString('id name', $result['query']);
        $this->assertStringContainsString('avatar bio', $result['query']);
    }

    public function testReset(): void
    {
        $this->builder
            ->loadFromString('query { user { id } }')
            ->withVariables(['id' => '123'])
            ->addFragment('test', 'fragment');
        
        $this->builder->reset();
        
        $this->assertEquals('', $this->builder->getQuery());
        $this->assertEquals([], $this->builder->getVariables());
    }

    public function testLoadFromFileNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('GraphQL file not found');
        
        $this->builder->loadFromFile('nonexistent.gql');
    }

    public function testDefineVariable(): void
    {
        $result = $this->builder
            ->loadFromString('query { user { id } }')
            ->defineVariable('id', 'ID!')
            ->build();
        
        $this->assertStringContainsString('query($id: ID!)', $result['query']);
    }

    public function testDefineVariableWithDefaultValue(): void
    {
        $result = $this->builder
            ->loadFromString('query { user { id } }')
            ->defineVariable('limit', 'Int', 10)
            ->build();
        
        $this->assertStringContainsString('query($limit: Int = 10)', $result['query']);
    }

    public function testDefineMultipleVariables(): void
    {
        $result = $this->builder
            ->loadFromString('query { user { id } }')
            ->defineVariable('id', 'ID!')
            ->defineVariable('limit', 'Int', 10)
            ->defineVariable('name', 'String', 'test')
            ->build();
        
        $this->assertStringContainsString('query($id: ID!, $limit: Int = 10, $name: String = "test")', $result['query']);
    }

    public function testDefineVariableWithMutation(): void
    {
        $result = $this->builder
            ->loadFromString('mutation { createUser { id } }')
            ->defineVariable('input', 'CreateUserInput!')
            ->build();
        
        $this->assertStringContainsString('mutation($input: CreateUserInput!)', $result['query']);
    }

    public function testDefineVariableDoesNotModifyExistingDefinitions(): void
    {
        $originalQuery = 'query GetUser($id: ID!) { user(id: $id) { id name } }';
        $result = $this->builder
            ->loadFromString($originalQuery)
            ->defineVariable('limit', 'Int')
            ->build();
        
        // Should not modify query that already has variable definitions
        $this->assertEquals($originalQuery, $result['query']);
    }

    public function testDefineVariableWithDefaultValueTypes(): void
    {
        $result = $this->builder
            ->loadFromString('query { test }')
            ->defineVariable('stringVar', 'String', 'hello')
            ->defineVariable('intVar', 'Int', 42)
            ->defineVariable('boolVar', 'Boolean', true)
            ->defineVariable('nullVar', 'String', null)
            ->build();
        
        $query = $result['query'];
        $this->assertStringContainsString('$stringVar: String = "hello"', $query);
        $this->assertStringContainsString('$intVar: Int = 42', $query);
        $this->assertStringContainsString('$boolVar: Boolean = true', $query);
        $this->assertStringContainsString('$nullVar: String = null', $query);
    }

    public function testDefineVariableWithFragments(): void
    {
        $result = $this->builder
            ->loadFromString('query { user { ...UserFragment } }')
            ->addFragment('UserFragment', 'id name email')
            ->defineVariable('id', 'ID!')
            ->build();
        
        $this->assertStringContainsString('query($id: ID!)', $result['query']);
        $this->assertStringContainsString('id name email', $result['query']);
    }

    public function testResetClearsVariableDefinitions(): void
    {
        $this->builder
            ->loadFromString('query { user { id } }')
            ->defineVariable('id', 'ID!')
            ->withVariables(['id' => '123']);
        
        $this->builder->reset();
        
        $result = $this->builder
            ->loadFromString('query { user { id } }')
            ->build();
        
        // Should not contain variable definitions after reset
        $this->assertStringNotContainsString('$id: ID!', $result['query']);
    }

    public function testFragmentNotReplacedInComments(): void
    {
        $result = $this->builder
            ->loadFromString('
                query {
                    # This comment mentions ...UserFragment but should not be replaced
                    user {
                        ...UserFragment
                    }
                }
            ')
            ->addFragment('UserFragment', 'id name')
            ->build();
        
        // Fragment should be replaced in the actual query
        $this->assertStringContainsString('id name', $result['query']);
        // But NOT in the comment
        $this->assertStringNotContainsString('# This comment mentions id name', $result['query']);
        $this->assertStringContainsString('# This comment mentions ...UserFragment', $result['query']);
    }

    public function testFragmentNotReplacedInStringLiterals(): void
    {
        $result = $this->builder
            ->loadFromString('
                query {
                    user(description: "This mentions ...UserFragment in a string") {
                        ...UserFragment
                    }
                }
            ')
            ->addFragment('UserFragment', 'id name')
            ->build();
        
        // Fragment should be replaced in the actual query
        $this->assertStringContainsString('id name', $result['query']);
        // But NOT in the string literal
        $this->assertStringNotContainsString('"This mentions id name in a string"', $result['query']);
        $this->assertStringContainsString('"This mentions ...UserFragment in a string"', $result['query']);
    }

    public function testNestedFragments(): void
    {
        $result = $this->builder
            ->loadFromString('query { user { ...UserFragment } }')
            ->addFragment('UserFragment', 'id name ...ProfileFragment')
            ->addFragment('ProfileFragment', 'avatar bio')
            ->build();
        
        // Both fragments should be resolved
        $this->assertStringContainsString('id name', $result['query']);
        $this->assertStringContainsString('avatar bio', $result['query']);
        // No fragment placeholders should remain
        $this->assertStringNotContainsString('...UserFragment', $result['query']);
        $this->assertStringNotContainsString('...ProfileFragment', $result['query']);
    }

    public function testLoadFragmentFromFile(): void
    {
        // Create a temporary fragment file
        $fragPath = '/tmp/test_fragment.gql';
        file_put_contents($fragPath, 'id name email created_at');
        
        $result = $this->builder
            ->loadFromString('query { user { ...TestFragment } }')
            ->loadFragmentFromFile('TestFragment', $fragPath)
            ->build();
        
        $this->assertStringContainsString('id name email created_at', $result['query']);
        $this->assertStringNotContainsString('...TestFragment', $result['query']);
        
        // Clean up
        unlink($fragPath);
    }

    public function testLoadFragmentFromFileNotFound(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Fragment file not found');
        
        $this->builder->loadFragmentFromFile('TestFragment', '/nonexistent/path.gql');
    }

    public function testCircularFragmentDependency(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Circular fragment dependency detected');
        
        $this->builder
            ->loadFromString('query { user { ...FragmentA } }')
            ->addFragment('FragmentA', 'id ...FragmentB')
            ->addFragment('FragmentB', 'name ...FragmentA')
            ->build();
    }

    public function testAddAlias(): void
    {
        $result = $this->builder
            ->loadFromString('query { user { name } }')
            ->addAlias('admin', 'user', ['role' => 'ADMIN'])
            ->build();
        
        $this->assertStringContainsString('admin: user(role: "ADMIN")', $result['query']);
        $this->assertStringNotContainsString('user {', $result['query']);
    }

    public function testAddAliasWithoutArguments(): void
    {
        $result = $this->builder
            ->loadFromString('query { user { name } }')
            ->addAlias('currentUser', 'user')
            ->build();
        
        $this->assertStringContainsString('currentUser: user', $result['query']);
    }

    public function testAddDirective(): void
    {
        $result = $this->builder
            ->loadFromString('query { user { name } }')
            ->addDirective('user', 'include', ['if' => '$showUser'])
            ->build();
        
        $this->assertStringContainsString('user @include(if: $showUser)', $result['query']);
    }

    public function testAddDirectiveWithoutArguments(): void
    {
        $result = $this->builder
            ->loadFromString('query { user { name } }')
            ->addDirective('user', 'deprecated')
            ->build();
        
        $this->assertStringContainsString('user @deprecated', $result['query']);
    }

    public function testMultipleDirectivesOnSameField(): void
    {
        $result = $this->builder
            ->loadFromString('query { user { name } }')
            ->addDirective('user', 'include', ['if' => '$showUser'])
            ->addDirective('user', 'deprecated')
            ->build();
        
        $query = $result['query'];
        $this->assertStringContainsString('user @include(if: $showUser) @deprecated', $query);
    }

    public function testCombinedAliasAndDirective(): void
    {
        $result = $this->builder
            ->loadFromString('query { user { name } }')
            ->addAlias('admin', 'user', ['role' => 'ADMIN'])
            ->addDirective('user', 'include', ['if' => '$showAdmin'])
            ->build();
        
        $this->assertStringContainsString('admin: user(role: "ADMIN") @include(if: $showAdmin)', $result['query']);
    }

    public function testDirectiveWithVariableDefinitions(): void
    {
        $result = $this->builder
            ->loadFromString('query { user { name } }')
            ->addDirective('user', 'include', ['if' => '$showUser'])
            ->defineVariable('showUser', 'Boolean', true)
            ->build();
        
        $query = $result['query'];
        $this->assertStringContainsString('query($showUser: Boolean = true)', $query);
        $this->assertStringContainsString('user @include(if: $showUser)', $query);
    }

    public function testAliasWithMutation(): void
    {
        $result = $this->builder
            ->loadFromString('mutation { createUser { id } }')
            ->addAlias('newUser', 'createUser', ['input' => '$userInput'])
            ->defineVariable('userInput', 'CreateUserInput!')
            ->build();
        
        $query = $result['query'];
        $this->assertStringContainsString('mutation($userInput: CreateUserInput!)', $query);
        $this->assertStringContainsString('newUser: createUser(input: $userInput)', $query);
    }

    public function testResetClearsAliasesAndDirectives(): void
    {
        $this->builder
            ->loadFromString('query { user { name } }')
            ->addAlias('admin', 'user')
            ->addDirective('user', 'include', ['if' => '$show']);
        
        $this->builder->reset();
        
        $result = $this->builder
            ->loadFromString('query { user { name } }')
            ->build();
        
        // Should not contain aliases or directives after reset
        $this->assertStringNotContainsString('admin:', $result['query']);
        $this->assertStringNotContainsString('@include', $result['query']);
    }

    public function testDirectiveArgumentTypes(): void
    {
        $result = $this->builder
            ->loadFromString('query { user { name } }')
            ->addDirective('user', 'testDirective', [
                'stringArg' => 'hello',
                'intArg' => 42,
                'boolArg' => true,
                'nullArg' => null,
                'varArg' => '$variable'
            ])
            ->build();
        
        $query = $result['query'];
        $this->assertStringContainsString('stringArg: "hello"', $query);
        $this->assertStringContainsString('intArg: 42', $query);
        $this->assertStringContainsString('boolArg: true', $query);
        $this->assertStringContainsString('nullArg: null', $query);
        $this->assertStringContainsString('varArg: $variable', $query);
    }

    // Fluent API Tests

    public function testFluentQueryBasic(): void
    {
        $result = $this->builder
            ->query('GetUser')
            ->field('id')
            ->field('name')
            ->build();
        
        $query = $result['query'];
        $this->assertStringContainsString('query GetUser {', $query);
        $this->assertStringContainsString('id', $query);
        $this->assertStringContainsString('name', $query);
    }

    public function testFluentQueryWithoutName(): void
    {
        $result = $this->builder
            ->query()
            ->field('user')
            ->build();
        
        $query = $result['query'];
        $this->assertStringContainsString('query {', $query);
        $this->assertStringContainsString('user', $query);
    }

    public function testFluentMutation(): void
    {
        $result = $this->builder
            ->mutation('CreateUser')
            ->field('createUser', ['input' => '$input'])
            ->build();
        
        $query = $result['query'];
        $this->assertStringContainsString('mutation CreateUser {', $query);
        $this->assertStringContainsString('createUser(input: $input)', $query);
    }

    public function testFluentSubscription(): void
    {
        $result = $this->builder
            ->subscription('UserUpdates')
            ->field('userUpdated')
            ->build();
        
        $query = $result['query'];
        $this->assertStringContainsString('subscription UserUpdates {', $query);
        $this->assertStringContainsString('userUpdated', $query);
    }

    public function testFluentFieldWithArguments(): void
    {
        $result = $this->builder
            ->query()
            ->field('user', ['id' => '$userId', 'active' => true])
            ->build();
        
        $query = $result['query'];
        $this->assertStringContainsString('user(id: $userId, active: true)', $query);
    }

    public function testFluentFieldWithAlias(): void
    {
        $result = $this->builder
            ->query()
            ->field('user', ['role' => 'ADMIN'], 'adminUser')
            ->build();
        
        $query = $result['query'];
        $this->assertStringContainsString('adminUser: user(role: "ADMIN")', $query);
    }

    public function testFluentNestedObjects(): void
    {
        $result = $this->builder
            ->query('GetUser')
            ->object('user', ['id' => '$userId'])
                ->field('id')
                ->field('name')
                ->object('profile')
                    ->field('avatar')
                    ->field('bio')
                ->end()
            ->end()
            ->build();
        
        $query = $result['query'];
        $this->assertStringContainsString('query GetUser {', $query);
        $this->assertStringContainsString('user(id: $userId) {', $query);
        $this->assertStringContainsString('profile {', $query);
        $this->assertStringContainsString('avatar', $query);
        $this->assertStringContainsString('bio', $query);
    }

    public function testFluentObjectWithAlias(): void
    {
        $result = $this->builder
            ->query()
            ->object('user', ['role' => 'ADMIN'], 'adminUser')
                ->field('id')
                ->field('name')
            ->end()
            ->build();
        
        $query = $result['query'];
        $this->assertStringContainsString('adminUser: user(role: "ADMIN") {', $query);
        $this->assertStringContainsString('id', $query);
        $this->assertStringContainsString('name', $query);
    }

    public function testFluentMixWithVariableDefinitions(): void
    {
        $result = $this->builder
            ->query('GetUser')
            ->defineVariable('userId', 'ID!')
            ->defineVariable('includeProfile', 'Boolean', false)
            ->object('user', ['id' => '$userId'])
                ->field('id')
                ->field('name')
            ->end()
            ->withVariables(['userId' => '123', 'includeProfile' => true])
            ->build();
        
        $query = $result['query'];
        $this->assertStringContainsString('query GetUser($userId: ID!, $includeProfile: Boolean = false) {', $query);
        $this->assertStringContainsString('user(id: $userId)', $query);
        $this->assertEquals(['userId' => '123', 'includeProfile' => true], $result['variables']);
    }

    public function testFluentMixWithExistingQuery(): void
    {
        // First set up with string query
        $this->builder->loadFromString('query { existing { field } }');
        
        // Then use fluent API - should override
        $result = $this->builder
            ->query('NewQuery')
            ->field('newField')
            ->build();
        
        $query = $result['query'];
        $this->assertStringContainsString('query NewQuery {', $query);
        $this->assertStringContainsString('newField', $query);
        $this->assertStringNotContainsString('existing', $query);
    }

    public function testFluentEndWithoutNestedSelection(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('Cannot call end() - no nested selection to close');
        
        $this->builder
            ->query()
            ->field('test')
            ->end(); // Should throw exception
    }

    public function testFluentBuildWithoutOperation(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('No fluent operation or selections defined');
        
        $this->builder
            ->query()
            // No fields added
            ->build();
    }

    public function testFluentArgumentFormatting(): void
    {
        $result = $this->builder
            ->query()
            ->field('test', [
                'stringArg' => 'hello',
                'intArg' => 42,
                'boolArg' => true,
                'nullArg' => null,
                'varArg' => '$variable'
            ])
            ->build();
        
        $query = $result['query'];
        $this->assertStringContainsString('stringArg: "hello"', $query);
        $this->assertStringContainsString('intArg: 42', $query);
        $this->assertStringContainsString('boolArg: true', $query);
        $this->assertStringContainsString('nullArg: null', $query);
        $this->assertStringContainsString('varArg: $variable', $query);
    }

    public function testFluentResetClearsFluentState(): void
    {
        $this->builder
            ->query('Test')
            ->field('testField')
            ->reset();
        
        // After reset, should be able to use regular string loading
        $result = $this->builder
            ->loadFromString('query { differentField }')
            ->build();
        
        $query = $result['query'];
        $this->assertStringContainsString('differentField', $query);
        $this->assertStringNotContainsString('testField', $query);
    }
}