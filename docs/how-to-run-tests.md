How To Run Tests
================

Tests can be run by creating a simple test file that defines which tests to run, and
calling the provided binary test runner.

It is also possible to create custom test scripts if, for example, the default runner
does not meet the requirements of your [CI server](https://en.wikipedia.org/wiki/Continuous_integration).

## Setting up the tests
The standard [command line scripts](#using-the-command-line-script) will look for a file named `test.php` in the current working directory.

The `test.php` should contain a php script that returns an array of [TestSuite](test-suite.md) instances.

```php
# test.php
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

return [$unit_tests, $acceptance_tests];
```

## Using the command line script
The standard way of running the tests is to call the tester script located in the
composer `bin` folder. If nothing else is specified, the `bin` folder is located at `vendor/bin/`
in the composer-root-dir.

```
~/composer-package-root$ vendor/bin/tester

---------------------------------------------------------------------------
 - Unit tests - cases: 2
 --- Unit test of User ✔
 --- Unit test of Address ✔
---------------------------------------------------------------------------
success: 2, failure: 0, assertions: 8, time: 0.04s
---------------------------------------------------------------------------

---------------------------------------------------------------------------
 - Acceptance tests - cases 4
 --- Creating a new user ✔
 --- Deleting a user ✔
 --- A user logs in ✔
 --- A user changes address ✔
---------------------------------------------------------------------------
success: 4, failure: 0, assertions: 8, time: 0.32s
---------------------------------------------------------------------------

~/composer-package-root$ vendor/bin/tester
```

### Arguments to the script

The script can be called with a number of arguments.

**Use a different test suite file:**
```
~ vendor/bin/tester --file=tests/test.php
```

**Output coverage HTML report to `coverage/`:**
```
~ vendor/bin/tester --coverage-html
```

**Output coverage HTML report to `dir/`:**
```
~ vendor/bin/tester --coverage-html=dir
```

**Output coverage clover report to `coverage.xml`:**

```
~ vendor/bin/tester --coverage-clover
```

**Output coverage clover report to `file.xml`:**
```
~ vendor/bin/tester --coverage-clover=file.xml
```

**Coverage is reported on custom directory:**

*(The default folder to cover is `src/`)*

```
~ vendor/bin/tester --coverage-clover --cover=dir
```

**Coverage is reported on custom directories:**
```
~ vendor/bin/tester --coverage-clover --cover=dir1,dir2,dir3
```

--

