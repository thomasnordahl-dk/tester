<?php

namespace Phlegmatic\Tester\Runner\Adapter\CodeCoverage;

use Phlegmatic\Tester\Runner\Runner;
use Phlegmatic\Tester\TestPackage;


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

    public function run($packages): void
    {
        $this->runWithCoverage($packages);

        $this->outputResults();
    }

    /**
     * @param TestPackage[] $packages
     */
    private function runWithCoverage($packages): void
    {
        $coverage_facade = $this->coverage_facade;

        $coverage_facade->start("phlegmatic-tester");

        $this->runner->run($packages);

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
