<?php

namespace ThomasNordahlDk\Tester\Runner;

use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CloverReportConfiguration;
use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\HtmlReportConfiguration;

/**
 * Defines the configurations available for running tests
 * with the Runner interface
 *
 * Applied by the Runner factory/factories to determine what runner
 * to create and how to configure it.
 */
class Configuration
{
    /**
     * @var bool
     */
    private $verbose = false;

    /**
     * @var string[]
     */
    private $coverage_whitelist = ["src"];

    /**
     * @var CloverReportConfiguration
     */
    private $clover_report_config;

    /**
     * @var HtmlReportConfiguration
     */
    private $html_report_config;

    public function __construct()
    {
        $this->clover_report_config = new CloverReportConfiguration();
        $this->html_report_config = new HtmlReportConfiguration();
    }

    public function setVerbose(bool $verbose): void
    {
        $this->verbose = $verbose;
    }

    public function isVerbose(): bool
    {
        return $this->verbose;
    }

    public function setCoverageWhitelist(string ... $paths)
    {
        $this->coverage_whitelist = $paths;
    }

    public function getCoverageWhitelist()
    {
        return $this->coverage_whitelist;
    }

    public function getCloverReportConfiguration(): CloverReportConfiguration
    {
        return $this->clover_report_config;
    }

    public function getHtmlReportConfiguration(): HtmlReportConfiguration
    {
        return $this->html_report_config;
    }
}
