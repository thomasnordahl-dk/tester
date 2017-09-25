Runners
=======
The `\Phlegmatic\Tester\Runner\Runner` interface defines
the librarys native way of running the tests defined by
`TestPackage` and `TestCase`.

A simple output runner can be created using the `RunnerFactory`.

To run the tests call the `run()` method with an array of test
packages.

```php
use \Phlegmatic\Tester\Runner\Factory\RunnerFactory;

$factory = new RunnerFactory();

$runner = $factory->create();

$tests = new TestPackage("my tests", [
    new UserUnitTest,
]);

$runner->run([$tests]);

```

## Adapters
The library contains a few native runners for running the tests through
the command line interface.

### OutputResultsRunner
The `OutputResultsRunner` class runs testpackages and outputs the results to the
output buffer (command line).

Use the `RunnerFactory` to create instances of this runner
```php
$factory = new RunnerFactory();

$runner = $factory->create();

// or

$runner = $factory->createVerbose();
```

### CodeCoverageRunner
The `CodeCoverageRunner` class decorates another runner with phpunit code coverage evaluation.

```php
$runner = $factory->create();

$coverage = new CodeCoverage();

$filter = $coverage->filter();

$filter->addDirectoryToWhiteList("dir/to/cover");

$coverage_facade = new CodeCoverageFacade($coverage, new Clover(), new Facade());
$runner = new CodeCoverageRunner($runner, $coverage_facade);

$runner->run([$test_package]);
```

Notice that the phpunit coverage classes are hidden behind a facade, so the runner does not
directly depend on phpunits code coverage classes, should you whish to replace the coverage
driver with another coverage library (if such a thing exists).