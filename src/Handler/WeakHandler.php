<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Handler;

/**
 * A class that wraps a handler (callable) using weak references.
 * It facilitates the invocation of the handler,
 * automatically nullifying it if the handler no longer exists.
 */
final readonly class WeakHandler implements InvokableInterface
{
    /**
     * @var \WeakReference<object> $handler
     */
    private \WeakReference $handler;

    public function __construct(callable $handler)
    {
        $this->handler              = \WeakReference::create($handler);
    }

    #[\Override]
    public function __invoke(mixed ...$args): mixed
    {
        $handler                    = $this->handler->get();

        if ($handler === null) {
            return null;
        }

        return $handler(...$args);
    }
}
