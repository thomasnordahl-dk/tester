thomasnordahldk/tester
=================

[![PHP Version](https://img.shields.io/badge/php-7.1%2B-blue.svg)](https://packagist.org/packages/thomasnordahldk/tester)
[![Latest Stable Version](https://poser.pugx.org/thomasnordahldk/tester/v/stable)](https://packagist.org/packages/thomasnordahldk/tester)
[![License](https://poser.pugx.org/thomasnordahldk/tester/license)](https://packagist.org/packages/thomasnordahldk/tester)
[![Build Status](https://travis-ci.org/thomasnordahl-dk/tester?branch=master)](https://travis-ci.org/thomasnordahl-dk/tester)

*An object oriented approach to testing PHP code.*

*Tester* aims to be: easy to learn, light weight, object oriented, and extensible.

[Documentation here](docs/index.md)

## Installation
The library is released as a composer package. 
```
composer require --dev thomasnordahldk/tester
```

## Tests

Tests are defined by creating `TestCase` classes.

```php
class MyTestCase implements TestCase
{
    public function getDescription(): string
    {
        return "My new unit test";
    }
    
    public function run(Tester $tester): void
    {
        $tester->assert(true, "This assertion passes!");
        $tester->assert(false, "This assertion fails!");
    }
}
```
The test case is defined as **a description of the test** and a **run test method**.

**Docs:** [Creating a test case](docs/creating-a-test-case.md).

## Test suites

Tests suites are defined by the `TestSuite` class which is created with a
description and an array of `TestCase` classes.

```php
$unit_tests = new TestSuite("Unit tests", [new UserUnitTest, AddressUnitTest]);
```

**Docs:** [Test Suites](docs/test-suite.md).

## Running tests

The library comes with a native test runner that is run from the command line interface, 
and outputs a summary of the test.

Which tests to run is defined in the file `test.php` in the root composer
directory. The file is expected to return an array of test suites.

```php
# test.php
$unit_tests = new TestSuite("Unit tests", [new UserUnitTest, new AddressUnitTest]);

return [$unit_tests];

```

```
$composer-root/~ bin/tester

---------------------------------------------------------------------------
 - Unit tests - cases: 2
 --- Unit test of User ✔
 --- Unit test of Address ✔
---------------------------------------------------------------------------
success: 2, failure: 0, assertions: 8, time: 0.04s
---------------------------------------------------------------------------

```

For a comprehensive description of the options available for the native test runner script:

**Docs:** [How to run tests](docs/how-to-run-tests.md).

## Inspiration
This library is inspired by the testing library [mindplay-dk/testies](https://github.com/mindplay-dk/testies).
