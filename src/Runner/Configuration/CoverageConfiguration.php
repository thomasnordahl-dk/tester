<?php

namespace ThomasNordahlDk\Tester\Runner\Configuration;


class CoverageConfiguration
{
    /**
     * @var string
     */
    private $clover_file = "coverage.xml";

    /**
     * @var bool
     */
    private $clover_output = false;

    /**
     * @var bool;
     */
    private $html_output = false;

    /**
     * @var string
     */
    private $directory = "coverage";

    /**
     * @var string[]
     */
    private $whitelist = ["src"];

    public function setWhitelist(string ... $paths)
    {
        $this->whitelist = $paths;
    }

    public function getWhitelist(): array
    {
        return $this->whitelist;
    }

    public function isHtmlOutput(): bool
    {
        return $this->html_output;
    }

    public function setHtmlOutput(bool $output): void
    {
        $this->html_output = $output;
    }

    public function getHtmlDirectory(): string
    {
        return $this->directory;
    }

    public function setHtmlDirectory(string $directory): void
    {
        $this->directory = $directory;
    }

    public function setCloverOutput(bool $output): void
    {
        $this->clover_output = $output;
    }

    public function isCloverOutput(): bool
    {
        return $this->clover_output;
    }

    public function setCloverFile(string $file): void
    {
        $this->clover_file = $file;
    }

    public function getCloverFile(): string
    {
        return $this->clover_file;
    }
}
