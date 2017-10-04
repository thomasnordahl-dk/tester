<?php

namespace ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\OutputResults\Assertion;


use \Exception;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\Assertion\OutputResultsTester;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\Assertion\FailedAssertionException;

class MockOutputResultsTester extends OutputResultsTester
{
    private $assertion_count = 0;

    private $assert_results = [];
    private $assert_reasons = [];
    private $exception_types = [];
    private $expect_functions = [];
    private $expect_reasons = [];

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

    public function expect(string $exception_type, callable $when, string $why): void
    {
        $this->exception_types[] = $exception_type;
        $this->expect_functions[] = $when;
        $this->expect_reasons[] = $why;

        //Skip outputting
        try {
            $when();
        } catch (Exception $exception) {
            if (! $exception instanceof $exception) {
                throw $exception;
            }
            $this->assertion_count++;

            return;
        }

        throw new FailedAssertionException($why);
    }

    public function getAssertionCount(): int
    {
        return $this->assertion_count;
    }

    public function getAssertResults(): array
    {
        return $this->assert_results;
    }

    public function getAssertReasons(): array
    {
        return $this->assert_reasons;
    }

    public function getExceptionTypes(): array
    {
        return $this->exception_types;
    }

    public function getExpectFunctions(): array
    {
        return $this->expect_functions;
    }

    public function getExpectReasons(): array
    {
        return $this->expect_reasons;
    }
}
