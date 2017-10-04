<?php

namespace ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\OutputResults;


use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestSuite\TestSuiteRunner;
use ThomasNordahlDk\Tester\TestSuite;

class MockTestSuiteRunner extends TestSuiteRunner
{
    private $test_suites = [];

    public function run(TestSuite $test_suite): void
    {
        $this->test_suites[] = $test_suite;
    }

    public function getTestSuites(): array
    {
        return $this->test_suites;
    }
}
