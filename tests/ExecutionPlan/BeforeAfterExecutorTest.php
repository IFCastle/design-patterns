<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

use PHPUnit\Framework\TestCase;

class BeforeAfterExecutorTest extends TestCase
{
    public function testAddBeforeHandler(): void
    {
        $x = 1;

        $beforeAfterExecutor = new BeforeAfterExecutor(new HandlerExecutorCallable());
        $beforeAfterExecutor->addBeforeHandler(function () use (&$x) {
            $x++;
        });

        $beforeAfterExecutor->executePlan();

        $this->assertEquals(2, $x);
    }

    public function testAddHandler(): void
    {
        $x = 1;

        $beforeAfterExecutor = new BeforeAfterExecutor(new HandlerExecutorCallable());
        $beforeAfterExecutor->addHandler(function () use (&$x) {
            $x++;
        });

        $beforeAfterExecutor->executePlan();

        $this->assertEquals(2, $x);
    }

    public function testAddAfterHandler(): void
    {
        $x = 1;

        $beforeAfterExecutor = new BeforeAfterExecutor(new HandlerExecutorCallable());
        $beforeAfterExecutor->addAfterHandler(function () use (&$x) {
            $x++;
        });

        $beforeAfterExecutor->executePlan();

        $this->assertEquals(2, $x);
    }

    public function testOrder(): void
    {
        $callOrder = [];

        $beforeAfterExecutor = new BeforeAfterExecutor(new HandlerExecutorCallable());

        $beforeAfterExecutor->addBeforeHandler(function () use (&$callOrder) {
            $callOrder[] = 'before';
        });

        $beforeAfterExecutor->addHandler(function () use (&$callOrder) {
            $callOrder[] = 'main';
        });

        $beforeAfterExecutor->addAfterHandler(function () use (&$callOrder) {
            $callOrder[] = 'after';
        });

        $beforeAfterExecutor->executePlan();

        $this->assertEquals(['before', 'main', 'after'], $callOrder);
    }

    public function testOrderWithInsertPosition(): void
    {
        $callOrder = [];

        $beforeAfterExecutor = new BeforeAfterExecutor(new HandlerExecutorCallable());

        $beforeAfterExecutor->addBeforeHandler(function () use (&$callOrder) {
            $callOrder[] = 'before';
        });

        $beforeAfterExecutor->addHandler(function () use (&$callOrder) {
            $callOrder[] = 'main';
        });

        $beforeAfterExecutor->addAfterHandler(function () use (&$callOrder) {
            $callOrder[] = 'after';
        });

        $beforeAfterExecutor->addBeforeHandler(function () use (&$callOrder) {
            $callOrder[] = 'before2';
        }, InsertPositionEnum::TO_START);

        $beforeAfterExecutor->addHandler(function () use (&$callOrder) {
            $callOrder[] = 'main2';
        }, InsertPositionEnum::TO_START);

        $beforeAfterExecutor->addAfterHandler(function () use (&$callOrder) {
            $callOrder[] = 'after2';
        }, InsertPositionEnum::TO_START);

        $beforeAfterExecutor->executePlan();

        $this->assertEquals(['before2', 'before', 'main2', 'main', 'after2', 'after'], $callOrder);
    }
}
