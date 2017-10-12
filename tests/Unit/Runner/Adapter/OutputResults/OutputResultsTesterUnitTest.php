<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\OutputResults;


use Exception;
use RuntimeException;
use ThomasNordahlDk\Tester\Decorator\ExpectedOutputTester;
use ThomasNordahlDk\Tester\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\OutputResultsTester;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\FailedAssertionException;
use ThomasNordahlDk\Tester\TestCase;

class OutputResultsTesterUnitTest implements TestCase
{
    /**
     * @var ExpectedOutputTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit test of " . OutputResultsTester::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new ExpectedOutputTester($tester);

        $this->testInterface();
        $this->testAssertMethod();
        $this->testAssertMethodVerbose();
        $this->testExpectMethod();
        $this->testExpectMethodVerbose();
        $this->testExpectRethrowsUnexpected();
    }

    private function testInterface(): void
    {
        $tester = $this->tester;

        $output_results_tester = new OutputResultsTester();

        $tester->assert($output_results_tester instanceof Tester,
            ExpectedOutputTester::class . " is an instance of Tester");


        $tester->assert($output_results_tester->getAssertionCount() === 0,
            "A new tester starts count at 0");
    }

    private function testAssertMethod(): void
    {
        $tester = $this->tester;

        $output_results_tester = new OutputResultsTester();

        $tester->expectOutput("",
            function () use ($output_results_tester) {
                $output_results_tester->assert(true, "reason");
            },
            "No output on non verbose tester on assert");

        $tester->assert($output_results_tester->getAssertionCount() === 1,
            "Non verbose tester counts single assertion on assert");

        $tester->expectOutput("",
            function () use ($output_results_tester) {
                $output_results_tester->assert(true, "reason");
            },
            "Still no output on non verbose tester on assert");

        $tester->assert($output_results_tester->getAssertionCount() === 2,
            "Non verbose tester counts multiple assertions");

        /** @var FailedAssertionException $caught_exception */
        $caught_exception = null;

        $tester->expectOutput("✖ failed\n",
            function () use ($output_results_tester, &$caught_exception) {
                try {
                    $output_results_tester->assert(false, "failed");
                } catch (FailedAssertionException $exception) {
                    $caught_exception = $exception;
                }
            },
            "Non verbose tester outputs failure reason on assert");

        $tester->assert($caught_exception instanceof FailedAssertionException,
            "Non verbose tester throws failed assertion exception on failed assertion");

        $tester->assert($caught_exception->getMessage() === "failed",
            "Non verbose tester passes reason as exception message on assert");

        $tester->assert($output_results_tester->getAssertionCount() === 2,
            "Non verbose tester does NOT count failed assertion");
    }

    private function testAssertMethodVerbose(): void
    {
        $tester = $this->tester;

        $output_results_tester = new OutputResultsTester(true);

        $tester->expectOutput("✔ reason\n",
            function () use ($output_results_tester) {
                $output_results_tester->assert(true, "reason");
            },
            "Verbose tester matches expected success format on assert");

        $tester->assert($output_results_tester->getAssertionCount() === 1,
            "Verbose tester counts single assertion on assert");

        $tester->expectOutput("✔ reason\n",
            function () use ($output_results_tester) {
                $output_results_tester->assert(true, "reason");
            },
            "Still correct success format by verbose tester on assert");

        $tester->assert($output_results_tester->getAssertionCount() === 2,
            "Verbose tester counts multiple assertions");

        /** @var FailedAssertionException $caught_exception */
        $caught_exception = null;

        $tester->expectOutput("✖ failed\n",
            function () use ($output_results_tester, &$caught_exception) {
                try {
                    $output_results_tester->assert(false, "failed");
                } catch (FailedAssertionException $exception) {
                    $caught_exception = $exception;
                }
            },
            "Verbose tester outputs failure reason on assert");

        $tester->assert($caught_exception instanceof FailedAssertionException,
            "Verbose tester throws failed assertion exception on failed assertion");

        $tester->assert($caught_exception->getMessage() === "failed",
            "Verbose tester passes reason as exception message on assert");

        $tester->assert($output_results_tester->getAssertionCount() === 2,
            "Verbose tester does NOT count failed assertion");
    }

    private function testExpectMethod(): void
    {
        $tester = $this->tester;

        $output_results_tester = new OutputResultsTester();

        $tester->expectOutput("",
            function () use ($output_results_tester) {
                $output_results_tester->expect(Exception::class, function () {
                    throw new Exception();
                }, "reason");
            },
            "No output on non verbose tester on expect");

        $tester->assert($output_results_tester->getAssertionCount() === 1,
            "Non verbose tester counts single assertion on expect");

        $tester->expectOutput("",
            function () use ($output_results_tester) {
                $output_results_tester->expect(Exception::class, function () {
                    throw new Exception();
                }, "reason");
            },
            "Still no output on non verbose tester on expect");

        $tester->assert($output_results_tester->getAssertionCount() === 2,
            "Non verbose tester counts multiple expectations");

        /** @var FailedAssertionException $caught_exception */
        $caught_exception = null;

        $tester->expectOutput("✖ failed\n",
            function () use ($output_results_tester, &$caught_exception) {
                try {
                    $output_results_tester->expect(Exception::class, function () {
                        //No exception thrown here
                    }, "failed");
                } catch (FailedAssertionException $exception) {
                    $caught_exception = $exception;
                }
            },
            "Non verbose tester outputs failure reason on expect");

        $tester->assert($caught_exception instanceof FailedAssertionException,
            "Non verbose tester throws failed assertion exception on failed expect");

        $tester->assert($caught_exception->getMessage() === "failed",
            "Non verbose tester passes reason as exception message on expect");

        $tester->assert($output_results_tester->getAssertionCount() === 2,
            "Non verbose tester does NOT count failed expect");
    }

    private function testExpectMethodVerbose(): void
    {
        $tester = $this->tester;

        $output_results_tester = new OutputResultsTester(true);

        $tester->expectOutput("✔ reason\n",
            function () use ($output_results_tester) {
                $output_results_tester->expect(Exception::class, function () {
                    throw new Exception();
                }, "reason");
            },
            "Verbose tester matches success format on expect");

        $tester->assert($output_results_tester->getAssertionCount() === 1,
            "Verbose tester counts single assertion on expect");

        $tester->expectOutput("✔ reason\n",
            function () use ($output_results_tester) {
                $output_results_tester->expect(Exception::class, function () {
                    throw new Exception();
                }, "reason");
            },
            "Still matches success format on expect");

        $tester->assert($output_results_tester->getAssertionCount() === 2,
            "Verbose tester counts multiple expectations");

        /** @var FailedAssertionException $caught_exception */
        $caught_exception = null;

        $tester->expectOutput("✖ failed\n",
            function () use ($output_results_tester, &$caught_exception) {
                try {
                    $output_results_tester->expect(Exception::class, function () {
                        //No exception thrown here
                    }, "failed");
                } catch (FailedAssertionException $exception) {
                    $caught_exception = $exception;
                }
            },
            "Verbose tester outputs failure reason on expect");

        $tester->assert($caught_exception instanceof FailedAssertionException,
            "Verbose tester throws failed assertion exception on failed expect");

        $tester->assert($caught_exception->getMessage() === "failed",
            "Verbose tester passes reason as exception message on expect");

        $tester->assert($output_results_tester->getAssertionCount() === 2,
            "Verbose tester does NOT count failed expect");
    }

    private function testExpectRethrowsUnexpected()
    {
        $tester = $this->tester;

        $output_results_tester = new OutputResultsTester();

        $tester->expect(Exception::class, function () use ($output_results_tester) {
            $output_results_tester->expect(RuntimeException::class, function () {
                throw new Exception();
            }, "reason");
        }, "tester rethrows unexpected exception");
    }
}
