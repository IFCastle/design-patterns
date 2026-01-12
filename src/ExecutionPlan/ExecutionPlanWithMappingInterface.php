<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

use IfCastle\DesignPatterns\Handler\HandlerWithHashAwareInterface;

interface ExecutionPlanWithMappingInterface extends ExecutionPlanInterface, HandlerWithHashAwareInterface, ExecutionPlanMutableInterface
{
    public function addStageUniqueHandler(
        string              $stage,
        mixed               $handler,
        bool                $noRedefine     = false,
        mixed               $afterHandler   = null,
        mixed               $beforeHandler  = null,
        InsertPositionEnum $insertPosition  = InsertPositionEnum::TO_END
    ): static;

    public function removeHandlerByHash(string|int|null $hash): void;
}
