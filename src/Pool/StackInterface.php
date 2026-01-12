<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Pool;

/**
 * @template T of object
 */
interface StackInterface
{
    /**
     * @return T|null
     */
    public function pop(): object|null;

    /**
     * @param T $object
     */
    public function push(object $object): void;

    public function getSize(): int;

    public function clear(): void;
}
