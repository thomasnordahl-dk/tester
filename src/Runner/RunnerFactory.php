<?php

namespace ThomasNordahlDk\Tester\Runner;

use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CodeCoverageFacade;
use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CodeCoverageRunner;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\RenderResultsRunner;
use ThomasNordahlDk\Tester\Runner\Configuration\Configuration;

class RunnerFactory
{
    /**
     * @var Configuration
     */
    private $configuration;

    public function __construct(Configuration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function create(): Runner
    {
        $config = $this->configuration;

        $runner = RenderResultsRunner::create($config->isVerbose());

        return $this->decorateWithCoverage($runner);
    }

    private function decorateWithCoverage($runner): Runner
    {
        $config = $this->configuration;
        $coverage_config = $config->getCoverageConfiguration();

        if ($coverage_config->isCloverOutput() || $coverage_config->isHtmlOutput()) {
            $whitelist = $coverage_config->getWhitelist();
            $coverage_facade = CodeCoverageFacade::create(... $whitelist);
            $runner = new CodeCoverageRunner($runner, $coverage_facade);

            $this->configureCloverReport($runner);
            $this->configureHtmlReport($runner);
        }

        return $runner;
    }

    private function configureCloverReport(CodeCoverageRunner $runner): void
    {
        $coverage_configuration = $this->configuration->getCoverageConfiguration();

        if ($coverage_configuration->isCloverOutput()) {
            $clover_file = $coverage_configuration->getCloverFile();
            $runner->outputClover($clover_file);
        }
    }

    private function configureHtmlReport(CodeCoverageRunner $runner): void
    {
        $coverage_configuration = $this->configuration->getCoverageConfiguration();

        if ($coverage_configuration->isHtmlOutput()) {
            $directory = $coverage_configuration->getHtmlDirectory();
            $runner->outputHtml($directory);
        }
    }
}
