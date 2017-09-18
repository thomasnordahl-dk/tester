<?php

namespace Phlegmatic\Tester\Adapters\OutputResults;

use Phlegmatic\Tester\Exception\FailedAssertionException;
use Phlegmatic\Tester\Exception\FailedTestsException;
use Phlegmatic\Tester\Runner;
use Phlegmatic\Tester\TestCase;
use Phlegmatic\Tester\TestPackage;

//TODO refactor this class and its related adapters - it's too messy!
class OutputResultsRunner implements Runner
{
    private $failed = 0;
    private $successes = 0;
    private $result = 0;

    const PAD_SIZE = 50;

    public function run($test_packages): void
    {
        foreach ($test_packages as $package) {
            $this->runPackage($package);
        }

        if (! $this->result == 0) {
            throw new FailedTestsException("Failed tests!");
        }
    }

    private function runPackage(TestPackage $test_package)
    {
        $this->failed = 0;
        $this->successes = 0;

        echo str_pad($test_package->getDescription() . " ", self::PAD_SIZE, "*") . "\n";

        $case_list = $test_package->listTestCases();

        foreach ($case_list as $test_case) {
            $this->runTestCase($test_case);
        }

        if ($this->failed > 0) {
            $this->result = 1;
        }

        $this->outputPackageResults();

        echo str_pad("", self::PAD_SIZE, "*") . "\n\n";
    }

    private function outputPackageResults(): void
    {
        $total = $this->failed + $this->successes;
        echo str_pad("", self::PAD_SIZE, "*") . "\n";
        if ($this->failed != 0) {
            echo "FAILED TEST" . ($this->failed > 1 ? "S" : "") . "!!!\n";
            echo "{$this->failed} out of {$total} tests failed\n";
        } else {
            echo "{$total} tests passed successfully!\n";
        }
    }

    private function runTestCase(TestCase $test_case): void
    {

        $logger = new ResultLogger();
        $tester = new LogResultsTester($logger);

        $description = $test_case->getDescription();

        try {
            $test_case->run($tester);
            $this->successes += 1;
        } catch (FailedAssertionException $e) {
            $this->failed += 1;
        }

        $this->outputLogResults($description, $logger);
    }

    private function outputLogResults(string $description, ResultLogger $logger): void
    {
        $results = $logger->getResults();

        if ($logger->getFailureCount()) {
            echo "{$description} - FAILED!\n";
        } else {
            echo "{$description} - Success!\n";
        }

        foreach ($results as $result) {
            echo "{$result}\n";
        }
    }
}
