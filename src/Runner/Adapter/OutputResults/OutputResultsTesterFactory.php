<?php

namespace Phlegmatic\Tester\Runner\Adapter\OutputResults;



/**
 * Creates instances of OutputResultsTester for the OutputResultsRunner
 *
 * @see \Phlegmatic\Tester\Runner\Adapter\OutputResults\OutputResultsRunner
 */
class OutputResultsTesterFactory
{
    public function create(bool $verbose): OutputResultsTester
    {
        return new OutputResultsTester($verbose);
    }
}
