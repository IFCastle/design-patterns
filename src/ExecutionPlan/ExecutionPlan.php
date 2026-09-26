<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

use IfCastle\DesignPatterns\Immutable\ImmutableTrait;
use IfCastle\Exceptions\LogicalException;
use IfCastle\Exceptions\UnexpectedValue;

class ExecutionPlan implements ExecutionPlanInterface
{
    use ImmutableTrait;

    /**
     * @var array<string, string[]>
     */
    protected array $stages         = [];

    /**
     * @param array<string> $stages
     */
    public function __construct(
        protected readonly HandlerExecutorInterface $handlerExecutor,
        array $stages,
        protected readonly PlanExecutorInterface $planExecutor = new SequentialPlanExecutor()
    ) {
        foreach ($stages as $stage) {
            $this->stages[$stage]   = [];
        }
    }

    #[\Override]
    public function executePlan(mixed ...$parameters): void
    {
        $this->planExecutor->executePlanStages($this->stages, $this->setCurrentStage(...), $this->handlerExecutor, ...$parameters);
    }

    /**
     * @throws UnexpectedValue
     * @throws LogicalException
     */
    #[\Override]
    public function addStageHandler(
        string $stage,
        mixed $handler,
        InsertPositionEnum  $insertPosition = InsertPositionEnum::TO_END
    ): static {
        $this->throwIfImmutable();

        if (false === \array_key_exists($stage, $this->stages)) {
            throw new UnexpectedValue('$stage', $stage, 'is not a valid stage');
        }

        if (\in_array($handler, $this->stages[$stage], true)) {
            return $this;
        }

        if ($insertPosition === InsertPositionEnum::TO_START) {
            \array_unshift($this->stages[$stage], $handler);

            return $this;
        }

        $this->stages[$stage][]     = $handler;

        return $this;
    }

    /**
     * Called when a run of the plan enters $stage. The plan itself keeps no per-run state, because
     * one plan may run for several requests at once; a subclass forwards the stage to its per-run
     * context.
     */
    protected function setCurrentStage(string $stage): void {}
}
