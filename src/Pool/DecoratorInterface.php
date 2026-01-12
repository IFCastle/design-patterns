<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Pool;

/**
 * @template T of object
 */
interface DecoratorInterface
{
    /**
     * Returns the original object.
     * @return T|null
     */
    public function getOriginalObject(): object|null;
}
