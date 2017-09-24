<?php

namespace Phlegmatic\Tester\Tests\Unit\Runner\Adapter\CodeCoverage;


use Phlegmatic\Tester\Runner\Adapter\CodeCoverage\CodeCoverageRunner;
use Phlegmatic\Tester\TestCase;
use Phlegmatic\Tester\Assertion\Tester;
use Phlegmatic\Tester\Tests\Mock\Runner\MockRunner;
use Phlegmatic\Tester\Tests\Mock\Runner\Adapter\CodeCoverage\MockCodeCoverageFacade;
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

        $tester->assert($mock_runner->wasRunWithPackages() === [], "packages passed to base runner");
        $tester->assert($mock_facade->wasStarted(), "runner must start coverage");
        $tester->assert($mock_facade->wasStarted(), "runner must stop coverage");
        $tester->assert($mock_facade->getHtmlDir() === null, "no html was output");
        $tester->assert($mock_facade->getXmlFile() === null, "no xml was output");


        $coverage_runner = new CodeCoverageRunner($mock_runner, $mock_facade);
        $coverage_runner->outputXml("file");

        $coverage_runner->run([]);

        $tester->assert($mock_facade->getXmlFile() === "file", "xml was output");

        $coverage_runner = new CodeCoverageRunner($mock_runner, $mock_facade);
        $coverage_runner->outputHtml("dir");

        $coverage_runner->run([]);

        $tester->assert($mock_facade->getHtmlDir() === "dir", "html was output");
    }
}
