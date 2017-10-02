<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner;

use ThomasNordahlDk\Tester\Assertion\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Assertion\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CloverReportConfiguration;
use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\HtmlReportConfiguration;
use ThomasNordahlDk\Tester\Runner\Configuration;
use ThomasNordahlDk\Tester\TestCase;

class ConfigurationUnitTest implements TestCase
{
    /**
     * @var ComparisonTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit test of " . Configuration::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new ComparisonTester($tester);

        $this->testDefaultConfiguration();

        $this->testSetVerboseMethod();

        $this->testSetCoverageWhitelistMethod();
    }

    private function testDefaultConfiguration(): void
    {
        $tester = $this->tester;

        $config = new Configuration();

        $tester->assert($config->isVerbose() === false, "default configuration is not verbose");

        $tester->assertEqual($config->getCloverReportConfiguration(), new CloverReportConfiguration(),
            "Default Clover configuration should be a new instance");

        $tester->assertEqual($config->getHtmlReportConfiguration(), new HtmlReportConfiguration(),
            "Default HTML report config should be new instance");

        $tester->assertSame($config->getCoverageWhitelist(), ["src"],
            "Default config whitelists \"src\" for coverage");
    }

    private function testSetVerboseMethod(): void
    {
        $tester = $this->tester;

        $config = new Configuration();

        $config->setVerbose(true);
        $tester->assert($config->isVerbose(), "setVerbose(true) sets config to verbose");

        $config->setVerbose(false);
        $tester->assert($config->isVerbose() === false, "setVerbose(false) sets config to not verbose");
    }

    private function testSetCoverageWhitelistMethod(): void
    {
        $tester = $this->tester;

        $config = new Configuration();

        $config->setCoverageWhitelist("src");
        $tester->assertSame($config->getCoverageWhitelist(), ["src"], "set whitelist sets single path");

        $config->setCoverageWhitelist("src", "test", "dist");
        $tester->assertSame($config->getCoverageWhitelist(), ["src", "test", "dist"],
            "set whitelist sets multiple paths");
    }
}
