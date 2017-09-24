<?php

namespace Phlegmatic\Tester\Tests\Unit\Runner\Adapter\CodeCoverage;


use Phlegmatic\Tester\Runner\Adapter\CodeCoverage\CodeCoverageFacade;
use Phlegmatic\Tester\TestCase;
use Phlegmatic\Tester\Assertion\Tester;
use Phlegmatic\Tester\Tests\Mock\ThirdParty\CodeCoverage\MockCodeCoverage;
use Phlegmatic\Tester\Tests\Mock\ThirdParty\CodeCoverage\MockFilter;
use Phlegmatic\Tester\Tests\Mock\ThirdParty\CodeCoverage\MockClover;
use Phlegmatic\Tester\Tests\Mock\ThirdParty\CodeCoverage\MockFacade;

class CodeCoverageFacadeUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . CodeCoverageFacade::class;
    }

    public function run(Tester $tester): void
    {
        $xml_file = "/my/special/coverage.xml";
        $html_directory = "/my/special/coverage";

        if (file_exists($html_directory)) {
            self::removeDirectoryRecursive($html_directory);
        }

        if (file_exists($xml_file)) {
            unlink($xml_file);
        }

        $coverage = new MockCodeCoverage();
        $filter = new MockFilter();
        $coverage->setFilter($filter);
        $xml_writer = new MockClover();
        $html_writer = new MockFacade();

        $facade = new CodeCoverageFacade($coverage, $xml_writer, $html_writer);

        $facade->start("facadetest");

        $facade->stop();

        $facade->outputXml($xml_file);

        $facade->outputHtml($html_directory);

        $tester->assert($coverage->wasStartedWithId() === "facadetest", "must start coverage with name as id");
        $tester->assert($coverage->isStopCalled(), "must stop coverage");
        $tester->assert($xml_writer->getCoverage() === $coverage, "xml writer processed with mock coverage");
        $tester->assert($xml_writer->getTarget() === $xml_file, "xml writer processed with xml file");
        $tester->assert($html_writer->getCoverage() === $coverage, "html writer processed with mock coverage");
        $tester->assert($html_writer->getTarget() === $html_directory, "html writer processed with directory");
    }

    private static function removeDirectoryRecursive($dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? self::removeDirectoryRecursive("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }
}
