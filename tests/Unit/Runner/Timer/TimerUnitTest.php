<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Timer;


use RuntimeException;
use ThomasNordahlDk\Tester\Runner\Timer\Timer;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Tester;

class TimerUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . Timer::class;
    }

    public function run(Tester $tester): void
    {
        $timer = new Timer();

        $tester->expect(RuntimeException::class, function () use ($timer) {
            $timer->stop();
        }, "Timer must throw if stopped before started");

        $tester->assert($timer->getTimePassed() === 0.0,
            "New timer that hasn't been started says 0.0 seconds have passed");

        $timer->start();

        $tester->assert($timer->getTimePassed() > 0.0, "Time is passing");

        $intermediate_time = $timer->getTimePassed();

        $tester->expect(RuntimeException::class, function () use ($timer) {
            $timer->start();
        }, "Started timer cannot be started again");

        $tester->assert($timer->getTimePassed() > $intermediate_time, "Time keeps on passing");

        $time_passed = $timer->stop();

        $tester->assert($timer->getTimePassed() === $time_passed, "stop() returns time passed");
        $tester->assert($timer->getTimePassed() === $time_passed, "Stops measuring time after stop()");
    }
}
