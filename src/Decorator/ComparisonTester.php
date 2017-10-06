<?php

namespace ThomasNordahlDk\Tester\Decorator;

use ThomasNordahlDk\Tester\Tester;

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

    /**
     * @param Tester $tester The tester to decorate with extra assertion methods
     */
    public function __construct(Tester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * This assertion is equivalent to the assertion:
     * assert($value === $expected, $why);
     *
     * @param mixed  $value    The value to test
     * @param mixed  $expected The expected value
     * @param string $why      The reason for making the assertion
     */
    public function assertSame($value, $expected, string $why): void
    {
        $this->assert($value === $expected, $why);
    }

    /**
     * This assertion is equivalent to the assertion:
     * assert($value == $expected, $why);
     *
     * @param mixed  $value    The value to test
     * @param mixed  $expected The expected value
     * @param string $why      The reason for making the assertion
     */
    public function assertEqual($value, $expected, string $why): void
    {
        $this->assert($value == $expected, $why);
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
