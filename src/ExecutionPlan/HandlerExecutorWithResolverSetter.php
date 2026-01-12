<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

use IfCastle\DI\ContainerInterface;
use IfCastle\DI\ResolverInterface;

class HandlerExecutorWithResolverSetter extends HandlerExecutorWithResolverAbstract
{
    public function setResolver(ResolverInterface $resolver): void
    {
        $this->resolver = $resolver;
    }

    public function setContainer(ContainerInterface $container): void
    {
        $this->container = \WeakReference::create($container);
    }
}
