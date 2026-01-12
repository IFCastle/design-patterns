<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

final class SequentialPlanExecutor implements PlanExecutorInterface
{
    #[\Override]
    public function executePlanStages(
        array                    $stages,
        callable                 $stageSetter,
        HandlerExecutorInterface $handlerExecutor,
        mixed                    ...$parameters
    ): void {
        foreach ($stages as $stage => $handlers) {

            if ($handlers === []) {
                continue;
            }

            $stageSetter($stage);

            foreach ($handlers as $handler) {
                $handlerExecutor->executeHandler($handler, $stage, ...$parameters);
            }
        }
    }
}
