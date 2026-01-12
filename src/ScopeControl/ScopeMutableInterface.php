<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ScopeControl;

interface ScopeMutableInterface extends ScopeInterface
{
    public function withScope(string $scope): static;

    public function withDefaultScope(): static;

    public function withRootScope(): static;

    public function withAdminScope(): static;

    public function withPublicScope(): static;
}
