<?php

namespace ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\OutputResults;

use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestResultsRenderer;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\TestSuite;

class MockTestResultsRenderer extends TestResultsRenderer
{
    public function renderTestCaseHeader(TestCase $case): string
    {
        return $case->getDescription() . "\n";
    }

    public function renderTestCaseSummary(bool $completed, int $assertion_count): string
    {
        return ($completed ? "true" : "false") . ",{$assertion_count}\n";
    }

    public function renderTestSuiteHeader(TestSuite $suite): string
    {
        return $suite->getDescription() . "\n";
    }

    public function renderTestSuiteSummary(int $successes, int $failures, int $assertions, float $time): string
    {
        return "{$successes}, {$failures}, {$assertions}, {$time}\n";
    }
}
