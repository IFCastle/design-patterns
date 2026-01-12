<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

/**
 * ## Plan Executor with Stage Control.
 *
 * The class allows Stage handlers to modify the execution
 * order of the plan by either terminating the current stage processing or directly moving to the next one.
 *
 * To control the order of stage processing,
 * the handler must return an object of the `StagePointer` class,
 * which clearly specifies how the execution order should be modified.
 *
 * Possible modifications:
 * - `finishPlan`   - stop the plan execution.
 * - `goToStage`    - move to the specified stage.
 * - `breakCurrent` - stop the current stage processing.
 *
 * @see StagePointer
 */
class PlanExecutorWithStageControl implements PlanExecutorInterface
{
    #[\Override]
    public function executePlanStages(array $stages, callable $stageSetter, HandlerExecutorInterface $handlerExecutor, mixed ...$parameters): void
    {
        $nextStage                  = null;

        foreach ($stages as $stage => $handlers) {

            if ($nextStage !== null && $stage !== $nextStage) {
                continue;
            }

            if ($handlers === []) {
                continue;
            }

            $stageSetter($stage);

            foreach ($handlers as $handler) {
                $stagePointer       = $handlerExecutor->executeHandler($handler, $stage, ...$parameters);

                if ($stagePointer instanceof StagePointer) {

                    if ($stagePointer->finishPlan) {
                        return;
                    }

                    if ($stagePointer->goToStage !== null) {
                        $nextStage  = $stagePointer->goToStage;
                        break;
                    }

                    if ($stagePointer->breakCurrent) {
                        break;
                    }
                }
            }
        }
    }
}
