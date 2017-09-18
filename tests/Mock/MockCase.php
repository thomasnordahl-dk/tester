<?php

namespace Phlegmatic\Tester\Tests\Mock;

use Phlegmatic\Tester\TestCase;
use Phlegmatic\Tester\Tester;

class MockCase implements TestCase
{
    public $run_with_this_tester;
    public $succeed = true;
    public $why = "";
    public $description = "mock test case";

    public function getDescription(): string
    {
        return $this->description;
    }

    public function run(Tester $tester): void
    {
        $this->run_with_this_tester = $tester;
        $tester->assert($this->succeed, $this->why);
    }
}
