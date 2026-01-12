<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Iterators;

use IfCastle\Exceptions\UnexpectedValueType;

/**
 * ## RecursiveIteratorByIterator
 * Recursive iterator over objects with implemented interface IteratorAggregate.
 *
 * @template TKey
 * @template TValue
 * @implements \RecursiveIterator<TKey, TValue>
 */
class RecursiveIteratorByIterator implements \RecursiveIterator, IteratorCloneInterface
{
    /**
     * @var \Iterator<TKey, TValue>
     */
    protected \Iterator $iterator;

    /**
     * @param \Traversable<TKey, TValue> $iterator
     * @throws UnexpectedValueType
     */
    public function __construct(\Traversable $iterator)
    {
        if ($iterator instanceof \Iterator) {
            $this->iterator         = $iterator;
        } else {
            throw new UnexpectedValueType('$iterator', $iterator, \Iterator::class);
        }
    }

    #[\Override]
    public function hasChildren(): bool
    {
        return $this->iterator->valid() && $this->iterator->current() instanceof \IteratorAggregate;
    }

    /**
     * @throws UnexpectedValueType
     * @throws \Exception
     *
     * @return \RecursiveIterator<TKey, TValue>|null
     */
    #[\Override]
    public function getChildren(): ?\RecursiveIterator
    {
        if (false === $this->iterator->valid()) {
            return null;
        }

        $current                    = $this->iterator->current();

        if ($current instanceof \IteratorAggregate) {
            /* @phpstan-ignore-next-line */
            return new static($current->getIterator());
        }

        return null;
    }

    #[\Override]
    public function current(): mixed
    {
        return $this->iterator->current();
    }

    #[\Override]
    public function next(): void
    {
        $this->iterator->next();
    }

    #[\Override]
    public function key(): mixed
    {
        return $this->iterator->key();
    }

    #[\Override]
    public function valid(): bool
    {
        return $this->iterator->valid();
    }

    #[\Override]
    public function rewind(): void
    {
        $this->iterator->rewind();
    }

    public function __clone(): void
    {
        $this->iterator             = clone $this->iterator;
    }

    #[\Override]
    public function cloneAndRewind(): static
    {
        $clone                      = clone $this;
        $clone->rewind();
        return $clone;
    }
}
