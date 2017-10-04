<?php

namespace ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\OutputResults;


use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\OutputResultsFactory;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestCase\TestCaseRunner;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\Assertion\OutputResultsTester;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestSuite\TestSuiteRunner;
use ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\OutputResults\Assertion\MockOutputResultsTester;

class MockOutputResultsFactory extends OutputResultsFactory
{
    /**
     * @var MockTestSuiteRunner
     */
    private $output_results_test_runner;

    public function createTester(): OutputResultsTester
    {
        return new MockOutputResultsTester();
    }

    public function createTestCaseRunner(OutputResultsTester $tester): TestCaseRunner
    {
        return new MockTestCaseRunner($tester);
    }

    public function createTestSuiteRunner(): TestSuiteRunner
    {
        return $this->getMockTestSuiteRunner();
    }

    public function getMockTestSuiteRunner(): MockTestSuiteRunner
    {
        if (! $this->output_results_test_runner instanceof MockTestSuiteRunner) {
            $this->output_results_test_runner = new MockTestSuiteRunner($this);
        }

        return $this->output_results_test_runner;
    }
}
