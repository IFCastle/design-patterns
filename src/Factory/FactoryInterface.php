<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Factory;

/**
 * @template T of object
 */
interface FactoryInterface
{
    /**
     * Creates an object.
     * @return T
     */
    public function createObject(): object;
}
