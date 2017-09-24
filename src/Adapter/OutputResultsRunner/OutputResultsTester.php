<?php

namespace Phlegmatic\Tester\Adapter\OutputResultsRunner;


use Exception;

use Phlegmatic\Tester\Exception\FailedAssertionException;
use Phlegmatic\Tester\Tester;

/**
 * Outputs reasons for failures / successes to the standard output buffer
 */
class OutputResultsTester implements Tester
{
    /**
     * @var bool
     */
    private $verbose;

    /**
     * @var int
     */
    private $success_count = 0;

    public function __construct(bool $verbose = false)
    {
        $this->verbose = $verbose;
    }

    public function assert(bool $result, string $why): void
    {
        if ($result === true) {
            $this->countAndOutputSuccess($why);
        } else {
            $this->outputFailure($why);
            throw new FailedAssertionException($why);
        }
    }

    public function expect(string $exception_type, callable $when, string $why): void
    {
        $failed = true;

        try {
            $when();
            $this->outputFailure($why);
        } catch (Exception $exception) {
            if (! $exception instanceof $exception_type) {
                throw $exception;
            }
            $failed = false;
            $this->countAndOutputSuccess($why);
        }

        if ($failed) {
            throw new FailedAssertionException("Failed assertion {$why}");
        }
    }

    public function getSuccessCount(): int
    {
        return $this->success_count;
    }

    private function countAndOutputSuccess($why): void
    {
        $this->success_count += 1;

        if ($this->verbose) {
            echo "✔ {$why}\n";
        }
    }

    private function outputFailure(string $why): void
    {
        echo "✖ {$why}\n";
    }
}
