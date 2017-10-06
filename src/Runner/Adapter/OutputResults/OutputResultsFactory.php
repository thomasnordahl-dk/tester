<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\OutputResults;

use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\Assertion\OutputResultsTester;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestCase\TestCaseRunner;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestSuite\TestSuiteRunner;

/**
 * Creates instances of the classes needed by the OutputResultsRunner
 *
 * @internal This factory should only be used as an internal component of
 *           the OutputResultsRunner
 */
class OutputResultsFactory
{
    /**
     * @var bool
     */
    private $is_verbose;

    /**
     * @param bool $verbose If true the factory creates verbose instances.
     */
    public function __construct(bool $verbose = false)
    {
        $this->is_verbose = $verbose;
    }

    public function createTester(): OutputResultsTester
    {
        return new OutputResultsTester($this->is_verbose);
    }

    public function createTestCaseRunner(OutputResultsTester $tester): TestCaseRunner
    {
        return new TestCaseRunner($tester, $this->is_verbose);
    }

    public function createTestSuiteRunner(): TestSuiteRunner
    {
        return new TestSuiteRunner($this);
    }
}
