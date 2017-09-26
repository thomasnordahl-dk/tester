Test Packages
=============

Tests are gathered in `TestPackage` instances with a description of the package.

```php
$unit_test = new Package("Unit tests", [
    new UserUnitTest,
    new AddressUnitTest,
    new UserRepositoryUnitTest,
]);

```
