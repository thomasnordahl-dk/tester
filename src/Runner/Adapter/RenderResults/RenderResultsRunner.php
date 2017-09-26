<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\RenderResults;


use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Result\PackageResult;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Result\TesterResult;
use ThomasNordahlDk\Tester\Runner\FailedTestsException;
use ThomasNordahlDk\Tester\Runner\Runner;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\TestPackage;

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

    public function run($packages): void
    {
        foreach ($packages as $package) {
            $this->runPackage($package);
        }

        if ($this->failed_tests) {
            throw new FailedTestsException($this->failed_tests . " failed tests!");
        }
    }

    private function runPackage(TestPackage $package): void
    {
        $renderer = $this->renderer_factory->createPackageRenderer();

        $renderer->renderHeader($package);

        $result = $this->getPackageResult($package);

        $this->failed_tests += $result->getFailureCount();

        $renderer->renderSummary($result);
    }

    private function getPackageResult(TestPackage $package): PackageResult
    {
        $package_result = new PackageResult();

        $test_case_list = $package->getTestCaseList();

        $start = microtime(true);

        foreach ($test_case_list as $test_case) {
            $this->addTestCaseResults($package_result, $test_case);
        }

        $package_result->setTimeInSeconds(microtime(true) - $start);

        return $package_result;
    }

    private function addTestCaseResults(PackageResult $package_result, TestCase $test_case): void
    {
        $renderer_factory = $this->renderer_factory;

        $assertion_result_renderer = $renderer_factory->createAssertionResultRenderer();
        $tester = new TesterResult($assertion_result_renderer);
        $test_case_renderer = $renderer_factory->createTestCaseRenderer();

        $test_case_renderer->renderHeader($test_case);

        try {
            $test_case->run($tester);

            $test_case_renderer->renderSummary($tester->getSuccessCount(), true);
            $package_result->success($tester->getSuccessCount());
        } catch (FailedAssertionException $exception) {
            $test_case_renderer->renderSummary($tester->getSuccessCount(), false);
            $package_result->failure($tester->getSuccessCount());
        }
    }

    public static function create(bool $verbose = false): RenderResultsRunner
    {
        return new RenderResultsRunner(new RendererFactory($verbose));
    }
}
