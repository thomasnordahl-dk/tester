<?php
use Phlegmatic\Tester\TestPackage;
use Phlegmatic\Tester\Tests\Unit\Adapter\OutputResultsRunner\OutputResultsTesterUnitTest;
use Phlegmatic\Tester\Tests\Unit\Helper\OutputAssertionTesterUnitTest;
use Phlegmatic\Tester\Tests\Unit\Adapter\OutputResultsRunner\OutputResultsRunnerUnitTest;
use Phlegmatic\Tester\Tests\Unit\Adapter\OutputResultsRunner\PackageResultRendererUnitTest;
use Phlegmatic\Tester\Tests\Unit\TestPackageUnitTest;

$unit_tests = new TestPackage("UNIT TESTS", [
    ## TestPackage
      new TestPackageUnitTest(),
    ## OutputResultRunner and related units
    new OutputResultsRunnerUnitTest(),
    new OutputResultsTesterUnitTest(),
    ## Helpers
    new OutputAssertionTesterUnitTest,
]);

return [$unit_tests];
