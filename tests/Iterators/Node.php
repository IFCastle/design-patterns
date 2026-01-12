<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\Iterators;

class Node
{
    public function __construct(public string $name, public array $children = []) {}
}
