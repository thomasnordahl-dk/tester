<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner;


use ThomasNordahlDk\Tester\Assertion\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Assertion\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CodeCoverageFacade;
use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CodeCoverageRunner;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\RenderResultsRunner;
use ThomasNordahlDk\Tester\Runner\Configuration\Configuration;
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
        $coverage_config = $config->getCoverageConfiguration();
        $whitelist = $coverage_config->getWhitelist();
        $factory = new RunnerFactory($config);

        $coverage_config->setCloverOutput(true);
        $coverage_config->setCloverFile("file.xml");

        $expected = new CodeCoverageRunner(RenderResultsRunner::create(), CodeCoverageFacade::create(... $whitelist));
        $expected->outputClover("file.xml");

        $tester->assertEqual($factory->create(), $expected, "Factory creates expected clover report runner");
    }

    private function testHtmlReportConfig(): void
    {
        $tester = $this->tester;
        $config = new Configuration();
        $coverage_config = $config->getCoverageConfiguration();
        $whitelist = $coverage_config->getWhitelist();

        $factory = new RunnerFactory($config);

        $coverage_config->setHtmlOutput(true);
        $coverage_config->setHtmlDirectory("directory");

        $expected = new CodeCoverageRunner(RenderResultsRunner::create(), CodeCoverageFacade::create(... $whitelist));
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

        $coverage_config = $config->getCoverageConfiguration();

        $coverage_config->setCloverOutput(true);
        $coverage_config->setCloverFile("file.xml");

        $coverage_config->setHtmlOutput(true);
        $coverage_config->setHtmlDirectory("directory");

        $coverage_config->setWhitelist("src", "test");

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
