<?php

namespace ThomasNordahlDk\Tester\Runner;

use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CodeCoverageFacade;
use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CodeCoverageRunner;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\RenderResultsRunner;

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
        $clover_config = $config->getCloverReportConfiguration();
        $html_config = $config->getHtmlReportConfiguration();
        $whitelist = $config->getCoverageWhitelist();

        if ($clover_config->isOutput() || $html_config->isOutput()) {
            $coverage_facade = CodeCoverageFacade::create(... $whitelist);
            $runner = new CodeCoverageRunner($runner, $coverage_facade);

            $this->configureCloverReport($runner);
            $this->configureHtmlReport($runner);
        }

        return $runner;
    }

    private function configureCloverReport(CodeCoverageRunner $runner): void
    {
        $clover_config = $this->configuration->getCloverReportConfiguration();

        if ($clover_config->isOutput()) {
            $clover_file = $clover_config->getFile();
            $runner->outputClover($clover_file);
        }
    }

    private function configureHtmlReport(CodeCoverageRunner $runner): void
    {
        $html_config = $this->configuration->getHtmlReportConfiguration();

        if ($html_config->isOutput()) {
            $directory = $html_config->getDirectory();
            $runner->outputHtml($directory);
        }
    }
}
