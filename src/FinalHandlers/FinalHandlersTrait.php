<?php

declare(strict_types=1);

namespace IfCastle\DesignPatterns\FinalHandlers;

trait FinalHandlersTrait
{
    protected array $finalHandlers  = [];

    public function addFinalHandler(callable $handler): static
    {
        $this->finalHandlers[]      = $handler;

        return $this;
    }

    public function removeFinalHandler(callable $handler): static
    {
        $index                      = \array_search($handler, $this->finalHandlers, true);

        if ($index !== false) {
            unset($this->finalHandlers[$index]);
        }

        return $this;
    }

    protected function executeFinalHandlers(): array
    {
        $errors                     = [];
        $finalHandlers              = $this->finalHandlers;
        $this->finalHandlers        = [];

        foreach ($finalHandlers as $handler) {
            try {
                $handler();
            } catch (\Throwable $exception) {
                $errors[]           = $exception;
            }
        }

        return $errors;
    }
}
