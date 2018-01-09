Custom Assertion Methods
===========================

The `Tester` interface only defines two basic assertion methods:

* `assert(bool $result, string $why): void`
* `expect(string $exception_type, callable $when, string $why): void`

This definition is very limited by design.

If more complex assertions are needed, the tester methods can be extended
by, for example, writing a Decorator to the Tester interface.

## Decorator classes
Assertion methods can be added by creating
a [Decorator](https://en.wikipedia.org/wiki/Decorator_pattern) 
class that "adds behaviour" in the form of one or more complex assertion types.

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

The decorator pattern tells us to implement `Tester`, but this is not strictly necessary if `assert()`
or `expect()` isn't needed in the test cases for some reasons. As long as the methods of the class
uses these methods to assert the result of the custom assertion.
 
The library aims to keep the definitions of what a test case is as minimalistic as possible, so extensions like
this are as simple to achieve as possible.

### Decorators provided by the library

### ComparisonTester
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

### ExpectedOutputTester
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
