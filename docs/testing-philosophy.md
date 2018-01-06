Testing Philosophy
==================

## Assertions are simple
In PHP asserting the validity of a class or function can be boiled down to two cases:

1. Confirming that the return values are as expected
2. Expecting the class/function to throw Exceptions invoked incorrectly.

## What is better than how
The philosophy of the assertions defined is that a test should confirm what a class does,
not how it does it. This means that tests should not test the internal state of a class.

This promotes a testing style that focuses on the confirming the external requirements 
of the test subject, rather than the internal state. This frees us up to refactor code 
internally without having to refactor tests. We don't want to refactor the tests 
everytime we refactor the internal structure of a class.

If you don't agree and still want to make assertions on the internal state of a class,
check out how to create [Custom Assertion Methods](custom-assertion-methods.md)
