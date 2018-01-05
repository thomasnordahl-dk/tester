<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage;

use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Report\Clover;
use SebastianBergmann\CodeCoverage\Report\Html\Facade;

/**
 * Delegates to the CodeCoverage instances provided to the constructor
 *
 * A facade layer to reduce dependency on phpunit/php-unit-codecoverage
 */
class CodeCoverageFacade
{
    private $coverage;

    /**
     * @var Clover
     */
    private $xml_writer;

    /**
     * @var Facade
     */
    private $html_writer;

    public function __construct(CodeCoverage $coverage, Clover $xml_writer, Facade $html_writer)
    {
        $this->coverage = $coverage;
        $this->xml_writer = $xml_writer;
        $this->html_writer = $html_writer;
    }

    /**
     * Start registering coverage
     */
    public function start(): void
    {
        $this->coverage->start(self::class);
    }

    /**
     * Stop registering coverage
     */
    public function stop(): void
    {
        $this->coverage->stop();
    }

    /**
     * Writes a clover report of the coverage registered
     *
     * @param string $file_name Clover report output file name
     */
    public function outputClover(string $file_name): void
    {
        $this->xml_writer->process($this->coverage, $file_name);
    }

    /**
     * Writes an HTML report of the coverage registered
     *
     * @param string $directory HTML report directory
     */
    public function outputHtml(string $directory): void
    {
        $this->html_writer->process($this->coverage, $directory);
    }

    /**
     * Factory method for creation of new facades.
     *
     * @param string[] $paths
     *
     * @return CodeCoverageFacade
     */
    public static function create(array $paths): CodeCoverageFacade
    {
        $coverage = new CodeCoverage();

        $filter = $coverage->filter();
        foreach ($paths as $path) {
            $filter->addDirectoryToWhitelist($path);
        }

        return new CodeCoverageFacade($coverage, new Clover(), new Facade());
    }
}
