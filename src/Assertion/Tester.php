<?php

namespace ThomasNordahlDk\Tester\Assertion;

/**
 * Tester classes handles assertions when running tests
 */
interface Tester
{
    public function assert(bool $result, string $why): void;

    public function expect(string $exception_type, callable $when, string $why): void;
}
