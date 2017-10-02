<?php

use ThomasNordahlDk\Tester\TestSuite;
use ThomasNordahlDk\Tester\Tests\Unit\SuiteUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Assertion\Decorator\ComparisonTesterUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Assertion\Decorator\ExpectedOutputTesterUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\CommandLineFactoryUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\CodeCoverage\CodeCoverageFacadeUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\CodeCoverage\CodeCoverageRunnerUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults\Renderer\AssertionResultRendererUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults\Renderer\TestSuiteRendererUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults\Renderer\TestCaseRendererUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults\RendererFactoryUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults\RenderResultsRunnerUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults\Result\TestSuiteResultUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults\Result\TesterResultUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Utility\CommandLineArgumentsUnitTest;

$unit_tests = new TestSuite(
    "UNIT TESTS",
    # Package class
    new SuiteUnitTest,

    # Tester decorators
    new ComparisonTesterUnitTest,
    new ExpectedOutputTesterUnitTest,

    # Runner/Factory
    new CommandLineFactoryUnitTest,

    # Runner/Adapter/CodeCoverage
    new CodeCoverageFacadeUnitTest,
    new CodeCoverageRunnerUnitTest,

    # Runner/RenderResults
    new RendererFactoryUnitTest,
    new RenderResultsRunnerUnitTest,

    # Runner/RenderResults/Renderer
    new AssertionResultRendererUnitTest,
    new TestSuiteRendererUnitTest,
    new TestCaseRendererUnitTest,

    # Runner/RenderResults/Result
    new TestSuiteResultUnitTest,
    new TesterResultUnitTest,

    # Runner/Utility
    new CommandLineArgumentsUnitTest
);

return [$unit_tests];