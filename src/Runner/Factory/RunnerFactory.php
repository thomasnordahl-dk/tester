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
    /**
     * @var OutputResultsTesterFactory
     */
    private $tester_factory;

    public function __construct()
    {
        $this->tester_factory = new OutputResultsTesterFactory();
    }

    public function create(): Runner
    {
        $tester_factory = $this->tester_factory;

        return new OutputResultsRunner($tester_factory, false);
    }

    public function createVerbose(): Runner
    {
        $tester_factory = $this->tester_factory;

        return new OutputResultsRunner($tester_factory, true);
    }
}
