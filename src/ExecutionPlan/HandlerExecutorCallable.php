<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

final class HandlerExecutorCallable implements HandlerExecutorInterface
{
    #[\Override]
    public function executeHandler(mixed $handler, string $stage, mixed ...$parameters): mixed
    {
        if (\is_callable($handler)) {
            return $handler($stage, ...$parameters);
        }

        /* @phpstan-ignore-next-line */
        return null;
    }
}
