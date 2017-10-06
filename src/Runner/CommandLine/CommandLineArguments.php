<?php

namespace ThomasNordahlDk\Tester\Runner\CommandLine;

/**
 * Reflects the content of the reserved variable argv.
 *
 * Used for checking command line arguments passed to a test script.
 *
 * The argv is passed as a constructor argument where the class is used.
 *
 * The argv variable is only available when the php script is invoked from the
 * commandline, but an array with similar content can be used for testing.
 *
 * Accepts the following formats
 *
 * script.php -j -i=v --long --long-argument=value
 *
 * http://us2.php.net/manual/en/reserved.variables.php
 *
 * @see CommandLineArguments::isSet()
 * @see CommandLineArguments::getValue()
 */
class CommandLineArguments
{
    private const SHORT_ARGUMENT_REGEX = "/^\-([a-z])(\=([a-z].*))?$/i";
    private const LONG_ARGUMENT_REGEX  = "/^\-\-([a-z][a-z\-]*)(\=([a-z].*))?$/i";

    /**
     * @var array
     */
    private $arguments = [];

    /**
     * @param array $argv The reserved variable $argv or similar array.
     */
    public function __construct(array $argv)
    {
        $arguments = array_slice($argv, 1, count($argv));

        foreach ($arguments as $argument) {
            $this->parseArgument($argument);
        }
    }

    /**
     * Returns true if option is set.
     *
     * @param string $option The option index
     *
     * @return bool
     */
    public function isSet(string $option): bool
    {
        return isset($this->arguments[$option]);
    }

    /**
     * Returns the value set for the option
     *
     * If no value is set an empty string is returned
     *
     * @param string $option
     *
     * @return string
     */
    public function getValue(string $option): string
    {
        return @$this->arguments[$option] ?: "";
    }

    /**
     * Uses regular expressions to extract index and value of valid argument
     *
     * @param string $argument
     */
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
