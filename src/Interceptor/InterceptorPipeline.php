<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Interceptor;

/**
 * @template T of object
 * @implements InterceptorPipelineInterface<T>
 *
 * This type of Pipeline implementation works in such a way that it allows only the substitution of arguments,
 * but not the Target or the list of interceptors.
 *
 * However, any of the handlers can do the following:
 *
 * * Stop the execution. In this case, only the main handler, the Target, will be executed.
 * * Throw an exception. In this case, the execution will be interrupted.
 */
class InterceptorPipeline implements InterceptorPipelineInterface
{
    protected bool $isStopped       = false;

    /**
     * @var \WeakReference<InterceptorPipelineInterface<T>>|null $mainContext
     */
    protected \WeakReference|null $mainContext = null;

    /**
     * @var InterceptorPipelineInterface<T>|null $nextContext
     */
    protected InterceptorPipelineInterface|null $nextContext = null;

    protected mixed $result         = null;

    protected bool $hasResult       = false;

    /**
     * @var \WeakReference<InterceptorPipelineInterface<T>>|null $lastContext
     */
    protected \WeakReference|null $lastContext = null;

    /**
     * @param array<mixed> $arguments
     * @param InterceptorInterface<T> ...$interceptors
     */
    public function __construct(
        protected object $target,
        protected array $arguments,
        InterceptorInterface ...$interceptors
    ) {
        $nextContext                = $this;

        foreach ($interceptors as $interceptor) {

            $this->lastContext     = \WeakReference::create($nextContext);
            $interceptor->intercept($nextContext);

            if ($this->isStopped) {
                break;
            }

            if ($nextContext->nextContext !== null) {
                $nextContext           = $nextContext->nextContext;
            }
        }
    }

    /**
     * @return T
     */
    #[\Override]
    public function getTarget(): object
    {
        return $this->target;
    }

    #[\Override]
    public function getArguments(): array
    {
        return $this->arguments;
    }

    #[\Override]
    public function withArguments(array $arguments): static
    {
        $clone                      = clone $this;
        $clone->mainContext         = $this->mainContext ?? \WeakReference::create($this);

        $this->nextContext          = $clone;
        $clone->nextContext         = null;

        $clone->arguments           = $arguments;
        $clone->result              = null;

        return $this;
    }

    #[\Override]
    public function hasResult(): bool
    {
        if ($this->mainContext !== null) {
            return $this->mainContext->get()->hasResult();
        }

        return $this->hasResult;
    }

    #[\Override]
    public function getResult(): mixed
    {
        if ($this->mainContext !== null) {
            return $this->mainContext->get()->getResult();
        }

        return $this->result;
    }

    /**
     * @return InterceptorPipelineInterface<T>
     */
    public function getLastContext(): InterceptorPipelineInterface
    {
        return $this->lastContext?->get() ?? $this;
    }

    /**
     * @return array<mixed>
     */
    public function getLastArguments(): array
    {
        $lastContext                = $this->lastContext?->get();

        if ($lastContext === null) {
            return $this->arguments;
        }

        return $lastContext->getArguments();
    }

    #[\Override]
    public function setResult(mixed $result): static
    {
        if ($this->mainContext !== null) {
            $this->mainContext->get()->setResult($result);
            return $this;
        }

        $this->result               = $result;
        $this->hasResult            = true;
        return $this;
    }

    #[\Override]
    public function resetResult(): static
    {
        if ($this->mainContext !== null) {
            $this->mainContext->get()->resetResult();
            return $this;
        }

        $this->result               = null;
        $this->hasResult            = false;
        return $this;
    }

    #[\Override]
    public function stop(): void
    {
        $this->isStopped            = true;
    }

    #[\Override]
    public function isStopped(): bool
    {
        return $this->isStopped;
    }
}
