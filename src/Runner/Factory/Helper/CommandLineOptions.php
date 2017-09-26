<?php

namespace Phlegmatic\Tester\Runner\Factory\Helper;

/**
 * Uses getopt to evaluate command line options
 *
 * The class is constructed with a callable function that should delegate to getopt.
 *
 * Construct new instances with the factory method
 * @see \Phlegmatic\Tester\Runner\Factory\Helper\CommandLineOptions::create()
 */
class CommandLineOptions
{
    /**
     * @var callable
     */
    private $option_function;

    /**
     * @param callable $option_function
     *
     * @internal - use CommandLineOptions::create() unless testing
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
        $callable = function (... $args) {
            return getopt(...$args);
        };

        return new self($callable);
    }
}
