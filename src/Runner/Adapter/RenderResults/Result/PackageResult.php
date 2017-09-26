<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Result;


class PackageResult
{
    /**
     * @var int number of successfull assertions registered
     */
    private $assertion_count = 0;

    /**
     * @var float the time it took to run the package in seconds
     */
    private $time_in_seconds = 0.0;

    /**
     * @var int number of failed tests registered
     */
    private $failure_count = 0;

    /**
     * @var int number of successfull tests registered
     */
    private $success_count = 0;

    /**
     * @return int the number of successfull tests registered
     */
    public function getSuccessCount(): int
    {
        return $this->success_count;
    }

    /**
     * @return int the number of successfull tests registered
     */
    public function getFailureCount(): int
    {
        return $this->failure_count;
    }

    public function getTimeInSeconds(): float
    {
        return $this->time_in_seconds;
    }

    public function getAssertionCount(): int
    {
        return $this->assertion_count;
    }

    public function success(int $assertion_count): void
    {
        $this->success_count++;
        $this->assertion_count += $assertion_count;
    }

    public function failure(int $assertion_count): void
    {
        $this->failure_count++;
        $this->assertion_count += $assertion_count;
    }

    public function setTimeInSeconds(float $time_in_seconds): void
    {
        $this->time_in_seconds = $time_in_seconds;
    }
}
