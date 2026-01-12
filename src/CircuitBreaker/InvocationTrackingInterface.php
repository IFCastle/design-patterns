<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\CircuitBreaker;

interface InvocationTrackingInterface
{
    public function registerSuccess(): void;

    public function registerFailure(): void;
}
