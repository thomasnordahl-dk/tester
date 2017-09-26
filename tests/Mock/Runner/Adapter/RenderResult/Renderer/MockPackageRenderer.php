<?php

namespace ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\RenderResult\Renderer;

use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Renderer\PackageRenderer;
use ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\Result\PackageResult;
use ThomasNordahlDk\Tester\TestPackage;

class MockPackageRenderer extends PackageRenderer
{
    public function renderHeader(TestPackage $package): void
    {
        echo "package-header:" . $package->getDescription() . ";";
    }

    public function renderSummary(PackageResult $package_result): void
    {
        echo "success:" . $package_result->getSuccessCount() . ";";
        echo "failure:" . $package_result->getFailureCount() . ";";
        echo "assertion:" . $package_result->getAssertionCount() . ";";
        echo "time:" . ($package_result->getTimeInSeconds() > 0.0 ? "yes" : "no");
    }
}
