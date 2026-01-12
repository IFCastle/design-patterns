<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\CircuitBreaker;

interface CircuitBreakerInterface extends InvocationTrackingInterface, InvocationStatAwareInterface
{
    public function getState(): CircuitBreakerStateEnum;

    public function canBeInvoked(): bool;

    public function getFailureThreshold(): int;

    public function getSuccessThreshold(): int;

    /**
     * Resets the Circuit Breaker to its initial state.
     */
    public function resetState(): void;
}
