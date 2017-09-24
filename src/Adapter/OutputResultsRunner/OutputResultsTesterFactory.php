<?php

namespace Phlegmatic\Tester\Adapter\OutputResultsRunner;


class OutputResultsTesterFactory
{
    public function create(bool $verbose): OutputResultsTester
    {
        return new OutputResultsTester($verbose);
    }
}
