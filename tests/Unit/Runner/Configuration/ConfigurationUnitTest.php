<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Configuration;

use ThomasNordahlDk\Tester\Assertion\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Assertion\Tester;
use ThomasNordahlDk\Tester\Runner\Configuration\CoverageConfiguration;
use ThomasNordahlDk\Tester\Runner\Configuration\Configuration;
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
    }

    private function testDefaultConfiguration(): void
    {
        $tester = $this->tester;

        $config = new Configuration();

        $tester->assert($config->isVerbose() === false, "default configuration is not verbose");

        $tester->assertEqual($config->getCoverageConfiguration(), new CoverageConfiguration(),
            "Default CoverageConfiguration is new instance");
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
}
