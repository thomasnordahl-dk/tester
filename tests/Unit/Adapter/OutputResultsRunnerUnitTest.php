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

//TODO add test for test causing an unexpected exception
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
    }

    private function testSinglePassingTestCase()
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

        $tester->assert([$expected_result] == $package_result_list, "Output must match the expected format!");
    }

    private function testTwoPassingTestCases()
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

        $tester->assert([$expected_result] == $package_result_list, "Output must match the expected format!");
    }

    private function testFailingTestCase()
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

        $tester->assert([$expected_result] == $package_result_list, "Output must match the expected format!");
    }
}
