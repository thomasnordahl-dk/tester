<?php

namespace Phlegmatic\Tester\Runner\Factory;

use Phlegmatic\Tester\Runner\Adapter\CodeCoverage\CodeCoverageFacade;
use Phlegmatic\Tester\Runner\Adapter\CodeCoverage\CodeCoverageRunner;
use Phlegmatic\Tester\Runner\CommandLine\CommandLineOptions;
use Phlegmatic\Tester\Runner\Runner;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Report\Clover;
use SebastianBergmann\CodeCoverage\Report\Html\Facade;

class CommandLineOptionsFactory
{
    private const VERBOSE_OPTION = "v";
    private const COVERAGE_XML_OPTION    = "coverage-xml";
    private const COVERAGE_HTML_OPTION   = "coverage-html";

    /**
     * @var Runner
     */
    private $runner;

    /**
     * @var RunnerFactory
     */
    private $factory;

    /**
     * @var CommandLineOptions
     */
    private $options;

    public function __construct(RunnerFactory $factory, CommandLineOptions $options)
    {
        $this->factory = $factory;
        $this->options = $options;
    }

    public function create(): Runner
    {
        $this->runner = null;

        $this->createBaseRunner();

        $this->decorateWithCoverage();

        return $this->runner;
    }

    private function decorateWithCoverage(): void
    {
        $options = $this->options;
        $runner = $this->runner;

        if ($options->isOptionSet(self::COVERAGE_XML_OPTION) || $options->isOptionSet(self::COVERAGE_HTML_OPTION)) {

            $coverage = new CodeCoverage();
            $filter = $coverage->filter();
            $filter->addDirectoryToWhitelist(getcwd() . "/src");

            $coverage_facade = new CodeCoverageFacade($coverage, new Clover(), new Facade());

            $runner = new CodeCoverageRunner($runner, $coverage_facade);

            if ($options->isOptionSet(self::COVERAGE_XML_OPTION)) {
                $output = $options->getValue(self::COVERAGE_XML_OPTION) ?: "coverage.xml";
                $runner->outputXml($output);
            }

            if ($options->isOptionSet(self::COVERAGE_HTML_OPTION)) {
                $output = $options->getValue(self::COVERAGE_HTML_OPTION) ?: "coverage";
                $runner->outputHtml($output);
            }
        }

        $this->runner = $runner;
    }

    private function createBaseRunner(): void
    {
        $options = $this->options;
        $factory = $this->factory;

        $runner_is_verbose = $options->isOptionSet(self::VERBOSE_OPTION);

        if ($runner_is_verbose) {
            $this->runner = $factory->createVerbose();
        } else {
            $this->runner = $factory->create();
        }
    }
}
