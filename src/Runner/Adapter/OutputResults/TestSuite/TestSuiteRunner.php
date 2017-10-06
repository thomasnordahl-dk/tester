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
    private const BREAKER_LENGTH    = 75;
    private const BREAKER_CHARACTER = "*";

    /**
     * @var float
     */
    private $start_time;

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
        $description = $test_suite->getDescription();
        $test_cases = $test_suite->getTestCaseList();

        $count = count($test_cases);
        $this->outputBreakerLine();
        echo "{$description} (test cases: {$count})\n";
        $this->outputBreakerLine();
        echo "\n";

        $this->start_time = microtime(true);

        $result = new TestSuiteResult();

        foreach ($test_cases as $test_case) {
            $this->runTestCase($test_case, $result);
        }

        $this->renderSummary($result);

        if ($result->getFailureCount()) {
            $message = $this->pluralize($result->getFailureCount(), "failed test");
            throw new FailedTestException($message);
        }
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
        $tester = $this->factory->createTester();
        $runner = $this->factory->createTestCaseRunner($tester);

        echo " - " . $test_case->getDescription() . "\n";

        try {
            $runner->run($test_case);
            $result->registerSuccess($tester->getAssertionCount());
        } catch (FailedTestException $exception) {
            $result->registerFailure($tester->getAssertionCount());
        }
    }

    /**
     * Renders a summary of the test suite from the results provided.
     *
     * @param TestSuiteResult $result The results of the test suite
     */
    private function renderSummary(TestSuiteResult $result): void
    {
        $time = number_format(microtime(true) - $this->start_time, 2, ".", ",");

        $assertions = $this->pluralize($result->getAssertionCount(), "assertion");

        echo "\n";
        $this->outputBreakerLine();

        if ($result->getFailureCount()) {
            $successful = $this->pluralize($result->getSuccessCount(), "successful test");
            $failed = $this->pluralize($result->getFailureCount(), "failed test");

            echo "FAILED! {$failed}, {$successful}, {$assertions} ({$time}s)\n";

        } else {
            $tests = $this->pluralize($result->getSuccessCount(), "test");
            echo "Success! {$tests}, {$assertions} ({$time}s)\n";
        }

        $this->outputBreakerLine();
        echo "\n";
    }

    /**
     * Formats a string describing an amount of items, with correct
     * pluralization.
     *
     * @param int    $count   The count of items
     * @param string $subject The subject string
     *
     * @return string "$count $subject(s)"
     */
    private function pluralize(int $count, string $subject): string
    {
        return "{$count} $subject" . ($count != 1 ? "s" : "");
    }

    private function outputBreakerLine(): void
    {
        echo str_pad("", self::BREAKER_LENGTH, self::BREAKER_CHARACTER) . "\n";
    }
}
