<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ScopeControl;

trait ScopeTrait
{
    /**
     * Current scope.
     */
    protected string $scope         = ScopeInterface::SCOPE_DEFAULT;

    public function getScopeName(): string
    {
        return $this->scope;
    }

    public function isScopeDefault(): bool
    {
        return $this->scope === ScopeInterface::SCOPE_DEFAULT;
    }

    public function isScopePublic(): bool
    {
        return $this->scope === ScopeInterface::SCOPE_PUBLIC;
    }

    public function isScopeAdmin(): bool
    {
        return $this->scope === ScopeInterface::SCOPE_ADMIN;
    }

    public function isScopeRoot(): bool
    {
        return $this->scope === ScopeInterface::SCOPE_ROOT;
    }
}
