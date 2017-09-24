<?php

namespace Phlegmatic\Tester\Tests\Mock\Runner\CommandLine;

use Phlegmatic\Tester\Runner\CommandLine\CommandLineOptions;

class MockCommandLineOptions extends CommandLineOptions
{
    /**
     * @var array
     */
    private $options;

    public function __construct(array $options)
    {
        parent::__construct(function () {
           return [];
        });

        $this->options = $options;
    }

    public function getValue(string $option): string
    {
        return @$this->options[$option] ?: "";
    }

    public function isOptionSet(string $option): bool
    {
        return isset($this->options[$option]);
    }
}
