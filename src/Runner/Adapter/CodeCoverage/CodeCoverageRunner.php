<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage;

use ThomasNordahlDk\Tester\Runner\Runner;
use ThomasNordahlDk\Tester\TestSuite;


/**
 * Runs the tests with code coverage and outputs the
 * coverage to the designated directory/file
 */
class CodeCoverageRunner implements Runner
{
    /**
     * @var string
     */
    private $html_output_directory;

    /**
     * @var string
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

    public function __construct(Runner $runner, CodeCoverageFacade $coverage_facade)
    {
        $this->runner = $runner;
        $this->coverage_facade = $coverage_facade;
    }

    public function outputXml(string $output_file): void
    {
        $this->xml_output_file = $output_file;
    }

    public function outputHtml(string $directory): void
    {
        $this->html_output_directory = $directory;
    }

    public function run(array $suites): void
    {
        $this->runWithCoverage($suites);

        $this->outputResults();
    }

    /**
     * @param TestSuite[] $test_suites
     */
    private function runWithCoverage($test_suites): void
    {
        $coverage_facade = $this->coverage_facade;

        $coverage_facade->start("ThomasNordahlDk-tester");

        $this->runner->run($test_suites);

        $coverage_facade->stop();
    }

    private function outputResults(): void
    {
        $coverage_facade = $this->coverage_facade;

        if ($this->html_output_directory) {
            $coverage_facade->outputHtml($this->html_output_directory);
        }

        if ($this->xml_output_file) {
            $coverage_facade->outputXml($this->xml_output_file);
        }
    }
}
