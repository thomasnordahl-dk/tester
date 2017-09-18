<?php

namespace Phlegmatic\Tester\Adapters\OutputResultsRunner;

use Exception;

use Phlegmatic\Tester\Exception\FailedAssertionException;
use Phlegmatic\Tester\Tester;

class OutputResultsTester implements Tester
{
    /**
     * @var CaseResult
     */
    private $test_case_results;

    public function __construct(CaseResult $results)
    {
        $this->test_case_results = $results;
    }

    public function assert(bool $result, string $why): void
    {
        $test_case_results = $this->test_case_results;

        if ($result === true) {
            $test_case_results->addSuccessReason($why);
        } else {
            $test_case_results->addFailReason($why);
            throw new FailedAssertionException("Failed assertion {$why}");
        }
    }

    public function expect(string $exception_type, callable $when, string $why): void
    {
        $test_case_results = $this->test_case_results;
        $failed = false;

        try {
            $when();
            $test_case_results->addFailReason($why);
            $failed = true;
        } catch (Exception $exception) {
            if (! $exception instanceof $exception_type) {
                throw $exception;
            }
            $test_case_results->addSuccessReason($why);
        }

        if ($failed) {
            throw new FailedAssertionException("Failed assertion {$why}");
        }
    }
}
