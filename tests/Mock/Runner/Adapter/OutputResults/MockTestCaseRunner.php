<?php

namespace ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\OutputResults;


use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestCase\TestCaseRunner;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\Assertion\OutputResultsTester;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\Assertion\FailedAssertionException;
use ThomasNordahlDk\Tester\Runner\FailedTestException;
use ThomasNordahlDk\Tester\TestCase;

class MockTestCaseRunner extends TestCaseRunner
{
    /**
     * @var OutputResultsTester
     */
    private $tester;

    public function __construct(OutputResultsTester $tester, $verbose = false)
    {
        $this->tester = $tester;

        parent::__construct($tester, $verbose);
    }

    public function run(TestCase $test_case): void
    {
        //Skip outputting
        try {
            $test_case->run($this->tester);
        } catch (FailedAssertionException $exception) {
            throw new FailedTestException($exception);
        }
    }
}
