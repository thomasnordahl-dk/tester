<?php

namespace ThomasNordahlDk\Tester\Runner\Timer;

use RuntimeException;

/**
 * Used to measure time passed in microseconds.
 */
class Timer
{
    /**
     * @var float
     */
    private $start_time = 0.0;

    /**
     * @var float
     */
    private $stop_time = 0.0;

    /**
     * @var bool
     */
    private $started = false;

    /**
     * Start the timer
     *
     * @throws RuntimeException if the timer has already been started
     */
    public function start(): void
    {
        if ($this->started) {
            throw new RuntimeException("Timer was already started!");
        }

        $this->start_time = microtime(true);
        $this->started = true;
    }

    /**
     * Stop the timer
     *
     * @return float the time passed in microseconds;
     *
     * @throws RuntimeException if the timer isn't started
     */
    public function stop(): float
    {
        if (! $this->started) {
            throw new RuntimeException("Timer was stopped before it was started!");
        }

        $this->stop_time = microtime(true);
        $this->started = false;

        return $this->stop_time - $this->start_time;
    }

    /**
     * If the timer is stopped the returned value is the time passed between start() and stop()
     *
     * If called when the timer is started, the method returns the currently passed time.
     *
     * @return float the time passed between start and stop in microseconds
     */
    public function getTimePassed(): float
    {
        if ($this->started) {
            return microtime(true) - $this->start_time;
        } else {
            return $this->stop_time - $this->start_time;
        }
    }
}
