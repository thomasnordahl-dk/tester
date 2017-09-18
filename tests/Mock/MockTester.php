<?php

namespace Phlegmatic\Tester\Tests\Mock;

use Phlegmatic\Tester\Tester;

class MockTester implements Tester
{
    public $assert_result;
    public $assert_why;

    public function assert(bool $result, string $why): void
    {
        $this->assert_result = $result;
        $this->assert_why = $why;
    }

    public function expect(string $exception_type, callable $when, string $why): void
    {

    }
}
