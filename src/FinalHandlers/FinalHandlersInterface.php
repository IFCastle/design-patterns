<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\FinalHandlers;

interface FinalHandlersInterface
{
    public function addFinalHandler(callable $handler): static;

    public function removeFinalHandler(callable $handler): static;
}
