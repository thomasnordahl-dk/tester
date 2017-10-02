<?php

namespace ThomasNordahlDk\Tester;

/**
 * Contains a list of test cases to perform under a common description.
 * A description of a suite could be "unit tests" or "acceptance tests"
 */
class TestSuite
{
    /**
     * @Var TestCase[] $test_case_list
     */
    private $test_case_list = [];

    /**
     * @var string $description
     */
    private $description = "";

    public function __construct(string $description, TestCase ...$test_case_list)
    {
        $this->description = $description;

        $this->test_case_list = $test_case_list;
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
}
