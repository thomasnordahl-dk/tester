<?php

namespace Phlegmatic\Tester\Tests\Mock\Adapter\OutputResultsRunner;


use Phlegmatic\Tester\Adapter\OutputResultsRunner\OutputResultsTester;
use Phlegmatic\Tester\Adapter\OutputResultsRunner\OutputResultsTesterFactory;

class MockOutputResultsTesterFactory extends OutputResultsTesterFactory
{
    /**
     * @var OutputResultsTester
     */
    private $return_this_on_create;

    public function __construct(OutputResultsTester $return_this_on_create)
    {
        $this->return_this_on_create = $return_this_on_create;
    }

    public function create(bool $verbose): OutputResultsTester
    {
        return $this->return_this_on_create;
    }
}
