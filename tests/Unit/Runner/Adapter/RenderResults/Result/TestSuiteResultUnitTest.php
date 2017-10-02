<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults\Result;


use ThomasNordahlDk\Tester\Assertion\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Result\TestSuiteResult;
use ThomasNordahlDk\Tester\TestCase;

class TestSuiteResultUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . TestSuiteResult::class;
    }

    public function run(Tester $tester): void
    {
        $package_results = new TestSuiteResult();

        $tester->assert($package_results->getSuccessCount() === 0, "new test returns 0 on success count");
        $tester->assert($package_results->getFailureCount() === 0, "new test returns 0 on failure count");
        $tester->assert($package_results->getAssertionCount() === 0, "new test returns 0 on assertion count");
        $tester->assert($package_results->getTimeInSeconds() === 0.00, "new tests returns 0.0 in time spent");

        $package_results->success(1);
        $tester->assert($package_results->getSuccessCount() === 1, "test case success registered");
        $tester->assert($package_results->getAssertionCount() === 1, "assertion count registered");

        $package_results->success(3);
        $tester->assert($package_results->getSuccessCount() === 2, "second test case success registered");
        $tester->assert($package_results->getAssertionCount() === 4, "second assertion count added");

        $package_results->failure(2);
        $tester->assert($package_results->getFailureCount() === 1, "test case failure registered");
        $tester->assert($package_results->getAssertionCount() === 6, "fail assertion count added");

        $package_results->setTimeInSeconds(1.234);
        $tester->assert($package_results->getTimeInSeconds() === 1.234, "setTimeInSeconds sets time");
    }
}
