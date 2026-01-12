<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Iterators;

/**
 * @template TKey
 * @template TValue
 */
interface IteratorParentAwareInterface
{
    /**
     * @return \Iterator<TKey, TValue>|null
     */
    public function getParentIterator(): \Iterator|null;

    public function getParent(): object|null;
}
