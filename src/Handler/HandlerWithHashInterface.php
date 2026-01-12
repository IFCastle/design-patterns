<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Handler;

/**
 * ## Handler with Hash.
 * An interface that describes a handler that can be associated with a unique hash (string or number).
 *
 * The pattern is used in cases where there is a need to find a handler by its hash,
 * meaning there is an algorithm for comparing handlers. Handlers with the same functionality have the same hash.
 */
interface HandlerWithHashInterface
{
    public function getHandlerHash(): string|int|null;
}
