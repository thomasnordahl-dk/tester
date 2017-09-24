<?php

namespace Phlegmatic\Tester\Exception;

use Exception;

/**
 * Assertion functions must throw this exception on
 * failed assertions
 */
class FailedAssertionException extends Exception {

}
