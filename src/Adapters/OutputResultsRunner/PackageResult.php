<?php

namespace Phlegmatic\Tester\Adapters\OutputResultsRunner;

use Phlegmatic\Tester\TestPackage;

class PackageResult
{
    /**
     * @var CaseResult[]
     */
    private $test_case_results = [];

    /**
     * @var TestPackage
     */
    private $package;

    public function __construct(TestPackage $package)
    {
        $this->package = $package;
    }

    public function getPackage(): TestPackage
    {
        return $this->package;
    }

    public function addTestCaseResult(CaseResult $case_result): void
    {
        $this->test_case_results[] = $case_result;
    }

    /**
     * @return CaseResult[]
     */
    public function getCaseResultList()
    {
        return $this->test_case_results;
    }
}
