<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

use PHPUnit\Framework\TestCase;

class BeforeAfterPlanWithMappingTest extends TestCase
{
    public function testAddActionHandler(): void
    {
        $beforeAfterPlan            = new BeforeAfterPlanWithMapping(
            new HandlerExecutorCallable(), ['first', 'second', 'third']
        );

        $result                     = [];

        $printer                    = function (string $text) use (&$result) {
            $result[]               = $text;
        };

        $beforeAfterPlan->addBeforeActionHandler(
            'first',
            fn() => $printer('before first action')
        );

        $beforeAfterPlan->addStageHandler(
            'first',
            fn() => $printer('first action')
        );

        $beforeAfterPlan->addAfterActionHandler(
            'first',
            fn() => $printer('after first action')
        );

        $beforeAfterPlan->addStageHandler(
            'second',
            fn() => $printer('second action')
        );

        $beforeAfterPlan->addStageHandler(
            'third',
            fn() => $printer('third action')
        );

        $beforeAfterPlan->addAfterActionHandler(
            'third',
            fn() => $printer('after third action')
        );

        $beforeAfterPlan->executePlan();

        $this->assertEquals(
            [
                'before first action',
                'first action',
                'after first action',
                'second action',
                'third action',
                'after third action',
            ],
            $result
        );
    }
}
