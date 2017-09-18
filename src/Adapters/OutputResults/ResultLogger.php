<?php

namespace Phlegmatic\Tester\Adapters\OutputResults;

class ResultLogger
{
    private $results = [];
    private $success_count = 0;
    private $failure_count = 0;
    private $verbose = false;

    public function __construct(bool $verbose = false)
    {
        $this->verbose = $verbose;
    }

    public function success(string $reason): void
    {
        if ($this->verbose) {
            $this->results[] = "- Success: {$reason}";
        }
        $this->success_count += 1;
    }

    public function failure(string $reason): void
    {
        $this->results[] = "- FAILED! {$reason}";
        $this->failure_count += 1;
    }

    public function getFailureCount(): int
    {
        return $this->failure_count;
    }

    public function getSuccessCount(): int
    {
        return $this->success_count;
    }

    /**
     * @return string[]
     */
    public function getResults()
    {
        return $this->results;
    }
}
