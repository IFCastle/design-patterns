<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ScopeControl;

interface ScopeInterface
{
    /**
     * Scope modes:
     * Defines how AQL, Entities and Resources work
     */
    final public const string SCOPE_DEFAULT = '';

    final public const string SCOPE_PUBLIC = 'public';

    final public const string SCOPE_ADMIN = 'admin';

    final public const string SCOPE_ROOT = 'root';

    public function getScopeName(): string;

    public function isScopeDefault(): bool;

    public function isScopePublic(): bool;

    public function isScopeAdmin(): bool;

    public function isScopeRoot(): bool;
}
