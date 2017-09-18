<?php

namespace Phlegmatic\Tester\Tests\Unit;

use Phlegmatic\Tester\Exception\FailedTestsException;
use Phlegmatic\Tester\Helpers\OutputAssertionTester;
use Phlegmatic\Tester\Adapters\OutputResults\OutputResultsRunner;
use Phlegmatic\Tester\TestCase;
use Phlegmatic\Tester\Tester;
use Phlegmatic\Tester\TestPackage;
use Phlegmatic\Tester\Tests\Mock\MockCase;

class OutputResultsTestRunnerUnitTest implements TestCase
{
    /**
     * @var OutputAssertionTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit Test of " . self::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new OutputAssertionTester($tester);

        $this->testSinglePassingTestCase();
        $this->testTwoPassingTestCases();
        $this->testFailingTestCase();
    }

    private function testSinglePassingTestCase()
    {
        $tester = $this->tester;

        $mock_case = new MockCase;
        $package = new TestPackage("description", [$mock_case]);
        $runner = new OutputResultsRunner();

        $expected =
            "description **************************************
mock test case - Success!
**************************************************
1 tests passed successfully!
**************************************************

";
        $tester->assertOutput($expected, function () use ($runner, $package) {
            $runner->run([$package]);
        }, "Output must match the expected format!");
    }

    private function testTwoPassingTestCases()
    {
        $tester = $this->tester;

        $case = new MockCase;
        $package = new TestPackage("description", [$case, $case]);
        $runner = new OutputResultsRunner();

        $expected =
            "description **************************************
mock test case - Success!
mock test case - Success!
**************************************************
2 tests passed successfully!
**************************************************

";
        $tester->assertOutput($expected, function () use ($runner, $package) {
            $runner->run([$package]);
        }, "Output must match the expected format!");
    }

    private function testFailingTestCase()
    {
        $tester = $this->tester;

        $case = new MockCase;
        $case->succeed = false;
        $case->why = "why";

        $package = new TestPackage("description", [$case]);
        $runner = new OutputResultsRunner();

        $expected =
            "description **************************************
mock test case - FAILED!
- FAILED! why
**************************************************
FAILED TEST!!!
1 out of 1 tests failed
**************************************************

";
        $tester->assertOutput($expected, function () use ($runner, $package) {
            try {
                $runner->run([$package]);
            } catch (FailedTestsException $e) {
                //No need to react for this test
            }
        }, "Output must match the expected format!");
    }
}
