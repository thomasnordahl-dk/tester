<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Timer;


use ThomasNordahlDk\Tester\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Runner\Timer\Timer;
use ThomasNordahlDk\Tester\Runner\Timer\TimerFactory;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Tester;

class TimerFactoryUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . TimerFactory::class;
    }

    public function run(Tester $tester): void
    {
        $tester = new ComparisonTester($tester);

        $factory = new TimerFactory();

        $expected = new Timer();

        $tester->assertEqual($factory->create(), $expected, "Factory creates instances of Timer");
    }
}
