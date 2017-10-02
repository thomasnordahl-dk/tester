<?php

namespace ThomasNordahlDk\Tester\Runner\Configuration;

use ThomasNordahlDk\Tester\Runner\Utility\CommandLineArguments;

class ConfigurationFactory
{
    public function createFromCommandLineArguments(CommandLineArguments $arguments): Configuration
    {
        $configuration = new Configuration();

        $this->setVerbose($arguments, $configuration);

        $this->setCoverPath($arguments, $configuration);
        $this->setCloverReportConfiguration($arguments, $configuration);
        $this->setHtmlReportConfiguration($arguments, $configuration);

        return $configuration;
    }

    private function setVerbose(CommandLineArguments $arguments, Configuration $configuration): void
    {
        $verbose = $arguments->isSet("v");
        $configuration->setVerbose($verbose);
    }

    private function setCoverPath(CommandLineArguments $arguments, Configuration $configuration): void
    {
        $paths = $arguments->getValue("cover") ?: "src";
        $coverage_config = $configuration->getCoverageConfiguration();

        $coverage_config->setWhitelist($paths);
    }

    private function setCloverReportConfiguration(CommandLineArguments $arguments, Configuration $configuration): void
    {
        $coverage_config = $configuration->getCoverageConfiguration();

        $is_ouput = $arguments->isSet("coverage-clover");
        $file = $arguments->getValue("coverage-clover") ?: "coverage.xml";

        $coverage_config->setCloverOutput($is_ouput);
        $coverage_config->setCloverFile($file);
    }

    private function setHtmlReportConfiguration(CommandLineArguments $arguments, Configuration $configuration): void
    {
        $coverage_config = $configuration->getCoverageConfiguration();

        $is_output = $arguments->isSet("coverage-html");
        $directory = $arguments->getValue("coverage-html") ?: "coverage";

        $coverage_config->setHtmlOutput($is_output);
        $coverage_config->setHtmlDirectory($directory);
    }
}
