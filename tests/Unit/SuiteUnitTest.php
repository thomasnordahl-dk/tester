<?php

namespace ThomasNordahlDk\Tester\Tests\Unit;

use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Tester;
use ThomasNordahlDk\Tester\TestSuite;
use ThomasNordahlDk\Tester\Tests\Mock\MockTestCase;

class SuiteUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . TestSuite::class;
    }

    public function run(Tester $tester): void
    {
        $empty_function = function () {
            //do nothing
        };

        $test_case_1 = new MockTestCase("test case 1", $empty_function);
        $test_case_2 = new MockTestCase("test case 2", $empty_function);

        $package = new TestSuite("Description", $test_case_1, $test_case_2);

        $tester->assert($package->getDescription() === "Description", "Assigned description correctly returned");
        $tester->assert($package->getTestCaseList() === [$test_case_1, $test_case_2],
            "Assigned test cases returned in correct order");

    }
}
