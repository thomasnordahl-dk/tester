<?php

namespace ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\OutputResults;


use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\OutputResultsFactory;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestCase\TestCaseRunner;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\Assertion\FailedAssertionException;
use ThomasNordahlDk\Tester\Runner\FailedTestException;
use ThomasNordahlDk\Tester\TestCase;

class MockTestCaseRunner extends TestCaseRunner
{
    /**
     * @var TestCase
     */
    private $case;

    /**
     * @var OutputResultsFactory
     */
    private $factory;

    /**
     * @var bool
     */
    private $verbose;

    /**
     * @var int
     */
    private $assertion_count = 0;

    public function __construct(TestCase $case, OutputResultsFactory $factory, $verbose = false)
    {
        parent::__construct($case, $factory, $verbose);

        $this->case = $case;
        $this->factory = $factory;
        $this->verbose = $verbose;
    }

    public function run(): void
    {
        $tester = $this->factory->createTester();

        //Skip outputting
        try {
            $this->case->run($tester);
            $this->assertion_count = $tester->getAssertionCount();
        } catch (FailedAssertionException $exception) {
            $this->assertion_count = $tester->getAssertionCount();
            throw new FailedTestException($exception);
        }
    }

    public function getAssertionCount(): int
    {
        return $this->assertion_count;
    }
}
