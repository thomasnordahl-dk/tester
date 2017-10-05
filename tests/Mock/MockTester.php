<?php

namespace ThomasNordahlDk\Tester\Tests\Mock;


use ThomasNordahlDk\Tester\Tester;

class MockTester implements Tester
{
    public $assert_result;
    public $assert_why;
    public $expect_exception_type;
    public $expect_when;
    public $expect_why;

    public function assert(bool $result, string $why): void
    {
        $this->assert_result = $result;
        $this->assert_why = $why;
    }

    public function expect(string $exception_type, callable $when, string $why): void
    {
        $this->expect_exception_type = $exception_type;
        $this->expect_when = $when;
        $this->expect_why = $why;
    }
}
