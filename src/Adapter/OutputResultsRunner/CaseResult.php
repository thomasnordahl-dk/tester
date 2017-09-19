<?php

namespace Phlegmatic\Tester\Adapter\OutputResultsRunner;

use Exception;
use Phlegmatic\Tester\TestCase;

class CaseResult
{
    /**
     * @var TestCase
     */
    private $case;

    /**
     * @var string[]
     */
    private $success_reasons = [];

    /**
     * @var string[]
     */
    private $fail_reasons = [];

    /**
     * @var Exception|null
     */
    private $exception = null;

    public function __construct(TestCase $case)
    {
        $this->case = $case;
    }

    public function getTestCase(): TestCase
    {
        return $this->case;
    }

    public function addSuccessReason(string $why): void
    {
        $this->success_reasons[] = $why;
    }

    /**
     * @return string[]
     */
    public function getSuccessReasonList()
    {
        return $this->success_reasons;
    }

    public function addFailReason(string $why): void
    {
        $this->fail_reasons[] = $why;
    }

    /**
     * @return string[]
     */
    public function getFailReasonList()
    {
        return $this->fail_reasons;
    }

    public function testEndedByException(Exception $exception_thrown): void
    {
        $this->exception = $exception_thrown;
    }

    public function getExceptionThrown(): ?Exception
    {
        return $this->exception;
    }

    public function wasEndedByException(): bool
    {
        return $this->exception instanceof Exception;
    }

    public function wasSuccessful()
    {
        return !count($this->fail_reasons) && ! $this->wasEndedByException();
    }
}
