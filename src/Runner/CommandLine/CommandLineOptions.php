<?php

namespace Phlegmatic\Tester\Runner\CommandLine;


/**
 * Uses getopt to evaluate command line options
 *
 * The class is constructed with a callable function that should delegate to getopt.
 *
 * This function is injected so that it can be replaced under unit test.
 *
 * Construct new instances with the factory method
 * @see \Phlegmatic\Tester\Runner\CommandLine\CommandLineOptions::createStandardOptions()
 */
class CommandLineOptions
{
    /**
     * @var callable
     */
    private $option_function;

    /**
     * @param callable $option_function use CommandLineOptions::createStandardOptionFunction
     */
    public function __construct(callable $option_function)
    {
        $this->option_function = $option_function;
    }

    public function isOptionSet(string $option): bool
    {
        $function = $this->option_function;
        if (strlen($option) == 1) {
            return count($function($option)) == 1;
        }

        return count($function("", ["{$option}::"])) == 1;
    }

    public function getValue(string $option): string
    {
        $function = $this->option_function;
        if (strlen($option) == 1) {
            $options = $function("{$option}::");
            $value = $options[$option];
        } else {
            $options = $function("", ["{$option}::"]);
            $value = $options[$option];
        }

        return $value = $value ?: "";
    }

    public static function createStandardOptions(): CommandLineOptions
    {
        $callable = self::createStandardOptionFunction();

        return new self($callable);
    }

    private static function createStandardOptionFunction(): callable
    {
        return function (...$args) {
            return getopt(...$args);
        };
    }
}
