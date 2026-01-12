<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

class BeforeAfterExecutor extends ExecutionPlan implements BeforeAfterExecutorInterface
{
    public const string BEFORE       = '-';

    public const string MAIN         = '*';

    public const string AFTER        = '+';

    public function __construct(HandlerExecutorInterface $handlerExecutor)
    {
        parent::__construct(
            $handlerExecutor,
            [self::BEFORE, self::MAIN, self::AFTER]
        );
    }

    #[\Override]
    public function addBeforeHandler(mixed $handler, InsertPositionEnum $insertPosition = InsertPositionEnum::TO_END): static
    {
        return $this->addStageHandler(self::BEFORE, $handler, $insertPosition);
    }

    #[\Override]
    public function addHandler(mixed $handler, InsertPositionEnum $insertPosition = InsertPositionEnum::TO_END): static
    {
        return $this->addStageHandler(self::MAIN, $handler, $insertPosition);
    }

    #[\Override]
    public function addAfterHandler(mixed $handler, InsertPositionEnum $insertPosition = InsertPositionEnum::TO_END): static
    {
        return $this->addStageHandler(self::AFTER, $handler, $insertPosition);
    }
}
