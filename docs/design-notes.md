Design Notes
============

## No Meta Code
No feature of the `thomasnordahldk/tester` library should
rely on "meta code".

Meta code is defined as any functionality that is defined
or configured by anything other than PHP code.

Examples of this are XML configuration files, 
assigning functionality by docblock annotations,
or defining tests through class names or method names.

Defining what functions to run and in what order to run them
is what we have code for. By defining all aspects through interfaces and composition
the definitions are opened up for extension in all aspects
of applying the tests.

## Minimum effort
The goal is to keep the definition and provided functionality
at a minimum. This is in order to free up the developer to
customize tests to the specific requirements of the package to
test or the preferred way of making assertions.


## Framework integration
Extensions or integration with common framework can be
provided, but should always be placed in dedicated libraries
defining that integration or extension.

This serves both to keep the tester library small and simple,
and making sure that anyone who needs to integrate tests with framework
X shouldn't be bothered with the code for integrating with
framework Y or Z cluttering up the dependencies.



