<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage;

use ThomasNordahlDk\Tester\Runner\Runner;
use ThomasNordahlDk\Tester\TestSuite;

/**
 * Runner Decorator class that runs the tests with code coverage, and outputs
 * either clover report, an HTML report, or both based on the settings.
 */
class CodeCoverageRunner implements Runner
{
    /**
     * @var string|null if set an HTML coverage report will be output to this directory
     */
    private $html_output_directory;

    /**
     * @var string|null if set a clover coverage report will be output to this file
     */
    private $xml_output_file;

    /**
     * @var Runner
     */
    private $runner;

    /**
     * @var CodeCoverageFacade
     */
    private $coverage_facade;

    /**
     * @param Runner             $runner          The runner in charge of running the tests
     * @param CodeCoverageFacade $coverage_facade The facade to cover with coverage
     */
    public function __construct(Runner $runner, CodeCoverageFacade $coverage_facade)
    {
        $this->runner = $runner;
        $this->coverage_facade = $coverage_facade;
    }

    /**
     * The runner will output a clover coverage report to the file.
     *
     * @param string $output_file The file to save the clover report to.
     */
    public function outputsCloverReportTo(string $output_file): void
    {
        $this->xml_output_file = $output_file;
    }

    /**
     * The runner will output an HTML report to the directory
     *
     * @param string $directory The dierectory to save the html report to.
     */
    public function outputsHtmlReportTo(string $directory): void
    {
        $this->html_output_directory = $directory;
    }

    /**
     * @inheritdoc
     */
    public function run($suites): void
    {
        $this->runWithCoverage($suites);

        $this->outputResults();
    }

    /**
     * Starts coverage, runs suites, stops coverage.
     *
     * @param TestSuite[] $test_suites
     */
    private function runWithCoverage($test_suites): void
    {
        $coverage_facade = $this->coverage_facade;

        $coverage_facade->start("ThomasNordahlDk-tester");

        $this->runner->run($test_suites);

        $coverage_facade->stop();
    }

    /**
     * Outputs the report(s) designated to be output.
     */
    private function outputResults(): void
    {
        $coverage_facade = $this->coverage_facade;

        if ($this->html_output_directory) {
            $coverage_facade->outputHtml($this->html_output_directory);
        }

        if ($this->xml_output_file) {
            $coverage_facade->outputClover($this->xml_output_file);
        }
    }
}
