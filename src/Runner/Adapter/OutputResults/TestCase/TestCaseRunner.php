<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestCase;

use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\Assertion\FailedAssertionException;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\Assertion\OutputResultsTester;
use ThomasNordahlDk\Tester\Runner\FailedTestException;
use ThomasNordahlDk\Tester\TestCase;

/**
 * Runs a test case and outputs a summary of the test.
 * The summary is output if the runner is verbose or the test failed.
 */
class TestCaseRunner
{
    /**
     * @var OutputResultsTester
     */
    private $tester;

    /**
     * @var bool
     */
    private $is_verbose;

    public function __construct(OutputResultsTester $tester, bool $verbose = false)
    {
        $this->tester = $tester;
        $this->is_verbose = $verbose;
    }

    /**
     * @param TestCase $test_case
     *
     * @throws FailedTestException
     */
    public function run(TestCase $test_case): void
    {
        $tester = $this->tester;
        try {
            $test_case->run($tester);

            $this->outputSuccessfulTestSummary($tester);
        } catch (FailedAssertionException $exception) {
            $this->outputFailedTestSummary($tester);

            throw new FailedTestException($exception->getMessage());
        }
    }

    private function outputSuccessfulTestSummary(OutputResultsTester $tester): void
    {
        $assertion_count = $tester->getAssertionCount();

        $assertions = $this->pluralize($assertion_count, "successful assertion");

        if ($this->is_verbose) {
            echo "Test completed after {$assertions}\n";
        }
    }

    private function outputFailedTestSummary(OutputResultsTester $tester): void
    {
        $assertion_count = $tester->getAssertionCount();

        $assertions = $this->pluralize($assertion_count, "successful assertion");

        echo "FAILED! Test failed after {$assertions}\n";
    }

    private function pluralize(int $count, string $subject): string
    {
        return "{$count} {$subject}" . ($count != 1 ? "s" : "");
    }
}
