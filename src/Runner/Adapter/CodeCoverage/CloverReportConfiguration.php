<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage;


class CloverReportConfiguration
{
    /**
     * @var string
     */
    private $file = "coverage.xml";

    /**
     * @var bool
     */
    private $output = false;

    public function setOutput(bool $output): void
    {
        $this->output = $output;
    }

    public function isOutput(): bool
    {
        return $this->output;
    }

    public function setFile(string $file): void
    {
        $this->file = $file;
    }

    public function getFile(): string
    {
        return $this->file;
    }
}
