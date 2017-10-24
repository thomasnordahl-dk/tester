<?php

namespace ThomasNordahlDk\Tester\Runner\Adapter\Simple;

use ThomasNordahlDk\Tester\Runner\Runner;
use ThomasNordahlDk\Tester\Runner\Timer\TimerFactory;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\TestSuite;

class SimpleRunner implements Runner
{
    /**
     * @var string[]
     */
    private $failures = [];

    /**
     * @var TimerFactory
     */
    private $timer_factory;

    public function __construct(TimerFactory $timer_factory)
    {
        $this->timer_factory = $timer_factory;
    }

    public static function create(): SimpleRunner
    {
        return new static(new TimerFactory());
    }

    public function run($suites): void
    {
        $this->failures = [];

        foreach ($suites as $suite) {
            $this->runSuite($suite);
        }

        $failure_count = count($this->failures);

        if ($failure_count) {
            echo "\n{$failure_count} failed test(s):\n";
            echo implode("\n", $this->failures) . "\n";
        }
    }

    private function runSuite(TestSuite $suite): void
    {
        $test_cases = $suite->getTestCaseList();
        $description = $suite->getDescription();

        $success = 0;
        $failure = 0;
        $assertions = 0;

        $timer = $this->timer_factory->create();
        $timer->start();

        echo "\n";
        $this->echoBreaker();
        echo " - {$description} - cases: " . count($test_cases) . "\n";

        foreach ($test_cases as $test_case) {
            echo " --- " . $test_case->getDescription();
            $tester = new SimpleTester();

            try {
                $test_case->run($tester);
                $success++;
                echo " ✔\n";
            } catch (FailedAssertionException $exception) {
                $file_link = $this->getFileLink($exception);
                $message = $exception->getMessage();
                echo " ✖\n";
                $this->failures[] = "# {$file_link}\n{$message}\n";
                $failure++;
            } finally {
                $assertions += $tester->countSuccessfulAssertions();
            }

        }
        $time = number_format($timer->stop(), 2, ".", ",");

        $this->echoBreaker();
        echo "success: {$success}, failure: {$failure}, assertions: {$assertions}, time: {$time}s\n";
        $this->echoBreaker();
    }

    private function getFileLink(FailedAssertionException $exception): string
    {
        $trace = $exception->getTrace();
        $file_link = "";
        $prev_item = [];
        foreach ($trace as $item) {
            if (is_subclass_of($item['class'], TestCase::class)) {
                $file_link = "{$prev_item['file']}:{$prev_item['line']}";
                break;
            }
            $prev_item = $item;
        }

        return $file_link;
    }

    private function echoBreaker(): void
    {
        echo str_pad("", 75, "-") . "\n";
    }
}
