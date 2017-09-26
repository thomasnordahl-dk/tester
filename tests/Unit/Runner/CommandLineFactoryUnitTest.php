<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner;

use ThomasNordahlDk\Tester\Assertion\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Assertion\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CodeCoverageFacade;
use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CodeCoverageRunner;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\RenderResultsRunner;
use ThomasNordahlDk\Tester\Runner\CommandLineFactory;
use ThomasNordahlDk\Tester\Runner\Utility\CommandLineArguments;
use ThomasNordahlDk\Tester\TestCase;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Report\Clover;
use SebastianBergmann\CodeCoverage\Report\Html\Facade;

class CommandLineFactoryUnitTest implements TestCase
{
    const COVERAGE_HTML_DEFAULT_DIR = "coverage";
    const DEFUALT_COVERAGE_XML_FILE = "coverage.xml";
    /**
     * @var ComparisonTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit test of " . CommandLineFactory::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new ComparisonTester($tester);

        $this->testNoArguments();
        $this->testVerboseArgument();
        $this->testCoverageHtmlArgument();
        $this->testCoverageXmlArgument();
        $this->testCoverageArgument();
        $this->testAllOptionsAtOnce();
    }

    private function testNoArguments()
    {
        $tester = $this->tester;

        $options = new CommandLineArguments(["script"]);

        $factory = new CommandLineFactory($options);

        $expected = RenderResultsRunner::create();

        $tester->assertEqual($factory->create(), $expected, "factory creates RenderResultsRunner on no arguments");
    }

    private function testVerboseArgument()
    {
        $tester = $this->tester;

        $options = new CommandLineArguments(["script", "-v"]);

        $factory = new CommandLineFactory($options);

        $expected = RenderResultsRunner::create(true);

        $tester->assertEqual($factory->create(), $expected,
            "factory creates verbose RenderResultsRunner on -v arguments");
    }

    private function testCoverageHtmlArgument()
    {
        $tester = $this->tester;

        $coverage_facade = $this->createExpectedCodeCoverageFacade("src");
        $render_results_runner = RenderResultsRunner::create();

        $expected = new CodeCoverageRunner($render_results_runner, $coverage_facade);
        $expected->outputHtml(self::COVERAGE_HTML_DEFAULT_DIR);

        $options = new CommandLineArguments(["script", "--coverage-html"]);
        $factory = new CommandLineFactory($options);

        $tester->assertEqual($factory->create(), $expected,
            "coverage runner coverage-html to standard dir");

        $expected->outputHtml("custom/dir");
        $options = new CommandLineArguments(["script", "--coverage-html=custom/dir"]);
        $factory = new CommandLineFactory($options);

        $tester->assertEqual($factory->create(), $expected,
            "coverage runner coverage-html to custom dir");
    }

    private function testCoverageXmlArgument()
    {
        $tester = $this->tester;

        $coverage_facade = $this->createExpectedCodeCoverageFacade("src");
        $render_results_runner = RenderResultsRunner::create();

        $expected = new CodeCoverageRunner($render_results_runner, $coverage_facade);
        $expected->outputXml(self::DEFUALT_COVERAGE_XML_FILE);

        $options = new CommandLineArguments(["script", "--coverage-clover"]);
        $factory = new CommandLineFactory($options);

        $tester->assertEqual($factory->create(), $expected,
            "coverage runner coverage-xml to standard file");

        $expected->outputXml("custom/file.xml");
        $options = new CommandLineArguments(["script", "--coverage-clover=custom/file.xml"]);
        $factory = new CommandLineFactory($options);

        $tester->assertEqual($factory->create(), $expected,
            "coverage runner coverage-xml to custom file");
    }

    private function testCoverageArgument()
    {
        $tester = $this->tester;

        $coverage_facade = $this->createExpectedCodeCoverageFacade("src");
        $render_results_runner = RenderResultsRunner::create();

        $expected = new CodeCoverageRunner($render_results_runner, $coverage_facade);

        $options = new CommandLineArguments(["script", "--cover"]);
        $factory = new CommandLineFactory($options);

        $tester->assertEqual($factory->create(), $expected,
            "coverage runner");

        $coverage_facade = $this->createExpectedCodeCoverageFacade("custom/dir");
        $render_results_runner = RenderResultsRunner::create();

        $expected = new CodeCoverageRunner($render_results_runner, $coverage_facade);

        $options = new CommandLineArguments(["script", "--cover=custom/dir"]);
        $factory = new CommandLineFactory($options);

        $tester->assertEqual($factory->create(), $expected,
            "coverage runner cover=custom/dir");
    }

    private function testAllOptionsAtOnce()
    {
        $tester = $this->tester;

        $render_result_runner = RenderResultsRunner::create(true);
        $facade = $this->createExpectedCodeCoverageFacade("custom/coverage/dir");
        $expected = new CodeCoverageRunner($render_result_runner, $facade);
        $expected->outputXml("custom/file.xml");
        $expected->outputHtml("custom/html/dir");

        $options = new CommandLineArguments([
            "script",
            "-v",
            "--cover=custom/coverage/dir",
            "--coverage-clover=custom/file.xml",
            "--coverage-html=custom/html/dir",
        ]);
        $factory = new CommandLineFactory($options);

        $tester->assertEqual($factory->create(), $expected, "All options set");
    }

    private function createExpectedCodeCoverageFacade(string $path): CodeCoverageFacade
    {
        $coverage = $this->createExpectedCodeCoverage($path);

        return new CodeCoverageFacade($coverage, new Clover(), new Facade());
    }

    private function createExpectedCodeCoverage(string $path): CodeCoverage
    {
        $coverage = new CodeCoverage();

        $filter = $coverage->filter();
        $filter->addDirectoryToWhitelist($path);

        return $coverage;
    }
}
