<?php

namespace Phlegmatic\Tester\Adapter;

use Exception;
use Phlegmatic\Tester\Adapter\OutputResultsRunner\CaseResult;
use Phlegmatic\Tester\Adapter\OutputResultsRunner\OutputResultsTester;
use Phlegmatic\Tester\Adapter\OutputResultsRunner\PackageResult;
use Phlegmatic\Tester\Adapter\OutputResultsRunner\PackageResultRenderer;
use Phlegmatic\Tester\Exception\FailedAssertionException;
use Phlegmatic\Tester\Runner;
use Phlegmatic\Tester\TestCase;
use Phlegmatic\Tester\TestPackage;

class OutputResultsRunner implements Runner
{
    /**
     * @var PackageResult[]
     */
    private $package_results = [];

    /**
     * @var PackageResultRenderer
     */
    private $renderer;

    public function __construct(PackageResultRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function run($test_packages): void
    {
        foreach ($test_packages as $package) {
            $this->runTestPackage($package);
        }

        $this->renderer->renderPackageResults($this->package_results);
    }

    /**
     * @param TestPackage $test_package
     */
    private function runTestPackage(TestPackage $test_package)
    {
        $package_result = new PackageResult($test_package);
        $case_list = $test_package->getTestCaseList();

        foreach ($case_list as $test_case) {
            $result = $this->runTestCase($test_case);
            $package_result->addTestCaseResult($result);
        }

        $this->package_results[] = $package_result;
    }

    private function runTestCase(TestCase $test_case): CaseResult
    {
        $result = new CaseResult($test_case);
        $tester = new OutputResultsTester($result);

        try {
            $test_case->run($tester);
        } catch (FailedAssertionException $e) {
            // This tester adds result to result object
        } catch (Exception $e) {
            $result->testEndedByException($e);
        }

        return $result;
    }
}
