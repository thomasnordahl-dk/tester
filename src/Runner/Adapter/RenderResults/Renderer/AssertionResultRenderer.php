<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer;

class AssertionResultRenderer
{
    /**
     * @var bool
     */
    private $verbose;

    public function __construct(bool $verbose = false)
    {
        $this->verbose = $verbose;
    }

    public function renderAssertionSuccess(string $why): void
    {
        if ($this->verbose) {
            echo "✔ {$why}\n";
        }
    }

    public function renderAssertionFailure(string $why): void
    {
        echo "✖ {$why}\n";
    }
}
