<?php

namespace Phlegmatic\Tester;

use Phlegmatic\Tester\Assertion\Tester;

/**
 * A TestCase uses a Tester to make assertions on code.
 */
interface TestCase
{
    /**
     * @return string The description of the test case, e.g. "Unit Test of User class"
     */
    public function getDescription(): string;

    /**
     * Run the test
     *
     * @param Tester $tester The tester to make assertions with.
     */
    public function run(Tester $tester): void;
}
