<?php

namespace ThomasNordahlDk\Tester\Tests\Mock\Runner;

use ThomasNordahlDk\Tester\Runner\Runner;
use ThomasNordahlDk\Tester\TestSuite;

class MockRunner implements Runner
{
    /**
     * @var TestSuite[]
     */
    private $suites;

    public function run(array $suites): void
    {
        $this->suites = $suites;
    }

    /**
     * @return TestSuite[]
     */
    public function wasRunWithPackages()
    {
        return $this->suites;
    }
}
