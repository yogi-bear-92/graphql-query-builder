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
}