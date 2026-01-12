<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ScopeControl;

trait ScopeMutableTrait
{
    use ScopeTrait;

    public function withScope(string $scope): static
    {
        $this->scope                = $scope;
        return $this;
    }

    public function withDefaultScope(): static
    {
        $this->scope                = ScopeInterface::SCOPE_DEFAULT;
        return $this;
    }

    public function withRootScope(): static
    {
        $this->scope                = ScopeInterface::SCOPE_ROOT;
        return $this;
    }

    public function withAdminScope(): static
    {
        $this->scope                = ScopeInterface::SCOPE_ADMIN;
        return $this;
    }

    public function withPublicScope(): static
    {
        $this->scope                = ScopeInterface::SCOPE_PUBLIC;
        return $this;
    }
}
