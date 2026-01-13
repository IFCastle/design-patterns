<?php

declare(strict_types=1);

use IfCastle\CodeStyle\Rector\RectorConfigurator;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    new RectorConfigurator()->configureSets($rectorConfig);
};
