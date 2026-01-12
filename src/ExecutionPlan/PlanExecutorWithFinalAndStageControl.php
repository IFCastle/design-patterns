<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

use IfCastle\Exceptions\CompositeException;

final class PlanExecutorWithFinalAndStageControl extends PlanExecutorWithStageControl
{
    use FinalStageHandlersTrait;

    /**
     * @throws \Throwable
     * @throws CompositeException
     */
    #[\Override]
    public function executePlanStages(
        array                    $stages,
        callable                 $stageSetter,
        HandlerExecutorInterface $handlerExecutor,
        mixed ...$parameters
    ): void {
        if ($stages === []) {
            return;
        }

        $finalStage                 = \array_key_last($stages);
        $finalHandlers              = \array_pop($stages);
        $errors                     = [];

        try {
            parent::executePlanStages(
                $stages,
                $stageSetter,
                $handlerExecutor,
                ...$parameters
            );
        } catch (\Throwable $exception) {
            $errors[]               = $exception;
        }

        $this->executeFinalStageHandler($finalStage, $finalHandlers, $errors, $stageSetter, $handlerExecutor, ...$parameters);
    }
}
