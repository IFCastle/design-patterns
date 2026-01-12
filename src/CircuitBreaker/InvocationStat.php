<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\CircuitBreaker;

class InvocationStat implements InvocationStatInterface
{
    protected int $lastCalledAt     = 0;

    protected int $lastSuccessAt    = 0;

    protected int $failureCount     = 0;

    protected int $successCount     = 0;

    protected int $totalCount       = 0;

    protected int $totalFailureCount = 0;

    public function __construct(?callable $tracingSetter = null)
    {
        if ($tracingSetter !== null) {
            $tracingSetter(fn(bool $isSuccess) => $this->registerEvent($isSuccess));
        }
    }


    #[\Override]
    public function getLastCalledAt(): int
    {
        return $this->lastCalledAt;
    }

    #[\Override]
    public function getLastSuccessAt(): int
    {
        return $this->lastSuccessAt;
    }

    #[\Override]
    public function getFailureCount(): int
    {
        return $this->failureCount;
    }

    #[\Override]
    public function getSuccessCount(): int
    {
        return $this->successCount;
    }

    #[\Override]
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    #[\Override]
    public function getTotalFailureCount(): int
    {
        return $this->totalFailureCount;
    }

    #[\Override]
    public function resetCounters(): void
    {
        $this->failureCount         = 0;
        $this->successCount         = 0;
    }

    protected function registerEvent(bool $isSuccess): void
    {
        if ($isSuccess) {
            $this->lastSuccessAt        = \time();
            $this->successCount++;
            $this->totalCount++;
        } else {
            $this->failureCount++;
            $this->totalCount++;
            $this->totalFailureCount++;
        }
    }
}
