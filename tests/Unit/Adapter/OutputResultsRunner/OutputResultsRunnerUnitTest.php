<?php

namespace Phlegmatic\Tester\Tests\Unit\Adapter\OutputResultsRunner;

use Exception;
use Phlegmatic\Tester\Adapter\OutputResultsRunner\OutputResultsRunner;
use Phlegmatic\Tester\Helper\OutputAssertionTester;
use Phlegmatic\Tester\TestCase;
use Phlegmatic\Tester\Tester;
use Phlegmatic\Tester\TestPackage;
use Phlegmatic\Tester\Tests\Mock\Adapter\OutputResultsRunner\MockOutputResultsTester;
use Phlegmatic\Tester\Tests\Mock\Adapter\OutputResultsRunner\MockOutputResultsTesterFactory;
use Phlegmatic\Tester\Tests\Mock\MockTestCase;
use RuntimeException;

/**
 * TODO refactor this - find a semantic and readable way of linking expected output to expected testcases
 */
class OutputResultsRunnerUnitTest implements TestCase
{
    /**
     * @var OutputAssertionTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit Test of " . OutputResultsRunner::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new OutputAssertionTester($tester);

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
            OutputResultsRunner::class . " should match the expected output for two passing tests");

    }

    private function testFailingTestCase(): void
    {
        $tester = $this->tester;

        $expected_output = $this->getExpectedOutputSingleFailingTest();

        $when = function () {
            $runner = $this->createRunner();

            $package = $this->createPackageWithSingleFailingTest();

            $runner->run([$package]);

        };

        $tester->expectOutput($expected_output, $when,
            OutputResultsRunner::class . " should match the expected output for single failing case");
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

        return new OutputResultsRunner($mock_tester_factory);
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
test case 2
✔ reason for success
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
