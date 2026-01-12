<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Pool;

use IfCastle\DI\DisposableInterface;

/**
 * @template T of object
 * @implements DecoratorInterface<T>
 */
final class Decorator implements DecoratorInterface, DisposableInterface
{
    /**
     * @var \WeakReference<object>|null
     */
    private \WeakReference|null $pool;

    /**
     * @param T|null   $originalObject
     * @param PoolInterface<T> $pool
     */
    public function __construct(private object|null $originalObject, PoolInterface $pool)
    {
        $this->pool                 = \WeakReference::create($pool);
    }

    public function __destruct()
    {
        $this->dispose();
    }

    /**
     * @param array<mixed> $arguments
     *
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->originalObject->{$name}(...$arguments);
    }

    #[\Override]
    public function getOriginalObject(): object|null
    {
        return $this->originalObject;
    }

    #[\Override]
    public function dispose(): void
    {
        $pool                       = $this->pool?->get();
        $originalObject             = $this->originalObject;
        $this->pool                 = null;
        $this->originalObject       = null;

        $pool?->return($originalObject);
    }
}
