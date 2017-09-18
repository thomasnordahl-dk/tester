<?php

namespace Phlegmatic\Tester\Adapters\OutputResultsRunner;


class PackageResultRenderer
{
    const PAD_CHARACTER = "*";
    const PAD_SIZE      = 50;
    /**
     * @var bool
     */
    private $verbose;

    public function __construct(bool $verbose = false)
    {
        $this->verbose = $verbose;
    }

    /**
     * @param PackageResult[] $package_result_list
     */
    public function renderPackageResults($package_result_list): void
    {
        foreach ($package_result_list as $package_result) {
            $this->renderPackageResult($package_result);
        }
    }

    private function renderPackageResult(PackageResult $package_result): void
    {
        $case_result_list = $package_result->getCaseResultList();

        $this->renderDescription($package_result);
        $this->renderCaseResultList($case_result_list);
        $this->renderPackageResultSummary($package_result);
    }

    private function renderDescription(PackageResult $package_result): void
    {
        $package_description = $package_result->getPackage()->getDescription();

        echo "{$this->padText($package_description . " ")}\n";
    }

    private function renderPackageResultSummary(PackageResult $package_result): void
    {
        $case_result_list = $package_result->getCaseResultList();
        $case_count = count($case_result_list);

        $failure_count = 0;

        foreach ($case_result_list as $case_result) {
            if (! $case_result->wasSuccessful()) {
                $failure_count += 1;
            }
        }

        $this->renderPaddedLine();
        if ($failure_count) {
            echo "{$failure_count} out of {$case_count} tests failed\n";
        } else {
            echo "{$case_count} test" . ($case_count != 1 ? "s" : "") . " passed successfully!\n";
        }
        $this->renderPaddedLine();
        echo "\n";
    }

    /**
     * @param CaseResult[] $case_results
     */
    private function renderCaseResultList($case_results): void
    {
        foreach ($case_results as $case_result) {
            $this->renderCaseResult($case_result);
        }
    }

    private function renderCaseResult(CaseResult $case_result): void
    {
        $this->renderCaseDescription($case_result);

        if ($this->verbose) {
            $this->renderSuccessReasons($case_result);
        }

        $this->renderFailReasons($case_result);

        $this->renderException($case_result);
    }

    private function padText(string $text): string
    {
        return str_pad($text, self::PAD_SIZE, self::PAD_CHARACTER);
    }

    private function renderCaseDescription(CaseResult $case_result)
    {
        $test_case = $case_result->getTestCase();
        $test_case_description = $test_case->getDescription();

        if ($case_result->wasSuccessful()) {
            echo "✔ {$test_case_description}\n";
        } else {
            echo "✖ {$test_case_description}\n";
        }
    }

    private function renderSuccessReasons(CaseResult $case_result): void
    {
        $success_reason_list = $case_result->getSuccessReasonList();

        foreach ($success_reason_list as $reason) {
            echo "{$reason}\n";
        }
    }

    private function renderFailReasons(CaseResult $case_result): void
    {
        $fail_reason_list = $case_result->getFailReasonList();

        foreach ($fail_reason_list as $reason) {
            echo "- FAILED!: {$reason}\n";
        }
    }

    private function renderException(CaseResult $case_result): void
    {
    }

    private function renderPaddedLine()
    {
        echo $this->padText("") . "\n";
    }
}
