<?php

namespace ThomasNordahlDk\Tester\Runner\Utility;


class CommandLineArgumentsParser
{
    private const SHORT_ARGUMENT_REGEX = "/^\-?([a-z])(\=([a-z].*))?$/i";
    private const LONG_ARGUMENT_REGEX  = "/^\-\-([a-z][a-z\-]*)(\=([a-z].*))?$/i";

    /**
     * @var array
     */
    private $arguments = [];

    public function parseArguments(array $argv): array
    {
        $this->arguments = [];

        $arguments = array_slice($argv, 1, count($argv));

        foreach ($arguments as $argument) {
            $this->parseArgument($argument);
        }

        return $this->arguments;
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
