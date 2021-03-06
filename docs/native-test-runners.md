Native Test Runners
===================

If you wish to just run the tests in the standard way as described in the 
[How to Run Tests guide](how-to-run-tests.md), there is no need to read on. Knowing about how
 the runners work should only be necessary for those who wish to extend
the functionality of the library.

## Runner interface
The `Runner` interface defines the librarys native way of running 
the the test classes as part of test suites.

```php
interface Runner
{
    /**
     * @var TestSuite[] $suites
     */ 
    public function run($suites): void;
}
```

The library defines an implementation that outputs results and summaries
to the command line, and a decorator class that decorates other runners
with code coverage.

### SimpleRunner

`SimpleRunner` outputs the results of the tests to the command line (the output buffer, to be precise).

```php
$runner = SimpleRunner::create();

$runner->run($test_suites);
```

## CodeCoverageRunner
The `CodeCoverageRunner` class decorates another runner with phpunit code coverage evaluation.

```php
$runner = $factory->createRunner();

$runner = new CodeCoverageRunner($runner, CoverageFacade::create("path/to/cover"));

$runner->outputsCloverToFile("coverage.xml");

$runner->run($suites);
```

Notice that the phpunit coverage classes are hidden behind a facade, so the runner does not
directly depend on phpunits code coverage classes, should you whish to replace the coverage
driver with another coverage library (if such a thing exists). 
Admittedly this might be a bit of an "overkill" at such an early stage of the librarys life time.

## Command Line Script
The command line script located in `dist/tester` utilizes the `CommandLineRunnerFactory`
and the `CommandLineArguments` to create a runner and fetch the suites from the
designated test file.

Check out the implementations for inspiration for ways to write custom test runner
scripts.