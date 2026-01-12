<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Handler;

interface InvokableInterface
{
    public function __invoke(mixed ...$args): mixed;
}
