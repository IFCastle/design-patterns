<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

interface BeforeAfterExecutorInterface
{
    public function addBeforeHandler(mixed $handler, InsertPositionEnum $insertPosition = InsertPositionEnum::TO_END): static;

    public function addHandler(mixed $handler, InsertPositionEnum $insertPosition = InsertPositionEnum::TO_END): static;

    public function addAfterHandler(mixed $handler, InsertPositionEnum $insertPosition = InsertPositionEnum::TO_END): static;
}
