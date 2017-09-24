<?php

namespace Phlegmatic\Tester\Tests\Mock\ThirdParty\CodeCoverage;


use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Report\Clover;

class MockClover extends Clover
{
    private $target;

    private $coverage;

    public function process(CodeCoverage $coverage, $target = null, $name = null)
    {
        $this->coverage = $coverage;
        $this->target = $target;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function getCoverage(): CodeCoverage
    {
        return $this->coverage;
    }
}
