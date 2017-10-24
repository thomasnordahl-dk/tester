<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\Simple;

use Exception;
use ThomasNordahlDk\Tester\Tester;

class SimpleTester implements Tester
{
    /**
     * @var int
     */
    private $successful_assertions = 0;

    /**
     * @inheritdoc
     *
     * @throws FailedAssertionException
     */
    public function assert(bool $result, string $why): void
    {
        if (! $result) {
            throw new FailedAssertionException($why);
        }

        $this->successful_assertions++;
    }

    /**
     * @inheritdoc
     *
     * @throws FailedAssertionException
     */
    public function expect(string $exception_type, callable $when, string $why): void
    {
        $failed = false;
        try {
            $when();
            $failed = true;
        } catch (Exception $exception) {
            if (! $exception instanceof $exception_type) {
                throw $exception;
            }

            $this->successful_assertions++;
        }

        if ($failed) {
            throw new FailedAssertionException($why);
        }
    }

    public function countSuccessfulAssertions(): int
    {
        return $this->successful_assertions;
    }
}
