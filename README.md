phlegmatic/tester
=================
*A light-weight and object oriented PHP testing framework*

**The documentation is at a bare minimum for the first unstable version - more to come later!**

## `Tester`
Two basic assertion methods are defined by the `Tester` interface:
```php
    public function assert(bool $result, string $why): void;
```
```php
    public function expect(string $exception_type, callable $when, string $why): void;
```
You'll see these methods applied in the next section.

## `TestCase`
A test is defined by implementing the interface `\Phlegmatic\Tester\TestCase`.

```php
class UserUnitTest implements \Phlegmatic\Tester\TestCase
{
    public function getDescription(): string
    {
        return "Unit test of User class";
    }
    
    public function run(Tester $tester)
    {
        $user = User::create("john.doe@email.com");
        
        $tester->assert($user instanceof User, "Static factory method must return instance of User");
        $tester->assert($user->getEmail() === "john.doe@email.com", "Factory method sets email correctly");
        
        $tester->expect(\RuntimeException::class, function () {
            User::create("not a valid email address");
        }, "Using an invalid email address should cause factory method to throw a RuntimeException");
    }
}
```

## `TestPackage`
Test cases are collected into test packages with the class `TestPackage`.
```php
$unit_tests = [
    new UserUnitTest,
    new AddressUnitTest,
    new UserRepositoryUnitTest,
]
$unit_test_package = new TestPackage("Unit tests", $unit_tests);
```

## `Runner`
A `Runner` implementation runs test packages.
```php
$runner->run($unit_test_package);
```

## Output format
`phlegmatic/tester` runs its own tests. Currently the output of running these tests looks like this:
```
~/tester$ php test.php 
UNIT TESTS ***************************************
Unit Test of LogResultsTester class - Success!
Unit Test of Phlegmatic\Tester\Tests\Unit\OutputResultsTestRunnerUnitTest - Success!
Unit test of Phlegmatic\Tester\Helpers\OutputAssertionTester - Success!
**************************************************
3 tests passed successfully!
**************************************************

```

## Helpers
The library delivers a set of basic helpers. Look at the and get inspired to make your own decorators or helpers that
 allows you to assert that special case as you need.
 
`OutputAssertionsTester` is a decorator that allows to test output to the output buffer.

```
public function run(Tester $tester): void
{
    $output_tester = new OutputAssertionsTester($tester);
    
    $tester->assertOutput("This is the output", function () {
        echo "This is the output";
    }, "The function provided outputs the expected output.");
}
```

This decorator is applied in `Phlegmatic\Tester\Unit\OutputResultsRunnerUnitTest`.

## Bootstrapping
Plans are to somehow deliver easy bootstrapping for libraries using `phlegmatic\tester` as its testing framework.
In the current form you can simply create a new instance of the `OutputResultsRunner`.
```php
$runner = new OutputResultsRunner();
```
