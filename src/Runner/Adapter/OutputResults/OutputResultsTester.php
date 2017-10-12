<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\OutputResults;


use Exception;
use ThomasNordahlDk\Tester\Tester;


/**
 * Outputs results to the output buffer, and counts successful assertions.
 */
class OutputResultsTester implements Tester
{
    /**
     * @var int
     */
    private $assertion_count = 0;

    /**
     * @var bool
     */
    private $is_verbose;

    public function __construct(bool $verbose = false)
    {
        $this->is_verbose = $verbose;
    }

    /**
     * Outputs the result of the assertion and counts successful assertion
     *
     * @inheritdoc
     *
     * @throws FailedAssertionException
     */
    public function assert(bool $result, string $why): void
    {
        if ($result) {
            $this->outputAndCountSuccess($why);
        } else {
            $this->outputFailure($why);

            throw new FailedAssertionException($why);
        }
    }

    /**
     * Outputs the result of the assertion and counts successful assertion
     *
     * @inheritdoc
     *
     * @throws FailedAssertionException
     */
    public function expect(string $exception_type, callable $when, string $why): void
    {
        try {
            $when();
        } catch (Exception $exception) {
            if (! $exception instanceof $exception_type) {
                throw $exception;
            }
            $this->outputAndCountSuccess($why);

            return;
        }

        $this->outputFailure($why);

        throw new FailedAssertionException($why);
    }

    /**
     * @return int The amount of successful assertions made so far
     */
    public function getAssertionCount(): int
    {
        return $this->assertion_count;
    }

    private function outputAndCountSuccess(string $why): void
    {
        $this->assertion_count++;

        if ($this->is_verbose) {
            echo "✔ {$why}\n";
        }
    }

    private function outputFailure(string $why): void
    {
        echo "✖ {$why}\n";
    }
}
