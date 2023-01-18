<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $config): void {
    $config->parallel();

    $config->paths([
        __DIR__ . '/src/',
    ]);

    $config->sets([
        SetList::PSR_12,
        SetList::COMMON,
        SetList::CLEAN_CODE,
    ]);

    $config->lineEnding("\n");
};
