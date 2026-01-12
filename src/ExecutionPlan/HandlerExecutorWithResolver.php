<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

use IfCastle\DI\ContainerInterface;
use IfCastle\DI\ResolverInterface;

class HandlerExecutorWithResolver extends HandlerExecutorWithResolverAbstract
{
    public function __construct(ContainerInterface $container, protected ResolverInterface $resolver)
    {
        $this->container            = $container;
    }
}
