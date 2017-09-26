<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer;

use ThomasNordahlDk\Tester\TestCase;

class TestCaseRenderer
{
    /**
     * @var bool
     */
    private $verbose;

    public function __construct(bool $verbose = false)
    {
        $this->verbose = $verbose;
    }

    public function renderHeader(TestCase $test_case): void
    {
        echo $test_case->getDescription() . "\n";
    }

    public function renderSummary(int $success_full_assertions, bool $succeeded): void
    {
        $plural = $success_full_assertions != 1 ? "s" : "";

        if ($succeeded && $this->verbose) {
            echo "Test completed after {$success_full_assertions} successfull assertion{$plural}\n";
        }

        if (! $succeeded) {
            echo "FAILED! Test failed after {$success_full_assertions} successfull assertion{$plural}\n";
        }
    }
}
