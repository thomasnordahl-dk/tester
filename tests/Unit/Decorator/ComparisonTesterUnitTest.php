<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Decorator;

use ThomasNordahlDk\Tester\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Tester;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Tests\Mock\MockTester;
use stdClass;

class ComparisonTesterUnitTest implements TestCase
{
    /**
     * @var Tester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit test of " . ComparisonTester::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = $tester;

        $this->testAssertMethod();
        $this->testExpectMethod();
        $this->testAssertEqualMethod();
        $this->testAssertSameMethod();
    }

    private function testAssertMethod(): void
    {
        $tester = $this->tester;

        $mock_tester = new MockTester();
        $comparison_tester = new ComparisonTester($mock_tester);

        $comparison_tester->assert(true, "reason for success");
        $tester->assert($mock_tester->assert_result, "should pass renderAssertionSuccess result to original tester");
        $tester->assert($mock_tester->assert_why === "reason for success",
            "should pass renderAssertionSuccess reason to original tester");

        $mock_tester = new MockTester();
        $comparison_tester = new ComparisonTester($mock_tester);

        $comparison_tester->assert(false, "reason for failure");
        $tester->assert(! $mock_tester->assert_result, "should pass false renderAssertionSuccess result to original tester");
        $tester->assert($mock_tester->assert_why === "reason for failure",
            "should pass renderAssertionSuccess failure reason to original tester");
    }

    private function testExpectMethod(): void
    {
        $tester = $this->tester;

        $mock_tester = new MockTester();
        $comparison_tester = new ComparisonTester($mock_tester);

        $function = function () {
            /* Do nothing */
        };

        $comparison_tester->expect("exception_type", $function, "reason");

        $tester->assert($mock_tester->expect_exception_type === "exception_type",
            "should pass exception type to original tester");
        $tester->assert($mock_tester->expect_when === $function, "Should pass when function to original tester");
        $tester->assert($mock_tester->expect_why === "reason", "should pass reason for expectation to original tester");
    }

    private function testAssertSameMethod(): void
    {
        $object = new stdClass();
        $object_2 = new stdClass();
        $array = [1, "2" => "b", ["nested" => "value"], $object];
        $same_array = [1, "2" => "b", ["nested" => "value"], $object];
        $not_same_array = [1, "2" => "b", ["nested" => "value"], $object_2];

        //arrays containing [value, expected_value, expected_result, why]
        $tests = [
            [123, 123, true, "123 and 123 are same"],
            [123, 321, false, "123 and 321 are not same"],
            [true, 1, false, "true and 1 are not same"],
            [false, 0, false, "false and 0 are not same"],
            [null, false, false, "null and false are not same"],
            ["1", 1, false, "\"1\" and 1 are not the same"],
            ["1", "1", true, "\"1\" and \"1\" are the same"],
            [$object, $object, true, "same object"],
            [$object, $object_2, false, "different object"],
            [$array, $same_array, true, "same array"],
            [$array, $not_same_array, false, "not the same array"],

        ];

        foreach ($tests as $arguments) {
            $this->testIfConsideredSame(...$arguments);
        }
    }

    private function testAssertEqualMethod(): void
    {
        $object = new stdClass();
        $object->hello = "hello";

        $equal_object = new stdClass();
        $equal_object->hello = "hello";

        $not_equal_object = new stdClass();
        $not_equal_object->hello = "bonjour";

        $array = [1, "2" => "3", $equal_object, [1 => 2]];
        $equal_array = [1, "2" => "3", $object, [1 => 2]];
        $not_equal_array = [1, "2" => "3", $not_equal_object, [1 => 2]];

        //arrays containing [value, expected_value, expected_result, why]
        $tests = [
            ["1 ", 1, true, "string and int are equal"],
            [true, 1, true, "true and 1 are equal"],
            ["hello", "hello", true, "string and string are equal"],
            ["", false, true, "empty string and false are equal"],
            [null, false, true, "null and false are equal"],
            [[], false, true, "empty array and false are equal"],
            [[], [], true, "empty arrays are equal"],
            [true, 0, false, "true and 0 are not equal"],
            ["2 ", 1, false, "string and int are not equal"],
            ["hello", "bonjour", false, "different strings are not equal"],
            [$array, $equal_array, true, "equal arrays"],
            [$array, $not_equal_array, false, "not equal arrays"],
            [$object, $equal_object, true, "equal objects"],
            [$object, $not_equal_object, false, "not equal objects"],
        ];

        foreach ($tests as $arguments) {
            $this->assertIfConsideredEqual(...$arguments);
        }
    }

    private function assertIfConsideredEqual($value, $expected, bool $expected_result, string $why): void
    {
        $tester = $this->tester;

        $mock_tester = new MockTester();
        $comparison_tester = new ComparisonTester($mock_tester);

        $comparison_tester->assertEqual($value, $expected, "is value equal to expected");

        $tester->assert($mock_tester->assert_result === $expected_result, $why);
        $tester->assert($mock_tester->assert_why === "is value equal to expected",
            "Should pass reason when asserting: {$why}");
    }

    private function testIfConsideredSame($value, $expected_value, bool $expected_result, string $why): void
    {
        $tester = $this->tester;

        $mock_tester = new MockTester();
        $comparison_tester = new ComparisonTester($mock_tester);

        $comparison_tester->assertSame($value, $expected_value, "renderAssertionSuccess if same");

        $tester->assert($mock_tester->assert_result === $expected_result, $why);
        $tester->assert($mock_tester->assert_why === "renderAssertionSuccess if same",
            "tester should pass reason when asserting: {$why}");

    }
}
