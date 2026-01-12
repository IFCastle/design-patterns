<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\ExecutionPlan;

use IfCastle\DesignPatterns\Handler\HandlerWithHashInterface;
use IfCastle\Exceptions\BaseException;
use IfCastle\Exceptions\LogicalException;
use IfCastle\Exceptions\UnexpectedMethodMode;

class ExecutionPlanWithMapping extends ExecutionPlan implements ExecutionPlanWithMappingInterface
{
    protected bool $forbidDuplicateHandlers = true;

    /**
     * @var array<string, callable[]>
     */
    protected array $handlers = [];

    #[\Override]
    public function findHandlerByHash(int|string|null $hash): ?callable
    {
        return $this->handlers[$hash] ?? null;
    }

    /**
     * @throws BaseException
     */
    #[\Override]
    public function addStageUniqueHandler(
        string              $stage,
        mixed               $handler,
        bool                $noRedefine = false,
        mixed               $afterHandler = null,
        mixed               $beforeHandler = null,
        InsertPositionEnum  $insertPosition = InsertPositionEnum::TO_END
    ): static {
        $this->throwIfImmutable();

        // validate action before insert
        if (false === \array_key_exists($stage, $this->stages)) {
            throw new BaseException([
                'template'          => 'The stage {stage} is not found',
                'stage'             => $stage,
                'tags'              => ['designPatterns'],
            ]);
        }

        $hash                       = $this->defineHandlerHash($handler);
        $hash                       = $hash !== null ? (string) $hash : null;

        if ($hash !== null && ($noRedefine || $this->forbidDuplicateHandlers) && \array_key_exists($hash, $this->handlers)) {
            throw new BaseException([
                'template'          => 'Attempting to add a handler {handler} with hash {hash} that already exists',
                'hash'              => $hash,
                'handler'           => \get_debug_type($handler),
                'tags'              => ['designPatterns'],
            ]);
        }

        $this->handlers[$hash]      = $handler;

        $handlers                   = &$this->stages[$stage];

        if ($noRedefine && \array_key_exists($hash, $handlers) && $handlers[$hash] !== $handler) {
            throw new BaseException([
                'template'          => 'Attempt to override an existing handler {handler} with hash {hash}',
                'hash'              => $hash,
                'handler'           => \get_debug_type($handler),
                'tags'              => ['designPatterns'],
            ]);
        }

        if ($beforeHandler === null && $afterHandler === null) {

            if ($insertPosition === InsertPositionEnum::TO_START) {
                $handlers           = [$hash => $handler] + $handlers;
            } else {
                $handlers[$hash]    = $handler;
            }

            return $this;
        }

        if ($beforeHandler !== null && $afterHandler !== null) {
            throw new UnexpectedMethodMode(
                __METHOD__, 'You cannot use parameters $afterHandler/$beforeHandler at the same time'
            );
        }

        $neededHash             = $this->defineHandlerHash($afterHandler ?? $beforeHandler);
        $index                  = $neededHash !== null ? \array_search((string) $neededHash, \array_keys($handlers), true) : false;

        if ($index === false) {
            $handlers[$hash]    = $handler;
            return $this;
        }

        if ($beforeHandler !== null && $index === 0) {
            $handlers           = [$hash => $handler] + $handlers;
            return $this;
        }

        if ($afterHandler !== null) {
            ++$index;
        }

        $handlers               = \array_slice($handlers, 0, $index, true)
                                + [$hash => $handler]
                                + \array_slice($handlers, $index, null, true);

        return $this;
    }

    /**
     * @throws LogicalException
     */
    #[\Override]
    public function removeHandlerByHash(string|int|null $hash): void
    {
        $this->throwIfImmutable();

        if ($hash === null || false === \array_key_exists($hash, $this->handlers)) {
            return;
        }

        unset($this->handlers[$hash]);

        foreach ($this->stages as &$handlers) {
            foreach ($handlers as $key => $handler) {
                if ($this->defineHandlerHash($handler) === $hash) {
                    unset($handlers[$key]);
                }
            }
        }

        unset($handlers);
    }

    #[\Override]
    public function removeStageHandler(callable $handler): void
    {
        $this->removeHandlerByHash($this->defineHandlerHash($handler));
    }

    protected function defineHandlerHash(mixed $handler): string|int|null
    {
        if (\is_string($handler) || \is_int($handler)) {
            return $handler;
        }

        if ($handler instanceof HandlerWithHashInterface) {
            return $handler->getHandlerHash();
        }

        if (\is_object($handler)) {
            return \spl_object_id($handler);
        }

        return null;
    }
}
