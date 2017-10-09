<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestSuite;

use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\OutputResultsFactory;
use ThomasNordahlDk\Tester\Runner\FailedTestException;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\TestSuite;

/**
 * Runs the test cases and outputs a summary of the test results.
 */
class TestSuiteRunner
{
    /**
     * @var OutputResultsFactory
     */
    private $factory;

    /**
     * @internal use OutputResultsFactory::createTestSuiteRunner() instead
     *
     * @param OutputResultsFactory $factory
     */
    public function __construct(OutputResultsFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Runs the test cases and outputs a summary of the test results.
     *
     * @param TestSuite $test_suite The test suite to run
     *
     * @throws FailedTestException If any of the test cases fail
     */
    public function run(TestSuite $test_suite): void
    {
        $this->renderHeader($test_suite);

        $result = $this->runSuite($test_suite);

        $this->renderSummary($result);

        if ($result->getFailureCount()) {
            throw new FailedTestException("{$result->getFailureCount()} failed test");
        }
    }

    private function runSuite(TestSuite $suite): TestSuiteResult
    {
        $timer = $this->factory->createTimer();
        $results = new TestSuiteResult();
        $test_cases = $suite->getTestCaseList();

        $timer->start();

        foreach ($test_cases as $test_case) {
            $this->runTestCase($test_case, $results);
        }

        $timer->stop();

        $results->setTimeSpent($timer->getTimePassed());

        return $results;
    }

    private function renderHeader(TestSuite $test_suite)
    {
        $description = $test_suite->getDescription();
        $test_cases = $test_suite->getTestCaseList();
        $case_count = count($test_cases);

        echo $this->createBreaker() . "\n";
        echo "{$description} (test cases: {$case_count})\n";
        echo $this->createBreaker() . "\n\n";

    }

    /**
     * Runs an individual test case and adds the results of the case to the
     * provided test suite results.
     *
     * @param TestCase        $test_case The test case to run
     * @param TestSuiteResult $result    Add the results of the test to this
     */
    private function runTestCase(TestCase $test_case, TestSuiteResult $result): void
    {
        $runner = $this->factory->createTestCaseRunner($test_case);

        try {
            $runner->run();
            $result->registerSuccess($runner->getAssertionCount());
        } catch (FailedTestException $exception) {
            $result->registerFailure($runner->getAssertionCount());
        }
    }

    /**
     * Renders a summary of the test suite from the results provided.
     *
     * @param TestSuiteResult $result The results of the test suite
     */
    private function renderSummary(TestSuiteResult $result): void
    {
        $time = number_format($result->getTimeSpent(), 2, ".", ",");
        $assertions = $result->getAssertionCount();
        $failed = $result->getFailureCount();
        $successful = $result->getSuccessCount();

        echo "\n" . $this->createBreaker() . "\n";

        if ($failed) {
            echo "FAILED! {$failed} failed test(s), {$successful} successful test(s), {$assertions} assertion(s) ({$time}s)\n";
        } else {
            echo "Success! {$successful} test(s), {$assertions} assertion(s) ({$time}s)\n";
        }

        echo $this->createBreaker() . "\n\n";
    }

    private function createBreaker(): string
    {
        return str_pad("", 75, "*");
    }
}
