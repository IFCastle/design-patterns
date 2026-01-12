<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

/**
 * Class WeakStaticClosureExecutor.
 *
 * Creates a handler through a static closure, with this object passed as the first argument.
 * This way, the class allows creating handlers that do not create additional
 * references to the $this object while still calling its internal methods through the closure.
 *
 * Example:
 * ```php
 * new WeakStaticClosureExecutor(static fn($self, $handler, $stage, mixed ...$parameters) => $self->handlerExecutor($handler, $stage, ...$parameters), $this)
 * ```
 *
 * @template T of object
 *
 */
final readonly class WeakStaticClosureExecutor implements HandlerExecutorInterface
{
    /**
     * @var \WeakReference<T>
     */
    private \WeakReference $self;

    /**
     * @param \Closure(object $self, mixed $handler, string $stage, mixed ...$parameters): mixed $executor
     * @param T        $self
     */
    public function __construct(private \Closure $executor, object $self)
    {
        $this->self                 = \WeakReference::create($self);
    }


    #[\Override]
    public function executeHandler(mixed $handler, string $stage, mixed ...$parameters): mixed
    {
        $self                       = $this->self->get();
        $executor                   = $this->executor;

        if (\is_callable($executor)) {
            return $executor($self, $handler, $stage, ...$parameters);
        }

        /* @phpstan-ignore-next-line */
        return null;
    }
}
