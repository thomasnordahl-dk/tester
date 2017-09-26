<?php

namespace ThomasNordahlDk\Tester\Tests\Unit\Runner\Adapter\CodeCoverage;


use ThomasNordahlDk\Tester\Assertion\Decorator\ComparisonTester;
use ThomasNordahlDk\Tester\Runner\Adapter\CodeCoverage\CodeCoverageFacade;
use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Assertion\Tester;
use ThomasNordahlDk\Tester\Tests\Mock\ThirdParty\CodeCoverage\MockCodeCoverage;
use ThomasNordahlDk\Tester\Tests\Mock\ThirdParty\CodeCoverage\MockClover;
use ThomasNordahlDk\Tester\Tests\Mock\ThirdParty\CodeCoverage\MockFacade;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Report\Clover;
use SebastianBergmann\CodeCoverage\Report\Html\Facade;

class CodeCoverageFacadeUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . CodeCoverageFacade::class;
    }

    public function run(Tester $tester): void
    {
        $tester = new ComparisonTester($tester);

        $coverage = new CodeCoverage();
        $coverage->filter()->addDirectoryToWhitelist("path");
        $expected = new CodeCoverageFacade($coverage, new Clover(), new Facade());

        $tester->assertEqual(CodeCoverageFacade::create("path"), $expected, "create method creates facade");

        $xml_file = "/my/special/coverage.xml";
        $html_directory = "/my/special/coverage";

        $coverage = new MockCodeCoverage();
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
}
