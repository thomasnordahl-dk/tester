<?php

namespace Phlegmatic\Tester\Tests\Unit\Runner\Factory;


use Phlegmatic\Tester\Assertion\Tester;
use Phlegmatic\Tester\Runner\Factory\RunnerFactory;
use Phlegmatic\Tester\Runner\Runner;
use Phlegmatic\Tester\TestCase;

class RunnerFactoryUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . RunnerFactory::class;
    }

    public function run(Tester $tester): void
    {
        $factory = new RunnerFactory();

        $tester->assert($factory->create() instanceof Runner, "Factory should create runner instance");
        $tester->assert($factory->createVerbose() instanceof Runner, "Factory should create runner instance");
    }
}
