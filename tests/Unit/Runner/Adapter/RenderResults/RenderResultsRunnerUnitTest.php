<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\RenderResults;


use ThomasNordahlDk\Tester\Assertion\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Assertion\Decorator\ExpectedOutputTester;
use ThomasNordahlDk\Tester\Assertion\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\RendererFactory;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\RenderResultsRunner;
use ThomasNordahlDk\Tester\Runner\FailedTestsException;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\TestSuite;
use ThomasNordahlDk\Tester\Tests\Mock\MockTestCase;
use ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\RenderResult\MockRendererFactory;

class RenderResultsRunnerUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . RenderResultsRunner::class;
    }

    public function run(Tester $tester): void
    {
        $comparison_tester = new ComparisonTester($tester);

        $expected = new RenderResultsRunner(new RendererFactory());
        $comparison_tester->assertEqual(RenderResultsRunner::create(), $expected, "create() creates new runner");

        $expected = new RenderResultsRunner(new RendererFactory(true));
        $comparison_tester->assertEqual(RenderResultsRunner::create(true), $expected, "create(true) creates new verbose runner");

        $tester = new ExpectedOutputTester($tester);

        $mock_case_1 = new MockTestCase("test-case-1", function (Tester $tester) {
            $tester->assert(true, "success");
            $tester->assert(true, "success");
        });
        $mock_case_2 = new MockTestCase("test-case-2", function (Tester $tester) {
            $tester->assert(true, "success");
            $tester->assert(false, "failure");
        });

        $package = new TestSuite("description", $mock_case_1);

        $expected = implode(";", [
            "suite-header:description",
            "case-header:test-case-1",
            "success:success",
            "success:success",
            "successfull_assertions:2",
            "succeeded:true",
            "success:1",
            "failure:0",
            "assertion:2",
            "time:yes",
        ]);

        $tester->expectOutput($expected, function () use ($package) {
            $render_results_runner = new RenderResultsRunner(new MockRendererFactory());
            $render_results_runner->run([$package]);
        }, "Matches mock renderers output format");

        $package = new TestSuite("description", $mock_case_1, $mock_case_2);

        $expected = implode(";", [
            "suite-header:description",
            "case-header:test-case-1",
            "success:success",
            "success:success",
            "successfull_assertions:2",
            "succeeded:true",
            "case-header:test-case-2",
            "success:success",
            "failure:failure",
            "successfull_assertions:1",
            "succeeded:false",
            "success:1",
            "failure:1",
            "assertion:3",
            "time:yes",
        ]);

        $failed_test_exception = null;

        $tester->expectOutput($expected, function () use ($package, &$failed_test_exception) {
            $render_results_runner = new RenderResultsRunner(new MockRendererFactory());
            try {
                $render_results_runner->run([$package]);
            } catch (FailedTestsException $exception) {
                $failed_test_exception = $exception;
            }
        }, "Matches mock renderers output format");

        $tester->assert($failed_test_exception instanceof FailedTestsException,
            "Runner throws FailedTestsException on failed test");
    }
}
