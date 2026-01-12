<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Handler;

/**
 * WeakStaticHandler.
 *
 * Creates a handler through a static closure that calls the internal method of the class.
 * This approach allows for creating a handler
 * that does not increase the number of references to the object containing the handler method.
 */
final readonly class WeakStaticHandler implements InvokableInterface
{
    /**
     * @var callable(object $self, mixed ...$args): mixed $handler
     */
    private mixed $handler;

    /**
     * @var \WeakReference<object> $object
     */
    private \WeakReference $object;

    public function __construct(callable $handler, object $object)
    {
        $this->handler              = $handler;
        $this->object               = \WeakReference::create($object);
    }

    #[\Override]
    public function __invoke(mixed ...$args): mixed
    {
        $handler                    = $this->handler;
        $object                     = $this->object->get();

        if ($object === null) {
            return null;
        }

        return $handler($object, ...$args);
    }
}
