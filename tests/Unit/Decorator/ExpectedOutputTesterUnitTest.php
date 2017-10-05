<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Decorator;


use ThomasNordahlDk\Tester\Decorator\ExpectedOutputTester;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Tester;
use ThomasNordahlDk\Tester\Tests\Mock\MockTester;

class ExpectedOutputTesterUnitTest implements TestCase
{
    /**
     * @var Tester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit test of " . ExpectedOutputTester::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = $tester;
        $this->testAssertMethod();
        $this->testExpectMethod();
        $this->testExpectOutputMethod();
    }

    private function testAssertMethod(): void
    {
        $tester = $this->tester;

        $mock_tester = new MockTester();
        $expected_output_tester = new ExpectedOutputTester($mock_tester);

        $expected_output_tester->assert(true, "reason for success");
        $tester->assert($mock_tester->assert_result, "should pass renderAssertionSuccess result to original tester");
        $tester->assert($mock_tester->assert_why === "reason for success",
            "should pass renderAssertionSuccess reason to original tester");

        $mock_tester = new MockTester();
        $expected_output_tester = new ExpectedOutputTester($mock_tester);

        $expected_output_tester->assert(false, "reason for failure");
        $tester->assert(! $mock_tester->assert_result,
            "should pass false renderAssertionSuccess result to original tester");
        $tester->assert($mock_tester->assert_why === "reason for failure",
            "should pass renderAssertionSuccess failure reason to original tester");
    }

    private function testExpectMethod(): void
    {
        $tester = $this->tester;

        $mock_tester = new MockTester();
        $comparison_tester = new ExpectedOutputTester($mock_tester);

        $function = function () {
            /* Do nothing */
        };

        $comparison_tester->expect("exception_type", $function, "reason");

        $tester->assert($mock_tester->expect_exception_type === "exception_type",
            "should pass exception type to original tester");
        $tester->assert($mock_tester->expect_when === $function, "Should pass when function to original tester");
        $tester->assert($mock_tester->expect_why === "reason", "should pass reason for expectation to original tester");
    }

    public function testExpectOutputMethod()
    {
        $tester = $this->tester;

        $mock_tester = new MockTester();
        $output_buffer_tester = new ExpectedOutputTester($mock_tester);

        $test_text = "This is the text expected to be caught by the output buffer and compared";

        $output_buffer_tester->expectOutput($test_text, function () use ($test_text) {
            echo $test_text;
        }, "why");

        $tester->assert($mock_tester->assert_result === true,
            "Matching text output by the \$when argument should cause a success expectOutput");
        $tester->assert("why" === $mock_tester->assert_why, "Reason why is passed to standard renderAssertionSuccess");

        $expected_why_reason_passed = "why\nExpected:\n{$test_text}\nActual:\nsomething else than {$test_text}";

        $output_buffer_tester->expectOutput($test_text, function () use ($test_text) {
            echo "something else than {$test_text}";
        }, "why");

        $tester->assert($mock_tester->assert_result === false,
            "Matching text output by the \$when argument should cause a success expectOutput");
        $tester->assert($expected_why_reason_passed === $mock_tester->assert_why,
            "Add expected and actual values as reason why on failed comparison");
    }

}
