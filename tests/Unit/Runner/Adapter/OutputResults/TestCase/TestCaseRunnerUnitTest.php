<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\OutputResults\TestCase;

use ThomasNordahlDk\Tester\Decorator\ExpectedOutputTester;
use ThomasNordahlDk\Tester\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestCase\TestCaseRunner;
use ThomasNordahlDk\Tester\Runner\FailedTestException;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Tests\Mock\MockTestCase;
use ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\OutputResults\MockOutputResultsFactory;

class TestCaseRunnerUnitTest implements TestCase
{
    /**
     * @var ExpectedOutputTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit test of " . TestCaseRunner::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new ExpectedOutputTester($tester);

        $this->testSuccessfulTestCaseSummary();
        $this->testSuccessfulTestCaseSummaryVerbose();
        $this->testFailedTestCaseSummary();
    }

    private function testSuccessfulTestCaseSummary()
    {
        $tester = $this->tester;

        $mock_factory = new MockOutputResultsFactory();
        $mock_case = new MockTestCase("description",
            function (Tester $tester) {
                $tester->assert(true, "reason 1");
            });

        $test_case_runner = new TestCaseRunner($mock_case, $mock_factory);

        $tester->expectOutput(
            " - description\n",
            function () use ($test_case_runner) {
                $test_case_runner->run();
            },
            "Only description on non verbose test case runner");

        $tester->assert($test_case_runner->getAssertionCount() === 1,
            "Test case runner counts successful assertions");
    }

    private function testSuccessfulTestCaseSummaryVerbose(): void
    {
        $tester = $this->tester;

        $mock_factory = new MockOutputResultsFactory();
        $mock_case = new MockTestCase("description",
            function (Tester $tester) {
                $tester->assert(true, "reason 1");
                $tester->assert(true, "reason 1");
            });

        $test_case_runner = new TestCaseRunner($mock_case, $mock_factory, true);

        $tester->expectOutput(
            " - description\nTest completed after 2 successful assertion(s)\n",
            function () use ($test_case_runner) {
                $test_case_runner->run();
            },
            "Success summary formatted correctly verbose test case runner");

        $tester->assert($test_case_runner->getAssertionCount() === 2,
            "Test case runner counts multiple assertions");
    }

    private function testFailedTestCaseSummary(): void
    {
        $tester = $this->tester;

        $mock_factory = new MockOutputResultsFactory();
        $mock_case = new MockTestCase("description",
            function (Tester $tester) {
                $tester->assert(true, "reason 1");
                $tester->assert(false, "reason 2");
            });

        $test_case_runner = new TestCaseRunner($mock_case, $mock_factory);

        /** @var FailedTestException $exception_caught */
        $exception_caught = null;

        $tester->expectOutput(
            " - description\nFAILED! Test failed after 1 successful assertion(s)\n",
            function () use ($test_case_runner, &$exception_caught) {
                try {
                    $test_case_runner->run();
                } catch (FailedTestException $exception) {
                    $exception_caught = $exception;
                }
            },
            "Success summary formatted correctly verbose test case runner");

        $tester->assert($exception_caught instanceof FailedTestException,
            "Test case runner throws failed test exception on failed test");

        $tester->assert($exception_caught->getMessage() === "reason 2",
            "Failed assertion reason passed as exception message");

        $tester->assert($test_case_runner->getAssertionCount() === 1,
            "Test runner counts succesful assertions, even on failure");
    }
}
