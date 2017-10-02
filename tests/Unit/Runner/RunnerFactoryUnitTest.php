<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner;


use ThomasNordahlDk\Tester\Assertion\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Assertion\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CodeCoverageFacade;
use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CodeCoverageRunner;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\RenderResultsRunner;
use ThomasNordahlDk\Tester\Runner\Configuration;
use ThomasNordahlDk\Tester\Runner\Runner;
use ThomasNordahlDk\Tester\Runner\RunnerFactory;
use ThomasNordahlDk\Tester\TestCase;

class RunnerFactoryUnitTest implements TestCase
{
    /**
     * @var ComparisonTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit test of " . RunnerFactory::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new ComparisonTester($tester);

        $this->testVerboseConfig();
        $this->testCloverReportConfig();
        $this->testHtmlReportConfig();
        $this->testCombinedConfigurations();
    }

    private function testVerboseConfig()
    {
        $tester = $this->tester;
        $config = new Configuration();
        $factory = new RunnerFactory($config);

        $tester->assertEqual($factory->create(), RenderResultsRunner::create(),
            "Default config results in default RenderResultsRunner");

        $config->setVerbose(true);
        $tester->assertEqual($factory->create(), RenderResultsRunner::create(true),
            "Verbose config results in verbose RenderResultsRunner");
    }

    private function testCloverReportConfig(): void
    {
        $tester = $this->tester;
        $config = new Configuration();
        $whitelist = $config->getCoverageWhitelist();
        $factory = new RunnerFactory($config);

        $clover_report_config = $config->getCloverReportConfiguration();
        $clover_report_config->setOutput(true);
        $clover_report_config->setFile("file.xml");

        $expected = new CodeCoverageRunner(RenderResultsRunner::create(), CodeCoverageFacade::create(... $whitelist));
        $expected->outputClover("file.xml");

        $tester->assertEqual($factory->create(), $expected, "Factory creates expected clover report runner");
    }

    private function testHtmlReportConfig(): void
    {
        $tester = $this->tester;
        $config = new Configuration();
        $white_list = $config->getCoverageWhitelist();
        $factory = new RunnerFactory($config);

        $html_report_config = $config->getHtmlReportConfiguration();
        $html_report_config->setOutput(true);
        $html_report_config->setDirectory("directory");

        $expected = new CodeCoverageRunner(RenderResultsRunner::create(), CodeCoverageFacade::create(... $white_list));
        $expected->outputHtml("directory");

        $tester->assertEqual($factory->create(), $expected, "Factory creates expected html report runner");
    }

    private function testCombinedConfigurations(): void
    {
        $tester = $this->tester;
        $config = $this->createConfigurationWithAllConfigsCombined();
        $factory = new RunnerFactory($config);

        $expected = $this->createExpectedRunnerForAllConfigsCombined();

        $tester->assertEqual($factory->create(), $expected, "Factory can combine all configuration settings correctly");
    }

    private function createConfigurationWithAllConfigsCombined(): Configuration
    {
        $config = new Configuration();

        $config->setVerbose(true);

        $clover_config = $config->getCloverReportConfiguration();
        $clover_config->setOutput(true);
        $clover_config->setFile("file.xml");

        $html_config = $config->getHtmlReportConfiguration();
        $html_config->setOutput(true);
        $html_config->setDirectory("directory");

        $config->setCoverageWhitelist("src", "test");

        return $config;
    }

    private function createExpectedRunnerForAllConfigsCombined(): Runner
    {
        $runner = RenderResultsRunner::create(true);

        $runner = new CodeCoverageRunner($runner, CodeCoverageFacade::create("src", "test"));

        $runner->outputClover("file.xml");
        $runner->outputHtml("directory");

        return $runner;
    }
}
