Design Principles
=================
## No meta-code
Functionality defined by the `phlegmatic/tester` library should **never** rely on meta code.

Meta code are configurations being written in yml or json structures, or doc blocks.

The tests should not rely on "magic" running of methods, i.e. invoking all public functions
in test classes, as some libraries do.

## Limited assertion methods
It is the belief of the author that testing should assert *what* a class or
function does, **not** *how* it does what it does. So the library will not
deliver any assertions based on code inspection, i.e. asserting the state
of a private variable or running a private method.

The library will not provide large "god" classes stuffed with different ways
of asserting that something is true.

The architecture should instead promote easy extension through composition.

## Simple interfaces
Adhering to both the Single Responsibility Principle, and the Interface Segregation Principle,
the interface definitions should be short and sweet. An interface with more than two public methods
should be considered subject to refactoring.

Internal classes, not exposed to test case classes, may relax this requirement at times, but
simplicity is always a priority.

## Small classes and methods
The Single Responsibility Principle also tells us that classes should be limited in size and
functions should do only one thing ("and do that thing well").

Naming should also be consistent and semantic. Any developer should be able to look at a
function or class in isolation and be able to get a general idea of the purpose of that 
function or class.

"Classes are nouns, and methods are verbs".

## SOLID
As you may have noticed from the sections above, the design tries to adhere to the
[SOLID principles](https://en.wikipedia.org/wiki/SOLID_(object-oriented_design)).

Hopefully this plan succeeds!