<?php

namespace Phlegmatic\Tester\Tests\Unit\Runner\Factory;

use Phlegmatic\Tester\Assertion\Tester;
use Phlegmatic\Tester\Runner\Adapter\CodeCoverage\CodeCoverageRunner;
use Phlegmatic\Tester\Runner\Adapter\OutputResults\OutputResultsRunner;
use Phlegmatic\Tester\Runner\Factory\CommandLineOptionsFactory;
use Phlegmatic\Tester\Runner\Factory\RunnerFactory;
use Phlegmatic\Tester\TestCase;
use Phlegmatic\Tester\Tests\Mock\Runner\CommandLine\MockCommandLineOptions;

class CommandLineOptionsFactoryUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . CommandLineOptionsFactory::class;
    }

    public function run(Tester $tester): void
    {
        $runner_factory = new RunnerFactory();
        $factory = new CommandLineOptionsFactory($runner_factory, new MockCommandLineOptions([]));

        $runner = $factory->create();

        $tester->assert($runner instanceof OutputResultsRunner, "No options means standard runner)");

        $command_line_options = new MockCommandLineOptions(["coverage-html" => ""]);
        $factory = new CommandLineOptionsFactory($runner_factory, $command_line_options);

        $tester->assert($factory->create() instanceof CodeCoverageRunner, "--coverage-html gives coverage runner");

        $command_line_options = new MockCommandLineOptions(["coverage-html" => "dirname"]);
        $factory = new CommandLineOptionsFactory($runner_factory, $command_line_options);

        $tester->assert($factory->create() instanceof CodeCoverageRunner, "--coverage-html gives coverage runner");

        $command_line_options = new MockCommandLineOptions(["coverage-xml" => ""]);
        $factory = new CommandLineOptionsFactory($runner_factory, $command_line_options);

        $tester->assert($factory->create() instanceof CodeCoverageRunner, "--coverage-xml gives coverage runner");

        $command_line_options = new MockCommandLineOptions(["coverage-xml" => "filename"]);
        $factory = new CommandLineOptionsFactory($runner_factory, $command_line_options);

        $tester->assert($factory->create() instanceof CodeCoverageRunner, "--coverage-xml gives coverage runner");

        $command_line_options = new MockCommandLineOptions(["v" => false]);
        $factory = new CommandLineOptionsFactory($runner_factory, $command_line_options);

        $tester->assert($factory->create() instanceof OutputResultsRunner, "-v gives output runner");
    }
}
