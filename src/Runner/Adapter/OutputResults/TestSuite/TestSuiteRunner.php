<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestSuite;

use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\OutputResultsFactory;
use ThomasNordahlDk\Tester\Runner\FailedTestException;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\TestSuite;

/**
 * Outputs the description and results of the test suite
 */
class TestSuiteRunner
{
    private const PAD_LENGTH    = 100;
    private const PAD_CHARACTER = "-";

    /**
     * @var float
     */
    private $start_time;

    /**
     * @var OutputResultsFactory
     */
    private $factory;

    public function __construct(OutputResultsFactory $factory)
    {
        $this->factory = $factory;
    }

    public function run(TestSuite $test_suite): void
    {
        $description = $test_suite->getDescription();
        $test_cases = $test_suite->getTestCaseList();

        $count = count($test_cases);
        $this->outputPaddedLine("{$description} ({$count}) ");

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

    private function runTestCase(TestCase $test_case, TestSuiteResult $result): void
    {
        $tester = $this->factory->createTester();
        $runner = $this->factory->createTestCaseRunner($tester);

        echo $test_case->getDescription() . "\n";

        try {
            $runner->run($test_case);
            $result->registerSuccess($tester->getAssertionCount());
        } catch (FailedTestException $exception) {
            $result->registerFailure($tester->getAssertionCount());
        }
    }

    private function renderSummary(TestSuiteResult $result): void
    {
        $time = number_format(microtime(true) - $this->start_time, 2, ".", ",");

        $assertions = $this->pluralize($result->getAssertionCount(), "assertion");

        if ($result->getFailureCount()) {
            $successful = $this->pluralize($result->getSuccessCount(), "successful test");
            $failed = $this->pluralize($result->getFailureCount(), "failed test");

            echo "\nFAILED! {$failed}, {$successful}, {$assertions} ({$time}s)\n";

        } else {
            $tests = $this->pluralize($result->getSuccessCount(), "test");
            echo "\nSuccess! {$tests}, {$assertions} ({$time}s)\n";
        }

        $this->outputPaddedLine("");
        echo "\n";
    }

    private function outputPaddedLine(string $value): void
    {
        echo str_pad($value, self::PAD_LENGTH, self::PAD_CHARACTER) . "\n";
    }

    private function pluralize(int $count, string $subject): string
    {
        return "{$count} $subject" . ($count != 1 ? "s" : "");
    }
}
