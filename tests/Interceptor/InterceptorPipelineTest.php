<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Interceptor;

use PHPUnit\Framework\TestCase;

class InterceptorPipelineTest extends TestCase
{
    public function testIntercept(): void
    {
        $interceptor1 = new class implements InterceptorInterface {
            public bool $wasCalled = false;

            public function intercept(InterceptorPipelineInterface $pipeline): void
            {
                $this->wasCalled = true;
                $pipeline->setResult('interceptor1');
            }
        };

        $interceptor2 = new class implements InterceptorInterface {
            public bool $wasCalled = false;

            public function intercept(InterceptorPipelineInterface $pipeline): void
            {
                $this->wasCalled = true;
                $pipeline->setResult('interceptor2');
            }
        };


        $pipeline = new InterceptorPipeline($this, ['arg1', 'arg2'], $interceptor1, $interceptor2);

        $this->assertTrue($interceptor1->wasCalled);
        $this->assertTrue($interceptor2->wasCalled);
        $this->assertTrue($pipeline->hasResult());
        $this->assertFalse($pipeline->isStopped());
        $this->assertEquals('interceptor2', $pipeline->getResult());
    }

    public function testStop(): void
    {
        $interceptor1 = new class implements InterceptorInterface {
            public bool $wasCalled = false;

            public function intercept(InterceptorPipelineInterface $pipeline): void
            {
                $this->wasCalled = true;
                $pipeline->setResult('interceptor1');
                $pipeline->stop();
            }
        };

        $interceptor2 = new class implements InterceptorInterface {
            public bool $wasCalled = false;

            public function intercept(InterceptorPipelineInterface $pipeline): void
            {
                $this->wasCalled = true;
                $pipeline->setResult('interceptor2');
            }
        };


        $pipeline = new InterceptorPipeline($this, ['arg1', 'arg2'], $interceptor1, $interceptor2);

        $this->assertTrue($interceptor1->wasCalled);
        $this->assertFalse($interceptor2->wasCalled);
        $this->assertTrue($pipeline->hasResult());
        $this->assertTrue($pipeline->isStopped());
        $this->assertEquals('interceptor1', $pipeline->getResult());
    }

    public function testChangeArguments(): void
    {
        $interceptor1 = new class implements InterceptorInterface {
            public bool $wasCalled = false;

            public function intercept(InterceptorPipelineInterface $pipeline): void
            {
                $this->wasCalled = true;

                if (['arg1', 'arg2'] !== $pipeline->getArguments()) {
                    throw new \RuntimeException('Arguments is not equal to expected');
                }

                $pipeline->setResult('interceptor1');
                $pipeline->withArguments(['arg3', 'arg4']);
            }
        };

        $interceptor2 = new class implements InterceptorInterface {
            public bool $wasCalled = false;

            public function intercept(InterceptorPipelineInterface $pipeline): void
            {
                $this->wasCalled = true;
                $pipeline->setResult($pipeline->getArguments());
            }
        };

        $pipeline = new InterceptorPipeline($this, ['arg1', 'arg2'], $interceptor1, $interceptor2);

        $this->assertTrue($interceptor1->wasCalled);
        $this->assertTrue($interceptor2->wasCalled);
        $this->assertTrue($pipeline->hasResult());
        $this->assertFalse($pipeline->isStopped());
        $this->assertEquals(['arg3', 'arg4'], $pipeline->getResult());
    }

}
