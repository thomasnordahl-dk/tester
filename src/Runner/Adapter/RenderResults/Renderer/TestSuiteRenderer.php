<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer;

use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Result\TestSuiteResult;
use ThomasNordahlDk\Tester\TestSuite;

class TestSuiteRenderer
{
    private const PAD_LENGTH    = 100;
    private const PAD_CHARACTER = "-";

    public function renderHeader(TestSuite $test_suite): void
    {
        $test_case_count = count($test_suite->getTestCaseList());
        $description = $test_suite->getDescription();

        echo "\n" . str_pad("{$description} ({$test_case_count}) ", self::PAD_LENGTH, self::PAD_CHARACTER) . "\n";
    }

    public function renderSummary(TestSuiteResult $test_suite_result): void
    {
        if (! $test_suite_result->getFailureCount()) {
            $this->renderSuccessSummary($test_suite_result);
        } else {
            $this->renderFailureSummary($test_suite_result);
        }
    }

    private function renderSuccessSummary(TestSuiteResult $test_suite_result): void
    {
        $success_count = $test_suite_result->getSuccessCount();
        $assertion_count = $test_suite_result->getAssertionCount();
        $success_plural = $success_count != 1 ? "s" : "";
        $assert_plural = $assertion_count != 1 ? "s" : "";
        $time_in_seconds = number_format($test_suite_result->getTimeInSeconds(), 2, ".", ",");

        echo "\nSuccess! {$success_count} test{$success_plural}, $assertion_count assertion{$assert_plural} ({$time_in_seconds}s)\n";

        $this->renderBreaker();
    }

    private function renderFailureSummary(TestSuiteResult $test_suite_result): void
    {
        $success_count = $test_suite_result->getSuccessCount();
        $failure_count = $test_suite_result->getFailureCount();
        $assertion_count = $test_suite_result->getAssertionCount();
        $success_plural = $success_count != 1 ? "s" : "";
        $fail_plural = $failure_count != 1 ? "s" : "";
        $assert_plural = $assertion_count != 1 ? "s" : "";
        $time_in_seconds = number_format($test_suite_result->getTimeInSeconds(), 2, ".", ",");

        echo "\nFAILED! {$failure_count} test{$fail_plural} failed, {$success_count} test{$success_plural} succeeded, {$assertion_count} assertion{$assert_plural} ({$time_in_seconds}s)\n";

        $this->renderBreaker();
    }

    private function renderBreaker()
    {
        echo str_pad("", self::PAD_LENGTH, self::PAD_CHARACTER) . "\n\n";
    }
}
