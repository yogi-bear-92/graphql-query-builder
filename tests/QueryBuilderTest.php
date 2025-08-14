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
}