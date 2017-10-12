<?php

namespace ThomasNordahlDk\Tester\Tests\Mock\Runner\Timer;


use ThomasNordahlDk\Tester\Runner\Timer\Timer;
use ThomasNordahlDk\Tester\Runner\Timer\TimerFactory;

class MockTimerFactory extends TimerFactory
{
    public function create(): Timer
    {
        return new MockTimer();
    }
}
