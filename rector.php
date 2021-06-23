<?php

declare(strict_types=1);

use Drupal\KernelTests\KernelTestBase;
use Rector\CodingStyle\Rector\FuncCall\PreslashSimpleFunctionRector;
use Rector\Core\Configuration\Option;
use Rector\Php73\Rector\FuncCall\JsonThrowOnErrorRector;
use Rector\PHPUnit\Rector\ClassMethod\AddDoesNotPerformAssertionToNonAssertingTestRector;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Renaming\Rector\MethodCall\RenameMethodRector;
use Rector\Renaming\ValueObject\MethodCallRename;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Rector\Core\ValueObject\PhpVersion;
use Symplify\SymfonyPhpConfig\ValueObjectInliner;

return static function (ContainerConfigurator $containerConfigurator): void {
  $parameters = $containerConfigurator->parameters();

  // paths to refactor; solid alternative to CLI arguments
  $parameters->set(Option::PATHS, [
    __DIR__ . '/docroot/profiles/contrib/degov/'
  ]);

  $parameters->set(Option::SKIP, [
    __DIR__ . '/docroot/profiles/contrib/degov/modules/forks',
    __DIR__ . '/docroot/profiles/contrib/degov/modules/lightning_media/tests/',
    __DIR__ . '/docroot/profiles/contrib/degov/modules/lightning_core/tests/',
    JsonThrowOnErrorRector::class,
    AddDoesNotPerformAssertionToNonAssertingTestRector::class
  ]);
  $parameters->set(Option::FILE_EXTENSIONS, ['php', 'module', 'install', 'profile', 'inc', 'theme']);
  // Define what rule sets will be applied
  $parameters->set(Option::SETS, [
    SetList::PHP_53,
    SetList::PHP_54,
    SetList::PHP_55,
    SetList::PHP_56,
    SetList::PHP_70,
    SetList::PHP_71,
    SetList::PHP_72,
    SetList::PHP_73,
    PHPUnitSetList::PHPUNIT_60,
    PHPUnitSetList::PHPUNIT_70,
    PHPUnitSetList::PHPUNIT_75,
    PHPUnitSetList::PHPUNIT_80,
    PHPUnitSetList::PHPUNIT_90,
    PHPUnitSetList::PHPUNIT_91,
    PHPUnitSetList::PHPUNIT_MOCK,
    PHPUnitSetList::PHPUNIT_YIELD_DATA_PROVIDER
  ]);

  $parameters->set(Option::IMPORT_SHORT_CLASSES, FALSE);
  $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_73);
  $parameters->set(Option::AUTOLOAD_PATHS, [
    __DIR__ . '/docroot/core/tests/bootstrap.php',
  ]);
  // Run Rector only on changed files
  $parameters->set(Option::ENABLE_CACHE, TRUE);
  // Path to phpstan with extensions, that PHPSTan in Rector uses to determine types
  $parameters->set(Option::PHPSTAN_FOR_RECTOR_PATH, __DIR__. '/phpstan.neon.dist');

  $services = $containerConfigurator->services();
  $services->set(PreslashSimpleFunctionRector::class);
  /*
   * Fix: AssertLegacyTrait::assertEqual() is deprecated in drupal:8.0.0 and is removed from drupal:10.0.0. Use $this->assertEquals() instead. See https://www.drupal.org/node/3129738
   */
  $services->set(RenameMethodRector::class)
    ->call('configure', [[
      RenameMethodRector::METHOD_CALL_RENAMES => ValueObjectInliner::inline([
        new MethodCallRename(KernelTestBase::class, 'assertEqual', 'assertEquals'),
        ]),
      ]]);
};
