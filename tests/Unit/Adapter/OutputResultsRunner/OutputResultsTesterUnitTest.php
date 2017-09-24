<?php

namespace Phlegmatic\Tester\Tests\Unit\Adapter\OutputResultsRunner;

use Exception;
use Phlegmatic\Tester\Exception\FailedAssertionException;
use Phlegmatic\Tester\Adapter\OutputResultsRunner\OutputResultsTester;
use Phlegmatic\Tester\Helper\ExpectedOutputTester;
use Phlegmatic\Tester\TestCase;
use Phlegmatic\Tester\Tester;

use RuntimeException;



class OutputResultsTesterUnitTest implements TestCase
{
    const SUCCESS_REASON = "the reason for success";
    const OUTPUT_ON_SUCCESS = "✔ the reason for success\n";

    const FAILURE_REASON = "the reason for failure";
    const OUTPUT_ON_FAILURE = "✖ the reason for failure\n";
    /**
     * @var ExpectedOutputTester $tester ;
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit Test of " . OutputResultsTester::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new ExpectedOutputTester($tester);

        $this->testAssertMethod();
        $this->testExpectMethod();
    }

    private function testAssertMethod()
    {
        $tester = $this->tester;
        $exception_thrown = false;
        $exception_message = "";

        $tester->expectOutput("", function () {
            $tester = new OutputResultsTester(false);
            $tester->assert(true, self::SUCCESS_REASON);
        }, "Tester::assert should not output reason for success when not verbose");

        $tester->expectOutput(self::OUTPUT_ON_SUCCESS, function () {
            $tester = new OutputResultsTester(true);
            $tester->assert(true, self::SUCCESS_REASON);
        }, "Tester::assert should output reason for success when verbose");

        $tester->expectOutput(self::OUTPUT_ON_FAILURE, function () use (&$exception_thrown, &$exception_message) {
            try {
                $tester = new OutputResultsTester();
                $tester->assert(false, self::FAILURE_REASON);
            } catch (FailedAssertionException $exception) {
                $exception_thrown = true;
                $exception_message = $exception->getMessage();
            }
        }, "Tester::assert should output reason for failed exception");

        $tester->assert($exception_thrown, "assert() should throw " . FailedAssertionException::class . " on false");

        $tester->assert($exception_message === "the reason for failure",
            "assert() should set test reason as exception message");
    }

    private function testExpectMethod()
    {
        $tester = $this->tester;
        $exception_thrown = false;
        $exception_message = "";

        $tester->expectOutput("", function () {
            $tester = new OutputResultsTester(false);
            $tester->expect(Exception::class, function () {
                throw new Exception("correct type");
            }, self::SUCCESS_REASON);
        }, "Tester should not output success reason when not verbose");

        $tester->expectOutput(self::OUTPUT_ON_SUCCESS, function () {
            $tester = new OutputResultsTester(true);
            $tester->expect(Exception::class, function () {
                throw new Exception("correct type");
            }, self::SUCCESS_REASON);
        }, "Tester should output success reason when verbose");

        $tester->expectOutput(self::OUTPUT_ON_FAILURE, function () use (&$exception_thrown, &$exception_message) {
            try {
                $tester = new OutputResultsTester();
                $tester->expect(RuntimeException::class, function () {
                    return;
                }, self::FAILURE_REASON);
            } catch (FailedAssertionException $exception) {
                $exception_thrown = true;
                $exception_message = $exception->getMessage();
            }
        }, "failed expect() should output failure reason");

        $tester->assert($exception_thrown, "expect() throws on failure to catch correct exception");

        $tester->expect(Exception::class, function () {
            throw new RuntimeException("wrong type");
        }, "expect rethrows unexpected exceptions");
    }
}
