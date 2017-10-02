Building a test
===============
Building a test from scratch can be summarized in 4 steps:

1. Create classes that implements `TestCase`.
2. Create a new file, `test.php` in the composer root directory.
3. In `test.php`, create instances of the test case classes
4. Return an array of `TestSuite` instances created with the test case instances.

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

---

#### Limited assertion methods
The philosophy of the assertions provided is that assertions can only assert on the state of classes from the public variables
and methods. No assertions should be made that evaluates the internal state of a class.

This promotes a testing style that focuses on testing *what* a class does, rather than *how* the class does what it does.
What a class does is the only behaviour that should matter if the tests are to help keep refactoring possible, without breaking
the expected behaviour of the class. So don't focus on whether the private counter is updated, instead focus on whether the `getCount` method returns the right count or not.

However the library also tries to be extendable, so tests can be shaped to the needs of the specific test cases. 
See the section about extending assertion methods below for more on how to add custom assertions.

## Running the test
### Creating a test file
The default location for the test file is the root directory of the composer package.
This file should be named `test.php`.

This file should return an array of test suites. In the case where we only have one test case, only one
suite is in the array returned.

```php
<?php
# test.php

use ThomasNordahlDk\Tester\TestSuite;
use Vendor\Package\Test\Unit\UserUnitTest;

$unit_test_suite = new TestPackage("Unit tests", [new UserUnitTest]);

return [$unit_test_suite];
```

As seen in the example, a test suite is defined by creating an instance of `TestSuite`.
The constructor takes two arguments:

1. The name of the suite
2. A list of `TestCase` instances

### Multiple cases and suites
A test suite can contain multiple cases and multiple suites can be returned;

```php
<?php
# test.php

use ThomasNordahlDk\Tester\TestSuite;
use Vendor\Package\Test\Unit\UserUnitTest;
use Vendor\Package\Test\Unit\AddressUnitTest;
use Vendor\Package\Test\Integration\UserRepositoryIntegrationTest;

$unit_test_suite = new TestSuite("Unit tests", [new UserUnitTest, new AddressUnitTest]);
$integration_test_suite = new TestSuite("Integration tests", [new UserRepositoryIntegrationTest]);

return [$unit_test_suite, $integration_test_suite];
```

From the command line interface, simply call the binary file provided by the library in the
bin directory defined in the `composer.json` of the project.

If no bin directory is defined in the JSON file, the default bin directory is `vendor/bin`.

```
~ vendor/bin/tester

Unit tests (2) -------------------------------------------------------------------------------------
Unit test of Vendor\Package\Model\User
Unit test of Vendor\Package\Model\Address

Success! 2 test, 7 assertions (0.04s)
----------------------------------------------------------------------------------------------------


Integration tests (1) ------------------------------------------------------------------------------
Integration test of Vendor\Package\Repository\UserRepository

Success! 1 test, 3 assertions (0.10s)
----------------------------------------------------------------------------------------------------


```

See the [readme](../README.md) for the different arguments that can be given to the tester.

## Extending assertion methods

Using the simple `Tester` interface as a base for making assertions, it is defined
that tests should always be able to be expressed in a way that evaluates to a boolean, or
expecting errors on certain sequences of operations.

This however doesn't mean that we can't add a layer of abstraction between the test case
and this boolean expression.

### Decorator Pattern
One way to do this is to create a decorator class, that adds assertion methods.

Let's say we wanted to make assertions on messages returned by expected exceptions.

We could create a decorator class called `ExceptionMessageTester`:

```php
class ExceptionMessageTester implements Tester
{
    private $tester;
    private $last_exception_message;
    
    public function __construct(Tester $tester): {
        $this->tester = $tester;
    }
    
    public function assert(bool $result, string $why): void
    {
        $this->tester->assert($result, $why);
    }
    
    public function expect(string $exception_type, closure $when, string why): void
    {
        $tester = $this->tester;
        
        $tester->expect($exception_type, function () use ($when) {
            try {
                $when;
            } catch (Exception $exception) {
                $this->last_exception_message = $exception->getMessage();
                throw $exception;
            }
        }, $why);
    }
    
    /**
     * Assert that the last expected exception returned the expected message.
     * 
     * @param string $message The expected message
     * @param string $why     The reason for performing the assertion
     */
    public function assertLastExceptionMessage(string $message, $why): void {
        $tester = $this->tester;
        
        $tester->assert($message === $this->last_exception_message, $why);
    }
}
```

We could then apply that decorator in a test case
```php
    //... UserUnitTest TestCase class
    public function run(Tester $tester): void
    {
        $tester = new ExceptionMessageTester($tester);
        
        $tester->expect(InvalidArgumentException, function () {
            new User("invalid-email");
        }, "Invalid email makes constructor throw exception");
        
        $tester->assertLastExceptionMessage("\"invalid-email\n is not a valid email address", 
            "The exception thrown on invalid email should have descriptive message");
    }
```

If one or both of the base assertions aren't needed for the tests following the
decorator pattern is not strictly necessary. If we chose to not implement `Tester` and the `assert()`
method, the class would more resemble an Adapter class, but would still be applicable to the test case
in the example.

The library aims to keep the definitions of what a test case is as minimalistic as possible, so extensions like
this are as simple to achieve as possible.

### Provided assertion extensions

#### Comparative assertions
The library provides a decorator: `ComparisonTester`, that provides two extra assertion methods,
`assertSame()` and `assertEqual()`.

The user class unit test example can be rewritten to use this decorator class:


```php
<?php
namespace Vendor\Package\Test\Unit;

use ThomasNordahlDk\Tester\TestCase;
use ThomasNordahlDk\Tester\Assertion\Tester;
use Vender\Package\Model\User;
use \InvalidArgumentException;
use ThomasNordahlDk\Tester\Assertion\Decorator\ComparisonTester;

class UserUnitTest implements TestCase
{
    public function getDescription(): string
    {
        return "Unit test of " . User::class;
    }
    public function run(Tester $tester): void
    {
        $tester = new ComparisonTester($tester);
        
        $email = "john.doe@email.com";
        $username = "johndoe";
        
        $user = new User($email, $username);
        
        $tester->assertSame($user->getEmail(), $email, "getEmail() returns the correct email");
        $tester->assertSame($user->getUserName(), $username, "getUserName() returns the correct name");
        $tester->assertEqual($user->isGuest(), true, "isGuest() returns truthy value on new user");
        
        $tester->expect(InvalidArgumentException::class, function() {
            new User("invalid email");
        }, "The constructor throws invalid argument exception on invalid email address");
    }
}
```

This decorator adds the assertions `assertSame` and `assertEqual`, that are equivalent to respectively 
strict and loose comparisons as known from phpunit. 

Internally the method simply calls the decorated class' assert method with a strict or loose comparison 
of the two values.

Being a decorator, `ComparisonTester` implements `Tester` and has the assert and expect method as well. 
The implementation simply delegates calls to these methods to the methods on the decorated tester.

### Assertions on output
The library also defines a decorator, `ExpectedOutputTester`, for testing the output to the output buffer.

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
