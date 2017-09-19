<?php

namespace Phlegmatic\Tester\Tests\Mock\Adapter\OutputResultsRunner;

use Phlegmatic\Tester\Adapter\OutputResultsRunner\PackageResult;
use Phlegmatic\Tester\Adapter\OutputResultsRunner\PackageResultRenderer;

class MockPackageResultRenderer extends PackageResultRenderer
{
    /**
     * @var PackageResult[]
     */
    public $package_result_list;

    /**
     * @param PackageResult[] $package_result_list
     */
    public function renderPackageResults($package_result_list): void
    {
        $this->package_result_list = $package_result_list;
    }
}
