<?php

namespace ThomasNordahlDk\Tester\Runner;


use ThomasNordahlDk\Tester\TestSuite;

/**
 * Runs test test suites
 */
interface Runner
{
    /**
     * @param TestSuite[] $suites
     *
     * @throws FailedTestException
     */
    public function run(array $suites): void;
}
