<?php

namespace Phlegmatic\Tester\Tests\Mock\Runner;

use Phlegmatic\Tester\Runner\Runner;
use Phlegmatic\Tester\TestPackage;

class MockRunner implements Runner
{
    /**
     * @var TestPackage[]
     */
    private $packages;

    public function run($packages): void
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
