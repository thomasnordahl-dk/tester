<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\CommandLine;


use ThomasNordahlDk\Tester\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CodeCoverageFacade;
use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CodeCoverageRunner;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\OutputResultsFactory;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\OutputResultsRunner;
use ThomasNordahlDk\Tester\Runner\CommandLine\CommandLineRunnerFactory;
use ThomasNordahlDk\Tester\TestCase;

class CommandLineRunnerFactoryUnitTest implements TestCase
{
    /**
     * @var ComparisonTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit test of " . CommandLineRunnerFactory::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new ComparisonTester($tester);

        $this->testCreateWithEmptyArguments();
        $this->testCreateWithVerboseArgument();
        $this->testCreateWithCoverageCloverArgments();
        $this->testCreateWithCoverageHtmlArgments();
        $this->testCreateWithCoveragePathArguments();
        $this->testCreateWithAllArgments();
    }

    private function testCreateWithEmptyArguments(): void
    {
        $tester = $this->tester;

        $argv = ["script"];

        $expected = new OutputResultsRunner(new OutputResultsFactory());

        $factory = new CommandLineRunnerFactory();

        $tester->assertEqual($factory->createFromArguments($argv), $expected,
            "No arguments results in default OutputResultsRunner");
    }

    private function testCreateWithVerboseArgument(): void
    {
        $tester = $this->tester;

        $argv = ["script", "-v"];

        $expected = new OutputResultsRunner(new OutputResultsFactory(true));

        $factory = new CommandLineRunnerFactory();

        $tester->assertEqual($factory->createFromArguments($argv), $expected,
            "-v in arguments results in OutputResultsRunner");
    }

    private function testCreateWithCoverageCloverArgments(): void
    {
        $tester = $this->tester;

        $argv = ["script", "--coverage-clover"];

        $output_results_runner = new OutputResultsRunner(new OutputResultsFactory());
        $coverage_facade = CodeCoverageFacade::create("src");

        $expected = new CodeCoverageRunner($output_results_runner, $coverage_facade);
        $expected->outputClover("coverage.xml");

        $factory = new CommandLineRunnerFactory();
        $tester->assertEqual($factory->createFromArguments($argv), $expected,
            "--coverage-clover results in codecoverage runner with clover output to coverage.xml");

        $argv = ["script", "--coverage-clover=file.xml"];
        $expected->outputClover("file.xml");
        $tester->assertEqual($factory->createFromArguments($argv), $expected,
            "--coverage-clover=file.xml results in codecoverage runner with clover output to file.xml");
    }

    private function testCreateWithCoverageHtmlArgments(): void
    {
        $tester = $this->tester;

        $argv = ["script", "--coverage-html"];

        $output_results_runner = new OutputResultsRunner(new OutputResultsFactory());
        $coverage_facade = CodeCoverageFacade::create("src");

        $expected = new CodeCoverageRunner($output_results_runner, $coverage_facade);
        $expected->outputHtml("coverage");

        $factory = new CommandLineRunnerFactory();
        $tester->assertEqual($factory->createFromArguments($argv), $expected,
            "--coverage-html results in codecoverage runner with html report output to coverage");

        $argv = ["script", "--coverage-html=custom/dir"];
        $expected->outputHtml("custom/dir");
        $tester->assertEqual($factory->createFromArguments($argv), $expected,
            "--coverage-html results in codecoverage runner with html report output to custom/dir");

    }

    private function testCreateWithCoveragePathArguments(): void
    {
        $tester = $this->tester;

        $argv = ["script", "--coverage-html", "--cover=path"];

        $output_results_runner = new OutputResultsRunner(new OutputResultsFactory());
        $coverage_facade = CodeCoverageFacade::create("path");

        $expected = new CodeCoverageRunner($output_results_runner, $coverage_facade);
        $expected->outputHtml("coverage");

        $factory = new CommandLineRunnerFactory();
        $tester->assertEqual($factory->createFromArguments($argv), $expected,
            "--coverage-html --cover=path results in coverage with custom path");


        $argv = ["script", "--coverage-html", "--cover=path;dir;src"];

        $output_results_runner = new OutputResultsRunner(new OutputResultsFactory());
        $coverage_facade = CodeCoverageFacade::create("path", "dir", "src");

        $expected = new CodeCoverageRunner($output_results_runner, $coverage_facade);
        $expected->outputHtml("coverage");

        $factory = new CommandLineRunnerFactory();
        $tester->assertEqual($factory->createFromArguments($argv), $expected,
            "--coverage-html --cover=path;dir;src results in coverage with multiple custom paths");
    }

    private function testCreateWithAllArgments(): void
    {
        $tester = $this->tester;

        $argv = ["script", "-v", "--coverage-clover=file.xml", "--coverage-html=dir", "--cover=path"];

        $output_results_runner = new OutputResultsRunner(new OutputResultsFactory(true));
        $coverage_facade = CodeCoverageFacade::create("path");

        $expected = new CodeCoverageRunner($output_results_runner, $coverage_facade);
        $expected->outputHtml("dir");
        $expected->outputClover("file.xml");

        $factory = new CommandLineRunnerFactory();
        $tester->assertEqual($factory->createFromArguments($argv), $expected,
            "All arguments set at the same time will result in correctly built runner");
    }
}
