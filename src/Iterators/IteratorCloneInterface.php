<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Iterators;

interface IteratorCloneInterface
{
    public function cloneAndRewind(): static;
}
