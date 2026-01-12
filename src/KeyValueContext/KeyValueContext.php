<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\KeyValueContext;

use IfCastle\DesignPatterns\Immutable\ImmutableInterface;
use IfCastle\DesignPatterns\Immutable\ImmutableTrait;
use IfCastle\Exceptions\LogicalException;
use Traversable;

/**
 * A simple key-value context.
 *
 * @template-implements \ArrayAccess<string, mixed>
 * @template-implements \IteratorAggregate<string, mixed>
 */
class KeyValueContext implements \ArrayAccess, \IteratorAggregate, ImmutableInterface
{
    use ImmutableTrait;

    /**
     * @param array<string, mixed> $context
     */
    public function __construct(protected array $context = [], bool $isImmutable = false)
    {
        $this->isImmutable          = $isImmutable;
    }

    #[\Override]
    public function offsetExists(mixed $offset): bool
    {
        if (!\is_string($offset)) {
            throw new \InvalidArgumentException('The offset must be a string');
        }

        return \array_key_exists($offset, $this->context);
    }

    #[\Override]
    public function offsetGet(mixed $offset): mixed
    {
        if (!\is_string($offset)) {
            throw new \InvalidArgumentException('The offset must be a string');
        }

        return $this->context[$offset] ?? null;
    }

    /**
     * @throws LogicalException
     */
    #[\Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!\is_string($offset)) {
            throw new \InvalidArgumentException('The offset must be a string');
        }

        $this->throwIfImmutable();

        $this->context[$offset] = $value;
    }

    /**
     * @throws LogicalException
     */
    #[\Override]
    public function offsetUnset(mixed $offset): void
    {
        if (!\is_string($offset)) {
            throw new \InvalidArgumentException('The offset must be a string');
        }

        $this->throwIfImmutable();

        if (\array_key_exists($offset, $this->context)) {
            unset($this->context[$offset]);
        }
    }

    #[\Override]
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->context);
    }
}
