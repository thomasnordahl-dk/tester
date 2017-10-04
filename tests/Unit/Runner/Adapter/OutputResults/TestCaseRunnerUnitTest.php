<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\OutputResults;

use ThomasNordahlDk\Tester\Decorator\ExpectedOutputTester;
use ThomasNordahlDk\Tester\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestCase\TestCaseRunner;
use ThomasNordahlDk\Tester\Runner\FailedTestException;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Tests\Mock\MockTestCase;
use ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\OutputResults\Assertion\MockOutputResultsTester;

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

        $mock_tester = new MockOutputResultsTester();
        $test_case_runner = new TestCaseRunner($mock_tester);

        $tester->expectOutput(
            "",
            function () use ($test_case_runner, $mock_tester) {

                $mock_case = new MockTestCase("description",
                    function (Tester $tester) {
                        $tester->assert(true, "reason 1");
                    });

                $test_case_runner->run($mock_case);
            },
            "No output on non verbose test case runner");

        $tester->assert($mock_tester->getAssertResults() === [true],
            "Test case runner runs test case with OutputResultsTester");
    }

    private function testSuccessfulTestCaseSummaryVerbose(): void
    {
        $tester = $this->tester;

        $mock_tester = new MockOutputResultsTester();

        $tester->expectOutput(
            "Test completed after 2 successful assertions\n",
            function () use ($mock_tester) {
                $test_case_runner = new TestCaseRunner($mock_tester, true);

                $mock_case = new MockTestCase("description",
                    function (Tester $tester) {
                        $tester->assert(true, "reason 1");
                        $tester->assert(true, "reason 1");
                    });

                $test_case_runner->run($mock_case);
            },
            "Success summary formatted correctly verbose test case runner");

        $tester->assert($mock_tester->getAssertResults() === [true, true],
            "Test case runner runs test case with OutputResultsTester");
    }

    private function testFailedTestCaseSummary(): void
    {
        $tester = $this->tester;

        /** @var FailedTestException $exception_caught */
        $exception_caught = null;

        $tester->expectOutput(
            "FAILED! Test failed after 1 successful assertion\n",
            function () use (&$exception_caught) {
                $mock_tester = new MockOutputResultsTester();

                $test_case_runner = new TestCaseRunner($mock_tester, true);

                $mock_case = new MockTestCase("description",
                    function (Tester $tester) {
                        $tester->assert(true, "reason 1");
                        $tester->assert(false, "reason 2");
                    });
                try {
                    $test_case_runner->run($mock_case);
                } catch (FailedTestException $exception) {
                    $exception_caught = $exception;
                }
            },
            "Success summary formatted correctly verbose test case runner");

        $tester->assert($exception_caught instanceof FailedTestException,
            "Test case runner throws failed test exception on failed test");

        $tester->assert($exception_caught->getMessage() === "reason 2",
            "Failed assertion reason passed as exception message");
    }
}
