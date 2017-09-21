<?php
require_once __DIR__ . "/vendor/autoload.php";

use Phlegmatic\Tester\Exception\FailedTestsException;
use Phlegmatic\Tester\Factory\RunnerFactory;
use Phlegmatic\Tester\TestPackage;
use Phlegmatic\Tester\Tests\Unit\Adapter\OutputResultsRunner\LogResultsTesterUnitTest;
use Phlegmatic\Tester\Tests\Unit\Helper\OutputAssertionTesterUnitTest;
use Phlegmatic\Tester\Tests\Unit\Adapter\OutputResultsRunnerUnitTest;
use Phlegmatic\Tester\Tests\Unit\Adapter\OutputResultsRunner\PackageResultRendererUnitTest;
use Phlegmatic\Tester\Tests\Unit\TestPackageUnitTest;

$unit_tests = new TestPackage("UNIT TESTS", [
    ## TestPackage
    new TestPackageUnitTest(),
    ## OutputResultRunner and related units
    new OutputResultsRunnerUnitTest(),
    new LogResultsTesterUnitTest(),
    new PackageResultRendererUnitTest(),
    ## Helpers
    new OutputAssertionTesterUnitTest,
]);

$runner = RunnerFactory::createDefault();

try {
    $runner->run([$unit_tests]);
    exit(0);
} catch (FailedTestsException $e) {
    exit(1);
}
