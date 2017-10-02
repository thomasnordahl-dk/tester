<?php

namespace ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\RenderResult\Renderer;

use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer\TestSuiteRenderer;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Result\TestSuiteResult;
use ThomasNordahlDk\Tester\TestSuite;

class MockTestSuiteRenderer extends TestSuiteRenderer
{
    public function renderHeader(TestSuite $test_suite): void
    {
        echo "suite-header:" . $test_suite->getDescription() . ";";
    }

    public function renderSummary(TestSuiteResult $test_suite_result): void
    {
        echo "success:" . $test_suite_result->getSuccessCount() . ";";
        echo "failure:" . $test_suite_result->getFailureCount() . ";";
        echo "assertion:" . $test_suite_result->getAssertionCount() . ";";
        echo "time:" . ($test_suite_result->getTimeInSeconds() > 0.0 ? "yes" : "no");
    }
}
