<?php

namespace ThomasNordahlDk\Tester\Runner\Timer;

/**
 * Creates instances of Timer
 *
 * @see Timer
 */
class TimerFactory
{
    public function create(): Timer
    {
        return new Timer();
    }
}
