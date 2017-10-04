<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\OutputResults;


use ThomasNordahlDk\Tester\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\OutputResultsFactory;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestCase\TestCaseRunner;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\Assertion\OutputResultsTester;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\TestSuite\TestSuiteRunner;
use ThomasNordahlDk\Tester\TestCase;

class OutputResultsFactoryUnitTest implements TestCase
{
    /**
     * @var ComparisonTester
     */
    private $tester;

    public function getDescription(): string
    {
        return "Unit test of " . OutputResultsFactory::class;
    }

    public function run(Tester $tester): void
    {
        $this->tester = new ComparisonTester($tester);

        $this->testCreateTesterMethod();
        $this->testCreateTestCaseRunnerMethod();
        $this->testCreateTestSuiteRunnerMethod();
    }

    private function testCreateTesterMethod(): void
    {
        $tester = $this->tester;

        $factory = new OutputResultsFactory();
        $expected = new OutputResultsTester();

        $tester->assertEqual($factory->createTester(), $expected,
            "createTester creates new instance of OutputResultsTester");

        $factory = new OutputResultsFactory(true);
        $expected = new OutputResultsTester(true);

        $tester->assertEqual($factory->createTester(), $expected,
            "createTester creates new verbose OutputResultsTester when verbose");
    }

    private function testCreateTestCaseRunnerMethod(): void
    {
        $tester = $this->tester;

        $factory = new OutputResultsFactory();
        $output_results_tester = $factory->createTester();
        $expected = new TestCaseRunner($output_results_tester);

        $tester->assertEqual($factory->createTestCaseRunner($output_results_tester), $expected,
            "createTestCaseRunner creates new TestCaseRunner");


        $factory = new OutputResultsFactory(true);
        $output_results_tester = $factory->createTester();
        $expected = new TestCaseRunner($output_results_tester, true);

        $tester->assertEqual($factory->createTestCaseRunner($output_results_tester), $expected,
            "createTestCaseRunner creates new verbose TestCaseRunner when verbose");
    }

    private function testCreateTestSuiteRunnerMethod(): void
    {
        $tester = $this->tester;

        $factory = new OutputResultsFactory();
        $expected = new TestSuiteRunner($factory);

        $tester->assertEqual($factory->createTestSuiteRunner(), $expected, "Creates new test suite runner");
    }
}
