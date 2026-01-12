<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Interceptor;

/**
 * @template T of object
 *
 * This type of Pipeline implementation works in such a way that it allows only the substitution of arguments,
 * but not the Target or the list of interceptors. However, any of the handlers can do the following:
 *
 * * Stop the execution. In this case, only the main handler, the Target, will be executed.
 * * Throw an exception. In this case, the execution will be interrupted.
 */
interface InterceptorPipelineInterface
{
    /**
     * @return T
     */
    public function getTarget(): object;

    /**
     * @return array<mixed>
     */
    public function getArguments(): array;

    /**
     * @param array<mixed> $arguments
     *
     * @return $this
     */
    public function withArguments(array $arguments): static;

    public function hasResult(): bool;

    public function getResult(): mixed;

    public function setResult(mixed $result): static;

    public function resetResult(): static;

    public function stop(): void;

    public function isStopped(): bool;
}
