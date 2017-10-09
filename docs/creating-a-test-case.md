Creating a test case
====================
Test cases are created by making a new class that implements the `TestCase` 
interface.

## Creating a test case
An example of a test case could be a unit test of a user class.

### Create the test case class
First a new class is created:
```php
<?php
namespace Vendor\Package\Test\Unit;

use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Assertion\Tester;

class UserUnitTest implements TestCase
{
    public function getDescription(): string
    {
        //TODO return description of test case
    }
    public function run(Tester $tester): void
    {
        //TODO run test
    }
}
```
#### The TestCase interface
The interface defines the two methods that define a test case, a method for retrieving the test case description, and
a method for running the tests.

There is no naming convention for the test classes. Feel free to name and compose your classes as needed.

## Writing the test
### Description
Implement the getDescription method, by returning a short and concise description of the test.

As such there is no restriction of the string length, but the description is meant to be presented in
test summaries, so keep this in mind.

```php
<?php
namespace Vendor\Package\Test\Unit;

use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Assertion\Tester;
use Vender\Package\Model\User;

class UserUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . User::class;
    }
    public function run(Tester $tester): void
    {
        //TODO run test
    }
}
```
### Implementing test
The `Tester` interface is kept very short and clean in order to make the library
minimalistic and extensible. There are only two methods: `assert()`, and `expect()`.

```php
<?php
namespace Vendor\Package\Test\Unit;

use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Assertion\Tester;
use Vender\Package\Model\User;
use \InvalidArgumentException;

class UserUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . User::class;
    }
    public function run(Tester $tester): void
    {
        $email = "john.doe@email.com";
        $username = "johndoe";
        
        $user = new User($email, $username);
        
        $tester->assert($user->getEmail() === $email, "getEmail() returns the correct email");
        $tester->assert($user->getUserName() === $username, "getUserName() returns the correct name");
        $tester->assert($user->isGuest() == true, "isGuest() returns thruthy value for new user");
        
        $tester->expect(InvalidArgumentException::class, function() {
            new User("invalid email");
        }, "The constructor throws invalid argument exception on invalid email address");
    }
}
```

#### Assert method:
The assert method of the tester takes a boolean as its first argument that should be true if the test was 
successfull and false otherwise. This enables comparing the output values of a class with the expected 
values, by performing comparison and using the result as input. 

The second argument is a description of the behaviour of the class that is tested, or in other words, 
*the reason why the the assertion was made.*

**This signature can be read semantically:**

---
**Assert** that the **Result is true**, and **"This is the reason *why* the assertion was made"**.

```php
assert(bool $result, string $why): void
```

#### Expect method:
The assert method takes 3 arguments.

The first argument is a string containing a specific `Exception` subtype class name.

The second argument is a closure function that performs a piece of code with the class being tested.
This function is expected to cause an instance of the exception type given as the first argument to be thrown.

The third argument is again a reason for expecting that specific exception type.

**This signature can be read semantically:**

---
**Expect** this **Exception type**, **When this happens**, and **"This is the reason *why* it's expected"**.

```php
expect(string $exception_type, callable $when, string $why): void;
```
---

## More assertion methods
You may find these two methods of assertion limiting, but this is in tune
with the [testing philosophy](testing-philosohpy.md) of the library.

If you need more specific assertion methods, checkout the documentation page
on [custom assertion methods](custom-assertion-methods.md).
