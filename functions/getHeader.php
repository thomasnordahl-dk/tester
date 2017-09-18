<?php

use Phlegmatic\Tester\Adapters\OutputResults\OutputResultsRunner;
use Phlegmatic\Tester\Runner;

function getRunner(): Runner
{
    static $runner;

    if (! $runner instanceof Runner) {
        $runner = new OutputResultsRunner();
    }

    return $runner;
}
