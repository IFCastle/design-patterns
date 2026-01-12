<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

final readonly class WeakHandlerExecutor implements HandlerExecutorInterface
{
    /**
     * @var \WeakReference<HandlerExecutorInterface>|\WeakReference<mixed> $executor
     */
    private \WeakReference $executor;

    /**
     * @param callable(mixed $handler, string $stage, mixed ...$parameters): mixed|HandlerExecutorInterface $handler
     */
    public function __construct(callable|HandlerExecutorInterface $handler)
    {
        $this->executor             = \WeakReference::create($handler);
    }

    #[\Override]
    public function executeHandler(mixed $handler, string $stage, mixed ...$parameters): mixed
    {
        $executor                   = $this->executor->get();

        if ($executor instanceof HandlerExecutorInterface) {
            $executor->executeHandler($handler, $stage, ...$parameters);
            return null;
        }

        /* @phpstan-ignore-next-line */
        if (\is_callable($executor)) {
            return $executor($handler, $stage, ...$parameters);
        }

        return null;
    }
}
