<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

interface PlanExecutorInterface
{
    /**
     * @param array<string, array<mixed>>   $stages
     * @param callable(string $stage): void $stageSetter
     */
    public function executePlanStages(
        array                    $stages,
        callable                 $stageSetter,
        HandlerExecutorInterface $handlerExecutor,
        mixed                    ...$parameters
    ): void;
}
