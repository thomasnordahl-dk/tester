<?php

namespace Phlegmatic\Tester\Adapters\OutputResults;

use Exception;

use Phlegmatic\Tester\Exception\FailedAssertionException;
use Phlegmatic\Tester\Tester;

class LogResultsTester implements Tester
{
    private $result_logger;

    public function __construct(ResultLogger $result_logger)
    {
        $this->result_logger = $result_logger;
    }

    public function assert(bool $result, string $why): void
    {
        $logger = $this->result_logger;

        if ($result === true) {
            $logger->success($why);
        } else {
            $logger->failure($why);
            throw new FailedAssertionException("Failed assertion {$why}");
        }
    }

    public function expect(string $exception_type, callable $when, string $why): void
    {
        $logger = $this->result_logger;
        try {
            $when();
            $logger->failure($why);
            throw new FailedAssertionException("Failed assertion {$why}");
        } catch (Exception $exception) {
            if (! $exception instanceof $exception_type) {
                throw $exception;
            }
            $logger->success($why);
        }
    }
}
