<?php

namespace Phlegmatic\Tester\Tests\Unit\Adapters\OutputResultsRunner;

use Exception;
use Phlegmatic\Tester\Adapters\OutputResultsRunner\CaseResult;
use Phlegmatic\Tester\Exception\FailedAssertionException;
use Phlegmatic\Tester\Adapters\OutputResultsRunner\OutputResultsTester;
use Phlegmatic\Tester\TestCase;
use Phlegmatic\Tester\Tester;
use Phlegmatic\Tester\Tests\Mock\MockTestCase;
use RuntimeException;

class LogResultsTesterUnitTest implements TestCase
{
    /**
     * @var Tester $tester ;
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit Test of " . OutputResultsTester::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = $tester;

        $this->testAssertMethod();
        $this->testExpectMethod();
    }

    private function testAssertMethod()
    {
        $tester = $this->tester;
        $case_results = new CaseResult(new MockTestCase());
        $tester_under_test = new OutputResultsTester($case_results);

        $tester_under_test->assert(true, "success 1");
        $tester_under_test->assert(true, "success 2");

        $expected = ["success 1", "success 2"];
        $tester->assert($expected === $case_results->getSuccessReasonList(),
            "OutputResultsTester added successes to list");

        $tester->expect(FailedAssertionException::class, function () use ($tester_under_test) {
            $tester_under_test->assert(false, "failure");
        }, "failed assertion causes exception");

        $tester->assert(["failure"] === $case_results->getFailReasonList(),
            "OutputResultsTester added failure to result");
    }

    private function testExpectMethod()
    {
        $tester = $this->tester;
        $case_results = new CaseResult(new MockTestCase());
        $tester_under_test = new OutputResultsTester($case_results);

        $tester_under_test->expect(RuntimeException::class, function () {
            throw new RuntimeException("exception");
        }, "success");

        $tester->assert(["success"] === $case_results->getSuccessReasonList(), "expect() should add success to result");

        $tester->expect(FailedAssertionException::class, function () use ($tester_under_test) {
            $tester_under_test->expect(Exception::class, function () {
                return true;
            }, "failure");
        }, "failed expect() should cause FailedTestException");

        $tester->assert(["failure"] === $case_results->getFailReasonList(), "expect() should add failure to result");

        $tester->expect(Exception::class, function () use ($tester_under_test) {
            $tester_under_test->expect(RuntimeException::class, function () {
                throw new Exception("this should be rethrown!");
            }, "exception rethrown");
        }, "expect() should rethrow unexpected exceptions");
    }
}
