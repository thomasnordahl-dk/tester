<?php

namespace Phlegmatic\Tester\Tests\Unit;

use Phlegmatic\Tester\TestCase;
use Phlegmatic\Tester\Assertion\Tester;
use Phlegmatic\Tester\TestPackage;
use Phlegmatic\Tester\Tests\Mock\MockTestCase;

class TestPackageUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . TestPackage::class;
    }

    public function run(Tester $tester): void
    {
        $empty_function = function () {
            //do nothing
        };

        $test_case_1 = new MockTestCase("test case 1", $empty_function);
        $test_case_2 = new MockTestCase("test case 2", $empty_function);

        $package = new TestPackage("Description", [$test_case_1, $test_case_2]);

        $tester->assert($package->getDescription() === "Description", "Assigned description correctly returned");
        $tester->assert($package->getTestCaseList() === [$test_case_1, $test_case_2],
            "Assigned test cases returned in correct order");

    }
}
