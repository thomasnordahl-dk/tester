<?php
require_once __DIR__ . "/header.php";

use Phlegmatic\Tester\Exception\FailedTestsException;
use Phlegmatic\Tester\TestPackage;
use Phlegmatic\Tester\Tests\Unit\LogResultsTesterUnitTest;
use Phlegmatic\Tester\Tests\Unit\OutputBufferTesterUnitTest;
use Phlegmatic\Tester\Tests\Unit\OutputResultsTestRunnerUnitTest;

$unit_tests = new TestPackage("UNIT TESTS", [
    new LogResultsTesterUnitTest(),
    new OutputResultsTestRunnerUnitTest(),
    new OutputBufferTesterUnitTest,
]);

$runner = getRunner();

try {
    $runner->run([$unit_tests]);
    exit(0);
} catch (FailedTestsException $e) {
    echo $e->getMessage() . "\n";
    exit(1);
}
