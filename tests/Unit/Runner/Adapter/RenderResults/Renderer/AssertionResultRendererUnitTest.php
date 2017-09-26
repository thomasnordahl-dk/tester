<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults\Renderer;

use ThomasNordahlDk\Tester\Assertion\Decorator\ExpectedOutputTester;
use ThomasNordahlDk\Tester\Assertion\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer\AssertionResultRenderer;
use ThomasNordahlDk\Tester\TestCase;

class AssertionResultRendererUnitTest implements TestCase
{
    private const ASSERTION_REASON         = "the reason";
    private const ASSERTION_FAILURE_OUTPUT = "✖ the reason\n";
    private const ASSERTION_SUCCESS_OUTPUT = "✔ the reason\n";
    /**
     * @var ExpectedOutputTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit test of " . AssertionResultRenderer::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new ExpectedOutputTester($tester);

        $this->testRenderAssertionSuccess();
        $this->testRenderAssertionFailure();
    }

    private function testRenderAssertionSuccess(): void
    {
        $tester = $this->tester;
        $tester->expectOutput("", function () {
            $results_renderer = new AssertionResultRenderer();
            $results_renderer->renderAssertionSuccess(self::ASSERTION_REASON);
        }, "not verbose state does not render renderAssertionSuccess success");

        $tester->expectOutput(self::ASSERTION_SUCCESS_OUTPUT, function () {
            $results_renderer = new AssertionResultRenderer(true);
            $results_renderer->renderAssertionSuccess(self::ASSERTION_REASON);
        }, "verbose state renders renderAssertionSuccess success");
    }

    private function testRenderAssertionFailure()
    {
        $tester = $this->tester;
        $tester->expectOutput(self::ASSERTION_FAILURE_OUTPUT, function () {
            $results_renderer = new AssertionResultRenderer();
            $results_renderer->renderAssertionFailure(self::ASSERTION_REASON);
        }, "not verbose state does not renders renderAssertionSuccess failure");

        $tester->expectOutput(self::ASSERTION_FAILURE_OUTPUT, function () {
            $results_renderer = new AssertionResultRenderer(true);
            $results_renderer->renderAssertionFailure(self::ASSERTION_REASON);
        }, "verbose state renders renderAssertionSuccess failure");
    }

}
