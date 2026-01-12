<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\CircuitBreaker\BackoffStrategy;

final readonly class ExponentialBackoff implements BackoffStrategyInterface
{
    /**
     * Constructor for ExponentialBackoffWithJitter.
     *
     * @param float $initialDelay The initial delay in seconds.
     * @param float $factor The factor by which the delay is multiplied after each failure.
     * @param float $maxDelay The maximum allowable delay in seconds.
     * @param float $jitter The maximum jitter value in seconds.
     */
    public function __construct(
        private float $initialDelay = 1.0,
        private float $factor = 2.0,
        private float $maxDelay = 60,
        private float $jitter = 5.0
    ) {}

    /**
     * Calculates the delay time before the next retry attempt based on the number of failures.
     *
     * @param int $failureAttempts The number of failure attempts.
     * @return float The delay time in seconds before the next attempt.
     */
    #[\Override]
    public function calculateDelay(int $failureAttempts): float
    {
        $baseDelay                  = (int) ($this->initialDelay * $this->factor ** $failureAttempts);
        $baseDelay                  = \min($baseDelay, $this->maxDelay);

        // Add jitter: a random value between -$jitter and +$jitter
        $jitterValue                = \random_int(-(int) $this->jitter * 1000, (int) $this->jitter * 1000) / 1000;
        $delayWithJitter            = $baseDelay + $jitterValue;

        // Ensure the delay is not less than zero
        return \max(0, $delayWithJitter);
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
