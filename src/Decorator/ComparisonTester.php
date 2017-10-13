<?php

namespace ThomasNordahlDk\Tester\Decorator;

use ReflectionClass;
use ReflectionProperty;
use ThomasNordahlDk\Tester\Tester;

/**
 * Decorates a Tester instance with the assertions assertSame and assertEqual
 *
 * @see ComparisonTester::assertSame()
 * @see ComparisonTester::assertEqual()
 */
class ComparisonTester implements Tester
{
    /**
     * @var Tester
     */
    private $tester;

    /**
     * @param Tester $tester The tester to decorate with extra assertion methods
     */
    public function __construct(Tester $tester)
    {
        $this->tester = $tester;
    }

    /**
     * This assertion is equivalent to the assertion:
     * assert($value === $expected, $why);
     *
     * @param mixed  $value    The value to test
     * @param mixed  $expected The expected value
     * @param string $why      The reason for making the assertion
     */
    public function assertSame($value, $expected, string $why): void
    {
        $this->assert($value === $expected, $why);
    }

    /**
     * This assertion is equivalent to the assertion:
     * assert($value == $expected, $why);
     *
     * @param mixed  $value    The value to test
     * @param mixed  $expected The expected value
     * @param string $why      The reason for making the assertion
     */
    public function assertEqual($value, $expected, string $why): void
    {
        $result = $this->compareEquality($value, $expected);

        $this->assert($result, $why);
    }

    /**
     * @inheritdoc
     */
    public function assert(bool $result, string $why): void
    {
        $this->tester->assert($result, $why);
    }

    /**
     * @inheritdoc
     */
    public function expect(string $exception_type, callable $when, string $why): void
    {
        $this->tester->expect($exception_type, $when, $why);
    }

    private function compareEquality($value, $expected): bool
    {
        if (is_array($value) && is_array($expected)) {
            return $this->compareArrayEquality($value, $expected);
        }

        if (is_object($value) && is_object($expected)) {
            return $this->compareObjectEquality($value, $expected);
        }

        return $value == $expected;
    }

    private function compareArrayEquality(array $value, array $expected): bool
    {
        foreach ($value as $key => $element) {
            if (! $this->compareEquality($element, @$expected[$key])) {
                return false;
            }
        }

        return true;
    }

    private function compareObjectEquality($value, $expected): bool
    {
        if (get_class($value) != get_class($expected)) {
            return false;
        }

        $value_public_properties = get_object_vars($value);
        $expected_public_properties = get_object_vars($expected);

        if (!$this->compareEquality($value_public_properties, $expected_public_properties)) {
            return false;
        }

        $reflection_class_value = new ReflectionClass($value);

        $is_protected = ReflectionProperty::IS_PROTECTED;
        $is_private = ReflectionProperty::IS_PRIVATE;

        $internal_properties = $reflection_class_value->getProperties($is_protected | $is_private);

        foreach ($internal_properties as $property) {
            $property->setAccessible(true);
            $property_value = $property->getValue($value);
            $expected_property_value = $property->getValue($expected);

            if (! $this->compareEquality($property_value, $expected_property_value)) {
                return false;
            }
        }

        return true;
    }
}
