<?php

namespace Phlegmatic\Tester\Tests\Unit\Adapter;

use Phlegmatic\Tester\Adapter\OutputResultsRunner\CaseResult;
use Phlegmatic\Tester\Adapter\OutputResultsRunner\PackageResult;
use Phlegmatic\Tester\Adapter\OutputResultsRunner;
use Phlegmatic\Tester\TestCase;
use Phlegmatic\Tester\Tester;
use Phlegmatic\Tester\TestPackage;
use Phlegmatic\Tester\Tests\Mock\Adapter\OutputResultsRunner\MockPackageResultRenderer;
use Phlegmatic\Tester\Tests\Mock\MockTestCase;
use RuntimeException;

class OutputResultsRunnerUnitTest implements TestCase
{
    /**
     * @var Tester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit Test of " . OutputResultsRunner::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = $tester;

        $this->testSinglePassingTestCase();
        $this->testTwoPassingTestCases();
        $this->testFailingTestCase();
        $this->testThrowingTestCase();
    }

    private function testSinglePassingTestCase(): void
    {
        $tester = $this->tester;

        $mock_renderer = new MockPackageResultRenderer();
        $runner = new OutputResultsRunner($mock_renderer);

        $mock_case = new MockTestCase;
        $mock_case->test = function (Tester $tester) {
            $tester->assert(true, "reason");
        };
        $package = new TestPackage("description", [$mock_case]);
        $expected_result = new PackageResult($package);

        $case_result = new CaseResult($mock_case);
        $case_result->addSuccessReason("reason");

        $expected_result->addTestCaseResult($case_result);

        $runner->run([$package]);

        $package_result_list = $mock_renderer->package_result_list;

        $tester->assert([$expected_result] == $package_result_list,
            "PackageResults match expected results when no assertions fail");
    }

    private function testTwoPassingTestCases(): void
    {
        $tester = $this->tester;

        $mock_renderer = new MockPackageResultRenderer();
        $runner = new OutputResultsRunner($mock_renderer);

        $mock_case = new MockTestCase;
        $mock_case->test = function (Tester $tester) {
            $tester->assert(true, "reason");
        };
        $package = new TestPackage("description", [$mock_case, $mock_case]);
        $expected_result = new PackageResult($package);

        $case_result = new CaseResult($mock_case);
        $case_result->addSuccessReason("reason");

        $expected_result->addTestCaseResult($case_result);
        $expected_result->addTestCaseResult($case_result);

        $runner->run([$package]);

        $package_result_list = $mock_renderer->package_result_list;

        $tester->assert([$expected_result] == $package_result_list,
            "PackageResults match expected results when no assertions fail");
    }

    private function testFailingTestCase(): void
    {
        $tester = $this->tester;

        $mock_renderer = new MockPackageResultRenderer();
        $runner = new OutputResultsRunner($mock_renderer);

        $mock_case = new MockTestCase;
        $mock_case->test = function (Tester $tester) {
            $tester->assert(false, "reason");
        };
        $package = new TestPackage("description", [$mock_case]);
        $expected_result = new PackageResult($package);

        $case_result = new CaseResult($mock_case);
        $case_result->addFailReason("reason");

        $expected_result->addTestCaseResult($case_result);

        $runner->run([$package]);

        $package_result_list = $mock_renderer->package_result_list;

        $tester->assert([$expected_result] == $package_result_list,
            "PackageResults match expected result when an assertion fails");
    }

    private function testThrowingTestCase(): void
    {
        $tester = $this->tester;

        $mock_renderer = new MockPackageResultRenderer();
        $runner = new OutputResultsRunner($mock_renderer);

        $mock_case = new MockTestCase;
        $mock_case->test = function (Tester $tester) {
            $tester->assert(true, "First everything is ok...");
            throw new RuntimeException("Oh no! Then something goes wrong!");
        };

        $package = new TestPackage("description", [$mock_case]);

        $runner->run([$package]);

        $package_result_list = $mock_renderer->package_result_list;
        $tester->assert(count($package_result_list) === 1, "One package run, one PackageResult added to list");
        $tester->assert($package_result_list[0]->getPackage() === $package, "Package passed to result");

        $case_result_list = $package_result_list[0]->getCaseResultList();
        $tester->assert(count($case_result_list) === 1, "One test case added to package, one CaseResult added");
        $tester->assert($case_result_list[0]->getTestCase() === $mock_case, "Case added to case result");
        $tester->assert($case_result_list[0]->getSuccessReasonList() === ["First everything is ok..."],
            "Successful assertion added correctly to testcase");
        $tester->assert(count($case_result_list[0]->getFailReasonList()) === 0, "No failed assertions in case result");
        $tester->assert($case_result_list[0]->wasEndedByException(), "Case was ended by exception");

        $exception = $case_result_list[0]->getExceptionThrown();
        $tester->assert($exception instanceof RuntimeException, "Correct exception type");
        $tester->assert($exception->getMessage() === "Oh no! Then something goes wrong!", "Correct exception message");
    }
}
