<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

use IfCastle\DI\AutoResolverInterface;
use IfCastle\DI\ContainerInterface;
use IfCastle\DI\DependencyInterface;
use IfCastle\DI\DisposableInterface;
use IfCastle\DI\InitializerInterface;
use IfCastle\DI\ResolverInterface;

class HandlerExecutorWithResolverAbstract implements HandlerExecutorInterface
{
    /**
     * @var ContainerInterface|\WeakReference<ContainerInterface>|null
     */
    protected ContainerInterface|\WeakReference|null $container = null;

    protected ResolverInterface $resolver;

    #[\Override]
    public function executeHandler(mixed $handler, string $stage, mixed ...$parameters): mixed
    {
        $container                  = $this->container instanceof \WeakReference ? $this->container->get() : $this->container;

        if ($container === null) {
            throw new \RuntimeException('Container is not set');
        }

        if ($handler instanceof InitializerInterface) {
            $handler                = $handler->executeInitializer($this->container);
        }

        if ($handler instanceof AutoResolverInterface) {
            $handler->resolveDependencies($this->container);
        } elseif ($handler instanceof DependencyInterface) {
            $handler                = $this->resolver->resolveDependency(
                $handler, $this->container, $handler->getDependencyName(), $handler->getDependencyName()
            );
        }

        try {
            if ($handler instanceof StageHandlerInterface) {
                $handler->handleStage($stage, ...$parameters);
            }
        } finally {
            if ($handler instanceof DisposableInterface) {
                $handler->dispose();
            }
        }

        return null;
    }
}
