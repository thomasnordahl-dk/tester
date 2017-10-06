<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\CommandLine;

use ThomasNordahlDk\Tester\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Tester;
use ThomasNordahlDk\Tester\Runner\CommandLine\CommandLineArguments;
use ThomasNordahlDk\Tester\TestCase;

class CommandLineArgumentsUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . CommandLineArguments::class;
    }

    public function run(Tester $tester): void
    {
        $tester = new ComparisonTester($tester);

        $arguments = [
            "script_name",
            "-a",
            "b",
            "c=d",
            "-e=f",
            "-g=hhh",
            "i=jjj",
            "--long",
            "--long-with-value=thevalue",
            "-long-with-one-dash",
            "with-no-dashes",
        ];

        $command_line_options = new CommandLineArguments($arguments);

        $tester->assert($command_line_options->isSet("script_name") === false, "script_name gets sorted");
        $tester->assert($command_line_options->isSet("a") === true, "-a is set");
        $tester->assert($command_line_options->isSet("b") === false, "b is invalid");
        $tester->assert($command_line_options->isSet("c") === false, "c=d is invalid");
        $tester->assert($command_line_options->isSet("e") === true, "-e is set");
        $tester->assert($command_line_options->isSet("g") === true, "-g is set");
        $tester->assert($command_line_options->isSet("i") === false, "i=jjj is invalid");
        $tester->assert($command_line_options->isSet("x") === false, "x is not set");
        $tester->assert($command_line_options->isSet("notset") === false, "--notset not set");
        $tester->assert($command_line_options->isSet("long") === true, "--long is set");
        $tester->assert($command_line_options->isSet("long-with-value") === true, "--long-with-value is set");
        $tester->assert($command_line_options->isSet("with-one-dash") === false, "-with-one-dash is not valid");
        $tester->assert($command_line_options->isSet("with-no-dashes") === false, "with-no-dashes is not valid");

        $tester->assertSame($command_line_options->getValue("a"), "", "-a has no value");
        $tester->assertSame($command_line_options->getValue("b"), "", "b has no value");
        $tester->assertSame($command_line_options->getValue("c"), "", "c=d is invalid");
        $tester->assertSame($command_line_options->getValue("e"), "f", "-e=f ");
        $tester->assertSame($command_line_options->getValue("g"), "hhh", "-g=hhh");
        $tester->assertSame($command_line_options->getValue("i"), "", "i=jjj is invalid");
        $tester->assertSame($command_line_options->getValue("long"), "", "--long has no value");
        $tester->assertSame($command_line_options->getValue("long-with-value"), "thevalue",
            "--long-with-value=thevalue");
    }
}
