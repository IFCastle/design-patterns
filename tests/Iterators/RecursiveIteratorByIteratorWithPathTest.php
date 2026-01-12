<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Iterators;

use PHPUnit\Framework\TestCase;

class RecursiveIteratorByIteratorWithPathTest extends TestCase
{
    private \RecursiveIterator $recursiveIterator;

    private RecursiveIteratorByIteratorWithPath $recursiveIteratorWithPath;

    #[\Override]
    protected function setUp(): void
    {
        $this->recursiveIterator    = $this->getMockBuilder(\RecursiveIterator::class)->getMock();
        $this->recursiveIteratorWithPath = new RecursiveIteratorByIteratorWithPath($this->recursiveIterator);
    }

    public function testGetCurrent(): void
    {
        $this->recursiveIterator->expects($this->once())
            ->method('current')
            ->willReturn('currentValue');

        $this->assertEquals('currentValue', $this->recursiveIteratorWithPath->current());
    }

    public function testGetKey(): void
    {
        $this->recursiveIterator->expects($this->once())
            ->method('key')
            ->willReturn('currentKey');

        $this->assertEquals('currentKey', $this->recursiveIteratorWithPath->key());
    }

    public function testValid(): void
    {
        $this->recursiveIterator->expects($this->once())
            ->method('valid')
            ->willReturn(true);

        $this->assertTrue($this->recursiveIteratorWithPath->valid());
    }

    public function testRewind(): void
    {
        $this->recursiveIterator->expects($this->once())
            ->method('rewind');

        $this->recursiveIteratorWithPath->rewind();
    }

    public function testTreeTraversal(): void
    {
        $tree                       = new NodeRecursiveIterator(
            new Node('root', [
                new Node('child1', [
                    new Node('grandchild1'),
                    new Node('grandchild2'),
                ]),
                new Node('child2', [
                    new Node('grandchild3'),
                    new Node('grandchild4'),
                ]),
            ])
        );

        $recursiveIteratorWithPath  = new RecursiveIteratorByIteratorWithPath($tree);
        $visitedNodes               = [];

        foreach ($recursiveIteratorWithPath as $node) {
            if ($node instanceof Node) {
                $visitedNodes[]     = $node->name;
            }

            // Test the path functionality
            if ($node->name === 'grandchild2') {
                $this->assertEquals(['root', 'child1'], \array_map(static fn(Node $node) => $node->name, $recursiveIteratorWithPath->getPath()));
            } elseif ($node->name === 'grandchild4') {
                $this->assertEquals(['root', 'child2'], \array_map(static fn(Node $node) => $node->name, $recursiveIteratorWithPath->getPath()));
            } elseif ($node->name === 'root') {
                $this->assertEquals([], $recursiveIteratorWithPath->getPath());
            }
        }

        $expectedVisitedNodes = ['root', 'child1', 'grandchild1', 'grandchild2', 'child2', 'grandchild3', 'grandchild4'];

        $this->assertEquals($expectedVisitedNodes, $visitedNodes);
    }

    public function testTreeTraversalWithSelfLast(): void
    {
        $tree                       = new NodeRecursiveIterator(
            new Node('root', [
                new Node('child1', [
                    new Node('grandchild1'),
                    new Node('grandchild2'),
                ]),
                new Node('child2', [
                    new Node('grandchild3'),
                    new Node('grandchild4'),
                ]),
            ])
        );

        $recursiveIteratorWithPath  = new RecursiveIteratorByIteratorWithPath($tree, false);
        $visitedNodes               = [];

        foreach ($recursiveIteratorWithPath as $node) {
            if ($node instanceof Node) {
                $visitedNodes[]     = $node->name;
            }
        }

        $expectedVisitedNodes = ['grandchild1', 'grandchild2', 'child1', 'grandchild3', 'grandchild4', 'child2', 'root'];

        $this->assertEquals($expectedVisitedNodes, $visitedNodes);
    }

    public function testParent(): void
    {
        $tree                       = new NodeRecursiveIterator(
            new Node('root', [
                new Node('child1', [
                    new Node('grandchild1'),
                    new Node('grandchild2'),
                ]),
                new Node('child2', [
                    new Node('grandchild3'),
                    new Node('grandchild4'),
                ]),
            ])
        );

        $recursiveIteratorWithPath  = new RecursiveIteratorByIteratorWithPath($tree);
        $visitedNodes               = [];

        foreach ($recursiveIteratorWithPath as $node) {
            if ($node instanceof Node) {
                $visitedNodes[]     = $node->name;
            }

            // Test the path functionality
            if ($node->name === 'grandchild2') {
                $this->assertEquals('child1', $recursiveIteratorWithPath->getParent()?->name);
            } elseif ($node->name === 'grandchild4') {
                $this->assertEquals('child2', $recursiveIteratorWithPath->getParent()?->name);
            } elseif ($node->name === 'root') {
                $this->assertNull($recursiveIteratorWithPath->getParent());
            }
        }

        $expectedVisitedNodes = ['root', 'child1', 'grandchild1', 'grandchild2', 'child2', 'grandchild3', 'grandchild4'];

        $this->assertEquals($expectedVisitedNodes, $visitedNodes);
    }

}
