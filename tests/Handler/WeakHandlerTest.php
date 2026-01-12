<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Handler;

use PHPUnit\Framework\TestCase;

class WeakHandlerTest extends TestCase
{
    public function testWeakHandler(): void
    {
        $x                          = 0;
        $callable                   = function () use (&$x) {$x++;};
        $handler                    = new WeakHandler($callable);
        $this->assertInstanceOf(WeakHandler::class, $handler);
        $handler();
        $this->assertEquals(1, $x);
    }

    public function testClassHandler(): void
    {
        $class = new class {
            public int $x = 0;

            public function someMethod(): void
            {
                $this->x++;
            }
        };

        $callable                   = $class->someMethod(...);
        $handler                    = new WeakHandler($callable);
        $this->assertInstanceOf(WeakHandler::class, $handler);

        $handler();

        $this->assertEquals(1, $class->x);
    }
}
