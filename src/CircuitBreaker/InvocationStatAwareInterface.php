<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\CircuitBreaker;

interface InvocationStatAwareInterface
{
    public function getInvocationStat(): InvocationStatInterface;
}
