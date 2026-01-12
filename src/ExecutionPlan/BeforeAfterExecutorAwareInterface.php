<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

interface BeforeAfterExecutorAwareInterface
{
    public function getBeforeAfterExecutor(): BeforeAfterExecutorInterface;
}
