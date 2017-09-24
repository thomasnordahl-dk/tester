<?php

namespace Phlegmatic\Tester\Tests\Unit\Runner\CommandLine;

use Phlegmatic\Tester\Assertion\Tester;
use Phlegmatic\Tester\Runner\CommandLine\CommandLineOptions;
use Phlegmatic\Tester\TestCase;

class CommandLineOptionsUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . CommandLineOptions::class;
    }

    public function run(Tester $tester): void
    {
        $standard_options = CommandLineOptions::createStandardOption();
        $tester->assert(!$standard_options->isOptionSet("definitely-not-an-option"), "try out standard options");

        $options_function = $this->getopt([]);
        $command_line_options = new CommandLineOptions($options_function);

        $tester->assert($command_line_options->isOptionSet("foo") === false, "no option set");

        $options_function = $this->getopt(["foo" => false]);
        $command_line_options = new CommandLineOptions($options_function);

        $tester->assert($command_line_options->isOptionSet("foo"), "long option set");
        $tester->assert($command_line_options->getValue("foo") === "", "long option value not set");

        $options_function = $this->getopt(["f" => false]);
        $command_line_options = new CommandLineOptions($options_function);

        $tester->assert($command_line_options->isOptionSet("f"), "option set");
        $tester->assert($command_line_options->getValue("f") === "", "option value not set");

        $options_function = $this->getopt(["foo" => "foo"]);
        $command_line_options = new CommandLineOptions($options_function);

        $tester->assert($command_line_options->getValue("foo") === "foo", "long option value set");

        $options_function = $this->getopt(["f" => "f"]);
        $command_line_options = new CommandLineOptions($options_function);

        $tester->assert($command_line_options->getValue("f") === "f", "option value not set");

    }

    private function getopt(array $options): callable
    {
        return function (string $opt, array $longparts = []) use ($options) {
            $option = $opt ?: $longparts[0];
            if (strpos($option, "::") === strlen($option) - 2) {
                $option = substr($option, 0, strlen($option) - 2);
            }
            if (isset($options[$option])) {
                return [$option => $options[$option]];
            }

            return [];
        };
    }
}
