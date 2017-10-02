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
     * @throws FailedTestsException
     */
    public function run(array $suites): void;
}
