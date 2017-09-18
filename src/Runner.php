<?php

namespace Phlegmatic\Tester;

use Phlegmatic\Tester\Exception\FailedTestsException;


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
