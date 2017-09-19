<?php

use Phlegmatic\Tester\Adapter\OutputResultsRunner;
use Phlegmatic\Tester\Adapter\OutputResultsRunner\PackageResultRenderer;
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
