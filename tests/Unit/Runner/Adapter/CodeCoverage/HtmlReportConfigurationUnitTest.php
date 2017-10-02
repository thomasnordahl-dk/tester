<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\CodeCoverage;


use ThomasNordahlDk\Tester\Assertion\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Assertion\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\HtmlReportConfiguration;
use ThomasNordahlDk\Tester\TestCase;

class HtmlReportConfigurationUnitTest implements TestCase
{
    /**
     * @var ComparisonTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit test of " . HtmlReportConfiguration::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new ComparisonTester($tester);

        $this->testDefaultConfiguration();

        $this->testSetOutputMethod();
        $this->testSetDirectoryMethod();
    }

    private function testDefaultConfiguration(): void
    {
        $tester = $this->tester;

        $config = new HtmlReportConfiguration();

        $tester->assert(! $config->isOutput(), "default configuration does not output codecoverage clover report");

        $tester->assertSame($config->getDirectory(), "coverage",
            "default configuration sets html directory to coverage");
    }

    private function testSetOutputMethod(): void
    {
        $tester = $this->tester;
        $config = new HtmlReportConfiguration();

        $config->setOutput(true);
        $tester->assert($config->isOutput(),
            "setOutput(true) sets makes isOutput return true");

        $config->setOutput(false);
        $tester->assert(! $config->isOutput(),
            "setOutput(false) sets makes isOutput return false");
    }

    private function testSetDirectoryMethod(): void
    {
        $tester = $this->tester;
        $config = new HtmlReportConfiguration();

        $config->setDirectory("custom/directory");
        $tester->assertSame($config->getDirectory(), "custom/directory", "setDirectory sets directory");
    }
}
