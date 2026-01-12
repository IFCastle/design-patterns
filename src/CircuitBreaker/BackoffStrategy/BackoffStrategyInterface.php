<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\CircuitBreaker\BackoffStrategy;

interface BackoffStrategyInterface
{
    /**
     * Calculates the delay time before the next retry attempt based on the number of failures.
     *
     *
     * @return float The delay time in seconds before the next attempt.
     */
    public function calculateDelay(int $failureAttempts): float;
}
