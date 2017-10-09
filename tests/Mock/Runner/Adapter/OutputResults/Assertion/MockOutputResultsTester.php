<?php

namespace ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\OutputResults\Assertion;

use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\Assertion\OutputResultsTester;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\Assertion\FailedAssertionException;

class MockOutputResultsTester extends OutputResultsTester
{
    private $assertion_count = 0;

    private $assert_results = [];
    private $assert_reasons = [];

    public function assert(bool $result, string $why): void
    {
        $this->assert_results[] = $result;
        $this->assert_reasons[] = $why;

        //Skip outputting
        $this->assertion_count += ($result ? 1 : 0);

        if (! $result) {
            throw new FailedAssertionException($why);
        }
    }

    public function getAssertionCount(): int
    {
        return $this->assertion_count;
    }

}
