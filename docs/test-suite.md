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