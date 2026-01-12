<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\CircuitBreaker\BackoffStrategy;

use IfCastle\DesignPatterns\CircuitBreaker\InvocationTrackingInterface;

final class TimeWindowBackoff implements BackoffStrategyInterface, InvocationTrackingInterface
{
    private int $lastFailureTime = 0;

    private int $failureAttempts = 0;

    /**
     * Constructor for TimeWindowBackoff.
     *
     * @param BackoffStrategyInterface $backoffStrategy The original backoff strategy to use.
     * @param float                    $timeWindow      The time window in seconds within which failures must occur (default is 5 minutes).
     */
    public function __construct(private readonly BackoffStrategyInterface $backoffStrategy, private readonly float $timeWindow = 300.0) {}

    #[\Override]
    public function registerSuccess(): void
    {
        $this->lastFailureTime      = 0;
        $this->failureAttempts      = 0;
    }

    #[\Override]
    public function registerFailure(): void
    {
        $time                       = \time();

        if (($time - $this->lastFailureTime) > $this->timeWindow) {
            $this->lastFailureTime  = $time;
            $this->failureAttempts  = 1;
            return;
        }

        $this->lastFailureTime      = $time;
        $this->failureAttempts++;
    }

    #[\Override]
    public function calculateDelay(int $failureAttempts): float
    {
        if ($this->failureAttempts === 0 || (\time() - $this->lastFailureTime) > $this->timeWindow) {
            return 0.0;
        }

        return $this->backoffStrategy->calculateDelay($this->failureAttempts);
    }
}
