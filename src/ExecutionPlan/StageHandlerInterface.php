<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

interface StageHandlerInterface
{
    public function handleStage(string $stage, mixed ...$parameters): void;
}
