<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\OutputResults\TestSuite;


use ThomasNordahlDk\Tester\Decorator\ExpectedOutputTester;
use ThomasNordahlDk\Tester\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestSuite\TestSuiteRunner;
use ThomasNordahlDk\Tester\Runner\FailedTestException;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Tests\Mock\MockTestCase;
use ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\OutputResults\MockOutputResultsFactory;
use ThomasNordahlDk\Tester\TestSuite;

//TODO Fix that the test will fail if the mock cases take longer than 0.0049 seconds to complete
class TestSuiteRunnerUnitTest implements TestCase
{
    /**
     * @var ExpectedOutputTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit test of " . TestSuiteRunner::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new ExpectedOutputTester($tester);

        $this->testSuccessfulTestSuite();
        $this->testFailedTestSuite();
    }

    private function testSuccessfulTestSuite(): void
    {
        $tester = $this->tester;

        $expected_output =
            "***************************************************************************
description (test cases: 2)
***************************************************************************


***************************************************************************
Success! 2 test(s), 3 assertion(s) (1.23s)
***************************************************************************

";
        $tester->expectOutput(
            $expected_output,
            function () {
                $suite = new TestSuite("description", ... [
                    new MockTestCase("case1", function (Tester $tester) {
                        $tester->assert(true, "reason1");
                        $tester->assert(true, "reason2");
                    }),
                    new MockTestCase("case2", function (Tester $tester) {
                        $tester->assert(true, "reason1");
                    }),
                ]);

                $mock_factory = new MockOutputResultsFactory();
                $suite_runner = new TestSuiteRunner($mock_factory);

                $suite_runner->run($suite);
            },
            "Suite runner matches success format");
    }

    private function testFailedTestSuite(): void
    {
        $tester = $this->tester;

        /** @var FailedTestException $exception_caught */
        $exception_caught = null;

        $expected_output =
            "***************************************************************************
description (test cases: 2)
***************************************************************************


***************************************************************************
FAILED! 1 failed test(s), 1 successful test(s), 2 assertion(s) (1.23s)
***************************************************************************

";
        $tester->expectOutput(
            $expected_output,
            function () use (&$exception_caught) {
                $suite = new TestSuite("description", ... [
                    new MockTestCase("case1", function (Tester $tester) {
                        $tester->assert(true, "reason1");
                        $tester->assert(false, "reason2");
                    }),
                    new MockTestCase("case2", function (Tester $tester) {
                        $tester->assert(true, "reason1");
                    }),
                ]);

                $mock_factory = new MockOutputResultsFactory();
                $suite_runner = new TestSuiteRunner($mock_factory);
                try {
                    $suite_runner->run($suite);
                } catch (FailedTestException $exception) {
                    $exception_caught = $exception;
                }
            },
            "Suite runner matches success format");

        $tester->assert($exception_caught instanceof FailedTestException,
            "Test suite runner throws failed test exception");
    }
}
