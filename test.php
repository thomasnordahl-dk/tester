<?php
require_once __DIR__ . "/header.php";

use Phlegmatic\Tester\Exception\FailedTestsException;
use Phlegmatic\Tester\TestPackage;
use Phlegmatic\Tester\Tests\Unit\Adapters\OutputResultsRunner\LogResultsTesterUnitTest;
use Phlegmatic\Tester\Tests\Unit\Helpers\OutputAssertionTesterUnitTest;
use Phlegmatic\Tester\Tests\Unit\Adapters\OutputResultsRunnerUnitTest;
use Phlegmatic\Tester\Tests\Unit\Adapters\OutputResultsRunner\PackageResultRendererUnitTest;

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
