<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\CircuitBreaker;

interface InvocationStatInterface
{
    public function getLastCalledAt(): int;

    public function getLastSuccessAt(): int;

    public function getFailureCount(): int;

    public function getSuccessCount(): int;

    public function getTotalCount(): int;

    public function getTotalFailureCount(): int;

    public function resetCounters(): void;
}
