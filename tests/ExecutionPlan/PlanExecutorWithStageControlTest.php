<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

use PHPUnit\Framework\TestCase;

class PlanExecutorWithStageControlTest extends TestCase
{
    public function testGoToStageResumesNormalOrderAfterTheTarget(): void
    {
        $calls                      = [];
        $record                     = static function (string $stage) use (&$calls): void {
            $calls[]                = $stage;
        };

        $executionPlan              = new ExecutionPlan(
            new HandlerExecutorCallable(), ['a', 'b', 'c', 'd'], new PlanExecutorWithStageControl()
        );

        $executionPlan->addStageHandler('a', static function (string $stage) use ($record): StagePointer {
            $record($stage);
            return new StagePointer(goToStage: 'c');
        });

        foreach (['b', 'c', 'd'] as $stage) {
            $executionPlan->addStageHandler($stage, $record);
        }

        $executionPlan->executePlan();

        $this->assertSame(['a', 'c', 'd'], $calls);
    }
}
