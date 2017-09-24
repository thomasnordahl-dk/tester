<?php

namespace Phlegmatic\Tester\Tests\Mock\Runner\Adapter\OutputResults;


use Phlegmatic\Tester\Runner\Adapter\OutputResults\OutputResultsTester;
use Phlegmatic\Tester\Runner\Adapter\OutputResults\OutputResultsTesterFactory;

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
