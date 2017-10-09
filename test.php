<?php

use ThomasNordahlDk\Tester\Tests\Unit\Runner\TimerUnitTest;
use ThomasNordahlDk\Tester\TestSuite;
use ThomasNordahlDk\Tester\Tests\Unit\SuiteUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Decorator\ComparisonTesterUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Decorator\ExpectedOutputTesterUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\CodeCoverage\CodeCoverageFacadeUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\CodeCoverage\CodeCoverageRunnerUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\OutputResults\Assertion\OutputResultsTesterUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\OutputResults\TestCase\TestCaseRunnerUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\OutputResults\TestSuite\TestSuiteRunnerUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\OutputResults\OutputResultsFactoryUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\OutputResults\OutputResultsRunnerUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\CommandLine\CommandLineRunnerFactoryUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\CommandLine\CommandLineArgumentsUnitTest;

$unit_tests = new TestSuite("UNIT TESTS", ...[
    new SuiteUnitTest,
    # Decorators/
    new ComparisonTesterUnitTest,
    new ExpectedOutputTesterUnitTest,
    # Runner/Adapter/CodeCoverage
    new CodeCoverageFacadeUnitTest,
    new CodeCoverageRunnerUnitTest,
    # Runner/Adapter/OutputResults/Assertion
    new OutputResultsTesterUnitTest,
    # Runner/Adapter/OutputResults/TestCase
    new TestCaseRunnerUnitTest,
    # Runner/Adapter/OutputResults/TestSuite
    new TestSuiteRunnerUnitTest,
    # Runner/Adapter/OutputResults
    new OutputResultsFactoryUnitTest,
    new OutputResultsRunnerUnitTest,
    # Runner/CommandLine
    new CommandLineArgumentsUnitTest,
    new CommandLineRunnerFactoryUnitTest,
    # Runner
    new TimerUnitTest,
]);

return [$unit_tests];
