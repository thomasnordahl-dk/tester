<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\OutputResults;


use Exception;
use ThomasNordahlDk\Tester\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Decorator\ExpectedOutputTester;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestResultsRenderer;
use ThomasNordahlDk\Tester\Runner\FailedTestException;
use ThomasNordahlDk\Tester\Runner\Timer\TimerFactory;
use ThomasNordahlDk\Tester\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\OutputResultsRunner;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Tests\Mock\MockTestCase;
use ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\OutputResults\MockTestResultsRenderer;
use ThomasNordahlDk\Tester\Tests\Mock\Runner\Timer\MockTimerFactory;
use ThomasNordahlDk\Tester\TestSuite;

class OutputResultsRunnerUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . OutputResultsRunner::class;
    }

    public function run(Tester $tester): void
    {
        $tester = new ExpectedOutputTester($tester);

        $mock_renderer = new MockTestResultsRenderer();
        $mock_timer_factory = new MockTimerFactory();

        $runner = new OutputResultsRunner($mock_renderer, $mock_timer_factory);

        $suite = new TestSuite("description", [
            new MockTestCase("case 1", function (Tester $tester) {
                $tester->assert(true, "reason 1");
            }),
            new MockTestCase("case 2", function (Tester $tester) {
                $tester->expect(Exception::class, function () {
                    throw new Exception("error!");
                }, "reason 2");
            }),
        ]);

        $tester->expectOutput(
            "description\ncase 1\ncase 2\n2, 0, 2, 1.23\n",
            function () use ($runner, $suite) {
                $runner->run([$suite]);
            },
            "Uses renderer correctly"
        );

        $suite = new TestSuite("description", [
            new MockTestCase("case 1", function (Tester $tester) {
                $tester->assert(true, "reason 1");
            }),
            new MockTestCase("case 2", function (Tester $tester) {
                $tester->assert(false, "reason 2");
            }),
        ]);


        /** @var FailedTestException $exception_caught */
        $exception_caught = null;

        $tester->expectOutput(
            "description\ncase 1\ncase 2\n✖ reason 2\n1, 1, 1, 1.23\n",
            function () use ($runner, $suite, &$exception_caught) {
                try {
                    $runner->run([$suite]);
                } catch (FailedTestException $e) {
                    $exception_caught = $e;
                }
            },
            "Uses renderer correctly on failure"
        );

        $tester = new ComparisonTester($tester);

        $tester->assert($exception_caught instanceof FailedTestException,
            "Runner throws FailedTestException on failed test");

        $expected = new OutputResultsRunner(new TestResultsRenderer(), new TimerFactory());
        $tester->assertEqual(OutputResultsRunner::create(), $expected, "Factory method creates new Runner");

        $expected = new OutputResultsRunner(new TestResultsRenderer(true), new TimerFactory());
        $tester->assertEqual(OutputResultsRunner::create(true), $expected, "Factory method creates new verbose Runner");
    }
}
