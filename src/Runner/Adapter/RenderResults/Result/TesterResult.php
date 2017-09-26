<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Result;

use Exception;
use ThomasNordahlDk\Tester\Assertion\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\FailedAssertionException;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer\AssertionResultRenderer;

class TesterResult implements Tester
{
    /**
     * @var int
     */
    private $assertion_count = 0;

    /**
     * @var AssertionResultRenderer
     */
    private $renderer;

    public function __construct(AssertionResultRenderer $renderer)
    {
        $this->renderer = $renderer;
    }

    public function assert(bool $result, string $why): void
    {
        if ($result) {
            $this->renderer->renderAssertionSuccess($why);
            $this->assertion_count++;
        } else {
            $this->renderer->renderAssertionFailure($why);
            throw new FailedAssertionException($why);
        }
    }

    public function expect(string $exception_type, callable $when, string $why): void
    {
        $failed = false;

        try {
            $when();
            $this->renderer->renderAssertionFailure($why);
            $failed = true;
        } catch (Exception $exception) {
            if (! $exception instanceof $exception_type) {
                throw $exception;
            }
            $this->renderer->renderAssertionSuccess($why);
            $this->assertion_count++;
        }

        if ($failed) {
            throw new FailedAssertionException("Failed assertion {$why}");
        }
    }

    public function getSuccessCount(): int
    {
        return $this->assertion_count;
    }
}
