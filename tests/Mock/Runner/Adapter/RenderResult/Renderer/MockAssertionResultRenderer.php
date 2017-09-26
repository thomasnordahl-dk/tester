<?php

namespace ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\RenderResult\Renderer;

use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer\AssertionResultRenderer;

class MockAssertionResultRenderer extends AssertionResultRenderer
{
    /**
     * @var bool
     */
    private $is_silent = false;

    public function renderAssertionSuccess(string $why): void
    {
        if (! $this->is_silent) {
            echo "success:{$why};";
        }
    }

    public function renderAssertionFailure(string $why): void
    {
        if (! $this->is_silent) {
            echo "failure:{$why};";
        }
    }

    public function silent(): void
    {
        $this->is_silent = true;
    }
}
