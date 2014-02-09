name = "eval()";
description = "eval() allows the compilation and execution of PHP code at runtime. It takes a string, and then run the code.

The number of problems linked to this function is important : 
+ Security problems, when some external variables are included in the executed PHP code
+ Caching problems, as this code won't be caught by APC or similar code cache
+ Performances, as it is much more efficient to have the code compiled by PHP before running, that at runtime.

It is usually possible to replace that kind of code by some permanant one, given the dynamic nature of PHP. ";
