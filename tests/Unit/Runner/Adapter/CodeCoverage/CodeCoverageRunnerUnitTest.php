<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\CodeCoverage;


use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CodeCoverageRunner;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Tester;
use ThomasNordahlDk\Tester\Tests\Mock\Runner\MockRunner;
use ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\CodeCoverage\MockCodeCoverageFacade;
class CodeCoverageRunnerUnitTest implements TestCase
{

    public function getDescription(): string
    {
        return "Unit test of " . CodeCoverageRunner::class;
    }

    public function run(Tester $tester): void
    {
        $mock_runner = new MockRunner();
        $mock_facade = new MockCodeCoverageFacade();

        $coverage_runner = new CodeCoverageRunner($mock_runner, $mock_facade);

        $coverage_runner->run([]);

        $tester->assert($mock_runner->wasRunWithPackages() === [], "suites passed to base runner");
        $tester->assert($mock_facade->wasStarted(), "runner must start coverage");
        $tester->assert($mock_facade->wasStopped(), "runner must stop coverage");
        $tester->assert($mock_facade->getHtmlDir() === null, "no html was output");
        $tester->assert($mock_facade->getXmlFile() === null, "no xml was output");


        $coverage_runner = new CodeCoverageRunner($mock_runner, $mock_facade);
        $coverage_runner->outputsCloverReportTo("file");

        $coverage_runner->run([]);

        $tester->assert($mock_facade->getXmlFile() === "file", "xml was output");

        $coverage_runner = new CodeCoverageRunner($mock_runner, $mock_facade);
        $coverage_runner->outputsHtmlReportTo("dir");

        $coverage_runner->run([]);

        $tester->assert($mock_facade->getHtmlDir() === "dir", "html was output");
    }
}
