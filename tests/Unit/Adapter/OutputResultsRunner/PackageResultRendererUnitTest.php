<?php

namespace Phlegmatic\Tester\Tests\Unit\Adapter\OutputResultsRunner;

use Phlegmatic\Tester\Adapter\OutputResultsRunner\CaseResult;
use Phlegmatic\Tester\Adapter\OutputResultsRunner\PackageResult;
use Phlegmatic\Tester\Adapter\OutputResultsRunner\PackageResultRenderer;
use Phlegmatic\Tester\Helper\OutputAssertionTester;
use Phlegmatic\Tester\TestCase;
use Phlegmatic\Tester\Tester;
use Phlegmatic\Tester\TestPackage;
use Phlegmatic\Tester\Tests\Mock\MockTestCase;
use RuntimeException;

class PackageResultRendererUnitTest implements TestCase
{
    /**
     * @var OutputAssertionTester
     */
    private $tester;

    /**
     * @return string The description of the test case, e.g. "Unit Test of User class"
     */
    public function getDescription(): string
    {
        return "Unit test of " . PackageResultRenderer::class;
    }

    /**
     * Run the test
     *
     * @param Tester $tester The tester to make assertions with.
     */
    public function run(Tester $tester): void
    {
        $this->tester = new OutputAssertionTester($tester);

        $this->testSuccessfulPackageResults();
        $this->testFailedPackageResults();
    }

    private function testSuccessfulPackageResults(): void
    {
        $tester = $this->tester;
        $package_result_renderer = new PackageResultRenderer();

        $mock_case = new MockTestCase();
        $mock_case->description = "This is the test";

        $first_package = new TestPackage("Package 1", [$mock_case]);
        $second_package = new TestPackage("Package 2", [$mock_case]);

        $case_results = new CaseResult($mock_case);
        $case_results->addSuccessReason("1st reason why");

        $first_package_result = new PackageResult($first_package);
        $first_package_result->addTestCaseResult($case_results);

        $second_package_result = new PackageResult($second_package);
        $second_package_result->addTestCaseResult($case_results);

        $package_result_list = [$first_package_result, $second_package_result];

        $expected_output =
            "Package 1 ****************************************
✔ This is the test
**************************************************
1 test passed successfully!
**************************************************

Package 2 ****************************************
✔ This is the test
**************************************************
1 test passed successfully!
**************************************************

";

        $tester->assertOutput($expected_output, function () use ($package_result_renderer, $package_result_list) {
            $package_result_renderer->renderPackageResults($package_result_list);
        }, "Output of successful testcase must match expected format");
    }

    private function testFailedPackageResults()
    {
        $tester = $this->tester;
        $package_result_renderer = new PackageResultRenderer();

        $failing_mock_case = new MockTestCase();
        $failing_mock_case->description = "This is the failing test case";

        $successful_mock_case = new MockTestCase();
        $successful_mock_case->description = "This is the successful test case";

        $exception_mock_case = new MockTestCase();
        $exception_mock_case->description = "This is the throwing test case";

        $first_package = new TestPackage("Package 1", [$failing_mock_case]);
        $second_package = new TestPackage("Package 2", [$successful_mock_case, $failing_mock_case]);
        $third_package = new TestPackage("Package 3", [$successful_mock_case, $exception_mock_case]);

        $successful_case_result = new CaseResult($successful_mock_case);
        $successful_case_result->addSuccessReason("reason for success");

        $failed_case_result = new CaseResult($failing_mock_case);
        $failed_case_result->addFailReason("reason for failure");

        $exception_thrown = new RuntimeException("runtime failure");
        $exception_line = $exception_thrown->getLine();
        $exception_message = $exception_thrown->getMessage();
        $exception_file = $exception_thrown->getFile();
        $exception_trace = $exception_thrown->getTraceAsString();

        $exception_ended_case_result = new CaseResult($exception_mock_case);
        $exception_ended_case_result->addSuccessReason("This is the successful test");
        $exception_ended_case_result->testEndedByException($exception_thrown);

        $first_package_result = new PackageResult($first_package);
        $first_package_result->addTestCaseResult($failed_case_result);

        $second_package_result = new PackageResult($second_package);
        $second_package_result->addTestCaseResult($successful_case_result);
        $second_package_result->addTestCaseResult($failed_case_result);

        $third_package_result = new PackageResult($third_package);
        $third_package_result->addTestCaseResult($successful_case_result);
        $third_package_result->addTestCaseResult($exception_ended_case_result);

        $package_result_list = [$first_package_result, $second_package_result, $third_package_result];

        $expected_output =
            "Package 1 ****************************************
✖ This is the failing test case
- FAILED!: reason for failure
**************************************************
1 out of 1 tests failed
**************************************************

Package 2 ****************************************
✔ This is the successful test case
✖ This is the failing test case
- FAILED!: reason for failure
**************************************************
1 out of 2 tests failed
**************************************************

Package 3 ****************************************
✔ This is the successful test case
✖ This is the throwing test case
RuntimeException thrown on line: {$exception_line} in {$exception_file}
{$exception_message}
{$exception_trace}
**************************************************
1 out of 2 tests failed
**************************************************

";

        $tester->assertOutput($expected_output, function () use ($package_result_renderer, $package_result_list) {
            $package_result_renderer->renderPackageResults($package_result_list);
        }, "Output of successful testcase must match expected format");
    }
}
