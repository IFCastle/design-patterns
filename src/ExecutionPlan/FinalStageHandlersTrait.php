<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

use IfCastle\Exceptions\CompositeException;

trait FinalStageHandlersTrait
{
    /**
     * @param array<mixed> $handlers
     * @param \Throwable[] $errors
     *
     * @throws \Throwable
     * @throws CompositeException
     */
    protected function executeFinalStageHandler(
        string                   $finalStage,
        array                    $handlers,
        array                    $errors,
        callable                 $stageSetter,
        HandlerExecutorInterface $handlerExecutor,
        mixed ...$parameters
    ): void {
        $stageSetter($finalStage);

        foreach ($handlers as $handler) {
            try {
                $handlerExecutor->executeHandler($handler, $finalStage, ...$parameters);
            } catch (\Throwable $exception) {
                $errors[]           = $exception;
            }
        }

        if (\count($errors) === 1) {
            throw $errors[0];
        }

        if (\count($errors) > 1) {
            throw new CompositeException('Error execution plan', ...$errors);
        }
    }
}
