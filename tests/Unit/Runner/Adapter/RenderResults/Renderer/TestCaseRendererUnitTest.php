<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults\Renderer;

use ThomasNordahlDk\Tester\Assertion\Decorator\ExpectedOutputTester;
use ThomasNordahlDk\Tester\Assertion\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer\TestCaseRenderer;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Tests\Mock\MockTestCase;

class TestCaseRendererUnitTest implements TestCase
{
    /**
     * @var ExpectedOutputTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit test of " . TestCaseRenderer::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new ExpectedOutputTester($tester);

        $this->testRenderTestCaseHeader();
        $this->testRenderTestCaseSummary();
    }

    private function testRenderTestCaseHeader()
    {
        $tester = $this->tester;

        $mock_case = new MockTestCase("test-case", function () {
            //will not be run
        });

        $tester->expectOutput("test-case\n", function () use ($mock_case) {
            $results_renderer = new TestCaseRenderer();
            $results_renderer->renderHeader($mock_case);

        }, "TestCase header is simply case description");


        $tester->expectOutput("test-case\n", function () use ($mock_case) {
            $results_renderer = new TestCaseRenderer(true);
            $results_renderer->renderHeader($mock_case);
        }, "Verbose TestCase header is simply case description");
    }

    private function testRenderTestCaseSummary()
    {
        $tester = $this->tester;

        $tester->expectOutput("", function () {
            $results_renderer = new TestCaseRenderer();
            $results_renderer->renderSummary(5, true);
            $results_renderer->renderSummary(1, true);
        }, "Non verbose doesnt render summary on success");


        $tester->expectOutput("Test completed after 1 successfull assertion\n", function () {
            $results_renderer = new TestCaseRenderer(true);
            $results_renderer->renderSummary(1, true);
        }, "Verbose renders summary on success");


        $tester->expectOutput("Test completed after 2 successfull assertions\n", function () {
            $results_renderer = new TestCaseRenderer(true);
            $results_renderer->renderSummary(2, true);
        }, "Verbose renders summary on success with correct plurals");


        $tester->expectOutput("FAILED! Test failed after 1 successfull assertion\n", function () {
            $results_renderer = new TestCaseRenderer();
            $results_renderer->renderSummary(1, false);
        }, "Verbose renders summary on success");


        $tester->expectOutput("FAILED! Test failed after 2 successfull assertions\n", function () {
            $results_renderer = new TestCaseRenderer();
            $results_renderer->renderSummary(2, false);
        }, "Verbose renders summary on success with correct plurals");
    }

}
