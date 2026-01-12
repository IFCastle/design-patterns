<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Interceptor;

use IfCastle\DI\ConstructibleDependencyByReflection;
use IfCastle\DI\Container;
use IfCastle\DI\ContainerInterface;
use IfCastle\DI\Exceptions\DependencyNotFound;
use IfCastle\DI\Resolver;
use IfCastle\DI\ResolverInterface;
use IfCastle\Exceptions\UnexpectedValueType;

class InterceptorRegistry extends Container implements InterceptorRegistryInterface
{
    /**
     * @var array<string, string[]>
     */
    protected array $referenceByInterface   = [];

    public function __construct(
        ?ContainerInterface $parentContainer = null,
        ?ResolverInterface $resolver         = null
    ) {
        parent::__construct($resolver ?? new Resolver(), [], $parentContainer, true);
    }

    #[\Override]
    public function registerInterceptor(array|string $interface, object $interceptor): static
    {
        $hash                       = (string) \spl_object_id($interceptor);

        $this->container[$hash]     = $interceptor;

        foreach (\is_array($interface) ? $interface : [$interface] as $key) {
            $this->referenceByInterface[$key][] = $hash;
        }

        return $this;
    }

    #[\Override]
    public function registerInterceptorConstructible(array|string $interface, string $class): static
    {
        return $this->registerInterceptor($interface, new ConstructibleDependencyByReflection($class));
    }

    #[\Override]
    public function registerInterceptorInjectable(array|string $interface, string $class): static
    {
        return $this->registerInterceptor($interface, new ConstructibleDependencyByReflection($class, false));
    }

    /**
     * @throws DependencyNotFound
     * @throws UnexpectedValueType
     */
    #[\Override]
    public function resolveInterceptors(string $interface): array
    {
        if (false === \array_key_exists($interface, $this->referenceByInterface)) {
            return [];
        }

        $interceptors               = [];

        foreach ($this->referenceByInterface[$interface] as $hash) {
            $interceptor            = $this->resolveDependency($hash);

            if (false === $interceptor instanceof InterceptorInterface) {
                throw new UnexpectedValueType('interceptor:' . $interface, $interceptor, InterceptorInterface::class);
            }

            $interceptors[]         = $interceptor;
        }

        return $interceptors;
    }
}
