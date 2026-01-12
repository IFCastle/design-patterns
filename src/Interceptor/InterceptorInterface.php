<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Interceptor;

/**
 * ## InterceptorInterface.
 *
 * Interceptor is a design pattern that allows you to add new behavior to an object without changing its code.
 * It is a way to modify the behavior of a class method, or a function, without changing the code of the method.
 *
 * @template T of object
 */
interface InterceptorInterface
{
    /**
     * Called before the execution of the target.
     * @param InterceptorPipelineInterface<T> $pipeline
     */
    public function intercept(InterceptorPipelineInterface $pipeline): void;
}
