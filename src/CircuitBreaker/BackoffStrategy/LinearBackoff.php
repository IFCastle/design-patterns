<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\CircuitBreaker\BackoffStrategy;

final readonly class LinearBackoff implements BackoffStrategyInterface
{
    /**
     * @param float $initialDelay     The initial delay in seconds.
     * @param float $increment        The increment value in milliseconds to add for each failure.
     * @param float $maxDelay         The maximum allowable delay in seconds.
     */
    public function __construct(private float $initialDelay = 1.0, private float $increment = 1.0, private float $maxDelay = 30.0) {}

    #[\Override]
    public function calculateDelay(int $failureAttempts): float
    {
        return \min($this->initialDelay + ($failureAttempts * $this->increment), $this->maxDelay);
    }

    /**
     * Returns the maximum delay that can be used.
     *
     * @return float The maximum delay in seconds.
     */
    public function getMaxDelay(): float
    {
        return $this->maxDelay;
    }
}
