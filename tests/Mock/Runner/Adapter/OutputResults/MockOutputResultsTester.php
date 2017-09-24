<?php

namespace Phlegmatic\Tester\Tests\Mock\Runner\Adapter\OutputResults;


use Phlegmatic\Tester\Runner\Adapter\OutputResults\OutputResultsTester;

class MockOutputResultsTester extends OutputResultsTester
{
    public function __construct($verbose = false)
    {
        parent::__construct($verbose);
    }

    public function assert(bool $result, string $why): void
    {
        parent::assert($result, $why);
    }

    public function expect(string $exception_type, callable $when, string $why): void
    {
        parent::expect($exception_type, $when, $why);
    }

    public function getSuccessCount(): int
    {
        return parent::getSuccessCount();
    }
}
