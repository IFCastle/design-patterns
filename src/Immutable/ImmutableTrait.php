<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Immutable;

use IfCastle\Exceptions\LogicalException;

trait ImmutableTrait
{
    protected bool $isImmutable     = false;

    public function isMutable(): bool
    {
        return $this->isImmutable === false;
    }

    public function isImmutable(): bool
    {
        return $this->isImmutable === true;
    }

    public function asImmutable(): static
    {
        $this->isImmutable = true;
        return $this;
    }

    public function cloneAsMutable(): static
    {
        $cloned                     = clone $this;
        $cloned->isImmutable        = false;
        return $cloned;
    }

    /**
     * @throws LogicalException
     */
    protected function throwIfImmutable(): void
    {
        if ($this->isImmutable) {

            $backtrace              = \debug_backtrace(limit: 3);
            $calledMethod           = 'unknown';

            if (isset($backtrace[2]['function'])) {
                $calledMethod       = $backtrace[2]['function'];
            }

            throw new LogicalException([
                'template'  => 'This object {class} is immutable and cannot be modified. Called from {method}',
                'class'     => static::class,
                'method'    => $calledMethod,
            ]);
        }
    }
}
