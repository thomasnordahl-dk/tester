<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\Simple;


use Exception;
use RuntimeException;
use ThomasNordahlDk\Tester\Runner\Adapter\Simple\FailedAssertionException;
use ThomasNordahlDk\Tester\Runner\Adapter\Simple\SimpleTester;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Tester;

class SimpleTesterUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . SimpleTester::class;
    }

    public function run(Tester $tester): void
    {
        $simple_tester = new SimpleTester();

        $tester->assert($simple_tester instanceof Tester, "SimpleTester is instanceof Tester");

        $tester->assert($simple_tester->countSuccessfulAssertions() === 0, "SimpleTester starts count at 0");

        $simple_tester->assert(true, "reason");
        $simple_tester->assert(true, "reason");
        $simple_tester->expect(RuntimeException::class, function () {
            throw new RuntimeException("reason for failure");
        }, "reason");

        $tester->assert($simple_tester->countSuccessfulAssertions() === 3, "SimpleTester counts successful assertions");

        $tester->expect(FailedAssertionException::class, function () use ($simple_tester) {
            $simple_tester->assert(false, "reason");
        }, "SimpleTester throws FailedAssertionException on failed assertion");

        $tester->expect(FailedAssertionException::class, function () use ($simple_tester) {
            $simple_tester->expect(RuntimeException::class, function () {
                //expected exception is not thrown
            }, "reason");
        }, "SimpleTester throws FailedAssertionException on failed expectation");

        $tester->expect(Exception::class, function () use ($simple_tester) {
            $simple_tester->expect(RuntimeException::class, function () {
                throw new Exception("unexpected exception");
            }, "reason");
        }, "SimpleTester rethrows unexpected exception");
    }
}
