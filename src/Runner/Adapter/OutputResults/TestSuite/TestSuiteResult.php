<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestSuite;

/**
 * Use instances to log the results of a test suite and return
 * them after the test.
 */
class TestSuiteResult
{
    /**
     * @var int
     */
    private $success_count = 0;

    /**
     * @var int
     */
    private $failure_count = 0;

    /**
     * @var int
     */
    private $assertion_count = 0;

    /**
     * @var float
     */
    private $time_spent = 0.0;

    /**
     * Registers that a test case succeeded and adds the assertion count
     * to the total assertion count
     *
     * @param int $assertion_count amount of assertions made during the test
     */
    public function registerSuccess(int $assertion_count): void
    {
        $this->success_count++;
        $this->assertion_count += $assertion_count;
    }

    /**
     * Registers that a test case failed, and adds the count of assertions
     * that succeeded before the test failed
     *
     * @param int $assertion_count Amount of successful assertions made during the test
     */
    public function registerFailure(int $assertion_count): void
    {
        $this->failure_count++;
        $this->assertion_count += $assertion_count;
    }

    /**
     * @return int The amount of successful test cases
     */
    public function getSuccessCount(): int
    {
        return $this->success_count;
    }

    /**
     * @return int The amount of failed test cases
     */
    public function getFailureCount(): int
    {
        return $this->failure_count;
    }

    /**
     * @return int The amount of successful assertions made while running the suite.
     */
    public function getAssertionCount(): int
    {
        return $this->assertion_count;
    }

    public function getTimeSpent(): float
    {
        return $this->time_spent;
    }

    public function setTimeSpent(float $time_spent)
    {
        $this->time_spent = $time_spent;
    }
}
