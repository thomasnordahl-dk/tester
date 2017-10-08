<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\OutputResults;

use ThomasNordahlDk\Tester\Runner\Runner;
use ThomasNordahlDk\Tester\TestSuite;

/**
 * Runner class that outputs a test summary to the output buffer.
 */
class OutputResultsRunner implements Runner
{
    /**
     * @var OutputResultsFactory
     */
    private $factory;

    /**
     * @internal Use create() method instead
     * @see      OutputResultsRunner::create()
     *
     * @param OutputResultsFactory $factory
     */
    public function __construct(OutputResultsFactory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Factory method for neat creaton of new OutputResultsRunner instance.
     *
     * @param bool $verbose Set to true to output verbose summaries.
     *
     * @return OutputResultsRunner
     */
    public static function create(bool $verbose = false): OutputResultsRunner
    {
        return new self(new OutputResultsFactory($verbose));
    }

    /**
     * @inheritdoc
     */
    public function run($suites): void
    {
        foreach ($suites as $suite) {
            $this->runSuite($suite);
        }
    }

    /**
     * Runs individual test suite
     *
     * @param TestSuite $suite The test suite to run.
     */
    private function runSuite(TestSuite $suite): void
    {
        $suite_runner = $this->factory->createTestSuiteRunner();

        $suite_runner->run($suite);
    }
}
