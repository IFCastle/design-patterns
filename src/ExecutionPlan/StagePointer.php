<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

/**
 * The data structure indicates how the execution order of the plan should be modified.
 */
final readonly class StagePointer
{
    public function __construct(
        public bool $breakCurrent       = false,
        public bool $finishPlan         = false,
        public string|null $goToStage   = null
    ) {}
}
