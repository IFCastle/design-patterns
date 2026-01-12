<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

final class HandlerExecutorWithClass implements HandlerExecutorInterface
{
    #[\Override]
    public function executeHandler(mixed $handler, string $stage, mixed ...$parameters): mixed
    {
        if (\is_string($handler) && \class_exists($handler)) {
            $handler = new $handler();
        }

        if (\is_callable($handler)) {
            return $handler($stage, ...$parameters);
        }

        return null;
    }
}
