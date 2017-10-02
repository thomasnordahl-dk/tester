<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Configuration;


use ThomasNordahlDk\Tester\Assertion\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Assertion\Tester;
use ThomasNordahlDk\Tester\Runner\Configuration\CoverageConfiguration;
use ThomasNordahlDk\Tester\TestCase;

class CoverageConfigurationUnitTest implements TestCase
{
    /**
     * @var ComparisonTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit test of " . CoverageConfiguration::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new ComparisonTester($tester);

        $this->testDefaultConfiguration();

        $this->testSetCloverOutputMethod();
        $this->testSetCloverFileMethod();
        $this->testSetHtmlOutputMethod();
        $this->testSetHtmlDirectoryMethod();
        $this->testSetWhitelistMethod();
    }

    private function testDefaultConfiguration(): void
    {
        $tester = $this->tester;

        $config = new CoverageConfiguration();

        $tester->assert(! $config->isCloverOutput(),
            "default configuration does not output codecoverage clover report");

        $tester->assertSame($config->getCloverFile(), "coverage.xml",
            "default configuration sets clover file to coverage.xml");

        $tester->assert(! $config->isHtmlOutput(), "default configuration does not output codecoverage clover report");

        $tester->assertSame($config->getHtmlDirectory(), "coverage",
            "default configuration sets html directory to coverage");

        $tester->assertSame($config->getWhitelist(), ["src"], "default configuration covers /src");
    }

    private function testSetCloverOutputMethod(): void
    {
        $tester = $this->tester;
        $config = new CoverageConfiguration();

        $config->setCloverOutput(true);
        $tester->assert($config->isCloverOutput(),
            "setCloverOutput(true) sets makes isCloverOutput return true");

        $config->setCloverOutput(false);
        $tester->assert(! $config->isCloverOutput(),
            "setCloverOutput(false) sets makes isCloverOutput return false");
    }

    private function testSetCloverFileMethod(): void
    {
        $tester = $this->tester;
        $config = new CoverageConfiguration();

        $config->setCloverFile("custom/file.xml");
        $tester->assertSame($config->getCloverFile(), "custom/file.xml", "setCloverFile() sets custom file");
    }

    private function testSetHtmlOutputMethod(): void
    {
        $tester = $this->tester;
        $config = new CoverageConfiguration();

        $config->setHtmlOutput(true);
        $tester->assert($config->isHtmlOutput(),
            "setHtmlOutput(true) sets makes isHtmlOutput return true");

        $config->setHtmlOutput(false);
        $tester->assert(! $config->isHtmlOutput(),
            "setthmlOutput(false) sets makes isHtmlOutput return false");
    }

    private function testSetHtmlDirectoryMethod(): void
    {
        $tester = $this->tester;
        $config = new CoverageConfiguration();

        $config->setHtmlDirectory("custom/directory");
        $tester->assertSame($config->getHtmlDirectory(), "custom/directory", "setHtmlDirectory sets directory");
    }

    private function testSetWhitelistMethod(): void
    {
        $tester = $this->tester;

        $config = new CoverageConfiguration();

        $config->setWhitelist("test");
        $tester->assertSame($config->getWhitelist(), ["test"], "setWhitelist takes single path");

        $config->setWhitelist("src", "test");
        $tester->assertSame($config->getWhitelist(), ["src", "test"], "multiple whitelists paths can be set");
    }
}
