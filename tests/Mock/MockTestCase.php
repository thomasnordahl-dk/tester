<?php

namespace ThomasNordahlDk\Tester\Tests\Mock;

use Closure;
use ThomasNordahlDk\Tester\Assertion\Tester;
use ThomasNordahlDk\Tester\TestCase;

class MockTestCase implements TestCase
{
    public $description = "";

    /**
     * @var Closure
     */
    private $run_function;

    public function __construct(string $description, Closure $run_function)
    {
        $this->description = $description;
        $this->run_function = $run_function;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function run(Tester $tester): void
    {
        $run_function = $this->run_function;
        $run_function($tester);
    }
}
