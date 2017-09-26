<?php

namespace ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\RenderResult\Renderer;

use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer\TestCaseRenderer;
use ThomasNordahlDk\Tester\TestCase;

class MockTestCaseRenderer extends TestCaseRenderer
{
    public function renderHeader(TestCase $test_case): void
    {
        echo "case-header:" . $test_case->getDescription() . ";";
    }

    public function renderSummary(int $successfull_assertions, bool $succeeded): void
    {
        echo "successfull_assertions:" . $successfull_assertions . ";";
        echo "succeeded:" . ($succeeded ? "true;" : "false;");
    }
}
