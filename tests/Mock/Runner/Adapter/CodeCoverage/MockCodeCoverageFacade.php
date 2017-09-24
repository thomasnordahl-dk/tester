<?php

namespace Phlegmatic\Tester\Tests\Mock\Runner\Adapter\CodeCoverage;


use Phlegmatic\Tester\Runner\Adapter\CodeCoverage\CodeCoverageFacade;
use Phlegmatic\Tester\Tests\Mock\ThirdParty\CodeCoverage\MockClover;
use Phlegmatic\Tester\Tests\Mock\ThirdParty\CodeCoverage\MockCodeCoverage;
use Phlegmatic\Tester\Tests\Mock\ThirdParty\CodeCoverage\MockFacade;
class MockCodeCoverageFacade extends CodeCoverageFacade
{
    private $xml_file;
    private $html_dir;
    private $stopped = false;
    private $id;

    public function __construct()
    {
        parent::__construct(new MockCodeCoverage(), new MockClover(), new MockFacade());
    }

    public function wasStarted(): bool
    {
        return $this->id != null;
    }

    public function getXmlFile()
    {
        return $this->xml_file;
    }

    public function getHtmlDir()
    {
        return $this->html_dir;
    }

    public function wasStopped(): bool
    {
        return $this->stopped;
    }

    public function getId()
    {
        return $this->id;
    }

    public function start($id): void
    {
        $this->id = $id;
    }

    public function stop(): void
    {
        $this->stopped = true;
    }

    public function outputHtml(string $directory): void
    {
        $this->html_dir = $directory;
    }

    public function outputXml(string $file_name): void
    {
        $this->xml_file = $file_name;
    }
}
