<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\OutputResults;

use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\TestSuite;

/**
 * Renders headers and summaries for test cases and test suites
 * and creates instances of OutputResultsTester
 */
class TestResultsRenderer
{
    /**
     * @var bool
     */
    private $is_verbose;

    /**
     * @param bool $is_verbose Render verbose results
     */
    public function __construct(bool $is_verbose = false)
    {
        $this->is_verbose = $is_verbose;
    }

    /**
     * Creates instances of OutputResultsTester
     *
     * @return OutputResultsTester
     */
    public function createTester(): OutputResultsTester
    {
        return new OutputResultsTester($this->is_verbose);
    }

    /**
     * Render test case header
     *
     * @param TestCase $case Case to render header for
     *
     * @return string
     */
    public function renderTestCaseHeader(TestCase $case): string
    {
        return " - " . $case->getDescription() . "\n";
    }

    /**
     * Renders summary of test case
     *
     * @param bool $completed       true if test completed without failure
     * @param int  $assertion_count successful assertion count
     *
     * @return string
     */
    public function renderTestCaseSummary(bool $completed, int $assertion_count): string
    {
        if ($completed && $this->is_verbose) {
            return "Success! Test completed after {$assertion_count} successful assertion(s)\n";
        }

        if (! $completed) {
            return "FAILED! Failed after {$assertion_count} successful assertion(s)\n";
        }

        return "";
    }

    /**
     * Renders header for test suite
     *
     * @param TestSuite $suite Suite to render header of
     *
     * @return string
     */
    public function renderTestSuiteHeader(TestSuite $suite): string
    {
        $breaker = $this->renderBreaker();
        $description = $suite->getDescription();
        $case_count = count($suite->getTestCaseList());

        return "{$breaker}\n{$description} (test cases: {$case_count})\n{$breaker}\n\n";
    }

    /**
     * Renders summary of TestSuite
     *
     * @param int   $successes  completed test count
     * @param int   $failures   failed test count
     * @param int   $assertions assertion count
     * @param float $time       time it took to run suite in seconds
     *
     * @return string
     */
    public function renderTestSuiteSummary(int $successes, int $failures, int $assertions, float $time): string
    {
        $breaker = $this->renderBreaker();
        $time_string = number_format($time, 2, ".", ",");

        $summary = "\n\n{$breaker}\n";
        if ($failures) {
            $summary .= "FAILED! {$failures} test(s) failed, {$successes} completed test(s), {$assertions} assertion(s), ({$time_string}s)\n";
        } else {
            $summary .= "Success! {$successes} test(s), {$assertions} assertion(s), ({$time_string}s)\n";
        }
        $summary .= "{$breaker}\n\n";

        return $summary;
    }

    /**
     * @return string breaker line for Suite header and summary
     */
    private function renderBreaker(): string
    {
        return str_pad("", 75, "*");
    }
}
