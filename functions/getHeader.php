<?php

use Phlegmatic\Tester\Adapters\OutputResultsRunner;
use Phlegmatic\Tester\Adapters\OutputResultsRunner\PackageResultRenderer;
use Phlegmatic\Tester\Runner;

function getRunner(): Runner
{
    static $runner;

    if (! $runner instanceof Runner) {
        $renderer = new PackageResultRenderer();
        $runner = new OutputResultsRunner($renderer);
    }

    return $runner;
}
