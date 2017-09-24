<?php

namespace Phlegmatic\Tester\Runner\Adapter\OutputResults;

use Exception;
use Phlegmatic\Tester\Runner\FailedTestsException;
use Phlegmatic\Tester\Runner\Runner;
use Phlegmatic\Tester\TestCase;
use Phlegmatic\Tester\TestPackage;

/**
 * Outputs formatted results of the test cases to the standard output buffer
 */
class OutputResultsRunner implements Runner
{
    private const PAD_COUNT                            = 100;
    private const PAD_CHARACTER                        = "-";
    private const WAIT_BEFORE_RETHROW_IN_MICRO_SECONDS = 50000;

    /**
     * @var int
     */
    private $total_test_count = 0;

    /**
     * @var int
     */
    private $failed_test_count = 0;

    /**
     * @var bool
     */
    private $is_verbose;

    /**
     * @var bool
     */
    private $failed_test = false;

    /**
     * @var OutputResultsTesterFactory
     */
    private $tester_factory;

    public function __construct(OutputResultsTesterFactory $tester_factory, bool $verbose = false)
    {
        $this->is_verbose = $verbose;
        $this->tester_factory = $tester_factory;
    }

    public function run($test_packages): void
    {
        foreach ($test_packages as $package) {
            $this->runTestPackage($package);
        }

        if ($this->failed_test) {
            throw new FailedTestsException("A test case failed!");
        }
    }

    /**
     * @param TestPackage $test_package
     */
    private function runTestPackage(TestPackage $test_package)
    {
        $this->total_test_count = 0;
        $this->failed_test_count = 0;

        $this->outputTestPackageHeader($test_package);

        $case_list = $test_package->getTestCaseList();

        foreach ($case_list as $test_case) {
            $this->runTestCase($test_case);
        }

        $this->outputTestPackageSummary();
    }


    private function runTestCase(TestCase $test_case): void
    {
        $factory = $this->tester_factory;
        $is_verbose = $this->is_verbose;
        $tester = $factory->create($is_verbose);

        $this->total_test_count += 1;

        $this->outputTestCaseHeader($test_case);

        try {
            $test_case->run($tester);
            $this->outputTestCaseSummary($tester);
        } catch (FailedAssertionException $exception) {
            $this->outputTestCaseSummary($tester, true);
            $this->failed_test_count += 1;
            $this->failed_test = true;
        } catch (Exception $exception) {
            usleep(self::WAIT_BEFORE_RETHROW_IN_MICRO_SECONDS);
            throw $exception;
        }
    }

    private function outputTestPackageHeader(TestPackage $test_package): void
    {
        $test_count = count($test_package->getTestCaseList());
        $description = $test_package->getDescription();

        $this->echoPaddedLine("{$description} ({$test_count}) ");
    }

    private function outputTestPackageSummary()
    {
        $failed_test_count = $this->failed_test_count;
        $total_test_count = $this->total_test_count;

        if ($failed_test_count) {
            echo "FAILED! {$failed_test_count} out of {$total_test_count} failed!\n";
        } else {
            echo "All {$total_test_count} tests succeeded!\n";
        }

        $this->echoPaddedLine("");
        echo "\n";
    }

    private function outputTestCaseSummary(OutputResultsTester $tester, bool $failed = false): void
    {
        $successfull_assertion_count = $tester->getSuccessCount();

        if ($failed) {
            echo "Test failed after {$successfull_assertion_count} successfull assertions\n";
        } else if ($this->is_verbose) {
            echo "Test ended with {$successfull_assertion_count} successfull assertions\n";
        }
    }

    private function echoPaddedLine(string $value): void
    {
        echo str_pad($value, self::PAD_COUNT, self::PAD_CHARACTER) . "\n";
    }

    private function outputTestCaseHeader(TestCase $test_case): void
    {
        $description = $test_case->getDescription();

        echo "{$description}\n";
    }
}
