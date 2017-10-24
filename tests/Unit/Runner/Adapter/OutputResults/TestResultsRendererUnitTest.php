<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\OutputResults;

use ThomasNordahlDk\Tester\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\OutputResultsTester;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestResultsRenderer;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Tester;
use ThomasNordahlDk\Tester\Tests\Mock\MockTestCase;
use ThomasNordahlDk\Tester\TestSuite;

class TestResultsRendererUnitTest implements TestCase
{
    /**
     * @var ComparisonTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit test of " . TestResultsRenderer::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new ComparisonTester($tester);

        $this->testRenderingTestCaseHeader();
        $this->testRenderingTestCaseResults();
        $this->testRenderingTestSuiteHeader();
        $this->testRenderingTestSuiteSummary();
        $this->testCreateOutputResultsTester();
    }

    private function testRenderingTestCaseHeader(): void
    {
        $tester = $this->tester;

        $description = "description";
        $case = new MockTestCase($description, function () {
            // does nothing;
        });

        $expected = " - {$description}\n";

        $renderer = new TestResultsRenderer();
        $tester->assertSame($renderer->renderTestCaseHeader($case), $expected,
            "TestCase header should match expected result");


        $renderer = new TestResultsRenderer(true);
        $tester->assertSame($renderer->renderTestCaseHeader($case), $expected,
            "TestCase header is the same for verbose rendering");
    }

    private function testRenderingTestCaseResults(): void
    {
        $tester = $this->tester;

        $renderer = new TestResultsRenderer();

        $tester->assertSame($renderer->renderTestCaseSummary(true, 2), "",
            "TestCase results not rendered when not verbose");

        $expected = "FAILED! Failed after 2 successful assertion(s)\n";

        $tester->assertSame($renderer->renderTestCaseSummary(false, 2), $expected,
            "TestCase result matches expected format for failed test case");


        $renderer = new TestResultsRenderer(true);

        $tester->assertSame($renderer->renderTestCaseSummary(false, 2), $expected,
            "TestCase verbose result matches expected format for failed test case");

        $expected = "Success! Test completed after 2 successful assertion(s)\n";
        $tester->assertSame($renderer->renderTestCaseSummary(true, 2), $expected,
            "TestCase verbose results matches expected format for completed test case");
    }

    private function testRenderingTestSuiteHeader()
    {
        $tester = $this->tester;

        $expected = "***************************************************************************\n";
        $expected .= "description (test cases: 2)\n";
        $expected .= "***************************************************************************\n\n";

        $suite = new TestSuite("description", [
            new MockTestCase("case 1", function () {
                //does nothing;
            }),
            new MockTestCase("case 2", function () {
                //does nothing;
            }),
        ]);

        $renderer = new TestResultsRenderer();
        $tester->assertSame($renderer->renderTestSuiteHeader($suite), $expected,
            "TestSuite header matches the expected format");

        $renderer = new TestResultsRenderer(true);
        $tester->assertSame($renderer->renderTestSuiteHeader($suite), $expected,
            "TestSuite header matches the expected format when verbose");
    }

    private function testRenderingTestSuiteSummary(): void
    {
        $tester = $this->tester;

        $expected_success = "\n\n***************************************************************************\n";
        $expected_success .= "Success! 2 test(s), 3 assertion(s), (1.23s)\n";
        $expected_success .= "***************************************************************************\n\n";

        $expected_failed = "\n\n***************************************************************************\n";
        $expected_failed .= "FAILED! 1 test(s) failed, 2 completed test(s), 3 assertion(s), (1.23s)\n";
        $expected_failed .= "***************************************************************************\n\n";

        $renderer = new TestResultsRenderer();

        $success_summary = $renderer->renderTestSuiteSummary(2, 0, 3, 1.23);
        $failed_summary = $renderer->renderTestSuiteSummary(2, 1, 3, 1.23);

        $tester->assertSame($success_summary, $expected_success,
            "Test suite results matches format for completed tests");
        $tester->assertSame($failed_summary, $expected_failed,
            "Test suite results matches format for failed tests");
    }

    private function testCreateOutputResultsTester(): void
    {
        $tester = $this->tester;

        $expected = new OutputResultsTester();
        $renderer = new TestResultsRenderer();

        $tester->assertEqual($renderer->createTester(), $expected,
            "Creates non verbose OutputResultsTester when not verbose");

        $expected = new OutputResultsTester(true);
        $renderer = new TestResultsRenderer(true);

        $tester->assertEqual($renderer->createTester(), $expected,
            "Creates verbose OutputResultsTester when verbose");
    }
}
