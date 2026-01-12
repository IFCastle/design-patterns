<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

interface BeforeAfterActionInterface
{
    public function getBeforeStage(string $action): string|null;

    public function getAfterStage(string $action): string|null;

    public function addBeforeActionHandler(
        string $action,
        mixed $handler,
        InsertPositionEnum $insertPosition = InsertPositionEnum::TO_END
    ): static;

    public function addAfterActionHandler(
        string $action,
        mixed $handler,
        InsertPositionEnum $insertPosition = InsertPositionEnum::TO_END
    ): static;
}
