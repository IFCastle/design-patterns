<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ScopeControl;

interface ScopeInterface
{
    /**
     * Scope modes:
     * Defines how AQL, Entities and Resources work
     * @var string
     */
    final public const string SCOPE_DEFAULT = '';

    /**
     * @var string
     */
    final public const string SCOPE_PUBLIC = 'public';

    /**
     * @var string
     */
    final public const string SCOPE_ADMIN = 'admin';

    /**
     * @var string
     */
    final public const string SCOPE_ROOT = 'root';

    public function getScopeName(): string;

    public function isScopeDefault(): bool;

    public function isScopePublic(): bool;

    public function isScopeAdmin(): bool;

    public function isScopeRoot(): bool;
}
