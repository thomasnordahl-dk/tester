<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\OutputResults;


use ThomasNordahlDk\Tester\Runner\Runner;
use ThomasNordahlDk\Tester\TestSuite;

class OutputResultsRunner implements Runner
{
    /**
     * @var OutputResultsFactory
     */
    private $factory;

    /**
     * @internal Use create() method instead
     * @see OutputResultsRunner::create()
     */
    public function __construct(OutputResultsFactory $factory)
    {
        $this->factory = $factory;
    }

    public static function create(bool $verbose = false): OutputResultsRunner
    {
        return new self(new OutputResultsFactory($verbose));
    }

    public function run(array $suites): void
    {
        foreach ($suites as $suite) {
            $this->runSuite($suite);
        }
    }

    private function runSuite(TestSuite $suite): void
    {
        $suite_runner = $this->factory->createTestSuiteRunner();

        $suite_runner->run($suite);
    }
}
