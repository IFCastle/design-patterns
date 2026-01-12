<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

use IfCastle\DesignPatterns\Handler\HandlerWithHashInterface;
use PHPUnit\Framework\TestCase;

class ExecutionPlanWithMappingTest extends TestCase
{
    public function testAddStageUniqueHandlerAndFind(): void
    {
        $executionPlan              = new ExecutionPlanWithMapping(
            new HandlerExecutorCallable(), ['first', 'second', 'third']
        );

        $result                     = [];

        $printer                    = function (string $text) use (&$result) {
            $result[]               = $text;
        };

        $hashHandler1               = new class ($printer) implements HandlerWithHashInterface {
            public function __construct(private readonly mixed $printer) {}

            public function __invoke(): void
            {
                ($this->printer)('hash1');
            }

            #[\Override]
            public function getHandlerHash(): string|int|null
            {
                return 'hash1';
            }
        };

        $hashHandler2               = new class ($printer) implements HandlerWithHashInterface {
            public function __construct(private readonly mixed $printer) {}

            public function __invoke(): void
            {
                ($this->printer)('hash2');
            }

            #[\Override]
            public function getHandlerHash(): string|int|null
            {
                return 'hash2';
            }
        };

        $executionPlan->addStageHandler(
            'first',
            fn() => $printer('first action')
        );

        $executionPlan->addStageHandler(
            'second',
            fn() => $printer('second action')
        );

        $executionPlan->addStageHandler(
            'third',
            fn() => $printer('third action')
        );

        $executionPlan->addStageUniqueHandler('first', $hashHandler1);
        $executionPlan->addStageUniqueHandler('first', $hashHandler2, beforeHandler: $hashHandler1);

        $foundHandler               = $executionPlan->findHandlerByHash('hash2');

        $this->assertEquals($hashHandler2, $foundHandler);

        $executionPlan->executePlan();

        $this->assertEquals(
            [
                'first action',
                'hash2',
                'hash1',
                'second action',
                'third action',
            ],
            $result
        );
    }
}
