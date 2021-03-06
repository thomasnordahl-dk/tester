<?php

use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\Simple\SimpleRunnerUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\Simple\SimpleTesterUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Timer\TimerFactoryUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Timer\TimerUnitTest;
use ThomasNordahlDk\Tester\TestSuite;
use ThomasNordahlDk\Tester\Tests\Unit\SuiteUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Decorator\ComparisonTesterUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Decorator\ExpectedOutputTesterUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\CodeCoverage\CodeCoverageFacadeUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\CodeCoverage\CodeCoverageRunnerUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\CommandLine\CommandLineRunnerFactoryUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\CommandLine\CommandLineArgumentsUnitTest;

$unit_tests = new TestSuite("UNIT TESTS", [
    new SuiteUnitTest,
    # Decorators/
    new ComparisonTesterUnitTest,
    new ExpectedOutputTesterUnitTest,
    # Runner/Adapter/CodeCoverage
    new CodeCoverageFacadeUnitTest,
    new CodeCoverageRunnerUnitTest,
    # Runner/CommandLine
    new CommandLineArgumentsUnitTest,
    new CommandLineRunnerFactoryUnitTest,
    # Runner
    new TimerUnitTest,
    new TimerFactoryUnitTest,
    # Runner\Adapter\Simple
    new SimpleTesterUnitTest,
    new SimpleRunnerUnitTest,
]);

return [$unit_tests];
