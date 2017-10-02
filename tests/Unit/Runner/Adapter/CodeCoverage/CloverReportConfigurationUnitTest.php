<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\CodeCoverage;


use ThomasNordahlDk\Tester\Assertion\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Assertion\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CloverReportConfiguration;
use ThomasNordahlDk\Tester\TestCase;

class CloverReportConfigurationUnitTest implements TestCase
{
    /**
     * @var ComparisonTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit test of " . CloverReportConfiguration::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new ComparisonTester($tester);

        $this->testDefaultConfiguration();

        $this->testSetOutputMethod();
        $this->testSetFileMethod();
    }

    private function testDefaultConfiguration(): void
    {
        $tester = $this->tester;

        $config = new CloverReportConfiguration();

        $tester->assert(! $config->isOutput(), "default configuration does not output codecoverage clover report");

        $tester->assertSame($config->getFile(), "coverage.xml",
            "default configuration sets clover file to coverage.xml");
    }

    private function testSetOutputMethod(): void
    {
        $tester = $this->tester;
        $config = new CloverReportConfiguration();

        $config->setOutput(true);
        $tester->assert($config->isOutput(),
            "setOutput(true) sets makes isOutput return true");

        $config->setOutput(false);
        $tester->assert(! $config->isOutput(),
            "setOutput(false) sets makes isOutput return false");
    }

    private function testSetFileMethod(): void
    {
        $tester = $this->tester;
        $config = new CloverReportConfiguration();

        $config->setFile("custom/file.xml");
        $tester->assertSame($config->getFile(), "custom/file.xml", "setFile() sets custom file");

    }
}
