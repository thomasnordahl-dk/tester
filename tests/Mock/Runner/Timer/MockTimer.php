<?php

namespace ThomasNordahlDk\Tester\Tests\Mock\Runner\Timer;

use ThomasNordahlDk\Tester\Runner\Timer\Timer;

class MockTimer extends Timer
{
    public function stop(): float
    {
        parent::stop();

        return 1.23;
    }
}
