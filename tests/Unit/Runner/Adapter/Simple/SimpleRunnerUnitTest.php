<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\Simple;


use Exception;
use ThomasNordahlDk\Tester\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Decorator\ExpectedOutputTester;
use ThomasNordahlDk\Tester\Runner\Adapter\Simple\SimpleRunner;

use ThomasNordahlDk\Tester\Runner\Runner;
use ThomasNordahlDk\Tester\Runner\Timer\TimerFactory;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Tester;
use ThomasNordahlDk\Tester\Tests\Mock\MockTestCase;
use ThomasNordahlDk\Tester\Tests\Mock\Runner\Timer\MockTimerFactory;
use ThomasNordahlDk\Tester\TestSuite;

class SimpleRunnerUnitTest implements TestCase
{
    /**
     * @var ExpectedOutputTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit test of " . SimpleRunner::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new ExpectedOutputTester($tester);

        $this->testSuccessfulTestSuite();
        $this->testMultipleTestSuites();
        $this->testCreateMethod();
    }

    private function testSuccessfulTestSuite(): void
    {
        $tester = $this->tester;

        $simple_runner = new SimpleRunner(new MockTimerFactory());
        $tester->assert($simple_runner instanceof Runner, "SimpleRunner is instanceof Runner");

        $mock_case_1 = new MockTestCase("case 1", function (Tester $tester) {
            $tester->assert(true, "reason 1");
            $tester->assert(true, "reason 2");
        });

        $test_suite = new TestSuite("Suite 1", [$mock_case_1]);

        $expected = "
---------------------------------------------------------------------------
 - Suite 1 - cases: 1
 --- case 1 ✔
---------------------------------------------------------------------------
success: 1, failure: 0, assertions: 2, time: 1.23s
---------------------------------------------------------------------------
";

        $tester->expectOutput($expected, function () use ($simple_runner, $test_suite) {
            $simple_runner->run([$test_suite]);
        }, "SimpleRunner output for 1 succesful case matches excpected");
    }

    private function testMultipleTestSuites(): void
    {
        $tester = $this->tester;

        $simple_runner = new SimpleRunner(new MockTimerFactory());
        $tester->assert($simple_runner instanceof Runner, "SimpleRunner is instanceof Runner");

        $mock_case_1 = new MockTestCase("case 1", function (Tester $tester) {
            $tester->assert(true, "reason 1");
            $tester->assert(true, "reason 2");
        });

        $line_num = __LINE__ + 6;
        $mock_case_2 = new MockTestCase("case 2", function (Tester $tester) {
            $tester->assert(true, "reason 1");
            $tester->assert(true, "reason 2");
            $tester->expect(Exception::class, function () {
                //No failure
            }, "reason 3");
        });

        $mock_case_3 = new MockTestCase("case 3", function (Tester $tester) {
            $tester->assert(true, "reason 1");
            $tester->assert(true, "reason 2");
        });

        $test_suite_1 = new TestSuite("Suite 1", [$mock_case_1]);
        $test_suite_2 = new TestSuite("Suite 2", [$mock_case_1, $mock_case_2, $mock_case_3]);
        $test_suites = [$test_suite_1, $test_suite_2];

        $expected = "
---------------------------------------------------------------------------
 - Suite 1 - cases: 1
 --- case 1 ✔
---------------------------------------------------------------------------
success: 1, failure: 0, assertions: 2, time: 1.23s
---------------------------------------------------------------------------

---------------------------------------------------------------------------
 - Suite 2 - cases: 3
 --- case 1 ✔
 --- case 2 ✖
 --- case 3 ✔
---------------------------------------------------------------------------
success: 2, failure: 1, assertions: 6, time: 1.23s
---------------------------------------------------------------------------

1 failed test(s):
# " . __FILE__ . ":{$line_num}
reason 3

";


        $tester->expectOutput($expected, function () use ($simple_runner, $test_suites) {
            $simple_runner->run($test_suites);
        }, "SimpleRunner output for 1 succesful case matches excpected");
    }

    private function testCreateMethod(): void
    {
        $tester = new ComparisonTester($this->tester);

        $expected = new SimpleRunner(new TimerFactory());

        $tester->assertEqual(SimpleRunner::create(), $expected,
            "create() returns a SimpleRunner with new instance of TimerFactory()");
    }
}
