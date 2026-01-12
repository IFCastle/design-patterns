<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Handler;

use PHPUnit\Framework\TestCase;

class WeakStaticHandlerTest extends TestCase
{
    public function testHandler(): void
    {
        $class                      = new class {
            public int $value       = 0;

            public function getHandler(): callable
            {
                return static fn(self $self) => $self->handler();
            }

            private function handler(): void
            {
                $this->value++;
            }
        };

        $handler                    = new WeakStaticHandler($class->getHandler(), $class);
        $handler();

        $this->assertEquals(1, $class->value);
    }
}
