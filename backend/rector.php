<?php
// rector.php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rector\Php74\Rector\LNumber\AddLiteralSeparatorToNumberRector;
use Rector\Defluent\Rector\ClassMethod\ReturnThisRemoveRector;
use Rector\Defluent\Rector\MethodCall\FluentChainMethodCallToNormalMethodCallRector;
use Rector\Defluent\Rector\Return_\DefluentReturnMethodCallRector;
use \Rector\SOLID\Rector\ClassMethod\UseInterfaceOverImplementationInConstructorRector;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    $parameters->set(Option::SETS, [
        SetList::CODE_QUALITY,
        SetList::SYMFONY_50,
        SetList::SYMFONY_50_TYPES,
    ]);

    $parameters->set(Option::EXCLUDE_RECTORS, [
        AddLiteralSeparatorToNumberRector::class,
        ReturnThisRemoveRector::class,
        DefluentReturnMethodCallRector::class,
        FluentChainMethodCallToNormalMethodCallRector::class,
        UseInterfaceOverImplementationInConstructorRector::class
    ]);
};
