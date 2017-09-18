<?php

namespace Phlegmatic\Tester;

use Phlegmatic\Tester\Exception\FailedAssertionException;

/**
 * Tester classes handles assertions when running tests
 */
interface Tester
{
    /**
     * @param bool   $result the result of the test
     * @param string $why    the reason why the test was relevant
     *
     * @throws FailedAssertionException
     */
    public function assert(bool $result, string $why): void;

    /**
     * @param string   $exception_type The fully qualified class name of the exception
     * @param callable $when           A function that is expected to throw an exception
     * @param string   $why            The reason why the test was relevant
     *
     * @throws FailedAssertionException
     */
    public function expect(string $exception_type, callable $when, string $why): void;
}
