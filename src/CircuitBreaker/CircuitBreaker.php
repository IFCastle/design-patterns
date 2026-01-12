<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\CircuitBreaker;

use IfCastle\DesignPatterns\CircuitBreaker\BackoffStrategy\BackoffStrategyInterface;

class CircuitBreaker implements CircuitBreakerInterface
{
    protected CircuitBreakerStateEnum $state = CircuitBreakerStateEnum::CLOSED;

    protected InvocationStatInterface $invocationStat;

    protected float $currentDelay   = 0.0;

    protected mixed $setter;

    public function __construct(
        protected BackoffStrategyInterface $backoffStrategy,
        protected int $failureThreshold = 3,
        protected int $successThreshold = 2
    ) {
        $this->invocationStat       = new InvocationStat(fn(callable $setter) => $this->setter = $setter);
    }

    #[\Override]
    public function getInvocationStat(): InvocationStatInterface
    {
        return $this->invocationStat;
    }

    /**
     * Registers a successful call, resets failure counters,
     * and sets the state to CLOSED if in HALF_OPEN state.
     */
    #[\Override]
    public function registerSuccess(): void
    {
        ($this->setter)(true);

        if ($this->backoffStrategy instanceof InvocationTrackingInterface) {
            $this->backoffStrategy->registerSuccess();
        }

        if ($this->state === CircuitBreakerStateEnum::HALF_OPEN && $this->invocationStat->getSuccessCount() >= $this->successThreshold) {
            $this->state            = CircuitBreakerStateEnum::CLOSED;
            $this->invocationStat->resetCounters();
        }
    }

    /**
     * Registers a failed call, increments failure counters,
     * and transitions to OPEN state if the failure threshold is reached.
     */
    #[\Override]
    public function registerFailure(): void
    {
        ($this->setter)(false);

        if ($this->backoffStrategy instanceof InvocationTrackingInterface) {
            $this->backoffStrategy->registerFailure();
        }

        if ($this->invocationStat->getFailureCount() >= $this->failureThreshold) {
            $this->currentDelay     = $this->backoffStrategy->calculateDelay($this->invocationStat->getFailureCount());
            $this->state            = $this->currentDelay > 0 ? CircuitBreakerStateEnum::OPEN : CircuitBreakerStateEnum::CLOSED;
        }
    }

    /**
     * Returns the current state of the Circuit Breaker.
     */
    #[\Override]
    public function getState(): CircuitBreakerStateEnum
    {
        return $this->state;
    }

    /**
     * Checks if the Circuit Breaker can be invoked based on its state.
     */
    #[\Override]
    public function canBeInvoked(): bool
    {
        if ($this->state === CircuitBreakerStateEnum::CLOSED) {
            return true;
        }

        if ($this->state === CircuitBreakerStateEnum::HALF_OPEN) {
            return true;
        }

        if ($this->state === CircuitBreakerStateEnum::OPEN
            && (\time() - $this->invocationStat->getLastCalledAt()) >= $this->currentDelay) {

            $this->state            = CircuitBreakerStateEnum::HALF_OPEN;
            return true;
        }

        return false;
    }

    /**
     * Returns the failure threshold value.
     */
    #[\Override]
    public function getFailureThreshold(): int
    {
        return $this->failureThreshold;
    }

    /**
     * Returns the success threshold value.
     */
    #[\Override]
    public function getSuccessThreshold(): int
    {
        return $this->successThreshold;
    }

    /**
     * Resets the Circuit Breaker to its initial state.
     */
    #[\Override]
    public function resetState(): void
    {
        $this->state                = CircuitBreakerStateEnum::CLOSED;
        $this->invocationStat->resetCounters();
        $this->currentDelay         = 0.0;
    }
}
