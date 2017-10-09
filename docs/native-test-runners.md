Test Runners
============

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
    public function run(TestSuite ...$suites): void;
}
```

The library defines an implementation that outputs results and summaries
to the command line, and a decorator class that decorates other runners
with code coverage.

### OutputResultsRunner

`OutputResultsRunner` outputs the results of the tests to the 
command line (output buffer, more precisely).

The runner is created with an instance of the related `OutputResultsFactory`.

```php
$factory = new OutputResultsFactory();
// Or, for verbose output
$factory = new OutputResultsFactory(true);

$runner = $factory->createRunner();

$runner->run(... $test_suites);
```

## CodeCoverageRunner
The `CodeCoverageRunner` class decorates another runner with phpunit code coverage evaluation.

```php
$runner = $factory->createRunner();

$runner = new CodeCoverageRunner($runner, CoverageFacade::create("path/to/cover"));

$runner->outputsCloverToFile("coverage.xml");

$runner->run(... $suites);
```

Notice that the phpunit coverage classes are hidden behind a facade, so the runner does not
directly depend on phpunits code coverage classes, should you whish to replace the coverage
driver with another coverage library (if such a thing exists). 
Admittedly this might be a bit of an "overkill" at such an early stage of the librarys life time.