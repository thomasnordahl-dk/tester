<?php
require_once __DIR__ . "/header.php";

use Phlegmatic\Tester\Exception\FailedTestsException;
use Phlegmatic\Tester\TestPackage;
use Phlegmatic\Tester\Tests\Unit\Adapter\OutputResultsRunner\LogResultsTesterUnitTest;
use Phlegmatic\Tester\Tests\Unit\Helper\OutputAssertionTesterUnitTest;
use Phlegmatic\Tester\Tests\Unit\Adapter\OutputResultsRunnerUnitTest;
use Phlegmatic\Tester\Tests\Unit\Adapter\OutputResultsRunner\PackageResultRendererUnitTest;

$unit_tests = new TestPackage("UNIT TESTS", [
    new OutputResultsRunnerUnitTest(),
    new LogResultsTesterUnitTest(),
    new PackageResultRendererUnitTest(),
    new OutputAssertionTesterUnitTest,
]);

$runner = getRunner();

try {
    $runner->run([$unit_tests]);
    exit(0);
} catch (FailedTestsException $e) {
    exit(1);
}
