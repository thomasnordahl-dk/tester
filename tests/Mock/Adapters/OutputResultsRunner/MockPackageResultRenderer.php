<?php

namespace Phlegmatic\Tester\Tests\Mock\Adapters\OutputResultsRunner;

use Phlegmatic\Tester\Adapters\OutputResultsRunner\PackageResult;
use Phlegmatic\Tester\Adapters\OutputResultsRunner\PackageResultRenderer;

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
