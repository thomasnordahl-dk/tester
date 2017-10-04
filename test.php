<?php

use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\OutputResults\OutputResultsFactoryUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\OutputResults\OutputResultsRunnerUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\OutputResults\TestCaseRunnerUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\OutputResults\Assertion\OutputResultsTesterUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\OutputResults\TestSuiteRunnerUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Configuration\CoverageConfigurationUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Configuration\ConfigurationUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Factory\ConfigurationRunnerFactoryUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Configuration\CommandLineRunnerFactoryTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\RunnerFactoryUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Utility\CommandLineRunnerFactoryUnitTest;
use ThomasNordahlDk\Tester\TestSuite;
use ThomasNordahlDk\Tester\Tests\Unit\SuiteUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Assertion\Decorator\ComparisonTesterUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Assertion\Decorator\ExpectedOutputTesterUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\CodeCoverage\CodeCoverageFacadeUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\CodeCoverage\CodeCoverageRunnerUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Utility\CommandLineArgumentsUnitTest;

$unit_tests = new TestSuite(...[
    "UNIT TESTS",
    # Package class
    new SuiteUnitTest,

    # Tester decorators
    new ComparisonTesterUnitTest,
    new ExpectedOutputTesterUnitTest,

    # Runner

    # Runner/Adapter/CodeCoverage
    new CodeCoverageFacadeUnitTest,
    new CodeCoverageRunnerUnitTest,

    # Runner/Utility
    new CommandLineArgumentsUnitTest,

    # Runner/Adapter/OutputResults
    new OutputResultsFactoryUnitTest,
    new OutputResultsTesterUnitTest,
    new TestCaseRunnerUnitTest,
    new TestSuiteRunnerUnitTest,
    new OutputResultsRunnerUnitTest,

    new CommandLineRunnerFactoryUnitTest,
]);

return [$unit_tests];
