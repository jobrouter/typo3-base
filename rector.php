<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php55\Rector\String_\StringClassNameToClassConstantRector;
use Rector\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\ValueObject\PhpVersion;

return static function (RectorConfig $config): void {
    $config->phpVersion(PhpVersion::PHP_81);

    $config->sets([
        LevelSetList::UP_TO_PHP_81,
        PHPUnitSetList::PHPUNIT_100,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::TYPE_DECLARATION,
    ]);

    $config->autoloadPaths([
        __DIR__ . '/.Build/vendor/autoload.php',
    ]);
    $config->paths([
        __DIR__ . '/Classes',
        __DIR__ . '/Configuration',
        __DIR__ . '/Tests',
    ]);
    $config->ruleWithConfiguration(StringClassNameToClassConstantRector::class, [
        ''
    ]);
    $config->skip([
        AddLiteralSeparatorToNumberRector::class,
    ]);
};
