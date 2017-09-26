<?php

namespace ThomasNordahlDk\Tester\Assertion\Decorator;

use ThomasNordahlDk\Tester\Assertion\Tester;

/**
 * Decorates a Tester instance with method expectOutput for asserting
 * expected output
 *
 * @see ExpectedOutputTester::expectOutput()
 */
class ExpectedOutputTester implements Tester
{
    /**
     * @var Tester
     */
    private $tester;

    public function __construct(Tester $tester)
    {
        $this->tester = $tester;
    }

    public function expectOutput(string $expected_output, callable $when, string $why): void
    {
        ob_start();
        $when();
        $output = ob_get_clean();

        if ($expected_output !== $output) {
            $why .= "\nExpected:\n{$expected_output}\nActual:\n{$output}";
        }

        $this->tester->assert($expected_output === $output, $why);
    }

    public function assert(bool $result, string $why): void
    {
        $this->tester->assert($result, $why);
    }

    public function expect(string $exception_type, callable $when, string $why): void
    {
        $this->tester->expect($exception_type, $when, $why);
    }
}
