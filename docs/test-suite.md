Test Suites
===========
Tests are normally divided into test suites, e.g. unit tests or acceptance tests.

A test suite is defined by creating an instance of the `TestSuite` class with
instances of the [TestCase class](creating-a-test-case.md).

```php
use ThomasNordahlDk\Tester\TestSuite;

$unit_tests = new TestSuite("Unit tests", [
    new UserUnitTest,
    new AddressUnitTest,
]);

$acceptance_tests = new TestSuite("Acceptance tests", [
    new CreateUserAcceptanceTest,
    new DeleteUserAcceptanceTest,
    new LoginAcceptanceTest,
    new ChangeAddressAcceptanceTest,
]);
```

When [running tests](how-to-run-tests.md) with the test runners
provided by the library tests are always gathered in suites like this.

However the `TestCase` interface is not dependent on the `TestSuite` interface,
so it should be possible customize a test runner to run test cases directly.

Check out the [how to run tests](how-to-run-tests.md) section for more on the subject. 