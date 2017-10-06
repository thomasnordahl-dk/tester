<?php

namespace ThomasNordahlDk\Tester\Decorator;

use ThomasNordahlDk\Tester\Tester;

/**
 * Decorates a Tester instance with method expectOutput for asserting
 * expected output to the output buffer
 *
 * @see ExpectedOutputTester::expectOutput()
 */
class ExpectedOutputTester implements Tester
{
    /**
     * @var Tester
     */
    private $tester;

    /**
     * @param Tester $tester The tester to decorate with extra assertion methods
     */
    public function __construct(Tester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * @param string   $expected_output A string containing the expected output
     * @param callable $when            A closure that executes the methods expected to output
     * @param string   $why             The reason for making this assertion
     */
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

    /**
     * @inheritdoc
     */
    public function assert(bool $result, string $why): void
    {
        $this->tester->assert($result, $why);
    }

    /**
     * @inheritdoc
     */
    public function expect(string $exception_type, callable $when, string $why): void
    {
        $this->tester->expect($exception_type, $when, $why);
    }
}
