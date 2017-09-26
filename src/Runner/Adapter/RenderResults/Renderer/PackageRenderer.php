<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer;

use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Result\PackageResult;
use ThomasNordahlDk\Tester\TestPackage;

class PackageRenderer
{
    private const PAD_LENGTH    = 100;
    private const PAD_CHARACTER = "-";

    public function renderHeader(TestPackage $package): void
    {
        $test_case_count = count($package->getTestCaseList());
        $description = $package->getDescription();

        echo "\n" . str_pad("{$description} ({$test_case_count}) ", self::PAD_LENGTH, self::PAD_CHARACTER) . "\n";
    }

    public function renderSummary(PackageResult $package_result): void
    {
        if (! $package_result->getFailureCount()) {
            $this->renderSuccessSummary($package_result);
        } else {
            $this->renderFailureSummary($package_result);
        }
    }

    private function renderSuccessSummary(PackageResult $package_result): void
    {
        $success_count = $package_result->getSuccessCount();
        $assertion_count = $package_result->getAssertionCount();
        $success_plural = $success_count != 1 ? "s" : "";
        $assert_plural = $assertion_count != 1 ? "s" : "";
        $time_in_seconds = number_format($package_result->getTimeInSeconds(), 2, ".", ",");

        echo "\nSuccess! {$success_count} test{$success_plural}, $assertion_count assertion{$assert_plural} ({$time_in_seconds}s)\n";

        $this->renderBreaker();
    }

    private function renderFailureSummary(PackageResult $package_result): void
    {
        $success_count = $package_result->getSuccessCount();
        $failure_count = $package_result->getFailureCount();
        $assertion_count = $package_result->getAssertionCount();
        $success_plural = $success_count != 1 ? "s" : "";
        $fail_plural = $failure_count != 1 ? "s" : "";
        $assert_plural = $assertion_count != 1 ? "s" : "";
        $time_in_seconds = number_format($package_result->getTimeInSeconds(), 2, ".", ",");

        echo "\nFAILED! {$failure_count} test{$fail_plural} failed, {$success_count} test{$success_plural} succeeded, {$assertion_count} assertion{$assert_plural} ({$time_in_seconds}s)\n";

        $this->renderBreaker();
    }

    private function renderBreaker()
    {
        echo str_pad("", self::PAD_LENGTH, self::PAD_CHARACTER) . "\n\n";
    }
}
