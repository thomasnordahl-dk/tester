<?php

namespace Phlegmatic\Tester\Runner;


use Phlegmatic\Tester\TestPackage;

/**
 * Runs test packages
 */
interface Runner
{
    /**
     * @param TestPackage[] $packages
     *
     * @throws FailedTestsException
     */
    public function run($packages): void;
}
