<?php

namespace Phlegmatic\Tester\Runner;

use Exception;

/**
 * Runner::run() should throw this exception when
 * a test fails
 *
 * @see \Phlegmatic\Tester\Runner\Runner
 */
class FailedTestsException extends Exception
{
}
