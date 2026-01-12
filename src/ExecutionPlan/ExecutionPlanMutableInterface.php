<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

interface ExecutionPlanMutableInterface
{
    public function removeStageHandler(callable $handler): void;
}
