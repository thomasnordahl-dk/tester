<?php

namespace ThomasNordahlDk\Tester\Runner;

use Exception;

/**
 * Runner::run() should throw this exception when
 * a test fails
 *
 * @see \ThomasNordahlDk\Tester\Runner\Runner
 */
class FailedTestException extends Exception
{
}
