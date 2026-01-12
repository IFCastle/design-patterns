<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ScopeControl;

readonly class ScopeContext implements ScopeContextInterface
{
    public static function defaultScope(): ScopeContextInterface
    {
        return new self(self::SCOPE_DEFAULT);
    }

    public static function publicScope(): ScopeContextInterface
    {
        return new self(self::SCOPE_PUBLIC);
    }

    public static function adminScope(): ScopeContextInterface
    {
        return new self(self::SCOPE_ADMIN);
    }

    public static function rootScope(): ScopeContextInterface
    {
        return new self(self::SCOPE_ROOT);
    }

    public function __construct(private string $scopeName) {}

    #[\Override]
    public function getScopeName(): string
    {
        return $this->scopeName;
    }

    #[\Override]
    public function isScopeDefault(): bool
    {
        return $this->scopeName === self::SCOPE_DEFAULT;
    }

    #[\Override]
    public function isScopePublic(): bool
    {
        return $this->scopeName === self::SCOPE_PUBLIC;
    }

    #[\Override]
    public function isScopeAdmin(): bool
    {
        return $this->scopeName === self::SCOPE_ADMIN;
    }

    #[\Override]
    public function isScopeRoot(): bool
    {
        return $this->scopeName === self::SCOPE_ROOT;
    }
}
