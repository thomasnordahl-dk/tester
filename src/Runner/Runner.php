<?php

namespace ThomasNordahlDk\Tester\Runner;


use ThomasNordahlDk\Tester\TestPackage;

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
