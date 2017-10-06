<?php

use ThomasNordahlDk\Tester\TestSuite;

/**
 * Returns the test suites returned in the test file.
 *
 * @param string $file_name the file name of the test file
 *
 * @return TestSuite[]
 * @throws RuntimeException
 */
function getTestSuites(string $file_name): array
{

    $filename = getcwd() . "/{$file_name}";

    if (file_exists($filename)) {
        return require $filename;
    }

    throw new RuntimeException("File not found: {$file_name}");
}