<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestSuite;


class TestSuiteResult
{
    private $success_count = 0;
    private $failure_count = 0;
    private $assertion_count = 0;

    public function registerSuccess(int $assertion_count): void
    {
        $this->success_count++;
        $this->assertion_count += $assertion_count;
    }

    public function registerFailure(int $assertion_count): void
    {
        $this->failure_count++;
        $this->assertion_count += $assertion_count;
    }

    public function getSuccessCount(): int
    {
        return $this->success_count;
    }

    public function getFailureCount(): int
    {
        return $this->failure_count;
    }

    public function getAssertionCount(): int
    {
        return $this->assertion_count;
    }
}
