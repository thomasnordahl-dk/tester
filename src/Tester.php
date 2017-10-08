<?php

namespace ThomasNordahlDk\Tester;

/**
 * Tester classes handles assertions when running tests
 */
interface Tester
{
    /**
     * Assert that a test result is true
     *
     * @param bool   $result Assert that this is true
     * @param string $why    The reason for making the assertion
     */
    public function assert(bool $result, string $why): void;

    /**
     * Assert that the expected Exception is thrown when the callable is
     * called.
     *
     * @param string   $exception_type The Exception type expected
     * @param callable $when           A callable containing the code expected to fail
     * @param string   $why            The reason why this exception is expected.
     */
    public function expect(string $exception_type, callable $when, string $why): void;
}
