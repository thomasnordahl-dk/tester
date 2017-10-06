Testing Philosophy
==================

## Limited assertion methods
The philosophy of the assertions provided is that assertions can only assert on the state of classes from the public variables
and methods. No assertions should be made that evaluates the internal state of a class.

This promotes a testing style that focuses on testing *what* a class does, rather than *how* the class does what it does.
What a class does is the only behaviour that should matter if the tests are to help keep refactoring possible, without breaking
the expected behaviour of the class. 

So focus on whether the `getCount` method returns the right count, not on whether the internal `$counter` attribute 
has been updated.

However the library also tries to be extendable, so tests can be shaped to the needs of the specific test cases. 
See the section about extending assertion methods below for more on how to add custom assertions.
