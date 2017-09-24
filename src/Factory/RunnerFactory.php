<?php

namespace Phlegmatic\Tester\Factory;

use Phlegmatic\Tester\Adapter\OutputResultsRunner\OutputResultsRunner;
use Phlegmatic\Tester\Adapter\OutputResultsRunner\OutputResultsTesterFactory;
use Phlegmatic\Tester\Runner;

class RunnerFactory
{
    public static function createDefault(): Runner
    {
        $tester_factory = new OutputResultsTesterFactory();

        return new OutputResultsRunner($tester_factory);
    }
}
