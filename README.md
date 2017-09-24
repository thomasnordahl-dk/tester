Tester
======
*A simple and object oriented approach to testing for PHP.*

**This library is still in its early stage of development.**

## Installation
```
composer require-dev phlegmatic/tester
```

## Tests
A test is defined by a class that implements `\Phlegmatic\Tester\TestCase`. The interface
defines two methods: 
* `getDescription(): string`
* `run(\Phlegmatic\Tester\Tester $tester): void`

The `getDescription()` method should return an apt description of the test case performed
by the class, when `run()` is invoked.

Example:
```php
class UserUnitTest implements \Phlegmatic\Tester\TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . \User::class;
    }
    
    public function run(\Phlegmatic\Tester\Tester $tester): string
    {
        $user = new User("john.doe@email.com");
        
        $tester->assert("john.doe@email.com" === $user->getEmail(), "User::getEmail() returns assigned email");
        
        $tester->expect(\InvalidArgumentException::class, function() {
            new User("invalid email address");
        }, 
        "Only valid emails allowed as constructor argument");
    }
}
```

## Assertions
Assertions are done through the `\Phlegmatic\Tester\Tester` interface. The interface
defines two methods:
* `assert(bool $result, string $why): void`
* `expect(string $exception_type, callable $when, string $why): void`

## Packages
A package defines a collection of related test cases. An example could be unit test package, or acceptance test package.

```php
$package = new \Phlegmatic\Tester\TestPackage("Unit tests", $test_case_list);
```

Packages are run through a runner, defined by `Phlegmatic\Tester\Runner`. A runner has one method:
```php
/**
 * @param TestPackage[] $package_list
 *
 * @throws \Phlegmatic\Tester\Exception\FailedTestException
 */
public function run($package_list): void;
```

An instance of the currently available implementation of the runner interface `Phlegmatic\Tester\Adapter\OutputResultRunner`
can be created with the static factory method `RunnerFactory::createDefault(): Runner`.

## Test file
In the following example the unit test defined above is run through a unit test package,
assuming that the `phlegmatic/tester` is located in the default vendor folder structure,
when using Composer to install the library.

```php
# test.php
require_once __DIR__ . "/vendor/autoload.php";

use \Phlegmatic\Tester\Factory\RunnerFactory;
use \Phlegmatic\Tester\TestPackage;
use \Phlegmatic\Tester\Exception\FailedTestException;
use \User\Test\UserUnitTest;

$unit_tests = new TestPackage("Unit tests", [new UserUnitTest()]);

$runner = RunnerFactory::createDefault();

try {
    $runner->run([$package]);
    exit(0);
} catch (FailedTestException $e) {
    exit(1);
}
```

## Decorators
One way of defining custom assertions for complex test cases is to define a decorator
class to the `Tester` interface.

The library defines a decorator, `\Phlegmatic\Tester\Helper\ExpectedOutputTester`, 
for testing the output to output buffer.

```php
public function run(Tester $tester): void
{
    $tester = new ExpectedOutputTester($tester);
    
    $tester->expectOutput("This is output with a variable", function () {
        $template_renderer = new TemplateRenderer();
        $template_renderer->render("This is output with a [variable]", ["variable" => "variable"]);
    }, 
    "The template renderer should replace variables with values in associative array");
}
```

## Testable and tested
All classes are unit tested. The tests can be found in the `test/` folder, and they
are executed by the file `/bin/tester`.

The libraries units are tested using the libraries own testing facilities. Refer to these
test cases for further usage examples.

## Inspiration
This library is inspired by the testing library [mindplay-dk/testies](https://github.com/mindplay-dk/testies).