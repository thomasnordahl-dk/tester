<?php

namespace Phlegmatic\Tester\Factory;

use Phlegmatic\Tester\Adapter\OutputResultsRunner;
use Phlegmatic\Tester\Adapter\OutputResultsRunner\PackageResultRenderer;
use Phlegmatic\Tester\Runner;

class RunnerFactory
{
    public static function createDefault(): Runner
    {
        $renderer = new PackageResultRenderer();
        return new OutputResultsRunner($renderer);
    }
}
