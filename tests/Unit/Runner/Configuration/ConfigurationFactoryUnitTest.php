<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Configuration;


use ThomasNordahlDk\Tester\Assertion\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Assertion\Tester;
use ThomasNordahlDk\Tester\Runner\Configuration\Configuration;
use ThomasNordahlDk\Tester\Runner\Utility\CommandLineArguments;
use ThomasNordahlDk\Tester\Runner\Configuration\ConfigurationFactory;
use ThomasNordahlDk\Tester\TestCase;

class ConfigurationFactoryUnitTest implements TestCase
{
    /**
     * @var ComparisonTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit test of " . ConfigurationFactory::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new ComparisonTester($tester);

        $tester = $this->tester;

        $factory = new ConfigurationFactory();

        $args = ["script"];
        $expected = new Configuration();
        $command_line_arguments = new CommandLineArguments($args);
        $tester->assertEqual($factory->createFromCommandLineArguments($command_line_arguments), $expected,
            "No arguments results in default config");

        $args[] = "-v";
        $expected->setVerbose(true);
        $command_line_arguments = new CommandLineArguments($args);
        $tester->assertEqual($factory->createFromCommandLineArguments($command_line_arguments), $expected,
            "Creates verbose config from command line args");

        $args[] = "--cover=test";
        $expected_coverage = $expected->getCoverageConfiguration();
        $expected_coverage->setWhitelist("test");
        $command_line_arguments = new CommandLineArguments($args);
        $tester->assertEqual($factory->createFromCommandLineArguments($command_line_arguments), $expected,
            "creates config with cover path");

        $args[] = "--coverage-clover";
        $expected_coverage->setCloverOutput(true);
        $command_line_arguments = new CommandLineArguments($args);
        $tester->assertEqual($factory->createFromCommandLineArguments($command_line_arguments), $expected,
            "creates config with clover at default file");

        array_pop($args);
        $args[] = "--coverage-clover=file.xml";
        $expected_coverage->setCloverFile("file.xml");
        $command_line_arguments = new CommandLineArguments($args);
        $tester->assertEqual($factory->createFromCommandLineArguments($command_line_arguments), $expected,
            "creates config with clover at default file");

        $args[] = "--coverage-html";
        $expected_coverage->setHtmlOutput(true);
        $command_line_arguments = new CommandLineArguments($args);
        $tester->assertEqual($factory->createFromCommandLineArguments($command_line_arguments), $expected,
            "creates config with coverage html at default directory");

        array_pop($args);
        $args[] = "--coverage-html=directory";
        $expected_coverage->setHtmlDirectory("directory");
        $command_line_arguments = new CommandLineArguments($args);
        $tester->assertEqual($factory->createFromCommandLineArguments($command_line_arguments), $expected,
            "creates config with coverage html at custom directory");
    }
}
