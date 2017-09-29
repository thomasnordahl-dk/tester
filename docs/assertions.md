Assertions
==========
Assertions are done through the `ThomasNordahlDk\Tester\Assertion\Tester` interface. The interface
defines two methods:
* `assert(bool $result, string $why): void`
* `expect(string $exception_type, callable $when, string $why): void`

## Decorators
One way of defining custom assertions for complex test cases is to define a decorator
class to the `Tester` interface.

### Comparative assertions
The library defines a decorator for those more comfortable with the phpunit semantics of
comparative assertions, `ComparisonTester`.

```php
public function run(Tester $tester): void
{
    $tester = new ComparisonTester($tester);
    
    $user = new User("john.doe@email.com");
    
    $user_repository->add($user);
    
    $tester->assertSame($user->getEmail(), "john.doe@email", "User should return assigned email");
    $tester->assertEqual($user->isGuestUser(), true, "new user should return thruthy for isGuestUser()");
}
```

### Assertions on output
The library defines a decorator, `ExpectedOutputTester`, for testing the output to the output buffer.

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

The intention is to deliver a test structure that allows you to easily make extensions to the
functionality of the library. Check out the implementations of the decorators above to see
how simple it can be to make custom assertion methods of your own.

Have fun!