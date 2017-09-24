<?php

namespace Phlegmatic\Tester\Exception;

use Exception;

/**
 * Runner::run() should throw this exception when
 * a test fails
 *
 * @see \Phlegmatic\Tester\Runner
 */
class FailedTestsException extends Exception
{

}
