<?php

namespace Phlegmatic\Tester\Helper;


use Phlegmatic\Tester\Tester;

/**
 * Decorates a Tester class with assertions for comparing expected output
 * and actual output.
 */
class OutputAssertionTester implements Tester
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
