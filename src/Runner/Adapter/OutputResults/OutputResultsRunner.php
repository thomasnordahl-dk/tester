<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\OutputResults;

use ThomasNordahlDk\Tester\Runner\FailedTestException;
use ThomasNordahlDk\Tester\Runner\Runner;
use ThomasNordahlDk\Tester\Runner\Timer\TimerFactory;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\TestSuite;

/**
 * Runner class that outputs a test summary to the output buffer.
 */
class OutputResultsRunner implements Runner
{
    /**
     * @var TestResultsRenderer
     */
    private $renderer;

    /**
     * @var TimerFactory
     */
    private $timer_factory;

    /**
     * @var int
     */
    private $assertion_count = 0;
    /**
     * @var int
     */
    private $total_failures = 0;

    /**
     * Factory method for neat creaton of new OutputResultsRunner instance.
     *
     * @param bool $verbose Set to true to output verbose summaries.
     *
     * @return OutputResultsRunner
     */
    public static function create(bool $verbose = false): OutputResultsRunner
    {
        $renderer = new TestResultsRenderer($verbose);
        $timer_factory = new TimerFactory();

        return new self($renderer, $timer_factory);
    }


    public function __construct(TestResultsRenderer $renderer, TimerFactory $timer_factory)
    {
        $this->renderer = $renderer;
        $this->timer_factory = $timer_factory;
    }

    /**
     * @inheritdoc
     *
     * @throws FailedTestException
     */
    public function run($suites): void
    {
        $this->total_failures = 0;

        foreach ($suites as $suite) {
            $this->runSuite($suite);
        }

        if ($this->total_failures > 0) {
            throw new FailedTestException("{$this->total_failures} test cases failed!");
        }
    }

    /**
     * Runs individual test suite
     *
     * @param TestSuite $suite The test suite to run.
     */
    private function runSuite(TestSuite $suite): void
    {
        $renderer = $this->renderer;
        $timer = $this->timer_factory->create();
        $test_cases = $suite->getTestCaseList();

        echo $renderer->renderTestSuiteHeader($suite);

        $successes = 0;
        $failures = 0;
        $this->assertion_count = 0;
        $timer->start();

        foreach ($test_cases as $case) {
            try {
                $this->runTestCase($case);
                $successes++;
            } catch (FailedTestException $e) {
                $failures++;
            }
        }

        echo $renderer->renderTestSuiteSummary($successes, $failures, $this->assertion_count, $timer->stop());

        $this->total_failures += $failures;
    }

    /**
     * Runs individual test case
     *
     * @param TestCase $test_case
     *
     * @throws FailedTestException
     */
    private function runTestCase(TestCase $test_case): void
    {
        $renderer = $this->renderer;
        $tester = $renderer->createTester();
        $failed = false;

        echo $renderer->renderTestCaseHeader($test_case);

        try {
            $test_case->run($tester);
        } catch (FailedAssertionException $e) {
            $failed = true;
        }
        $assertion_count = $tester->getAssertionCount();

        $this->assertion_count += $assertion_count;

        $renderer->renderTestCaseSummary($failed, $assertion_count);

        if ($failed) {
            throw new FailedTestException("{$test_case->getDescription()} failed!");
        }
    }
}
