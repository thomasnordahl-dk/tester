<?php

namespace Phlegmatic\Tester\Runner\Adapter\CodeCoverage;

use Phlegmatic\Tester\Runner\Runner;


/**
 * todo refactor to utilize Dependendy Injection and perform unit tests
 *
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
        $coverage = $this->coverage_facade;

        $coverage->start("code-coverage");
        $this->runner->run($packages);
        $coverage->stop();

        if ($this->html_output_directory) {
            $coverage->outputHtml($this->html_output_directory);
        }

        if ($this->xml_output_file) {
            $coverage->outputXml($this->xml_output_file);
        }
    }
}
