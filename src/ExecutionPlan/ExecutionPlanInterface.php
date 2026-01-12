<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

use IfCastle\DesignPatterns\Immutable\ImmutableInterface;

interface ExecutionPlanInterface extends ImmutableInterface
{
    public function getCurrentStage(): string;

    public function executePlan(mixed ...$parameters): void;

    public function addStageHandler(
        string $stage,
        mixed $handler,
        InsertPositionEnum $insertPosition = InsertPositionEnum::TO_END
    ): static;

    public function isMutable(): bool;

    public function asImmutable(): static;
}
