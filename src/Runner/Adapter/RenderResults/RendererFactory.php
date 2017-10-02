<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\RenderResults;

use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer\AssertionResultRenderer;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer\TestSuiteRenderer;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer\TestCaseRenderer;

class RendererFactory
{
    /**
     * @var bool
     */
    private $verbose;

    public function __construct(bool $verbose = false)
    {
        $this->verbose = $verbose;
    }

    public function createTestSuiteRenderer(): TestSuiteRenderer
    {
        return new TestSuiteRenderer();
    }

    public function createTestCaseRenderer(): TestCaseRenderer
    {
        return new TestCaseRenderer($this->verbose);
    }

    public function createAssertionResultRenderer(): AssertionResultRenderer
    {
        return new AssertionResultRenderer($this->verbose);
    }
}
