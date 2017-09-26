<?php

use ThomasNordahlDk\Tester\TestPackage;
use ThomasNordahlDk\Tester\Tests\Unit\TestPackageUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Assertion\Decorator\ComparisonTesterUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Assertion\Decorator\ExpectedOutputTesterUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\CommandLineFactoryUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\CodeCoverage\CodeCoverageFacadeUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\CodeCoverage\CodeCoverageRunnerUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults\Renderer\AssertionResultRendererUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults\Renderer\PackageRendererUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults\Renderer\TestCaseRendererUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults\RendererFactoryUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults\RenderResultsRunnerUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults\Result\PackageResultUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults\Result\TesterResultUnitTest;
use ThomasNordahlDk\Tester\Tests\Unit\Runner\Utility\CommandLineArgumentsUnitTest;

$unit_tests = new TestPackage(
    "UNIT TESTS",
    [
        # Package class
        new TestPackageUnitTest,

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
        new PackageRendererUnitTest,
        new TestCaseRendererUnitTest,

        # Runner/RenderResults/Result
        new PackageResultUnitTest,
        new TesterResultUnitTest,

        # Runner/Utility
        new CommandLineArgumentsUnitTest,
    ]
);

return [$unit_tests];
