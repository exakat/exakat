.. _Rules:

Rules list
**********

###`$HTTP\_RAW\_POST\_DATA`

Starting at PHP 5.6, `$HTTP\_RAW\_POST\_DATA` will be deprecated, and should be replaced by php://input. You may get ready by setting always\_populate\_raw\_post\_data to -1.

This analyzer is part of the following recipes :  [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56), [Analyze](./Recipes.md#Analyze), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###`$this` Belongs To Classes

`$this` variable represents an object (the current object) and it should be used within class's methods (except for static) and not outside.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###`$this` is not an array

`$this` variable represents an object (the current object) and it is not an array, unless the class (or its parents) has the ArrayAccess interface.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###`$this` is not for static methods

`$this` variable represents an object (the current object) and it is not compatible with a static method, which may operate without any object.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-static-this.md">no-static-this</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###** for exponent

PHP 5.6 introduced the operator ** to provide exponents, instead of the slower function pow().

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55)




###... usage

Usage of the ... keyword, either in function definitions, either in functioncalls.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55)




###::class

PHP 5.5 introduced a special class constant, relying on the 'class' keyword. It will solve the classname that is used in the left part of the operator.

ClassName::class; // return Namespace\ClassName

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54)




###&lt;?= usage

Usage of the &lt;?= tag, that echo's directly the following content.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Abstract static methods

Methods cannot be both abstract and static. Static methods belong to a class, and will not be overridden by the child class. For normal methods, PHP will start at the object level, then go up the hierarchy to find the method. With static, you have to mention the name, or use Late Static Binding, with self or static. Hence, it is useless to have an abstract static method : it should be a simple static method.

A child class is able to declare a method with the same name than a static method in the parent, but those two methods will stay independant.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Access protected structures

It is not allowed to access protected properties or methods from outside the class or its relatives.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Accessing private

List of calls to private properties/methods that will compile but yield some fatal error upon execution.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Adding Zero

Adding 0 is useless. 

If it is used to type cast a value to integer, then casting (integer) is clearer. 

In (0 - `$x)` structures, 0 may be omitted.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-math.md">no-useless-math</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Aliases usage

Some functions have several names, and both may be used the same way. However, one of the names is the main name, and the others are aliases. Aliases may be removed or change or dropped in the future. Even if this is not forecast, it is good practice to use the main name, instead of the aliases.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-aliases.md">no-aliases</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Altering Foreach Without Reference

When using a foreach loop that modifies the original source, it is recommended to use referenced variables, rather than access the original value with `$source[$index].` 

Using references is then must faster, and easier to read. 

foreach(`$source` as `$key` => &amp;`$value)` {
    `$value` = newValue(`$value,` `$key);`
}

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/use-reference-to-alter-in-foreach.md">use-reference-to-alter-in-foreach</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Ambiguous Index

List of all indexes that are defined in the same array, with different types. 

Example : `$x[1]` = 1; `$x['1']` = 2; 

They are indeed distinct, but may lead to confusion.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Anonymous Classes

Mark anonymous classes.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56)




###Argument should be typehinted

When a method expects objects as argument, those arguments should be typehinted, so as to provide early warning that a wrong object is being sent to the method.

The analyzer will detect situations where a class, or the keywords 'array' or 'callable'. 

Closure arguments are omitted.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/always-typehint.md">always-typehint</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Assign Default To Properties

Properties may be assigned default values at declaration time. Such values may be later modified, if needed. 

Default values will save some instructions in the constructor, and makes the value obvious in the code.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/use-properties-default-values.md">use-properties-default-values</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Avoid Parenthesis

Avoid Parenthesis for language construct. Languages constructs are a few PHP native elements, that looks like functions but are not. 

Among other distinction, those elements cannot be directly used as variable function call, and they may be used with or without parenthesis.

The usage of parenthesis actually give some feeling of confort, it won't prevent PHP from combining those argument with any later operators, leading to unexpected results.

Even if most of the time, usage of parenthesis is legit, it is recommended to avoid them.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Avoid Those Crypto

The following cryptographic algorithms are considered unsecure, and should be replaced with new and more performant algorithms. 

MD2, MD4, MD5, SHA0, SHA1, CRC, DES, 3DES, RC2, RC4. 

When possible, avoid using them, may it be as PHP functions, or hashing function configurations (mcrypt, hash...).

This analyzer is part of the following recipes :  [Security](./Recipes.md#Security)




###Avoid array\_unique

The native function array\_unique is much slower than using other alternative, such as array\_count\_values(), array\_flip/array\_keys, or even a foreach() loops.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Binary Glossary

List of all the integer values using the binary format, such as 0b10 or 0B0101.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53)




###Break Outside Loop

Starting with PHP 7, breaks or continue that are outside a loop (for, foreach, do...while, while) or a switch() statement won't compile anymore.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###Break With 0

Cannot break 0, as this makes no sense. Break 1 is the minimum, and is the default value.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53)




###Break With Non Integer

When using a break, the argument of the operator should be a positive non-null integer, and nothing else.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###Buried Assignation

Those assignations are buried in the code, and placed in unexpected situations. They will be difficult to spot, and may be confusing. It is advised to place them in a more visible place.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Calltime Pass By Reference

PHP doesn't like anymore when the value is turned into a reference at the moment of function call. Either the function use a reference in its signature, either the reference won't pass.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###Case After Default

Default must be the last case in the switch. Any case after 'default' will be unreachable.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Case For Parent, Static And Self

Until PHP 5.5, the special Parent, Static and Self keywords needed to be lowercase to be useable. Otherwise, they would yield a 'PHP Fatal error:  Class 'PARENT' not found'.

Until PHP 5.5, non-lowercase version of those keywords are generating a bug.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53)




###Catch Overwrite Variable

The try...catch structure uses some variables that also in use in this scope. In case of a caught exception, the exception will be put in the catch variable, and overwrite the current value, loosing some data.

It is recommended to use another name for these catch variables.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-catch-overwrite.md">no-catch-overwrite</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Class Const With Array

Constant defined with const keyword may be arrays but only stating with PHP 5.6. Define never accept arrays : it only accepts scalar values.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55)




###Class, Interface or Ttrait With Identical Names

The following names are used at the same time for classes, interfaces or traits. For example, 

class a {}
interface a {}
trait a {}

Even if they are in different namespaces, this makes them easy to confuse. Besides, it is recommended to have markers to differentiate classes from interfaces from traits.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Classes Mutually Extending Each Other

Those classes are extending each other, creating an extension loop. PHP will yield a fatal error at running time, even if it is compiling the code.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Close Tags

PHP manual recommends that script should be left open, without the final closing ?>. This way, one will avoid the infamous bug 'Header already sent', associated with left-over spaces, that are lying after this closing tag.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/leave-last-closing-out.md">leave-last-closing-out</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Closure May Use `$this`

When closure were introduced in PHP, they couldn't use the `$this` variable, making is cumbersome to access local properties when the closure was created within an object. 

This is not the case anymore since PHP 5.4.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53)




###Compare Hash

When comparing hash values, it is important to use the strict comparison : === or !==. 

In a number of situations, the hash value will start with '0e', and PHP will understand that the comparison involves integers : it will then convert the strings into numbers, and it may end up converting them to 0.

Here is an example 

&lt;?php
// more at https://blog.whitehatsec.com/magic-hashes/
`$hashed\_password` = 0e462097431906509000000000000;
if (hash('md5','240610708',false) == `$hashed\_password)` {
  print Matched.\n;
}
?>

You may also use password\_hash and password\_verify.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/strict-comparisons.md">strict-comparisons</a>


This analyzer is part of the following recipes :  [Security](./Recipes.md#Security)




###Compared comparison

Usually, comparison are sufficient, and it is rare to have to compare the result of comparison. Check if this two-stage comparison is really needed.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Concrete Visibility

Methods that implements an interface in a class must be public.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Const With Array

The const keyword supports array since PHP 5.6.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55)




###Constant Class

A class or an interface only made up of constants. Constants usually have to be used in conjunction of some behavior (methods, class...) and never alone. 

As such, they should be PHP constants (build with define or const), or included in a class with other methods and properties.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Constant Scalar Expression

Since PHP 5.6, it is possible to use expression with Constants and simple operators in places where one define default values.



###Constant Scalar Expressions

Starting with PHP 5.6, it is possible to define constant that are the result of expressions.

Those expressions (using simple operators) may only manipulate other constants, and all values must be known at compile time. 

This is not compatible with previous versions.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55)




###Constants Created Outside Its Namespace

Using the define() function, it is possible to create constant outside their namespace, but using the fully qualified namespace.

However, this makes the code confusing and difficult to debug. It is recommended to move the constant definition to its namespace.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Constants With Strange Names

List of constants being defined with names that are incompatible with PHP standards. 

For example, define('ABC!', 1); The constant ABC! will not be accessible via the PHP syntax, such as `$x` = ABC!; but only with the function constant('ABC!');. It may also be tested with defined('ABC!');.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Could Be Class Constant

The following property is defined and used, but never modified. This may be transformed into a constant.

Starting with PHP 5.6, even array() may be defined as constants.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Could Be Static

This global is only used in one function or method. It may be called 'static', instead of global. This will allow you to keep the value between call to the function, but will not be accessible outside this function.

function x() {
    static `$variableIsReservedForX;` // only accessible within x(), even between calls.
    global `$variableIsGlobal;`       //      accessible everywhere in the application
}

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Could Use Short Assignation

Some operators have a 'do-and-assign' version, that looks like a compacted version for = and the operator. 

`$x` = `$x` + 2; may be written `$x` += 2;

This approach is good for readability, and saves some memory in the process. 

List of those operators : +=, -=, *=, /=, %=, **=, .=, &amp;=, |=, ^=, >>=, &lt;&lt;=

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/use-short-assignations.md">use-short-assignations</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Could use self

Self keywords refers to the current class, or any of its parents. Using it is just as fast as the full classname, it is as readable and it is will not be changed upon class or namespace change.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Dangling Array References

It is highly recommended to unset blind variables when they are set up as references after a loop. 

When omitting this step, the next loop that will also require this variable will deal with garbage values, and produce unexpected results.

Add unset( `$as\_variable)` after the loop.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-dangling-reference.md">no-dangling-reference</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Deep Definitions

Structures, such as functions, classes, interfaces, traits, etc. may be defined anywhere in the code, including inside functions. This is legit code for PHP. 

Since the availability of \_\_autoload, there is no need for that kind of code. Structures should be defined, and accessible to the autoloading. Inclusion and deep definitions should be avoided, as they compell code to load some definitions, while autoloading will only load them if needed. 

Functions are excluded from autoload, but shall be gathered in libraries, and not hidden inside other code.

Constants definitions are tolerated inside functions : they may be used for avoiding repeat, or noting the usage of such function.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Define With Array

PHP 7.0 has the ability to define an array as a constant, using the define() native call. This was not possible until that version, only with the const keyword.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56)




###Deprecated code

The following functions have been deprecated in PHP. Whatever the version you are using, it is recommended to stop using them and replace them with a durable equivalent.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-deprecated.md">no-deprecated</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Dereferencing String And Arrays

PHP 5.5 introduced the direct dereferencing of strings and array. No need anymore for an intermediate variable between a string and array (or any expression generating such value) and accessing an index.

`$x` = array(4,5,6); 
`$y` = `$x[2]` ; // is 6

May be replaced by 
`$y` = array(4,5,6)[2];
`$y` = [4,5,6][2];

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54)




###Direct Injection

The following code act directly upon PHP incoming variables like `$\_GET` and `$\_POST.` This make those snippet very unsafe.

This analyzer is part of the following recipes :  [Security](./Recipes.md#Security)




###Don't Change Incomings

PHP hands over a lot of information using special variables like `$\_GET,` `$\_POST,` etc... Modifying those variables and those values inside de variables means that the original content will be lost, while it will still look like raw data, and, as such, will be untrustworthy.

It is recommended to put the modified values in another variable, and keep the original one intact.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Double Assignation

This is when a same container (variable, property, array index) are assigned with values twice in a row. One of them is probably a debug instruction, that was forgotten.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Double Instruction

Twice the same call in a row. This is worth a check.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Echo With Concat

Optimize your echo's by not concatenating at echo time, but serving all argument separated. This will save PHP a memory copy.
If values (literals and variables) are small enough, this won't have impact. Otherwise, this is less work and less memory waste.

echo `$a,` ' b ', `$c;`

instead of

echo  `$a` . ' b ' . `$c;`
echo `$a` b `$c;`

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-unnecessary-string-concatenation.md">no-unnecessary-string-concatenation</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Else If Versus Elseif

The keyword elseif SHOULD be used instead of else if so that all control keywords look like single words. (Directly quoted from the PHP-FIG documentation).

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Empty Classes

List of empty classes. Classes that are directly derived from an exception are not considered here.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Empty Function

Function or method whose body is empty. Such functions or methods are rarely useful. As a bare minimum, the function should return some useful value, even if constant.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Empty Instructions

Empty instructions are part of the code that have no instructions. This may be trailing semi-colon or empty blocks for if-then structures.

`$condition` = 3;;;;
if (`$condition)` { }

This analyzer is part of the following recipes :  [Dead code](./Recipes.md#Dead-code), [Analyze](./Recipes.md#Analyze)




###Empty Interfaces

Empty interfaces. Interfaces should have some function defined, and not be totally empty.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Empty List

Empty list() are not allowed anymore in PHP 7. There must be at least one variable in the list call.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###Empty Namespace

Declaring a namespace in the code and not using it for structure declarations (classes, interfaces, etc...) or global instructions is useless.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-empty-namespace.md">no-empty-namespace</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [Dead code](./Recipes.md#Dead-code)




###Empty Try Catch

The code does try, then catch errors but do no act upon the error. 

At worse, the error should be logged somewhere, so as to measure the actual usage of the log.

catch( Exception `$e)` should be banned, as they will simply ignore any error.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Empty With Expression

The function 'empty()' doesn't accept expressions until PHP 5.5. Until then, it is necessary to store the result of the expression in a variable and then, test it with empty().

This analyzer is part of the following recipes :  [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###Empty traits

List of all empty trait defined in the code. May be they are RFU.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Eval Without Try

Eval() emits a ParseError Exception with PHP 7 and later. Catching this exception is the recommended way to handle errors while using the eval function.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56)




###Eval() Usage

Using eval is bad for performances (compilation time), for caches (it won't be compiled), and for security (if it includes external data).

Most of the time, it is possible to replace the code by some standard PHP, like variable variable for accessing a variable for which you have the name.
At worse, including a pre-generated file will be faster.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-eval.md">no-eval</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Exit() Usage

Using exit or die() in the code makes the code untestable (it will break unit tests). Morover, if there is no reason or string to display, it may take a long time to spot where the application is stuck. 

Try exiting the function/class, or throw exception that may be caught later in the code.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-exit.md">no-exit</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Exponent usage

Usage of the ** operator or **=, to make exponents.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55)




###Extension fann

ext/fann

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Followed injections

There is a link between those function and some of the sensitive PHP functions. This may lead to Injections of various kind.

This analyzer is part of the following recipes :  [Security](./Recipes.md#Security)




###For Using Functioncall

It is advised to avoid functioncall in the for() statement. For example, `$nb` = count(`$array);` for(`$i` = 0; `$i` &lt; `$nb;` `$i++)` {} is faster than for(`$i` = 0; `$i` &lt; count(`$array);` `$i++)` {}.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-functioncall-in-loop.md">no-functioncall-in-loop</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Foreach Dont Change Pointer

In PHP 7.0, the foreach loop won't change the internal pointer of the array, but will work on a copy. So, applying array pointer's functions such as current or next to the source array won't have the same behavior than in PHP 5.

This analyzer is part of the following recipes :  [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###Foreach Needs Reference Array

When using foreach with a reference as value, the source must be a referenced array, which is a variable (or array or property or static property). When the array is the result of an expression, the array is not kept in memory after the foreach loop, and any change made with &amp; are lost.

This will do nothing
foreach(array(1,2,3) as &amp;value) {
    `$value` *= 2;
}

This will have a longer effect

`$array` = array(1,2,3);
foreach(`$array` as &amp;value) {
    `$value` *= 2;
}

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Foreach Reference Is Not Modified

Foreach statement may loop using a reference, especially when the loop has to change values of the array it is looping on. In the spotted loop, reference are used but never modified. They may be removed.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Foreach With list()

PHP 5.5 introduced the ability to use list in foreach loops. This was not possible in the earlier versions.

foreach(`$array` as list(`$a,` `$b))` { 
    // do something 
}

Previously, it was compulsory to extract the data from the blind array : 

foreach(`$array` as `$c)` { 
    list(`$a,` `$b)` = `$c;`
    // do something 
}

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54)




###Forgotten Visibility

Some classes elements (constant, property, method) are missing their explicit visibility. By default, it is public.

It should at least be mentioned as public, or may be reviewed as protected or private.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/always-have-visibility.md">always-have-visibility</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Forgotten Whitespace

Those are white space that are at either end of a script : at the beginning or the end. 

Usually, such white space are forgotten, and may end up summoning the infamous 'headers already sent' error. It is better to remove them.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Fully Qualified Constants

When defining constants with define() function, it is possible to include the actual namespace : 

define('a\b\c', 1); 

However, the name should be fully qualified without the initial \. Here, \a\b\c constant will never be accessible as a namespace constant, though it will be accessible via the constant() function.

Also, the namespace will be absolute, and not a relative namespace of the current one.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Function Subscripting, Old Style

Since PHP 5.4, it is now possible use function results as an array, and access directly its element : 

`$x` = f()[1];

instead of spreading this on two lines : 

`$tmp` = f();
`$x` = `$tmp[1];`

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Function subscripting

This is a new PHP 5.4 feature, where one may use the result of a method directly as an array, given that the method actually returns an array. 

This was not possible until PHP 5.4. Is used to be necessary to put the result in a variable, and then access the desired index.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53)




###Functions Removed In PHP 5.4

Those functions were removed in PHP 5.4.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###Functions Removed In PHP 5.5

Those functions were removed in PHP 5.5.

This analyzer is part of the following recipes :  [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###Functions in loop calls

The following functions call each-other in a loop fashion : A -> B -> A.

When those functions have no other interaction, the code is useless and should be dropped.

Loops of size 2, 3 and 4 are supported.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Global usage

List usage of globals variables, with global keywords or direct access to `$GLOBALS.`

It is recommended to avoid using global variables, at it makes it very difficult to track changes in values across the whole application.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-global.md">no-global</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Hardcoded passwords

Hardcoding passwords is a bad idea. Not only it make the code difficult to change, but it is an information leak. It is better to hide this kind of information out of the code.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-hardcoded-credential.md">no-hardcoded-credential</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Hash Algorithms

There is a long but limited list of hashing algorithm available to PHP. The one found below doesn't seem to be existing.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Hash Algorithms incompatible with PHP 5.3

List of hash algorithms incompatible with PHP 5.3. They were introduced in newer version, and, as such, are not available with older versions.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53)




###Hash Algorithms incompatible with PHP 5.4/5

List of hash algorithms incompatible with PHP 5.4 and 5.5. They were introduced in newer version, or removed in PHP 5.4. As such, they are not available with older versions.

This analyzer is part of the following recipes :  [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###Hexadecimal In String

Mark strings that may be confused with hexadecimal.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###Htmlentities Calls

htmlentities() and htmlspecialchars() are used to prevent injecting special characters in HTML code. As a bare minimum, they take a string and encode it for HTML.

The second argument of the functions is the type of protection. The protection may apply to quotes or not, to HTML4 or 5, etc. It is highly recommended to set it explicitely.

The third argument of the functions is the encoding of the string. In PHP 5.3, it as 'ISO-8859-1', in 5.4, was 'UTF-8', and in 5.6, it is now default\_charset, a php.ini configuration that has the default value of 'UTF-8'. It is highly recommended to set this argument too, to avoid distortions from the configuration.

Also, note that arguments 2 and 3 are constants and string (respectively), and should be issued from the list of values available in the manual. Other values than those will make PHP use the default values.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Implement is for interface

When deriving classes, implements should be used for interfaces, and extends with classes.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Implicit global

Global variables, that are used in local scope with global Keyword, but are not declared as Global in the global scope. They may be mistaken with distinct values, while, in PHP, variables in the global scope are truely global.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Incompilable Files

Files that cannot be compiled, and, as such, be run by PHP. Scripts are linted against PHP versions 5.2, 5.3, 5.4, 5.5, 5.6, 7.0-dev and 7.1. 

This is usually undesirable, as all code must compile before being executed. It may simply be that such files are not compilable because they are not yet ready for an upcoming PHP version.

Code that is incompilable with older PHP versions means that the code is breaking backward compatibility : good or bad is project decision.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-incompilable.md">no-incompilable</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Indices Are Int Or String

Indices in an array notation such as `$array['indice']` should be integers or string. Boolean, null or float will be converted to their integer or string equivalent.

Even integers inside strings will be converted, though not all of them : `$array['8']` and `$array[8]` are the same, though `$array['08']` is not. 

As a general rule of thumb, only use integers or strings that don\'t look like integers.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Instantiating Abstract Class

Those code will raise a PHP fatal error at execution time : 'Cannot instantiate abstract class'. The classes are actually abstract classes, and should be derived into a concrete class to be instantiated.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Invalid constant name

According to PHP's manual, constant names, ' A valid constant name starts with a letter or underscore, followed by any number of letters, numbers, or underscores.'.

Constant, when defined using 'define()' function, must follow this regex : /[a-zA-Z\_\x7f-\xff][a-zA-Z0-9\_\x7f-\xff]*/

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Isset With Constant

Until PHP 7, it was possible to use arrays as constants, but it was not possible to test them with isset.

&lt;?php
const X = [1,2,3];

if (isset(X[4])) {}
?>

This would yield an error : 

Fatal error: Cannot use isset() on the result of an expression (you can use "null !== expression" instead) in test.php on line 7

This is a backward incompatibility.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56)




###List With Appends

List() behavior has changed in PHP 7.0 and it has impact on the indexing when list is used with the [] operator.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###Locally Unused Property

Those properties are defined in a class, and this class doesn't have any method that makes use of them. 

While this is syntacticly correct, it is unusual that defined ressources are used in a child class. It may be worth moving the definition to another class, or to move accessing methods to the class.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [Dead code](./Recipes.md#Dead-code)




###Logical should use &amp;&amp;, ||, ^

Logical operators come in two flavors :  and / &amp;&amp;, || / or, ^ / xor. However, they are not exchangeable, as &amp;&amp; and and have different precedence. 

It is recommended to use the symbol operators, rather than the letter ones.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-letter-logical.md">no-letter-logical</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Lone blocks

Blocks are compulsory when defining a structure, such as a class or a function. They are most often used with flow control instructions, like if then or switch. 

Blocks are also valid syntax that group several instructions together, though it has no effect at all, except confuse the reader. Most often, it is a ruin from a previous flow control instruction, whose condition was removed or commented. They should be removed.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Lost References

When assigning a referenced variable with another reference, the initial reference is lost, while the intend was to transfer the content. 

Do not reassign a reference with another reference. Assign new content to the reference to change its value.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Magic Visibility

The magic methods must have public visibility and cannot be static

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56)




###Malformed Octal

Those numbers starts with a 0, so they are using the PHP octal convention. Therefore, one can't use 8 or 9 figures in those numbers, as they don't belong to the octal base. The resulting number will be truncated at the first erroneous figure. For example, 090 is actually 0, and 02689 is actually 22. 

Also, note that very large octal, usually with more than 21 figures, will be turned into a real number and undergo a reduction in precision.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Methodcall On New

This was added in PHP 5.4+

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53)




###Mixed Keys

When defining default values in arrays, it is recommended to avoid mixing constant and literals, as PHP may mistake them and overwrite a few of them.

Either switch to a newer version of PHP (5.5 or newer), or make sure the resulting array is the one you expect. If not, reorder the definitions.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54)




###Multiple Class Declarations

It is possible to declare several times the same class in the code. PHP will not notice it until execution time, since declarations may be conditional. 

It is recommended to avoid declaring several times the same class in the code. At least, separate them with namespaces, they are for here for that purpose.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Multiple Constant Definition

Some constants are defined several times in your code. This will lead to a fatal error, if they are defined during the same execution.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Multiple Definition of the same argument

A method's signature is holding twice (or more) the same argument. For example, function x (`$a,` `$a)` { ... }. 

This is accepted as is by PHP, and the last parameter's value will be assigned to the variable : 

function x (`$a,` `$a)` { print `$a;` };
x(1,2); => will display 2

However, this is not common programming practise : all arguments should be named differently.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/all-unique-arguments.md">all-unique-arguments</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###Multiple Index Definition

List of all indexes that are defined multiple times in the same array. 

Example : `$x` = array(1 => 2, 2 => 3,  1 => 3);

They are indeed overwriting each other. This is most probably a typo.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Multiples Identical Case

Some cases are defined multiple times, but only one will be processed. Check the list of cases, and remove the extra one.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-duplicate-case.md">no-duplicate-case</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Multiply By One

Multiplying by 1 is useless. 

If it is used to type cast a value to number, then casting (integer) or (real) is clearer.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-math.md">no-useless-math</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Must Return Methods

Those methods are expected to return a value that will be used later. Without return, they are useless.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Namespace with fully qualified name

The 'namespace' keyword has actually 2 usages : one is for declaring namespace, such as namespace A\B\C, use as first instruction in the script.

It may also mean 'current namespace' : for example, namespace\A\B\C represents the constant C, in the sub-namespace A\B of the current namespace (which is whatever you want).

The PHP compiler makes no difference between 'namespace \A\B\C', and 'namespace\A\B\C'. In each case, it will try to locate the constant C in the namespace \A\B, and will generate a fatal error if it can't find it.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Nested Ternary

Ternary operators (`$a` == 1 ? `$b` : `$c)` are a convenient instruction to apply some condition, and avoid a if() structure when it is simple (like in a one liner). 

However, ternary operators tends to make the syntax very difficult to read when they are nested. It is then recommended to use an if() structure, and make the whole code readable.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-nested-ternary.md">no-nested-ternary</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Never used properties

Properties that are never used. They are defined, but never actually used.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###New functions in PHP 5.4

PHP introduced new functions in PHP 5.4. If you have already defined functions with such names, you will get a conflict when trying to upgrade. It is advised to change those functions' name.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###New functions in PHP 5.5

PHP introduced new functions in PHP 5.5. If you have already defined functions with such names, you will get a conflict when trying to upgrade. It is advised to change those functions' name.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###New functions in PHP 5.6

PHP introduced new functions in PHP 5.6. If you have already defined functions with such names, you will get a conflict when trying to upgrade. It is advised to change those functions' name.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55)




###No Direct Call To MagicMethod

PHP magic methods, such as \_\_get(), \_\_set(), ... are supposed to bed used in an object environnement, and not with direct call. 

For example, print `$x->\_\_get('a');` should be written `$x->a;.` 

Accessing those methods in a static way is also discouraged.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###No Direct Usage

The results of the following functions shouldn't be used directly, but checked first. 

For example, glob() returns an array, unless some error happens, in which case it returns a boolean (false). In such case, however rare it is, plugging glob() directly in a foreach() loops will yield errors.

// Used without check : 
foreach(glob('.') as `$file)` { /* do Something */ }.

// Used without check : 
`$files` = glob('.');
if (!is\_array(`$files))` {
    foreach(`$files` as `$file)` { /* do Something */ }.
}

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###No Hardcoded Ip

Do not leave hard coded IP in your code.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###No Hardcoded Path

It is not recommended to have literals when reaching for files. Either use \_\_FILE\_\_ and \_\_DIR\_\_ to make the path relative to the current file, or add some DOC\_ROOT as a configuration constant that will allow you to move your script later.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-hardcoded-path.md">no-hardcoded-path</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###No Hardcoded Port

When connecting to a remove serve, port is an important information. It is recommended to make this configurable (with constant or configuration), to as to be able to change this value without changing the code.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###No Implied If

It is possible to emulate a 'if...then' structure by using the operators 'and' and 'or'. Since optimizations will be applied to them : 
when the left operand of 'and' is false, the right one is not executed, as its result is useless; 
when the left operand of 'or' is true, the right one is not executed, as its result is useless; 

However, such structures are confusing. It is easy to misread them as conditions, and ignore an important logic step. 

It is recommended to use a real 'if then' structures, to make the condition readable.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-implied-if.md">no-implied-if</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###No List With String

list() can't be used anymore to access particular offset in a string. This should be done with substr() or `$string[$offset]` syntax.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56)




###No Parenthesis For Language Construct

Some PHP language constructs, such are include, print, echo don't need parenthesis. They will handle parenthesis, but it is may lead to strange situations. 

It it better to avoid using parenthesis with echo, print, return, throw, include and require (and \_once).

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-parenthesis-for-language-construct.md">no-parenthesis-for-language-construct</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###No Public Access

Properties are declared with public access, but are never used publicly. May be they can be made protected or private.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###No Real Comparison

Avoid comparing decimal numbers with ==, ===, !==, != : those numbers have an error margin which is random, and makes it very difficult to match even if the compared value is a literal. 

Use formulas like 'abs(`$value` - 1.2) &lt; 0.0001' to approximate values with a given precision.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-real-comparison.md">no-real-comparison</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###No Self Referencing Constant

It is not possible to use 'self' when defining a constant in a class. It will yield a fatal error at runtime. 

class a { 
    const C1 = 1; 
    const C2 = self::C1; 
    const C3 = a::C3; 
}

The code needs to reference the full class's name to do so, without using the current class's name. 

class a { 
    const C1 = 1; 
    const C2 = a::C1; 
}

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###No array\_merge In Loops

The function array\_merge() is memory intensive : every call will duplicate the arguments in memory, before merging them. 

Since arrays way be quite big, it is recommended to avoid using merge in a loop. Instead, one should use array\_merge with as many arguments as possible, making the merge a on time call.

This may be achieved easily with the variadic operator : array\_merge(...array\_collecting\_the\_arrays), or 
with call\_user\_func\_array('array\_merge', array\_collecting\_the\_arrays()). The Variadic is slightly faster than call\_user\_func\_array.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-array_merge-in-loop.md">no-array_merge-in-loop</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Non Ascii variables

PHP supports variables with '[a-zA-Z\_\x7f-\xff][a-zA-Z0-9\_\x7f-\xff]*'. In practice, letters outside the scope of a-zA-Z0-9 are rare, and require more care when éditing the code or passing it from OS to OS.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Non Static Methods Called In A Static

Static methods have to be declared as such (using the static keyword). Then, 
one may call them without instantiating the object.

However, PHP doesn't check that a method is static or not : at any point, you may call one
method statically : 

class x {
    static public function sm() { echo \_\_METHOD\_\_.\n; }
    public sm() { echo \_\_METHOD\_\_.\n; }
} 

x::sm(); // echo x::sm 

It is a bad idea to call non-static method statically. Such method may make use of special
variable `$this,` which will be undefined. PHP will not check those calls at compile time,
nor at running time. 

It is recommended to fix this situation : make the method actually static, or use it only 
in object context.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###Non-constant Index In Array

In '`$array[index]',` PHP cannot find index as a constant, but, as a default behavior, turns it into the string 'index'. 

This default behavior raise concerns when a corresponding constant is defined, either using define() or the const keyword (outside a class). The definition of the index constant will modify the behavior of the index, as it will now use the constant definition, and not the 'index' string. 

`$array[index]` = 1; // assign 1 to the element index in `$array`
define('index', 2);
`$array[index]` = 1; // now 1 to the element 2 in `$array`

It is recommended to make index a real string (with ' or "), or to define the corresponding constant to avoid any future surprise.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Not Definitions Only

Files should only include definitions (class, functions, traits, interfaces, constants), or global instructions, but not both. 

Within this context, globals, use, and namespaces instructions are not considered a warning.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Not Not

This is a wrongly done type casting to boolean : !!(`$x)` is (boolean) `$x.`

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-implied-cast.md">no-implied-cast</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Not Substr One

There are two ways to access a byte in a string : substr(`$string,` `$pos,` 1) or `$v[$pos];`

The second one is more readable. It may be up to four times faster, though it is a micro-optimization. 
It is recommended to use it. 

Beware that substr and `$v[$pos]` are similar, while mb\_substr() is not.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Null On New

The following classes used to have a very specific behavior during instantiation : they were able to return NULL on new.

After issuing a 'new' with those classes, it was important to check if the returned object were null (sic) or not. No exception were thrown.

This inconsistency has been cleaned in PHP 7 : see https://wiki.php.net/rfc/internal\_constructor\_behaviour.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56)




###Objects Don't Need References

There is no need to create references for objects, as those are always passed by reference when used as arguments.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-references-on-objects.md">no-references-on-objects</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Old Style Constructor

A long time ago, PHP classes used to have the method bearing the same name as the class acts as the constructor.

This is no more the case in PHP 5, which relies on \_\_construct() to do so. Having this old style constructor may bring in confusion, unless you are also supporting old time PHP 4.

Note that classes with methods bearing the class name, but inside a namespace are not following this convention, as this is not breaking backward compatibility. Those are excluded from the analyze.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-php4-class-syntax.md">no-php4-class-syntax</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###One Letter Functions

One letter functions seems to be really short for a meaningful name. This may happens for very high usage functions, so as to keep code short, but such functions should be rare.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###One variable String

These strings only contains one variable (or function call, or methodcall, or array defererence). 

If the goal is to convert it to a string, use the type casting (string) operator : it is then clearer to understand the conversion. It is also marginally faster, though very little.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Only Variable Returned By Reference

When a function returns a reference, one may only return variables, properties or static properties. Anything else will yield a warning.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Or Die

Interrupting a script will leave the application with a blank page, will make your life miserable for testing. Just don't do that.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-implied-if.md">no-implied-if</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Overwritten Exceptions

In catch blocks, it is good practice not to overwrite the incoming exception, as information about the exception will be lost.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Overwritten Literals

In those methods, the same variable is assigned a literal twice. One of them is too much.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###PHP 7.0 New Classes

Those classes are now declared natively in PHP 7.0 and should not be declared in custom code.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###PHP 7.0 New Functions

The following functions are now native functions in PHP 7.0. It is advised to change them before moving to this new version.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###PHP 7.0 New Interfaces

The following interfaces are introduced in PHP 7.0. They shouldn't be defined in custom code.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###PHP 70 Removed Directive

List of directives that are removed in PHP 7.0.

This analyzer is part of the following recipes :  [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###PHP 70 Removed Functions

The following PHP native functions were removed in PHP 7.0.

This analyzer is part of the following recipes :  [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###PHP Keywords as Names

PHP has a set of reserved keywords. It is recommended not to use those keywords for names structures. 

PHP does check that a number of structures, such as classes, methods, interfaces... can't be named or called using one of the keywords. However, in a few other situations, no check are enforced. Using keywords in such situation is confusing.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###PHP5 Indirect Variable Expression

The following structures are evaluated differently in PHP 5 and 7. It is recommended to review them or switch to a less ambiguous syntax.

See also &lt;a href="http://php.net/manual/en/migration70.incompatible.php">http://php.net/manual/en/migration70.incompatible.php&lt;/a>
&lt;table>
&lt;tr>&lt;td>Expression&lt;/td>&lt;td>PHP 5 interpretation&lt;/td>&lt;td>PHP 7 interpretation&lt;/td>&lt;/tr>
&lt;tr>&lt;td>`$$foo['bar']['baz']&lt;/td>&lt;td>${$foo['bar']['baz']}&lt;/td>&lt;td>($$foo)['bar']['baz']&lt;/td>&lt;/tr>`
&lt;tr>&lt;td>`$foo->$bar['baz']&lt;/td>&lt;td>$foo->{$bar['baz']}&lt;/td>&lt;td>($foo->$bar)['baz']&lt;/td>&lt;/tr>`
&lt;tr>&lt;td>`$foo->$bar['baz']()&lt;/td>&lt;td>$foo->{$bar['baz']}()&lt;/td>&lt;td>($foo->$bar)['baz']()&lt;/td>&lt;/tr>`
&lt;tr>&lt;td>Foo::`$bar['baz']()&lt;/td>&lt;td>Foo::{$bar['baz']}()&lt;/td>&lt;td>(Foo::$bar)['baz']()&lt;/td>&lt;/tr>`
&lt;/table>

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56)




###PHP7 Dirname

With PHP 7, dirname has a second argument that represents the number of parent folder to follow. This prevent us from using nested dirname() calls to reach an grand-parent direct.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56)




###PREG Option e

preg\_replaced had a /e option until PHP 7.0 which allowed the use of eval'ed expression as replacement. This has been dropped in PHP 7.0, for security reasons.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [Security](./Recipes.md#Security), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###Parent, static or self outside class

Parent, static and self keywords must be used within a class or a trait. They make no sens outside a class or trait scope, as self and static refers to the current class and parent refers to one of parent above.

Static may be used in a function or a closure, but not globally.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Parenthesis As Parameter

Using parenthesis around parameters used to silent some internal check. This is not the case anymore in PHP 7, and should be fixed by removing the parenthesis and making the value a real reference.

This analyzer is part of the following recipes :  [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###Php 7 Indirect Expression

Those are variable indirect expressions that are interpreted differently between PHP 5 and PHP 7. You should check them so they don't behave strangely.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56)




###Php7 Relaxed Keyword

PHP Keywords may be used as classes, trait or interfaces elements (such as properties, constants or methods). 

This was not the case in PHP 5, and will yield parse errors.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56)




###Phpinfo

Phpinfo is a great function to learn about the current configuration of the server.

If left in the production code, it may lead to a critical leak, as any attacker gaining access to this data will know a lot about the server configuration.
It is advised to never leave that kind of instruction in a production code.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Pre-Increment

When possible, use the pre-increment operator (++`$i` or --`$i)` instead of the post-increment operator (`$i++` or `$i--).`

The latter needs an extra memory allocation that costs about 10% of performances.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Preprocess Arrays

Using long list of '`$array[$key]` = `$value;` for initializing arrays is significantly slower than the alternative of declaring them with the array() function. 

If the array has to be completed rather than created, it is also faster to use += when there are more than ten elements to add.



###Preprocessable

The following expression are made of literals or already known values : they may be fully calculated before running PHP.

By doing so, this will reduce the amount of work of PHP.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [Analyze](./Recipes.md#Analyze)




###Print And Die

When stopping a script with die() and echo(), it is possible to provide a message as first argument, that will be displayed at execution. There is no need to make a specific call to print or echo.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Property/Variable Confusion

Within a class, there is both a property and some variables bearing the same name. 

class Object {
    private `$x;`
    
    function SetData() {
        `$this->x` = `$x` + 2;
    }
}

the property and the variable may easily be confused one for another and lead to a bug. 

Sometimes, if the property will be changed, and its value replaced by some incoming argument, or data based on such argument, this naming schema is made on purpose, indicating that the current argument will eventually end up in the property. When the argument has the same name as the property, no warning is reported.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Queries in loops

Querying an external database in a loop usually leads to performances problems. 

It is recommended to reduce the number of queries by making one query, and dispatching the results afterwards. 
This is not always possible.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Redeclared PHP Functions

Function that bear the same name as a PHP function, and that are declared. This is possible when managing some backward compatibility (emulating some old function), or preparing for newer PHP version (emulating new upcoming function).

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Redefined Property

Using heritage, it is possible to define several times the same property, at different levels of the hierarchy.

When this is the case, it is difficult to understand which class will actually handle the property. 

In the case of a private property, the different instances will stay distinct. In the case of protected or public properties, they will all share the same value. 

It is recommended to avoid redefining the same property in a hierarchy.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Register Globals

register\_globals was a PHP directive that dumped all incoming variables from GET, POST, COOKIE and FILES as global variables in the called scripts.
This lead to security failures, as the variables were often used but not filtered. 

Though it is less often found in more recent code, register\_globals is sometimes needed in legacy code, that haven't made the move to eradicate this style of coding.
Backward compatible pieces of code that mimic the register\_globals features usually create even greater security risks by being run after scripts startup. At that point, some important variables are already set, and may be overwritten by the incoming call, creating confusion in the script.

Mimicking register\_globals is achieved with variables variables, extract(), parse\_str() and import\_request\_variables() (Up to PHP 5.4).

This analyzer is part of the following recipes :  [Security](./Recipes.md#Security)




###Relay Function

Relay functions (or method) are delegating the actual work to another function or method. They do not have any impact on the results, besides exposing another name for the same feature.

Relay functions are typical of transition API, where an old API have to be preserved until it is fully migrated. Then, they may be removed, so as to reduce confusion, and unclutter the API.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Repeated prints

It is recommended to use concatenation instead of multiple calls to print or echo when outputting several blob of text.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Reserved Keywords in PHP 7

Php reserved names for class/trait/interface. They won't be available anymore in user space starting with PHP 7.

This analyzer is part of the following recipes :  [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###Scalar Typehint Usage

Spot usage of scalar type hint : int, float, boolean and string.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56)




###Sequences In For

For() instructions allows several instructions in each of its parameters. Then, the instruction separator is comma ',', not semi-colon, which is used for separating the 3 arguments.

for (`$a` = 0, `$b` = 0; `$a` &lt; 10, `$b` &lt; 20; `$a++,` `$b` += 3) {}

This loop will simultaneously increment `$a` and `$b.` It will stop only when the last of the central sequence reach a value of false : here, when `$b` reach 20 and `$a` will be 6. 

This structure is often unknown, and makes the for instruction quite difficult to read. It is also easy to oversee the multiples instructions, and omit one of them.
It is recommended not to use it.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Setlocale Needs Constants

The first argument of setlocale must be one of the valid constants, LC\_ALL, LC\_COLLATE, LC\_CTYPE, LC\_MONETARY, LC\_NUMERIC, LC\_TIME, LC\_MESSAGES.

The PHP 5 usage of strings (same name as above, enclosed in ' or ") is not legit anymore in PHP 7 and later.

This analyzer is part of the following recipes :  [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###Several Instructions On The Same Line

Usually, instructions do not share their line : one instruction, one line. This is good for readability, and help at understanding the code. This is especially important when fast-reading the code to find some special situation, where such double-meaning line way have an impact.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Short Open Tags

Usage of short open tags is discouraged. The following files were found to be impacted by the short open tag directive at compilation time. They must be reviewed to ensure no &amp;lt;? tags are found in the code.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Short syntax for arrays

List of all arrays written the new PHP 5.4 short syntax. They mean that it won't be possible to downgrade to PHP 5.3.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53)




###Should Be Single Quote

Static content inside a string, that has no single quotes nor escape sequence (such as \n or \t),
 should be using single quote delimiter, instead of double quote. 

If you have too many of them, don't loose your time switching them all. If you have a few of them, it may be good for consistence.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-double-quote.md">no-double-quote</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Should Chain Exception

When catching an exception and rethrowing another one, it is recommended to chain the exception : this means providing the original exception, so that the final recipiend has a chance to track the origin of the problem. 
This doesn't change the thrown message, but provides more information.

Note : Chaining requires PHP > 5.3.0.

&lt;?php
    try {
        throw new Exception('Exception 1', 1);
    } catch (\Exception `$e)` {
        throw new Exception('Exception 2', 2, `$e);` 
        // Chaining here. 

    }
?>

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Should Typecast

When typecasting, it is better to use the casting operator, such as (int) or (bool), instead of the slower functions such as intval or settype.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Should Use `$this`

Classes' methods should use `$this,` or a static method or property (when they are static). 

Otherwise, the method doesn't belong to the object. It may be a function.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/not-a-method.md">not-a-method</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Should Use Constants

The following functions have related constants that should be used as arguments, instead of scalar literals, such as integers or strings.

For example, `$lines` = file('file.txt', 2); is less readable than `$lines` = file('file.txt', FILE\_IGNORE\_NEW\_LINES)

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Should Use Prepared Statement

Modern databases provides support for prepared statement : it separates the query from the processed data and highten significantly the security. 

Building queries with concatenations is not recommended, though not always avoidable. When possible, use prepared statements.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [Security](./Recipes.md#Security)




###Silently Cast Integer

Those are integer literals that are cast to a float when running PHP. They are simply too big for the current PHP version, and PHP resort to make them a float, which has a much larger capacity but a lower precision.

Compare your literals to PHP\_MAX\_INT (typically 9223372036854775807) and PHP\_MIN\_INT (typically -9223372036854775808).
This applies to binary (0b10101...), octals (0123123...) and hexadecimals (0xfffff...) too.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Simple Global Variable

global keyword should only be used with simple variables (global `$var),` and not with complex or dynamic structures.

This analyzer is part of the following recipes :  [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###Sleep is a security risk

Pausing the script for a specific amount of time means that the Web server is also making all related ressources sleep, such as database, sockets, session, etc. This may used to set up a DOS on the server.

This analyzer is part of the following recipes :  [Security](./Recipes.md#Security)




###Static Loop

It looks like the following loops are static : the same code is executed each time, without taking into account loop variables.

It is possible to create loops that don't use any blind variables, and this is fairly rare.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Static Methods Called From Object

Static methods may be called without instantiating an object.
As such, they never interact with the special variable '`$this',` as they do not
depend on object existence. 

Besides this, static methods are normal methods that may be called directly from
object context, to perform some utility task. 

To maintain code readability, it is recommended to call static method in a static
way, rather than within object context.

class x {
    static function y() {}
}

`$z` = new x();

`$z->y();` // Readability : no one knows it is a static call
x::y();  // Readability : here we know

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Static Methods Can't Contain `$this`

Static methods are also called 'class methods' : they may be called even if the class has no instantiated object. Thus, the local variable `$this` won't exist, PHP will set it to NULL as usual. 

Either, this is not a static method (simply remove the static keyword), or replace all `$this` mention by static properties Class::`$property.`

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-static-this.md">no-static-this</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Strict comparison with booleans

Booleans may be easily mistaken with other values, especially when the function may return integer or boolean as a normal course of action. 

It is encouraged to use strict comparison === or !== when booleans are involved in a comparison.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###String May Hold A Variable

This is a list of string using single quotes and Nowdoc syntax : as such, they are treated as literals, and they won't be scanned to interpolate variables.

However, there are some potential variables in those strings, making it possible for an error : the variable was forgotten and will be published as such. It is worth checking the content and make sure those strings are not variables.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Strpos Comparison

Strpos() returns a string position, starting at 0, or false, in case of failure. 

It is recommended to check the result of strpos with === or !==, so as to avoid confusing 0 and false. 
This analyzer list all the strpos function that are directly compared with == or !=.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/strict-comparisons.md">strict-comparisons</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Switch With Too Many Default

Switch statements should only hold one default, not more. Check the code and remove the extra default.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Switch Without Default

Switch statements hold a number of 'case' that cover all known situations, and a 'default' one which is executed when all other options are exhausted. 

Most of the time, Switch do need a default case, so as to catch the odd situation where the 'value is not what it was expected'. This is a good place to catch unexpected values, to set a default behavior.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-switch-without-default.md">no-switch-without-default</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Throws An Assignement

It is possible to throw an exception, and, in the same time, assign this exception to a variable : throw `$e` = new() Exception().

However, `$e` will never be used, as the exception is thrown, and any following code is not executed. 

The assignement should be removed.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Timestamp Difference

Time() and microtime() shouldn't be used to calculate duration. 

Time() and microtime are subject to variation, depending on system clock variations, such as daylight saving time difference (every spring and fall, one hour variation), or leap seconds, happening on June, 30th or december 31th, as announcec by IERS.

When the difference may be rounded to a larger time unit (rounding the differnce to days, or several hours), the variations may be ignored safely.

If the difference may be very small, it requires a better way to mesure time difference, such as ticks, ext/hrtime, or including a check on the actual time zone (ini\_get(date.timezone)).

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Unchecked Resources

Resources are created, but never checked before being used. This is not safe.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-unchecked-resources.md">no-unchecked-resources</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Undefined Class Constants

Class constants that are used, but never defined. This should yield a fatal error upon execution, but no feedback at compile level.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [Analyze](./Recipes.md#Analyze)




###Undefined Classes

Those classes were used in the code, but there is no way to find a definition of that class in the PHP code.

This may happens under normal conditions, if the application makes use of an unsupported extension, that defines extra classes; 
or if some external libraries, such as PEAR, are not provided during the analysis.

Otherwise, this should be checked.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Undefined Constants

Those constants are not defined in the code, and will raise errors, or use the fallback mechanism of being treated like a string. 

It is recommended to define them all, or to avoid using them.



###Undefined Interfaces

Typehint or instanceof that are relying on undefined interfaces (or classes) : they will always return false. Any condition based upon them are dead code.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Undefined function

This function is not defined in the code. This means that the function is probably defined in a missing library, or in an extension. If not, this will yield a Fatal error at execution.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Undefined parent

List of properties and methods that are accessed using 'parent' keyword but are not defined in the parent class. 

This will be compilable but will yield a fatal error during execution.

Note that if the parent is defined (extends someClass) but someClass is not available in the tested code (it may be in composer,
another dependency, or just not there) it will not be reported.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Undefined properties

List of properties that are not explicitely defined in the class, its parents or traits.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-undefined-properties.md">no-undefined-properties</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Undefined static:: or self::

List of all undefined static and self properties and methods.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Unicode Escape Partial

PHP 7 introduces a new escape sequence for strings : \u{hex}. It is backward incompatible with previous PHP versions for two reasons : 

PHP 7 will recognize en replace those sequences, while PHP 5 keep them intact.
PHP 7 will chocke on partial Unicode Sequences, as it tries to understand them, but may fail. 

Is is recommended to check all those strings, and make sure they will behave correctly in PHP 7.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56)




###Unicode Escape Syntax

Usage of the PHP 7 Unicode Escape syntax, with the \u{xxxxx} format.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56)




###Unpreprocessed values

PHP is good at manipulating data. However, it is also good to preprocess those values, and put them in the code directly as expected, rather than have PHP go the extra step and do it for you.

For example : 
`$x` = explode(',', 'a,b,c,d'); 

could be written 

`$x` = array('a', 'b', 'c', 'd');

and avoid preprocessing the string into an array first.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/always-preprocess.md">always-preprocess</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Unreachable Code

Code located after throw, return, exit(), die(), break or continue cannot be reached, as the previous instruction will divert the engine to another part of the code. 

This is dead code, that may be removed.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-dead-code.md">no-dead-code</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [Dead code](./Recipes.md#Dead-code)




###Unresolved Catch

Classes in Catch expression may turn useless because the code was namespaced and the catch is set on Exception (no \).

Or, the expected class is not even an Exception : that is not needed for catching, but for throwing. Catching will only match the class, if it reaches it.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-unresolved-catch.md">no-unresolved-catch</a>


This analyzer is part of the following recipes :  [Dead code](./Recipes.md#Dead-code)




###Unresolved Instanceof

Instanceof checks if an variable is of a specific class. However, if the reference class doesn't exists, because of a bug, a missed inclusion or a typo, the operator will always fail, without a warning. 

Make sure the following classes are well defined.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-unresolved-instanceof.md">no-unresolved-instanceof</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [Dead code](./Recipes.md#Dead-code)




###Unresolved classes

The following classes are instantiated in the code, but their definition couldn't be found. 

Check for namespaces and aliases and make sure they are correctly configured.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Unresolved use

The following use instructions cannot be resolved to a class or a namespace. They should be dropped or fixed.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-unresolved-use.md">no-unresolved-use</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Unset In Foreach

Unset applied to the variables of a foreach loop are useless, as they are mere copies and not the actual value. Even if the value is a reference, unsetting it will not have effect on the original array.

This analyzer is part of the following recipes :  [Dead code](./Recipes.md#Dead-code), [Analyze](./Recipes.md#Analyze)




###Unthrown Exception

These are exceptions that are defined in the code but never thrown.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-unthrown-exceptions.md">no-unthrown-exceptions</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [Dead code](./Recipes.md#Dead-code)




###Unused Global

List of global keyword, used in various functions but not actually used in the code. for example : 

function foo() {
    global bar;
    
    return 1;
}

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Unused Interfaces

Those interfaces are defined but not used. They should be removed.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [Dead code](./Recipes.md#Dead-code)




###Unused Label

The following labels have been defined in the code, but they are not used. They may be removed.

This analyzer is part of the following recipes :  [Dead code](./Recipes.md#Dead-code), [Analyze](./Recipes.md#Analyze)




###Unused Methods

The following methods are never called as methods. They are probably dead code.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [Dead code](./Recipes.md#Dead-code)




###Unused Static Properties

List of all static properties that are not used. This looks like dead code.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [Dead code](./Recipes.md#Dead-code)




###Unused Trait

Those traits were not found in a class.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Unused classes

The following classes are never used in the code.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [Dead code](./Recipes.md#Dead-code)




###Unused constants

Those constants are defined in the code but never used. Defining unused constants will slow down the application, has they are executed and stored in PHP hashtables. 

It is recommended to comment them out, and only define them when it is necessary.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [Dead code](./Recipes.md#Dead-code)




###Unused functions

The functions below are unused. They look like deadcode.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [Dead code](./Recipes.md#Dead-code)




###Unused static methods

List of all static methods that are not used. This looks like dead code.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [Dead code](./Recipes.md#Dead-code)




###Unused use

List of use statement that are not used in the following code : they may be removed, as they clutter the code and slows PHP by forcing it to search in this list for nothing.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-use.md">no-useless-use</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [Dead code](./Recipes.md#Dead-code)




###Use === null

It is faster to use === null instead of is\_null().

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/avoid-those-slow-functions.md">avoid-those-slow-functions</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Use Const And Functions

Since PHP 5.6 it is possible to import specific functions or constants from other namespaces.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55)




###Use Constant As Arguments

Some methods and functions are defined to be used with constants as arguments. Those constants are made to be meaningful and readable, keeping the code maintenable. It is recommended to use such constants as soon as they are documented.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Use Instanceof

get\_class() should be replaced with the 'instanceof' operator to check the class of an object.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Use Object Api

When PHP offers the alternative between procedural and OOP api for the same features, it is recommended to sue the OOP API. 

Often, this least to more compact code, as methods are shorter, and there is no need to bring the resource around. Lots of new extensions are directly written in OOP form too.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/use-object-api.md">use-object-api</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Use Pathinfo

Is is recommended to use pathinfo() function instead of string manipulation functions to extract the various parts of a path. It is more efficient and readable.

If you're using path with UTF-8 characters, pathinfo will strip them. There, you might have to use string functions.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Use const

The const keyword may be used to define constant, just like the define() function. 

When defining a constant, it is recommended to use 'const' when the features of the constant are not dynamical (name or value are known at compile time). 
This way, constant will be defined at compile time, and not at execution time. 

define() function is useful for all other situations.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Use password\_hash()

PHP 5.5 introduced password\_hash() and password\_check() to replace the use of crypt() to check password.

This analyzer is part of the following recipes :  [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###Use with fully qualified name

PHP manual recommends not to use fully qualified name (starting with \) when using the 'use' statement : they are "the leading backslash is unnecessary and not recommended, as import names must be fully qualified, and are not processed relative to the current namespace".

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Used once variables

This is the list of used once variables. 

Such variables are useless. Variables must be used at least twice : once for writing, once for reading, at least. It is recommended to remove them.

In special situations, variables may be used once : 

+ PHP predefined variables, as they are already initialized. They are omitted in this analyze.
+ Interface function's arguments, since the function has no body; They are omitted in this analyze.
+ Dynamically created variables (`$$x,` `${$this->y}` or also using extract), as they are runtime values and can't be determined at static code time. They are reported for manual review.
+ Dynamically included files will provide in-scope extra variables.

The current analyzer count variables at the application level, and not at a method scope level.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Used once variables (in scope)

This is the list of used once variables, broken down by scope. Those variable are used once in a function, a method, a class or a namespace. In any case, this means the variable is used only once, while it should be used at least twice.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-unused-arguments.md">no-unused-arguments</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Useless Abstract Class

Those classes are marked 'abstract' and they are never extended. This way, they won't be instantiated nor used. 

Abstract classes that have only static methods are omitted here : one usage of such classes are Utilities classes, which only offer static methods.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Useless Brackets

You may remove those brackets, they have no use here. It may be a left over of an old instruction, or a misunderstanding of the alternative syntax.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Useless Final

When a class is declared final, all of its methods are also final by default. There is no need to declare them individually final.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-final.md">no-useless-final</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Useless Global

The listed global below are useless : they are only used once.

Also, PHP has superglobals, a special team of variables that are always available, whatever the context. 
They are : `$GLOBALS,` `$\_SERVER,` `$\_GET,` `$\_POST,` `$\_FILES,` `$\_COOKIE,` `$\_SESSION,` `$\_REQUEST` and `$\_ENV.` 
Simply avoid using 'global `$\_POST'.`

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Useless Interfaces

The interfaces below are defined and are implemented by some classes. 
However, they are never used to enforce objects in the code, using instanceof or a typehint. 
As they are currently used, those interfaces may be removed without change in behavior.

// only defined interface but never enforced
interface i {};
class c implements i {} 

interfaces should be used in Typehint or with the instanceof operator.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-interfaces.md">no-useless-interfaces</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Useless Parenthesis

Situations where parenthesis are not necessary, and may be removed.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Useless Unset

Unsetting variables may not be applicable with a certain type of variables. This is the list of such cases.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-unset.md">no-useless-unset</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Useless constructor

Class constructor that have empty bodies are useless. They may be removed.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Useless instructions

The instructions below are useless. For example, running '&amp;lt;?php 1 + 1; ?&amp;gt;' will do nothing, as the addition is actually performed, but not used : not displayed, not stored, not set. Just plain lost. 

The first level of the spotted instructions may be removed safely. For example, the analyzer will spot : '1 + `$a++';` as a useless instruction. The addition is useless, but the plusplus is not.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-instruction.md">no-useless-instruction</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Useless return

The spotted functions or methods have a return statement, but this statement is useless. This is the case for constructor and destructors, whose return value are ignored or inaccessible.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Uses default values

Default values are provided to methods so as to make it convenient to use. However, with new versions, those values may change. For example, in PHP 5.4, html\_entities switched from Latin1 to UTF-8 default encoding.

As much as possible, it is recommended to use explicit values in those methods, so as to prevent from being surprise at a future PHP evolution.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Usort Sorting In PHP 7.0

Usort (and co) sorting has changed in PHP 7. Values that are equals (based on user-provided method) may be sorted differently than in PHP 5. 

If this sorting is important, it is advised to add extra comparison in the user-function and avoid returning 0 (thus, depending on default implementation).

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###Var

Var was used in PHP 4 to mark properties as public. Nowadays, new keywords are available : public, protected, private. Var is equivalent to public.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-php4-class-syntax.md">no-php4-class-syntax</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Variable Global

Variable global such as global `$$foo->bar` are valid in PHP 5.6, but no in PHP 7.0. They should be replaced with `${$foo->bar}.`

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56)




###While(List() = Each())

This code structure is quite old : it should be replace by the more modern and efficient foreach(`$array` as `$key` => `$value)` {}.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Written Only Variables

Those variables are being written, but never read. This way, they are useless and should be removed, or read at some point.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-unused-variable.md">no-unused-variable</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Wrong Number Of Arguments

Those functioncalls are made with too many or too few arguments. Some of them will be dropped, or PHP will raise errors when values are missing.

It is recommended to check the signature of the methods, and fix the arguments.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-missing-argument.md.md">no-missing-argument.md</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###Wrong Optional parameter

PHP parameters are optional when they defined with a default value, like this : function x(`$arg` = 1) {...}.

When there are compulsory and optional parameters, the first ones should appear first, and the second should appear last : function x(`$arg,` `$arg2` = 2) {...}.

PHP will solve this problem at runtime, assign values in the same other, but will miss some of the default values and emits warnings. 

It is better to put all the optional parameters at the end of the method's signature.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###\_\_debugInfo()

The magic function \_\_debugInfo() has been introduced in PHP 5.6. In the previous versions of PHP, this method is ignored and won't be called when debugging.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55)




###\_\_toString() Throws Exception

Magical method \_\_toString() can't throw exceptions, according to the world.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###crypt without salt

PHP 5.6 and later require a salt, while previous versions didn't require it. Salt is a simple string, that is usually only known by the application.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###error\_reporting() With Integers

Using named constants with error\_reporting is strongly encouraged to ensure compatibility for future versions. As error levels are added, the range of integers increases, so older integer-based error levels will not always behave as expected. (Adapted from the documentation)

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###ext/apc

Extension APC

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###ext/dba

Extension ext/dba

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53)




###ext/ereg

Extension ext/ereg

This analyzer is part of the following recipes :  [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###ext/fdf

Extension ext/fdf

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53)




###ext/ming

Extension ext/ming, to create swf files with PHP.

This analyzer is part of the following recipes :  [CompatibilityPHP53](./Recipes.md#CompatibilityPHP53)




###ext/mysql

Extension ext/mysql

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###ext/sqlite

Extension ext/sqlite3

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###func\_get\_arg Modified

func\_get\_arg() and func\_get\_args() used to report the calling value of the argument until PHP 7. Since PHP 7, it is reporting the value of the argument at calling time, which may have been modified by a previous instruction. 

&lt;?php

function x(`$a)` {
    `$a++;`
    print func\_get\_arg(0);
}

x(0);
?>

This code will display 1 in PHP 7, and 0 in PHP 5.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###include\_once() Usage

All the \_once inclusion functions should be avoided for performances reasons.

Try using auto\_load() for loading classes, or using include() and make it possible to include several times the same file without errors.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###list() May Omit Variables

list() is the only PHP function that accepts to have omitted arguments. If the code is not going to use a listed variable, just don't name it. 

list (`$a,` , `$b)` = array(1, 2, 3);

`$b` will be 3, and the 2 value will be omitted. 

This is cleaner, and save some memory.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###mcrypt\_create\_iv with default values

mcrypt\_create\_iv used to have MCRYPT\_DEV\_RANDOM as default values, and in PHP 5.6, it now uses MCRYPT\_DEV\_URANDOM.

If the code doesn't have a second argument, it relies on the default value. It is recommended to set explicitely the value, so has to avoid problems while migrating.

This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze), [CompatibilityPHP54](./Recipes.md#CompatibilityPHP54), [CompatibilityPHP55](./Recipes.md#CompatibilityPHP55), [CompatibilityPHP70](./Recipes.md#CompatibilityPHP70), [CompatibilityPHP56](./Recipes.md#CompatibilityPHP56), [CompatibilityPHP71](./Recipes.md#CompatibilityPHP71)




###old \_\_autoload

Do not use the old \_\_autoload() function, but rather the new spl\_register\_autoload() function.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/use-smart-autoload.md">use-smart-autoload</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




###parse\_str warning

The parse\_str function will parse a query string and assign the resulting variables to the local scope. This may create a unexpected number of variables, and even overwrite the one existing.

Always use an empty variable a second parameter to parse\_str, so as to collect the incoming values, and then, filter them in that array.

This analyzer is part of the following recipes :  [Security](./Recipes.md#Security)




###var\_dump()... Usage

var\_dump(), print\_r() or var\_export() are debugging functions, that should not be left in any production code. 

They may be tolerated during development time, but must be removed so as not to have any chance to be run in production.

clearPHP: <a href="https://github.com/dseguy/clearPHP/tree/master/rules/no-debug-code.md">no-debug-code</a>


This analyzer is part of the following recipes :  [Analyze](./Recipes.md#Analyze)




