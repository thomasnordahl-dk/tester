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

    /**
     * @param string     $description
     * @param TestCase[] $test_cases
     */
    public function __construct(string $description, array $test_cases)
    {
        $this->description = $description;

        foreach ($test_cases as $test_case) {
            $this->addTestCase($test_case);
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

    private function addTestCase(TestCase $test_case): void
    {
        $this->test_case_list[] = $test_case;
    }
}
