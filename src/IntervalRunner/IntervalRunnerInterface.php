<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\IntervalRunner;

interface IntervalRunnerInterface
{
    public function tryInvoke(?callable $function = null): void;

    public function shouldInvoke(): bool;

    public function isSuccessful(): bool;

    public function getLastInvocationTime(): int;
}
