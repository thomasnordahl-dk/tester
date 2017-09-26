<?php

namespace ThomasNordahlDk\Tester\Assertion\Decorator;


use ThomasNordahlDk\Tester\Assertion\Tester;

/**
 * Decorates a Tester instance with the assertions assertSame and assertEqual
 *
 * @see ComparisonTester::assertSame()
 * @see ComparisonTester::assertEqual()
 */
class ComparisonTester implements Tester
{
    /**
     * @var Tester
     */
    private $tester;

    public function __construct(Tester $tester)
    {
        $this->tester = $tester;
    }

    public function assert(bool $result, string $why): void
    {
        $this->tester->assert($result, $why);
    }

    public function expect(string $exception_type, callable $when, string $why): void
    {
        $this->tester->expect($exception_type, $when, $why);
    }

    public function assertSame($value, $expected, string $why): void
    {
        $this->assert($value === $expected, $why);
    }

    public function assertEqual($value, $expected, string $why): void
    {
        $this->assert($value == $expected, $why);
    }
}
