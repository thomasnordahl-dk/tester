<?php

namespace ThomasNordahlDk\Tester\Tests\Mock\Runner;

use ThomasNordahlDk\Tester\Runner\Runner;
use ThomasNordahlDk\Tester\TestPackage;

class MockRunner implements Runner
{
    /**
     * @var TestPackage[]
     */
    private $packages;

    public function run(array $packages): void
    {
        $this->packages = $packages;
    }

    /**
     * @return TestPackage[]
     */
    public function wasRunWithPackages()
    {
        return $this->packages;
    }
}
