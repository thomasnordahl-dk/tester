<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestCase;

use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\Assertion\FailedAssertionException;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\Assertion\OutputResultsTester;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\OutputResultsFactory;
use ThomasNordahlDk\Tester\Runner\FailedTestException;
use ThomasNordahlDk\Tester\TestCase;

/**
 * Runs a test case and outputs a summary of the test.
 * The summary is output if the runner is verbose or the test failed.
 */
class TestCaseRunner
{
    /**
     * @var TestCase
     */

    private $case;

    /**
     * @var OutputResultsFactory
     */
    private $factory;

    /**
     * @var bool
     */
    private $is_verbose;

    /**
     * @var int
     */
    private $assertion_count = 0;

    public function __construct(TestCase $case, OutputResultsFactory $factory, bool $verbose = false)
    {
        $this->case = $case;
        $this->factory = $factory;
        $this->is_verbose = $verbose;
    }

    /**
     * @throws FailedTestException
     */
    public function run(): void
    {
        $this->outputHeader();

        $tester = $this->factory->createTester();

        try {
            $this->case->run($tester);

            $this->outputSuccessfulTestSummary($tester);
        } catch (FailedAssertionException $exception) {
            $this->outputFailedTestSummary($tester);

            throw new FailedTestException($exception->getMessage());
        } finally {
            $this->assertion_count = $tester->getAssertionCount();
        }
    }

    public function getAssertionCount(): int
    {
        return $this->assertion_count;
    }

    private function outputHeader(): void
    {
        echo " - " . $this->case->getDescription() . "\n";
    }

    private function outputSuccessfulTestSummary(OutputResultsTester $tester): void
    {
        $assertion_count = $tester->getAssertionCount();

        if ($this->is_verbose) {
            echo "Test completed after {$assertion_count} successful assertion(s)\n";
        }
    }

    private function outputFailedTestSummary(OutputResultsTester $tester): void
    {
        $assertion_count = $tester->getAssertionCount();

        echo "FAILED! Test failed after {$assertion_count} successful assertion(s)\n";
    }
}
