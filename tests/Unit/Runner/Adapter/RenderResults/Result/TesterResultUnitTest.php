<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults\Result;

use Exception;
use ThomasNordahlDk\Tester\Assertion\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\FailedAssertionException;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Result\TesterResult;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\RenderResult\Renderer\MockAssertionResultRenderer;
use RuntimeException;

class TesterResultUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . TesterResult::class;
    }

    public function run(Tester $tester): void
    {
        $mock_renderer = new MockAssertionResultRenderer();
        $mock_renderer->silent();
        $tester_result = new TesterResult($mock_renderer);

        $tester->assert($tester_result->getSuccessCount() === 0, "starts at zero");

        $tester_result->assert(true, "reason");
        $tester_result->assert(true, "reason");
        $tester_result->expect(Exception::class, function () {
            throw new Exception();
        }, "reason");

        $tester->assert($tester_result->getSuccessCount() === 3, "counted successes");

        $tester->expect(FailedAssertionException::class, function () use ($tester_result) {
            $tester_result->assert(false, "reason");
        }, "throws exception when assert result false");

        $tester->expect(FailedAssertionException::class, function () use ($tester_result) {
            $tester_result->expect(Exception::class, function () {
                return "no exception here";
            }, "reason");
        }, "throws exception when expectation fails");

        $tester->expect(Exception::class, function () use ($tester_result) {
            $tester_result->expect(RuntimeException::class, function () {
                throw new Exception();
            }, "reason");
        }, "rethrows unmatched exception");
    }
}
