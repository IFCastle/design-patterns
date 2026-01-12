<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

interface HandlerExecutorInterface
{
    /**
     * @param callable(mixed $handler, string $stage, mixed ...$parameters): mixed $handler
     */
    public function executeHandler(mixed $handler, string $stage, mixed ...$parameters): mixed;
}
