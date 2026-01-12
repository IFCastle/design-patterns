<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

interface BeforeAfterHandlersAwareInterface
{
    public function getBeforeAfterHandlers(): BeforeAfterActionInterface;
}
