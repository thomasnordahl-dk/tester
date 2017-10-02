<?php


namespace ThomasNordahlDk\Tester\Runner;

use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CodeCoverageFacade;
use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CodeCoverageRunner;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\RenderResultsRunner;
use ThomasNordahlDk\Tester\Runner\Utility\CommandLineArguments;

/**
 * Returns an instance of Runner that matches the commandline options
 */
class CommandLineFactory
{
    const COVERAGE                  = "cover";
    const COVERAGE_CLOVER           = "coverage-clover";
    const COVERAGE_HTML             = "coverage-html";
    const VERBOSE_ARGUMENT          = "v";
    const DEFAULT_COVERAGE_DIR      = "src";
    const DEFUALT_HTML_COVERAGE_DIR = "coverage";
    const DEFUALT_XML_COVERAGE_FILE = "coverage.xml";

    /**
     * @var CommandLineArguments
     */
    private $command_line_options;

    public function __construct(CommandLineArguments $command_line_options)
    {
        $this->command_line_options = $command_line_options;
    }

    public function create(): Runner
    {
        $runner = $this->createRenderResultsRunner();
        $runner = $this->decorateWithCodeCoverage($runner);

        return $runner;
    }

    private function createRenderResultsRunner(): RenderResultsRunner
    {
        $options = $this->command_line_options;

        if ($options->isSet(self::VERBOSE_ARGUMENT)) {
            return RenderResultsRunner::create(true);
        } else {
            return RenderResultsRunner::create(false);
        }
    }

    private function decorateWithCodeCoverage(Runner $runner): Runner
    {
        if ($this->isAnyCodeCoverageOptionSet()) {
            $runner = $this->createCoverageRunner($runner);
        }

        return $runner;
    }

    private function isAnyCodeCoverageOptionSet(): bool
    {
        $options = $this->command_line_options;

        return $options->isAnySet(self::COVERAGE, self::COVERAGE_CLOVER, self::COVERAGE_HTML);
    }

    private function createCoverageRunner(Runner $runner): CodeCoverageRunner
    {
        $options = $this->command_line_options;

        $facade = $this->createCoverageFacade();

        $runner = new CodeCoverageRunner($runner, $facade);

        if ($options->isSet(self::COVERAGE_HTML)) {
            $value = $options->getValue(self::COVERAGE_HTML);
            $runner->outputHtml($value ?: self::DEFUALT_HTML_COVERAGE_DIR);
        }

        if ($options->isSet(self::COVERAGE_CLOVER)) {
            $value = $options->getValue(self::COVERAGE_CLOVER);
            $runner->outputClover($value ?: self::DEFUALT_XML_COVERAGE_FILE);
        }

        return $runner;
    }

    private function createCoverageFacade(): CodeCoverageFacade
    {
        $options = $this->command_line_options;

        $path = $options->getValue(self::COVERAGE) ?: self::DEFAULT_COVERAGE_DIR;

        return CodeCoverageFacade::create($path);
    }
}
