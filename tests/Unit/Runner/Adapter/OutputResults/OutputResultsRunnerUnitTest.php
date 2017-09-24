<?php

namespace Phlegmatic\Tester\Tests\Unit\Runner\Adapter\OutputResults;

use Exception;
use Phlegmatic\Tester\Assertion\Tester;
use Phlegmatic\Tester\Runner\Adapter\OutputResults\OutputResultsRunner;
use Phlegmatic\Tester\Runner\FailedTestsException;
use Phlegmatic\Tester\Assertion\Decorator\ExpectedOutputTester;
use Phlegmatic\Tester\TestCase;
use Phlegmatic\Tester\TestPackage;
use Phlegmatic\Tester\Tests\Mock\Runner\Adapter\OutputResults\MockOutputResultsTester;
use Phlegmatic\Tester\Tests\Mock\Runner\Adapter\OutputResults\MockOutputResultsTesterFactory;
use Phlegmatic\Tester\Tests\Mock\MockTestCase;
use RuntimeException;

/**
 * TODO refactor this - find a semantic and readable way of linking expected output to expected testcases
 */
class OutputResultsRunnerUnitTest implements TestCase
{
    /**
     * @var ExpectedOutputTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit Test of " . OutputResultsRunner::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new ExpectedOutputTester($tester);

        $this->testTwoPassingTestCases();
        $this->testTwoPassingTestCasesVerbose();
        $this->testFailingTestCase();
        $this->testThrowingTestCase();
    }

    private function testTwoPassingTestCases(): void
    {
        $tester = $this->tester;

        $expected_output = $this->getExpectedOutputTwoPassingTests();
        $when = function () {
            $runner = $this->createRunner();

            $package = $this->createPackageWithTwoPassingTests();

            $runner->run([$package]);

        };
        $tester->expectOutput($expected_output, $when,
            OutputResultsRunner::class . " should match the expected output for two passing tests");
    }

    private function testTwoPassingTestCasesVerbose()
    {
        $tester = $this->tester;

        $expected_output = $this->getExpectedOutputTwoPassingTestsVerbose();
        $when = function () {
            $runner = $this->createRunner(true);

            $package = $this->createPackageWithTwoPassingTests();

            $runner->run([$package]);

        };
        $tester->expectOutput($expected_output, $when,
            OutputResultsRunner::class . " should match the expected output for two passing tests verbose");

    }

    private function testFailingTestCase(): void
    {
        $tester = $this->tester;

        $expected_output = $this->getExpectedOutputSingleFailingTest();
        $exception_thrown = false;

        $when = function () use (&$exception_thrown) {
            $runner = $this->createRunner();

            $package = $this->createPackageWithSingleFailingTest();

            try {
                $runner->run([$package]);
            } catch (FailedTestsException $exception) {
                $exception_thrown = true;
            }
        };

        $tester->expectOutput($expected_output, $when,
            OutputResultsRunner::class . " should match the expected output for single failing case");

        $tester->assert($exception_thrown, "Failed test should cause runner to throw failed test exception");
    }

    private function testThrowingTestCase(): void
    {
        $tester = $this->tester;

        $expected_output = $this->getExpectedOutputThrowingTestCase();
        $when = function () {
            $runner = $this->createRunner();

            $package = $this->createPackageWithThrowingTestCase();

            try {
                $runner->run([$package]);
            } catch (Exception $e) {
                //do nothing
            }
        };

        $tester->expectOutput($expected_output, $when,
            OutputResultsRunner::class . " should output up until exception thrown");
    }

    private function createRunner(bool $verbose = false): OutputResultsRunner
    {
        $mock_tester = new MockOutputResultsTester($verbose);
        $mock_tester_factory = new MockOutputResultsTesterFactory($mock_tester);

        return new OutputResultsRunner($mock_tester_factory, $verbose);
    }

    private function createPackageWithTwoPassingTests(): TestPackage
    {
        $test_case_list = [
            new MockTestCase("test case 1", function (Tester $tester) {
                $tester->assert(true, "reason for success");
            }),
            new MockTestCase("test case 2", function (Tester $tester) {
                $tester->assert(true, "reason for success");
            }),
        ];

        return new TestPackage("package description", $test_case_list);
    }

    private function getExpectedOutputTwoPassingTests(): string
    {
        return "package description (2) ----------------------------------------------------------------------------
test case 1
test case 2
All 2 tests succeeded!
----------------------------------------------------------------------------------------------------

";
    }

    private function getExpectedOutputTwoPassingTestsVerbose(): string
    {
        return "package description (2) ----------------------------------------------------------------------------
test case 1
✔ reason for success
Test ended with 1 successfull assertions
test case 2
✔ reason for success
Test ended with 2 successfull assertions
All 2 tests succeeded!
----------------------------------------------------------------------------------------------------

";
    }

    private function createPackageWithSingleFailingTest(): TestPackage
    {
        $mock_case = new MockTestCase("test case 1", function (Tester $tester) {
            $tester->assert(false, "reason");
        });

        return new TestPackage("description", [$mock_case]);
    }

    private function getExpectedOutputSingleFailingTest(): string
    {
        return "description (1) ------------------------------------------------------------------------------------
test case 1
✖ reason
Test failed after 0 successfull assertions
FAILED! 1 out of 1 failed!
----------------------------------------------------------------------------------------------------

";
    }

    private function createPackageWithThrowingTestCase(): TestPackage
    {
        $mock_case = new MockTestCase("test case 1", function (Tester $tester) {
            $tester->assert(true, "First everything is ok...");
            throw new RuntimeException("Oh no! Then something goes wrong!");
        });

        return new TestPackage("description", [$mock_case]);
    }

    private function getExpectedOutputThrowingTestCase(): string
    {
        return "description (1) ------------------------------------------------------------------------------------
test case 1
";
    }
}
