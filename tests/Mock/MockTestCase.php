<?php

namespace Phlegmatic\Tester\Tests\Mock;

use Closure;
use Phlegmatic\Tester\TestCase;
use Phlegmatic\Tester\Tester;

class MockTestCase implements TestCase
{
    public $description = "mock test case";

    /**
     * @var Closure $test function(Tester $tester) { // mock test here}
     */
    public $test;

    public function __construct()
    {
        $this->test = function (Tester $tester) {
            $tester->assert(true, "");
        };
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function run(Tester $tester): void
    {
        $test_function = $this->test;
        $test_function($tester);
    }
}
