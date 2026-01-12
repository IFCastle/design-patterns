<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Pool;

use IfCastle\DesignPatterns\Factory\FactoryInterface;
use IfCastle\DI\DisposableInterface;

/**
 * @template T of object
 * @implements PoolInterface<T>
 */
class Pool implements PoolInterface
{
    /**
     * List of decorated objects<T>.
     * @var T[]
     */
    private array $borrowed         = [];

    private int   $lastBorrowAt     = 0;

    /**
     * @param FactoryInterface<T>       $factory
     * @param StackInterface<T>         $stack
     * @param ReturnFactoryInterface<T> $returnFactory
     */
    public function __construct(
        protected FactoryInterface          $factory,
        protected int                       $maxPoolSize,
        protected int                       $minPoolSize        = 0,
        protected int                       $timeout            = -1,
        protected int                       $delayPoolReduction = 0,
        protected StackInterface            $stack              = new Stack(),
        protected ReturnFactoryInterface    $returnFactory      = new ReturnFactory()
    ) {}

    protected function init(): void
    {
        for ($i = 0; $i < $this->minPoolSize; $i++) {
            $this->stack->push($this->factory->createObject());
        }
    }

    #[\Override]
    public function borrow(): object|null
    {
        if (\count($this->borrowed) >= $this->maxPoolSize) {
            return null;
        }

        if ($this->stack->getSize() === 0) {
            $this->init();
        }

        if ($this->stack->getSize() > 0) {
            $originalObject         = $this->stack->pop();
            $decorator              = $this->returnFactory->createDecorator($originalObject, $this);
            $this->borrowed[\spl_object_id($decorator)] = $decorator;

            if ($this->delayPoolReduction > 0) {
                $this->lastBorrowAt = \time();
            }

            return $decorator;
        }

        $decorator               = $this->returnFactory->createDecorator($this->factory->createObject(), $this);
        $this->borrowed[\spl_object_id($decorator)] = $decorator;

        return $decorator;
    }

    #[\Override]
    public function return(object $object): void
    {
        if (false === \array_key_exists(\spl_object_id($object), $this->borrowed)) {

            if ($object instanceof DisposableInterface) {
                $object->dispose();
            }

            return;
        }

        unset($this->borrowed[\spl_object_id($object)]);

        $originalObject             = $object->getOriginalObject();

        if ($object instanceof DisposableInterface) {
            $object->dispose();
        }

        //
        // Reduction algorithm:
        // We don't reduce the pool size if the delay time has not expired.
        // This is necessary to avoid frequent creation and destruction of objects.
        //
        $isReduceTimeout            = $this->lastBorrowAt === 0
                                      || (\time() - $this->lastBorrowAt) > $this->delayPoolReduction;

        if ($this->stack->getSize() + 1 > $this->minPoolSize && $isReduceTimeout) {
            return;
        }

        if ($originalObject !== null) {
            $this->stack->push($originalObject);
        }
    }

    #[\Override]
    public function rebuild(): void
    {
        $this->stack->clear();
        $this->borrowed             = [];
    }

    #[\Override]
    public function getMaxPoolSize(): int
    {
        return $this->maxPoolSize;
    }

    #[\Override]
    public function getMinPoolSize(): int
    {
        return $this->minPoolSize;
    }

    #[\Override]
    public function getMaxWaitTimeout(): int
    {
        return 0;
    }

    #[\Override]
    public function getUsed(): int
    {
        return \count($this->borrowed);
    }

    #[\Override]
    public function getPoolSize(): int
    {
        return $this->stack->getSize();
    }
}
