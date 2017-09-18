<?php

namespace Phlegmatic\Tester\Tests\Unit;

use Exception;
use Phlegmatic\Tester\Exception\FailedAssertionException;
use Phlegmatic\Tester\Adapters\OutputResults\LogResultsTester;
use Phlegmatic\Tester\Adapters\OutputResults\ResultLogger;
use Phlegmatic\Tester\TestCase;
use Phlegmatic\Tester\Tester;
use RuntimeException;

class LogResultsTesterUnitTest implements TestCase
{
    /**
     * @var Tester $tester ;
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit Test of " . LogResultsTester::class;
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
        $logger = new ResultLogger();
        $tester_under_test = new LogResultsTester($logger);

        $tester_under_test->assert(true, "success");
        $tester->assert($logger->getSuccessCount() === 1, "LogResultsTester logged assertOutput success to logger");

        $tester->expect(FailedAssertionException::class, function () use ($tester_under_test) {
            $tester_under_test->assert(false, "failure");
        }, "failed assertion causes exception");
        $tester->assert($logger->getFailureCount() === 1, "LogResultsTester logged assertOutput failure to logger");
    }

    private function testExpectMethod()
    {
        $tester = $this->tester;
        $logger = new ResultLogger();
        $tester_under_test = new LogResultsTester($logger);

        $tester_under_test->expect(Exception::class, function () {
            return true;
        }, "failure");
        $tester->assert($logger->getFailureCount() === 1, "expect() should fail if no exception is thrown");

        $tester->expect(Exception::class, function () use ($tester_under_test) {
            $tester_under_test->expect(RuntimeException::class, function () {
                throw new Exception("this should be rethrown!");
            }, "exception rethrown");
        }, "expect() should rethrow unexpected exceptions");
    }
}
