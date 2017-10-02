thomasnordahldk/tester
=================
*An object oriented approach to testing PHP code.*

## Installation
```
composer require --dev thomasnordahldk/tester
```

## Tests
A test is defined by a class that implements `TestCase`. 

```php
namespace Vendor\Project\Test;

use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Assertion\Tester;
use Vendor\Project\User;

class UserUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . User::class;
    }
    
    public function run(Tester $tester): string
    {
        $email = "john.doe@email.com";
        $user = new User($email);
        
        $tester->assert($user->getEmail() === $email, "getEmail() returns email");
        
        $tester->expect(\InvalidArgumentException::class, function() {
            new User("not valid");
        }, "Constructor throws on invalid email address");
    }
}

```

### `TestCase` 

`run(Tester $tester): void` - This method is where the tests are written. The `Tester` is used to
make assertions about the tested software / unit.

`getDescription(): string` - Should return a short description of the test case. 

## Running tests
Running tests is done by putting a file `test.php` in the root folder of the composer
project, that returns an array of test suites.
```php
use ThomasNordahlDk\Tester\TestSuite;
use Vendor\Project\Tests\Unit\UserUnitTest;
use Vendor\Project\Tests\Integration\UserRepositoryIntegrationTest;

$unit_tests = new TestSuite("Unit tests", [new UserUnitTest]);
$integration_tests = new TestSuite("Integration tests", [new UserRepositoryIntegrationTest]);

return [$unit_tests, $integration_tests];
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
the tests. The following arguments are available. Other output formats may be added later on.

#### Output clover report:

Outputs to `coverage.xml`:
```
~ bin/tester --coverage-xml
```
Outputs to custom file:
```
~ bin/tester --coverage-xml=filename.xml
```

#### Output coverage HTML report:
Outputs to directory `coverage`:
```
~ bin/tester --coverage-html
```
Outputs to custom directory:
```
~ bin/tester --coverage-html=custom/directory
```

### Coverage on custom path
```
~ bin/tester --coverage-html --coverage=cover/this/instead
```

## Inspiration
This library is inspired by the testing library [mindplay-dk/testies](https://github.com/mindplay-dk/testies).