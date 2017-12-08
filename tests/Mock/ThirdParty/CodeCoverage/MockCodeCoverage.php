<?php

namespace ThomasNordahlDk\Tester\Tests\Mock\ThirdParty\CodeCoverage;

use SebastianBergmann\CodeCoverage\CodeCoverage;

class MockCodeCoverage extends CodeCoverage
{

    private $stop_called = false;

    private $started = false;

    /**
     * @return bool
     */
    public function isStopCalled(): bool
    {
        return $this->stop_called;
    }

    public function isStartCalled(): bool
    {
        return $this->started;
    }

    public function start($id, $clear = false)
    {
        $this->started = true;
    }

    public function stop(
        $append = true,
        $linesToBeCovered = [],
        array $linesToBeUsed = [],
        $ignoreForceCoversAnnotation = false
    ) {

        $this->stop_called = true;
    }
}
