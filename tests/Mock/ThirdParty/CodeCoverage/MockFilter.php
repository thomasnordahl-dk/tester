<?php

namespace Phlegmatic\Tester\Tests\Mock\ThirdParty\CodeCoverage;


use SebastianBergmann\CodeCoverage\Filter;

class MockFilter extends Filter
{
    private $whitelist = [];

    public function addDirectoryToWhitelist($directory, $suffix = '.php', $prefix = '')
    {
        $this->whitelist[] = $directory;
    }

    public function getWhitelist(): array
    {
        return $this->whitelist;
    }
}
