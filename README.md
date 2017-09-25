phlegmatic/tester
=================
*A simple and object oriented approach to testing PHP code.*

## Installation
```
composer require-dev phlegmatic/tester
```

## Tests
A test is defined by a class that implements `\Phlegmatic\Tester\TestCase`. 

```php
namespace \Vendor\Project\Test;

use \Phlegmatic\Tester\TestCase;
use \Phlegmatic\Tester\Assertion\Tester;

class UserUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . \User::class;
    }
    
    public function run(Tester $tester): string
    {
        $user = new User("john.doe@email.com");
        
        $tester->assert("john.doe@email.com" === $user->getEmail(), "getEmail() returns email");
        
        $tester->expect(\InvalidArgumentException::class, 
        function() {
            new User("not valid");
        }, 
        "Only valid email addresses allowed");
    }
}
```
The `TestCase` interface defines two methods: 

`run(Tester $tester): void`

The `run()` method is where the tests are written. The `Tester` implementation is used to
make assertions about the tested software / unit.

`getDescription(): string`

The `getDescription()` method should return an apt description of the test case performed
by the class.

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
~ bin/tester --coverage-xml
```
Outputs to custom file:
```
~ bin/tester --coverage-xml=custom-coverage-file.xml
```
#### Output coverage to html:
Outputs to folder `coverage`:
```
~ bin/tester --coverage-html
```
Outputs to custom folder:
```
~ bin/tester --coverage-html=custom-folder
```

## Inspiration
This library is inspired by the testing library [mindplay-dk/testies](https://github.com/mindplay-dk/testies).