<?php

namespace ThomasNordahlDk\Tester\Runner\CommandLine;

/**
 * Construct with command line options array $argv
 *
 * @see CommandLineArguments::isSet()
 * @see CommandLineArguments::getValue()
 */
class CommandLineArguments
{
    private const SHORT_ARGUMENT_REGEX = "/^\-?([a-z])(\=([a-z].*))?$/i";
    private const LONG_ARGUMENT_REGEX  = "/^\-\-([a-z][a-z\-]*)(\=([a-z].*))?$/i";

    /**
     * @var array
     */
    private $arguments = [];

    public function __construct(array $argv)
    {
        $arguments = array_slice($argv, 1, count($argv));

        foreach ($arguments as $argument) {
            $this->parseArgument($argument);
        }
    }

    public function isSet(string $option): bool
    {
        return isset($this->arguments[$option]);
    }

    public function getValue(string $option): string
    {
        return @$this->arguments[$option] ?: "";
    }

    private function parseArgument(string $argument): void
    {
        $argument_parts = [];

        if (preg_match(self::SHORT_ARGUMENT_REGEX, $argument, $argument_parts)) {
            $this->arguments[$argument_parts[1]] = @$argument_parts[3] ?: "";
        }

        if (preg_match(self::LONG_ARGUMENT_REGEX, $argument, $argument_parts)) {
            $this->arguments[$argument_parts[1]] = @$argument_parts[3] ?: "";
        }
    }
}
