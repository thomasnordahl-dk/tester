<?php

namespace ThomasNordahlDk\Tester\Runner\CommandLine;

use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CodeCoverageFacade;
use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CodeCoverageRunner;
use ThomasNordahlDk\Tester\Runner\Adapter\Simple\SimpleRunner;
use ThomasNordahlDk\Tester\Runner\Runner;

/**
 * Creates instances of Runner based on the provided $argv array
 *
 * http://us2.php.net/manual/en/reserved.variables.php
 * @see CommandLineArguments
 */
class CommandLineRunnerFactory
{
    /**
     * @var CommandLineArguments
     */
    private $options;

    /**
     * Creates an instance of Runner based on the provided command line arguments.
     *
     * --coverage-clover        => Specify coverage clover report
     * --coverage-clover=file   => Specify coverage clover report to file
     * --coverage-html          => Specify coverage HTML report
     * --coverage-html=dir      => Specify coverage HTML report to directory
     * --cover=path1,path       => Specify which paths to run coverage on.
     *
     * @param array $argv the command line argument array
     *
     * @return Runner
     */
    public function createFromArguments(array $argv): Runner
    {
        $this->options = new CommandLineArguments($argv);

        $runner = $this->createOutputResultsRunner();

        return $this->decorateWithCoverage($runner);
    }

    private function createOutputResultsRunner(): SimpleRunner
    {
        return SimpleRunner::create();
    }

    private function decorateWithCoverage(Runner $runner): Runner
    {
        $options = $this->options;

        if ($options->isSet("coverage-clover") || $options->isset("coverage-html")) {
            $runner = $this->createCoverageRunner($runner);
        }

        return $runner;
    }

    private function createCoverageRunner($runner): Runner
    {
        $options = $this->options;

        $facade = $this->createCoverageFacade();
        $runner = new CodeCoverageRunner($runner, $facade);

        if ($options->isSet("coverage-clover")) {
            $output_file = $options->getValue("coverage-clover") ?: "coverage.xml";
            $runner->outputsCloverReportTo($output_file);
        }

        if ($options->isSet("coverage-html")) {
            $directory = $options->getValue("coverage-html") ?: "coverage";
            $runner->outputsHtmlReportTo($directory);
        }

        return $runner;
    }

    private function createCoverageFacade(): CodeCoverageFacade
    {
        $options = $this->options;

        $whitelist = explode(",", $options->getValue("cover") ?: "src");

        return CodeCoverageFacade::create(... $whitelist);
    }
}
