<?php

namespace Phlegmatic\Tester\Runner\Adapter\OutputResults;

use Exception;

/**
 * Assertion functions must throw this exception on
 * failed assertions
 */
class FailedAssertionException extends Exception {

}
