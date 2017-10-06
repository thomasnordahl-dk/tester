Runners
=======
The `\ThomasNordahlDk\Tester\Runner\Runner` interface defines
the librarys native way of running the tests defined by
`TestSuite` and `TestCase`.

If you wish to just run the tests in the standard way as described in the 
[readme](../README.md), there is no need to read on. Knowing about how
 the runners work should only be necessary for those who wish to extend
the functionality of the library.

`RenderResultsRenderer` outputs the results of the tests to the 
output buffer.

To run the tests call the `run()` method with an array of test
packages.

```php
use \ThomasNordahlDk\Tester\Runner\Adapter\RenderResults\RenderResultRunner;

$runner = RenderResultsRunner::create();

$tests = new TestSuite("my tests", [
    new UserUnitTest,
]);

$runner->run([$tests]);

```

## CodeCoverageRunner
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
Admittedly this might be a bit of an "overkill" at such an early stage of the library.