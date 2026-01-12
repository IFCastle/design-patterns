<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ScopeControl;

interface ScopeContextAwareInterface
{
    public function obtainScopeContext(): ScopeContextInterface;
}
