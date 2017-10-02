<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\RenderResults;


use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Result\TestSuiteResult;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Result\TesterResult;
use ThomasNordahlDk\Tester\Runner\FailedTestsException;
use ThomasNordahlDk\Tester\Runner\Runner;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\TestSuite;

class RenderResultsRunner implements Runner
{
    /**
     * @var RendererFactory
     */
    private $renderer_factory;

    /**
     * @var int
     */
    private $failed_tests = 0;

    public function __construct(RendererFactory $renderer_factory)
    {
        $this->renderer_factory = $renderer_factory;
    }

    public function run(array $suites): void
    {
        foreach ($suites as $suite) {
            $this->runTestSuite($suite);
        }

        if ($this->failed_tests) {
            throw new FailedTestsException($this->failed_tests . " failed tests!");
        }
    }

    private function runTestSuite(TestSuite $suite): void
    {
        $renderer = $this->renderer_factory->createTestSuiteRenderer();

        $renderer->renderHeader($suite);

        $result = $this->getTestSuiteResult($suite);

        $this->failed_tests += $result->getFailureCount();

        $renderer->renderSummary($result);
    }

    private function getTestSuiteResult(TestSuite $test_suite): TestSuiteResult
    {
        $test_suite_result = new TestSuiteResult();

        $test_case_list = $test_suite->getTestCaseList();

        $start = microtime(true);

        foreach ($test_case_list as $test_case) {
            $this->addTestCaseResults($test_suite_result, $test_case);
        }

        $test_suite_result->setTimeInSeconds(microtime(true) - $start);

        return $test_suite_result;
    }

    private function addTestCaseResults(TestSuiteResult $test_suite_result, TestCase $test_case): void
    {
        $renderer_factory = $this->renderer_factory;

        $assertion_result_renderer = $renderer_factory->createAssertionResultRenderer();
        $tester = new TesterResult($assertion_result_renderer);
        $test_case_renderer = $renderer_factory->createTestCaseRenderer();

        $test_case_renderer->renderHeader($test_case);

        try {
            $test_case->run($tester);

            $test_case_renderer->renderSummary($tester->getSuccessCount(), true);
            $test_suite_result->success($tester->getSuccessCount());
        } catch (FailedAssertionException $exception) {
            $test_case_renderer->renderSummary($tester->getSuccessCount(), false);
            $test_suite_result->failure($tester->getSuccessCount());
        }
    }

    public static function create(bool $verbose = false): RenderResultsRunner
    {
        return new RenderResultsRunner(new RendererFactory($verbose));
    }
}
