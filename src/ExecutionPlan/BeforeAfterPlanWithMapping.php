<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

use IfCastle\Exceptions\BaseException;

/**
 * Organizes an execution plan where Before and After steps can be added to the established plan.
 * The Before/After steps of the plan are guaranteed to be executed in the correct order.
 */
class BeforeAfterPlanWithMapping extends ExecutionPlanWithMapping implements BeforeAfterActionInterface
{
    public const string BEFORE      = '-';

    public const string AFTER       = '+';

    #[\Override]
    public function getBeforeStage(string $action): string|null
    {
        return $action . self::BEFORE;
    }

    #[\Override]
    public function getAfterStage(string $action): string|null
    {
        return $action . self::AFTER;
    }

    /**
     * @throws BaseException
     */
    #[\Override]
    public function addBeforeActionHandler(
        string             $action,
        mixed              $handler,
        InsertPositionEnum $insertPosition = InsertPositionEnum::TO_END
    ): static {
        $this->throwIfImmutable();

        $stage                      = $this->getBeforeStage($action);

        if (false === \array_key_exists($stage, $this->stages)) {
            // Add stage before action

            if (false === \array_key_exists($action, $this->stages)) {
                throw new BaseException([
                    'template'          => 'The action {action} is not found',
                    'action'            => $action,
                    'tags'              => ['designPatterns'],
                ]);
            }

            $position               = \array_search($action, \array_keys($this->stages), true);

            $this->stages           = \array_merge(
                \array_slice($this->stages, 0, $position, true),
                [$stage => []],
                \array_slice($this->stages, $position, null, true)
            );
        }

        return $this->addStageUniqueHandler(
            $this->getBeforeStage($action), $handler, false, null, null, $insertPosition
        );
    }

    /**
     * @throws BaseException
     */
    #[\Override]
    public function addAfterActionHandler(
        string             $action,
        mixed              $handler,
        InsertPositionEnum $insertPosition = InsertPositionEnum::TO_END
    ): static {
        $stage                      = $this->getAfterStage($action);

        if (false === \array_key_exists($stage, $this->stages)) {
            // Add stage after action
            if (false === \array_key_exists($action, $this->stages)) {
                throw new BaseException([
                    'template'          => 'The action {action} is not found',
                    'action'            => $action,
                    'tags'              => ['designPatterns'],
                ]);
            }

            $position               = \array_search($action, \array_keys($this->stages), true);

            $this->stages           = \array_merge(
                \array_slice($this->stages, 0, $position + 1, true),
                [$stage => []],
                \array_slice($this->stages, $position + 1, null, true)
            );
        }

        return $this->addStageUniqueHandler(
            $this->getAfterStage($action), $handler, false, null, null, $insertPosition
        );
    }
}
