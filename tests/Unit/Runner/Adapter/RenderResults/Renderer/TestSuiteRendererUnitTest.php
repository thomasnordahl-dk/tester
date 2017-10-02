<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults\Renderer;

use ThomasNordahlDk\Tester\Assertion\Decorator\ExpectedOutputTester;
use ThomasNordahlDk\Tester\Assertion\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer\TestSuiteRenderer;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Result\TestSuiteResult;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\TestSuite;
use ThomasNordahlDk\Tester\Tests\Mock\MockTestCase;

class TestSuiteRendererUnitTest implements TestCase
{
    /**
     * @var ExpectedOutputTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit test of " . TestSuiteRenderer::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new ExpectedOutputTester($tester);

        $this->testRenderHeaderMethod();
        $this->testRenderSuccessSummary();
        $this->testRenderFailureSummary();
    }

    private function testRenderHeaderMethod()
    {
        $tester = $this->tester;

        $package = $this->createTestSuite("description", 2);
        $expected_output = "\n" . str_pad("description (2) ", 100, "-") . "\n";

        $tester->expectOutput($expected_output, function () use ($package) {
            $package_renderer = new TestSuiteRenderer();
            $package_renderer->renderHeader($package);
        }, "Suite header is padded with \"-\" up to 100 chars - 2 cases");

        $package = $this->createTestSuite("description", 11);
        $expected_output = "\n" . str_pad("description (11) ", 100, "-") . "\n";

        $tester->expectOutput($expected_output, function () use ($package) {
            $package_renderer = new TestSuiteRenderer();
            $package_renderer->renderHeader($package);
        }, "Summary header is padded with \"-\" up to 100 chars - 11 cases");
    }

    private function testRenderSuccessSummary(): void
    {
        $tester = $this->tester;

        $expected = "\nSuccess! 1 test, 1 assertion (0.10s)\n";
        $expected .= str_pad("", 100, "-") . "\n";
        $expected .= "\n";

        $package_result = new TestSuiteResult();
        $package_result->success(1);
        $package_result->setTimeInSeconds(0.101);

        $tester->expectOutput($expected, function () use ($package_result) {
            $package_renderer = new TestSuiteRenderer();
            $package_renderer->renderSummary($package_result);
        }, "success summary with singular counts");

        $expected = "\nSuccess! 2 tests, 2 assertions (1.11s)\n";
        $expected .= str_pad("", 100, "-") . "\n";
        $expected .= "\n";

        $package_result->success(1);
        $package_result->setTimeInSeconds(1.105);

        $tester->expectOutput($expected, function () use ($package_result) {
            $package_renderer = new TestSuiteRenderer();
            $package_renderer->renderSummary($package_result);
        }, "success summary with plural counts");
    }

    private function testRenderFailureSummary()
    {
        $tester = $this->tester;

        $expected = "\nFAILED! 1 test failed, 1 test succeeded, 1 assertion (0.10s)\n";
        $expected .= str_pad("", 100, "-") . "\n";
        $expected .= "\n";

        $package_result = new TestSuiteResult();
        $package_result->success(1);
        $package_result->failure(0);
        $package_result->setTimeInSeconds(0.101);

        $tester->expectOutput($expected, function () use ($package_result) {
            $package_renderer = new TestSuiteRenderer();
            $package_renderer->renderSummary($package_result);
        }, "failure summary with singular counts");

        $expected = "\nFAILED! 2 tests failed, 2 tests succeeded, 2 assertions (1.11s)\n";
        $expected .= str_pad("", 100, "-") . "\n";
        $expected .= "\n";

        $package_result->success(1);
        $package_result->failure(0);
        $package_result->setTimeInSeconds(1.105);

        $tester->expectOutput($expected, function () use ($package_result) {
            $package_renderer = new TestSuiteRenderer();
            $package_renderer->renderSummary($package_result);
        }, "failed summary with plural counts");
    }

    private function createTestSuite(string $description, int $case_count): TestSuite
    {
        $mock_test_case = new MockTestCase("test-case", function () {
            //not used
        });
        $test_case_list = array_fill(0, $case_count, $mock_test_case);

        return new TestSuite($description, ... $test_case_list);
    }
}
