<?php

namespace Phlegmatic\Tester\Runner\Factory;

use Phlegmatic\Tester\Runner\Adapter\OutputResults\OutputResultsRunner;
use Phlegmatic\Tester\Runner\Adapter\OutputResults\OutputResultsTesterFactory;
use Phlegmatic\Tester\Runner\Runner;

/**
 * Creates default instances of Runner implementations
 */
class RunnerFactory
{
    public function create(): Runner
    {
        $tester_factory = new OutputResultsTesterFactory();

        return new OutputResultsRunner($tester_factory);
    }
}
