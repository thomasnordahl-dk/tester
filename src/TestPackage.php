<?php

namespace Phlegmatic\Tester;

/**
 * Contains a list of test cases to perform under a common description.
 * A description a package could be "unit tests" or "acceptance tests"
 */
class TestPackage
{
    /**
     * @Var TestCase[] $test_case_list
     */
    private $test_case_list = [];

    /**
     * @var string $description
     */
    private $description = "";

    /**
     * @param string     $description
     * @param TestCase[] $test_case_list
     */
    public function __construct(string $description, $test_case_list)
    {
        $this->description = $description;

        foreach ($test_case_list as $test_case) {
            $this->addToList($test_case);
        }
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return TestCase[]
     */
    public function getTestCaseList()
    {
        return $this->test_case_list;
    }

    private function addToList(TestCase $test_case): void
    {
        $this->test_case_list[] = $test_case;
    }
}
