<?php

namespace ThomasNordahlDk\Tester\Tests\Mock\ThirdParty\CodeCoverage;

use SebastianBergmann\CodeCoverage\CodeCoverage;

class MockCodeCoverage extends CodeCoverage
{

    private $stop_called = false;

    private $started_with_id = false;

    /**
     * @return bool
     */
    public function isStopCalled(): bool
    {
        return $this->stop_called;
    }

    public function wasStartedWithId()
    {
        return $this->started_with_id;
    }

    public function start($id, $clear = false)
    {
        $this->started_with_id = $id;
    }

    public function stop($append = true, $linesToBeCovered = [], array $linesToBeUsed = [])
    {
        $this->stop_called = true;
    }
}
