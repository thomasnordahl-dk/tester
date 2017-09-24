<?php

namespace Phlegmatic\Tester\Adapter\OutputResultsRunner;

/**
 * Creates instances of OutputResultsTester for the OutputResultsRunner
 *
 * @see \Phlegmatic\Tester\Adapter\OutputResultsRunner\OutputResultsRunner
 */
class OutputResultsTesterFactory
{
    public function create(bool $verbose): OutputResultsTester
    {
        return new OutputResultsTester($verbose);
    }
}
