<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Pool;

/**
 * Creates a decorator for the original object.
 * @template T of object
 * @implements ReturnFactoryInterface<T>
 */
final class ReturnFactory implements ReturnFactoryInterface
{
    /**
     * @param T                 $originalObject
     * @param PoolInterface<T>  $pool
     *
     * @return Decorator<T>
     */
    #[\Override]
    public function createDecorator(object $originalObject, PoolInterface $pool): object
    {
        return new Decorator($originalObject, $pool);
    }
}
