<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage;


class HtmlReportConfiguration
{
    /**
     * @var bool;
     */
    private $output = false;

    /**
     * @var string
     */
    private $directory = "coverage";

    public function isOutput(): bool
    {
        return $this->output;
    }

    public function setOutput(bool $output): void
    {
        $this->output = $output;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function setDirectory(string $directory): void
    {
        $this->directory = $directory;
    }
}
