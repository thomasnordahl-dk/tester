<?php

namespace Phlegmatic\Tester\Tests\Unit\Helper;


use Phlegmatic\Tester\Helper\OutputAssertionTester;
use Phlegmatic\Tester\TestCase;
use Phlegmatic\Tester\Tester;
use Phlegmatic\Tester\Tests\Mock\MockTester;

class OutputAssertionTesterUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . OutputAssertionTester::class;
    }

    public function run(Tester $tester): void
    {
        $mock_tester = new MockTester();
        $output_buffer_tester = new OutputAssertionTester($mock_tester);

        $test_text = "This is the text expected to be caught by the output buffer and compared";

        $output_buffer_tester->expectOutput($test_text, function () use ($test_text) {
            echo $test_text;
        }, "why");

        $tester->assert($mock_tester->assert_result === true,
            "Matching text output by the \$when argument should cause a success expectOutput");
        $tester->assert("why" === $mock_tester->assert_why, "Reason why is passed to standard assert");

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
