<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\OutputResults;


use ThomasNordahlDk\Tester\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Tester;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\OutputResultsFactory;
use ThomasNordahlDk\Tester\Runner\Adapter\OutputResults\OutputResultsRunner;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Tests\Mock\MockTestCase;
use ThomasNordahlDk\Tester\Tests\Mock\Runner\Adapter\OutputResults\MockOutputResultsFactory;
use ThomasNordahlDk\Tester\TestSuite;

class OutputResultsRunnerUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . OutputResultsRunner::class;
    }

    public function run(Tester $tester): void
    {
        $tester = new ComparisonTester($tester);

        $mock_render_results_factory = new MockOutputResultsFactory();
        $mock_suite_runner = $mock_render_results_factory->getMockTestSuiteRunner();

        $test_case_list = [
            new MockTestCase("case1", function () {
            }),
            new MockTestCase("case2", function () {
            }),
        ];
        $suite_1 = new TestSuite("description1", ... $test_case_list);
        $suite_2 = new TestSuite("description2", ... $test_case_list);

        $expected = [$suite_1, $suite_2];

        $runner = new OutputResultsRunner($mock_render_results_factory);

        $runner->run($expected);

        $tester->assertSame($mock_suite_runner->getTestSuites(), $expected,
            "Uses test suite runner from factory to run suites");

        $expected = new OutputResultsRunner(new OutputResultsFactory());
        $tester->assertEqual(OutputResultsRunner::create(), $expected,
            "static factory method creates the expected runner");


        $expected = new OutputResultsRunner(new OutputResultsFactory(true));
        $tester->assertEqual(OutputResultsRunner::create(true), $expected,
            "static factory method creates the expected verbose runner");
    }
}
