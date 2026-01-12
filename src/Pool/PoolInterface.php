<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Pool;

/**
 * # Pool Interface.
 *
 * A pool is a collection of objects that can be borrowed and returned.
 *
 * @template T of object
 */
interface PoolInterface
{
    /**
     * Borrow an object from the pool.
     * @return T|null
     */
    public function borrow(): object|null;

    /**
     * Return an object to the pool.
     * @param T $object
     */
    public function return(object $object): void;

    /**
     * Rebuild pool state and free unused objects.
     *
     */
    public function rebuild(): void;

    /**
     * Get the maximum pool size.
     *
     */
    public function getMaxPoolSize(): int;

    /**
     * Get the minimum pool size.
     *
     */
    public function getMinPoolSize(): int;

    /**
     * Get the timeout for borrowing an object.
     *
     */
    public function getMaxWaitTimeout(): int;

    /**
     * Get used objects count.
     *
     */
    public function getUsed(): int;

    /**
     * Get the pool size.
     *
     */
    public function getPoolSize(): int;
}
