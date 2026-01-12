<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Interceptor;

interface InterceptorRegistryInterface
{
    /**
     * Register an interceptor for a given interface.
     *
     * @param string|string[] $interface
     *
     */
    public function registerInterceptor(string|array $interface, object $interceptor): static;

    /**
     * Register an interceptor for a given interface.
     *
     * @param string|string[] $interface
     *
     */
    public function registerInterceptorConstructible(string|array $interface, string $class): static;

    /**
     * Register an interceptor for a given interface.
     *
     * @param string|string[] $interface
     *
     */
    public function registerInterceptorInjectable(string|array $interface, string $class): static;

    /**
     * Resolve interceptors for a given interface.
     *
     *
     * @return InterceptorInterface<object>[]
     */
    public function resolveInterceptors(string $interface): array;
}
