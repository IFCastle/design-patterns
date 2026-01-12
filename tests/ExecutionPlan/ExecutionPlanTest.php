<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

use IfCastle\Exceptions\UnexpectedValue;
use PHPUnit\Framework\TestCase;

class ExecutionPlanTest extends TestCase
{
    /**
     * @throws UnexpectedValue
     */
    public function testAddStageHandler(): void
    {
        $x                          = 1;

        $executionPlan = new ExecutionPlan(new HandlerExecutorCallable(), ['test1', 'test2', 'test3']);
        $executionPlan->addStageHandler('test1', function () use (&$x) {
            $x++;
        });

        $executionPlan->executePlan();

        $this->assertEquals(2, $x);
    }

    public function testOrderOfExecution(): void
    {
        $callOrder = [];

        $executionPlan = new ExecutionPlan(new HandlerExecutorCallable(), ['test1', 'test2', 'test3']);

        $executionPlan->addStageHandler('test1', function () use (&$callOrder) {
            $callOrder[] = 'test1';
        });

        $executionPlan->addStageHandler('test2', function () use (&$callOrder) {
            $callOrder[] = 'test2';
        });

        $executionPlan->addStageHandler('test3', function () use (&$callOrder) {
            $callOrder[] = 'test3';
        });

        $executionPlan->executePlan();

        $this->assertEquals(['test1', 'test2', 'test3'], $callOrder);
    }
}
