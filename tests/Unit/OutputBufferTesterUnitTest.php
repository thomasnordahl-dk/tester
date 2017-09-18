<?php

namespace Phlegmatic\Tester\Tests\Unit;


use Phlegmatic\Tester\Helpers\OutputAssertionTester;
use Phlegmatic\Tester\TestCase;
use Phlegmatic\Tester\Tester;
use Phlegmatic\Tester\Tests\Mock\MockTester;

class OutputBufferTesterUnitTest implements TestCase
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

        $output_buffer_tester->assertOutput($test_text, function () use ($test_text) {
            echo $test_text;
        }, "why");

        $tester->assert($mock_tester->assert_result === true,
            "Matching text output by the \$when argument should cause a success assertOutput");
        $tester->assert("why" === $mock_tester->assert_why, "Only pass reason why on successful comparison");

        $expected_why_reason_passed = "why\nExpected:\n{$test_text}\nActual:\nsomething else than {$test_text}";

        $output_buffer_tester->assertOutput($test_text, function () use ($test_text) {
            echo "something else than {$test_text}";
        }, "why");

        $tester->assert($mock_tester->assert_result === false,
            "Matching text output by the \$when argument should cause a success assertOutput");
        $tester->assert($expected_why_reason_passed === $mock_tester->assert_why,
            "Pass values as reason why on failed comparison");
    }
}
