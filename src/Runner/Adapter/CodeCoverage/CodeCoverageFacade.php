<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage;

use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Report\Clover;
use SebastianBergmann\CodeCoverage\Report\Html\Facade;

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

    public function start($id): void
    {
        $this->coverage->start($id);
    }

    public function stop(): void
    {
        $this->coverage->stop();
    }

    public function outputClover(string $file_name): void
    {
        $this->xml_writer->process($this->coverage, $file_name);
    }

    public function outputHtml(string $directory): void
    {
        $this->html_writer->process($this->coverage, $directory);
    }

    public static function create(string ...$paths): CodeCoverageFacade
    {
        $coverage = new CodeCoverage();

        $filter = $coverage->filter();
        foreach ($paths as $path) {
            $filter->addDirectoryToWhitelist($path);
        }

        return new CodeCoverageFacade($coverage, new Clover(), new Facade());
    }
}
