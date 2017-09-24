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
namespace \Vendor\Project\Test;

use \Phlegmatic\Tester\TestCase;
use \Phlegmatic\Tester\Tester;

class UserUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . \User::class;
    }
    
    public function run(Tester $tester): string
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

## Packages
A package defines a collection of related test cases. An example could be unit test package, or acceptance test package.

```php
$package = new \Phlegmatic\Tester\TestPackage("Unit tests", $test_case_list);
```

## Running tests
Running tests is done by putting a file `test.php` in the root folder of the composer
project, that returns an array of testpackages.
```php
use \Phlegmatic\Tester\TestPackage;
use \Vendor\Project\Tests\UserUnitTest;

$unit_tests = new TestPackage("Unit tests", [new UserUnitTest]);

return [$unit_tests];
```

Tests are then invoked by calling the binary `bin/tester`;
```
~ bin/tester
```

For verbose output:
```
~ bin/tester -v
``` 

### Code coverage
The library utilizes the PHPUnit Code Coverage package to create code coverage reports for 
the tests.

#### Output coverage to xml:

Outputs to `coverage.xml`:
```
~ vendor/bin/tester --coverage-xml
```
Outputs to custom file:
```
~ vendor/bin/tester --coverage-xml=custom-coverage-file.xml
```
#### Output coverage to html:
Outputs to folder `coverage`:
```
~ vendor/bin/tester --coverage-html
```
Outputs to custom folder:
```
~ vendor/bin/tester --coverage-html=custom-folder
```

- ***Depending on a phpunit for code coverage is subject to change***

## Assertions
Assertions are done through the `\Phlegmatic\Tester\Assertion\Tester` interface. The interface
defines two methods:
* `assert(bool $result, string $why): void`
* `expect(string $exception_type, callable $when, string $why): void`

### Decorators
One way of defining custom assertions for complex test cases is to define a decorator
class to the `Tester` interface.

The library defines a decorator, `\Phlegmatic\Tester\Assertion\Decorator\ExpectedOutputTester`, 
for testing the output to output buffer.

```php
public function run(Tester $tester): void
{
    $tester = new ExpectedOutputTester($tester);
    
    $tester->expectOutput("This is output with a variable", function () {
        $template_renderer = new TemplateRenderer();
        $template_renderer->render("This is output with a [name]", ["name" => "variable"]);
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