<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Iterators;

class NodeRecursiveIterator implements \RecursiveIterator
{
    private array $nodes;

    public function __construct(Node ...$nodes)
    {
        $this->nodes                = $nodes;
    }

    #[\Override]
    public function current(): mixed
    {
        return \current($this->nodes);
    }

    #[\Override]
    public function next(): void
    {
        \next($this->nodes);
    }

    #[\Override]
    public function key(): mixed
    {
        return \key($this->nodes);
    }

    #[\Override]
    public function valid(): bool
    {
        return $this->current() !== false;
    }

    #[\Override]
    public function rewind(): void
    {
        \reset($this->nodes);
    }

    #[\Override]
    public function hasChildren(): bool
    {
        return \count($this->current()->children) > 0;
    }

    #[\Override]
    public function getChildren(): ?\RecursiveIterator
    {
        return new self(...$this->current()->children);
    }
}
