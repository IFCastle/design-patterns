<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Iterators;

/**
 * ## RecursiveIteratorByIteratorWithPath
 * Allows you
 * to iterate objects recursively through a recursive iterator with the ability to get the current path from the nodes.
 * The $isSelfFirst parameter specifies that parent nodes should be used first.
 *
 * @template TKey
 * @template TValue
 * @implements \Iterator<TKey, TValue>
 * @implements IteratorParentAwareInterface<TKey, TValue>
 */
class RecursiveIteratorByIteratorWithPath implements
    \Iterator,
    IteratorWithPathInterface,
    IteratorParentAwareInterface,
    IteratorCloneInterface
{
    /**
     * @var array<\RecursiveIterator<TKey, TValue>>
     */
    protected array $path           = [];

    protected bool $isSelfPassed    = false;

    /**
     * @param \RecursiveIterator<TKey, TValue> $currentIterator
     */
    public function __construct(protected \RecursiveIterator $currentIterator, protected bool $isSelfFirst = true) {}

    #[\Override]
    public function getPath(): array
    {
        $path                       = [];

        foreach ($this->path as $iterator) {

            if ($iterator instanceof IteratorWithPathInterface) {
                $path               = \array_merge($path, $iterator->getPath());
            }

            $path[]                 = $iterator->current();
        }

        return $path;
    }

    #[\Override]
    public function getParentIterator(): \Iterator|null
    {
        if ($this->path === []) {
            return null;
        }

        return $this->path[\array_key_last($this->path)] ?? null;
    }

    #[\Override]
    public function getParent(): object|null
    {
        if ($this->path === []) {
            return null;
        }

        $iterator                   = $this->path[\array_key_last($this->path)] ?? null;

        if ($iterator instanceof \Iterator) {
            return $iterator->current();
        }

        return null;
    }

    #[\Override]
    public function current(): mixed
    {
        return $this->currentIterator->current();
    }

    #[\Override]
    public function next(): void
    {
        if ($this->isSelfFirst) {

            // Try to go deeper if possible to the first leaf
            if ($this->isSelfPassed && $this->currentIterator->hasChildren()) {
                $this->path[]           = $this->currentIterator;
                $this->currentIterator  = $this->currentIterator->getChildren();
                $this->currentIterator->rewind();
                $this->isSelfPassed     = true;
                return;
            }

            $this->currentIterator->next();
            $this->isSelfPassed     = true;

            if ($this->currentIterator->valid()) {
                return;
            }

            // Try to go up if possible
            while ($this->path !== []) {
                $this->currentIterator  = \array_pop($this->path);
                $this->currentIterator->next();

                if ($this->currentIterator->valid()) {
                    return;
                }
            }

            return;
        }

        if (false === $this->isSelfPassed) {
            $this->isSelfPassed     = true;
            return;
        }

        $this->isSelfPassed         = false;
        $this->currentIterator->next();

        if (false === $this->currentIterator->valid()) {
            // Try to go up if possible
            while ($this->path !== []) {
                $this->currentIterator  = \array_pop($this->path);
                if ($this->currentIterator->valid()) {
                    $this->isSelfPassed = true;
                    return;
                }
            }

            return;
        }

        $this->isSelfPassed         = true;

        while ($this->currentIterator->hasChildren()) {
            $this->path[]           = $this->currentIterator;
            $this->currentIterator  = $this->currentIterator->getChildren();
            $this->currentIterator->rewind();
        }
    }

    /**
     * The method tells the iterator not to go into the child nodes of the current node if they exist.
     * The method makes no sense together with the mode $isSelfFirst = false.
     */
    public function skipChildNodes(): void
    {
        $this->isSelfPassed         = false;
    }

    #[\Override]
    public function key(): mixed
    {
        return $this->currentIterator->key();
    }

    #[\Override]
    public function valid(): bool
    {
        return $this->currentIterator->valid();
    }

    #[\Override]
    public function rewind(): void
    {
        $this->isSelfPassed         = true;

        if ($this->path !== []) {
            $this->currentIterator  = \array_shift($this->path);
        }

        $this->path                 = [];
        $this->currentIterator->rewind();

        if (false === $this->isSelfFirst && $this->currentIterator->hasChildren()) {
            while (true) {
                $this->path[]           = $this->currentIterator;
                $this->currentIterator  = $this->currentIterator->getChildren();
                $this->currentIterator->rewind();

                if ($this->currentIterator->hasChildren() === false) {
                    $this->isSelfPassed = true;
                    return;
                }
            }
        }
    }

    public function __clone(): void
    {
        $this->currentIterator      = clone $this->currentIterator;

        foreach ($this->path as $key => $iterator) {
            $this->path[$key]       = clone $iterator;
        }
    }

    #[\Override]
    public function cloneAndRewind(): static
    {
        $clone                      = clone $this;
        $clone->rewind();
        return $clone;
    }
}
