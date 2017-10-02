<?php

namespace ThomasNordahlDk\Tester\Runner\Configuration;

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
     * @var CoverageConfiguration
     */
    private $coverage_config;

    /**
     * @var bool
     */
    private $verbose = false;

    public function __construct()
    {
        $this->coverage_config = new CoverageConfiguration();
    }

    public function setVerbose(bool $verbose): void
    {
        $this->verbose = $verbose;
    }

    public function isVerbose(): bool
    {
        return $this->verbose;
    }

    public function getCoverageConfiguration()
    {
        return $this->coverage_config;
    }
}
