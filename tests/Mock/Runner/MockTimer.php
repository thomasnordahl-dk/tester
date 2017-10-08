<?php

namespace ThomasNordahlDk\Tester\Tests\Mock\Runner;

use ThomasNordahlDk\Tester\Runner\Timer;

class MockTimer extends Timer
{
    public function stop(): float
    {
        parent::stop();

        return 1.23;
    }

    public function getTimePassed(): float
    {
        return 1.23;
    }
}
