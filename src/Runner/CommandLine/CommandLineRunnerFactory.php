<?php

namespace ThomasNordahlDk\Tester\Runner\CommandLine;

use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CodeCoverageFacade;
use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CodeCoverageRunner;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\OutputResultsFactory;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\OutputResultsRunner;
use ThomasNordahlDk\Tester\Runner\Runner;

class CommandLineRunnerFactory
{
    /**
     * @var CommandLineArguments
     */
    private $options;

    public function createFromArguments(array $argv): Runner
    {
        $this->options = new CommandLineArguments($argv);

        $runner = $this->createOutputResultsRunner();
        $runner = $this->decorateWithCoverage($runner);

        return $runner;
    }

    private function createOutputResultsRunner(): Runner
    {
        $verbose = $this->options->isSet("v");

        $factory = new OutputResultsFactory($verbose);

        return new OutputResultsRunner($factory);
    }

    private function decorateWithCoverage($runner): Runner
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
            $runner->outputClover($options->getValue("coverage-clover") ?: "coverage.xml");
        }

        if ($options->isSet("coverage-html")) {
            $runner->outputHtml($options->getValue("coverage-html") ?: "coverage");
        }

        return $runner;
    }

    private function createCoverageFacade(): CodeCoverageFacade
    {
        $options = $this->options;

        $whitelist = explode(";", $options->getValue("cover") ?: "src");

        return CodeCoverageFacade::create(... $whitelist);
    }
}
