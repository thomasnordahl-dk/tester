<?php

use Phlegmatic\Tester\TestPackage;
use Phlegmatic\Tester\Tests\Unit\Assertion\Decorator\ExpectedOutputTesterUnitTest;
use Phlegmatic\Tester\Tests\Unit\Runner\Adapter\CodeCoverage\CodeCoverageFacadeUnitTest;
use Phlegmatic\Tester\Tests\Unit\Runner\Adapter\CodeCoverage\CodeCoverageRunnerUnitTest;
use Phlegmatic\Tester\Tests\Unit\Runner\Adapter\OutputResults\OutputResultsRunnerUnitTest;
use Phlegmatic\Tester\Tests\Unit\Runner\Adapter\OutputResults\OutputResultsTesterUnitTest;
use Phlegmatic\Tester\Tests\Unit\Runner\CommandLine\CommandLineOptionsUnitTest;
use Phlegmatic\Tester\Tests\Unit\Runner\Factory\CommandLineOptionsFactoryUnitTest;
use Phlegmatic\Tester\Tests\Unit\Runner\Factory\RunnerFactoryUnitTest;
use Phlegmatic\Tester\Tests\Unit\TestPackageUnitTest;

$unit_tests = new TestPackage(
    "UNIT TESTS",
    [
        # Package class
        new TestPackageUnitTest(),

        # Tester decorators
        new ExpectedOutputTesterUnitTest,

        # Runner/OutputResults
        new OutputResultsRunnerUnitTest(),
        new OutputResultsTesterUnitTest(),

        # Runner/CodeCoverage
        new CodeCoverageFacadeUnitTest(),
        new CodeCoverageRunnerUnitTest(),

        # Runner/CommandLineOptions
        new CommandLineOptionsUnitTest(),

        # Runner/Factory
        new CommandLineOptionsFactoryUnitTest(),
        new RunnerFactoryUnitTest(),
    ]
);

return [$unit_tests];
