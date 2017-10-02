<?php

namespace ThomasNordahlDk\Tester\Runner\Utility;

/**
 * Construct with command line options array $argv
 *
 * @see CommandLineArguments::isSet()
 * @see CommandLineArguments::getValue()
 */
class CommandLineArguments
{
    /**
     * @var array
     */
    private $arguments = [];

    public function __construct(array $argv)
    {
        $argument_parser = new CommandLineArgumentsParser();
        $this->arguments = $argument_parser->parseArguments($argv);
    }

    public function isSet(string $option): bool
    {
        return isset($this->arguments[$option]);
    }

    public function getValue(string $option): string
    {
        return @$this->arguments[$option] ?: "";
    }
}
