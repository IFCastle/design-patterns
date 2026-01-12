<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Immutable;

/**
 * ## ImmutableInterface.
 *
 * This interface is used to define the methods that are required to make an object immutable.
 */
interface ImmutableInterface
{
    public function isMutable(): bool;

    public function isImmutable(): bool;

    public function asImmutable(): static;

    public function cloneAsMutable(): static;
}
