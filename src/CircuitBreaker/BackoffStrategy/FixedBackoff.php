<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\CircuitBreaker\BackoffStrategy;

final readonly class FixedBackoff implements BackoffStrategyInterface
{
    public function __construct(private float $delay) {}

    #[\Override]
    public function calculateDelay(int $failureAttempts): float
    {
        return $this->delay;
    }
}
