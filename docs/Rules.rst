.. _Rules:

Rules list
----------

Introduction
############

.. comment: The rest of the document is automatically generated. Don't modify it manually. 
.. comment: Rules details
.. comment: Generation date : Mon, 18 Jan 2016 09:57:20 +0000
.. comment: Generation hash : 12c2d38b65965e568aa09774f69c87b3d39d4fde


.. _$http\_raw\_post\_data:

$HTTP\_RAW\_POST\_DATA
######################


Starting at PHP 5.6, $HTTP\_RAW\_POST\_DATA will be deprecated, and should be replaced by php://input. You may get ready by setting always\_populate\_raw\_post\_data to -1.

+--------------+-------------------------------------------------------------------------------------------------+
| Command Line | Php/RawPostDataUsage                                                                            |
+--------------+-------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                 |
+--------------+-------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP56`, :ref:`Analyze`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71` |
+--------------+-------------------------------------------------------------------------------------------------+



.. _$this-belongs-to-classes:

$this Belongs To Classes
########################


$this variable represents an object (the current object) and it should be used within class's methods (except for static) and not outside.

+--------------+--------------------------+
| Command Line | Classes/ThisIsForClasses |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Analyze`           |
+--------------+--------------------------+



.. _$this-is-not-an-array:

$this is not an array
#####################


$this variable represents an object (the current object) and it is not an array, unless the class (or its parents) has the ArrayAccess interface.

+--------------+--------------------------+
| Command Line | Classes/ThisIsNotAnArray |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Analyze`           |
+--------------+--------------------------+



.. _$this-is-not-for-static-methods:

$this is not for static methods
###############################


$this variable represents an object (the current object) and it is not compatible with a static method, which may operate without any object.

+--------------+---------------------------------------------------------------------------------------------+
| Command Line | Classes/ThisIsNotForStatic                                                                  |
+--------------+---------------------------------------------------------------------------------------------+
| clearPHP     | `no-static-this <https://github.com/dseguy/clearPHP/tree/master/rules/no-static-this.md>`__ |
+--------------+---------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                              |
+--------------+---------------------------------------------------------------------------------------------+



.. _**-for-exponent:

\*\* for exponent
#################


PHP 5.6 introduced the operator \*\* to provide exponents, instead of the slower function pow().

+--------------+---------------------------------------------------------------------------------+
| Command Line | Php/NewExponent                                                                 |
+--------------+---------------------------------------------------------------------------------+
| clearPHP     |                                                                                 |
+--------------+---------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55` |
+--------------+---------------------------------------------------------------------------------+



.. _...-usage:

... usage
#########


Usage of the ... keyword, either in function definitions, either in functioncalls.

+--------------+---------------------------------------------------------------------------------+
| Command Line | Php/EllipsisUsage                                                               |
+--------------+---------------------------------------------------------------------------------+
| clearPHP     |                                                                                 |
+--------------+---------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55` |
+--------------+---------------------------------------------------------------------------------+



.. _\:\:class:

::class
#######


PHP 5.5 introduced a special class constant, relying on the 'class' keyword. It will solve the classname that is used in the left part of the operator.

ClassName::class; // return Namespace\ClassName

+--------------+------------------------------------------------------+
| Command Line | Php/StaticclassUsage                                 |
+--------------+------------------------------------------------------+
| clearPHP     |                                                      |
+--------------+------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54` |
+--------------+------------------------------------------------------+



.. _<?=-usage:

<?= usage
#########


Usage of the <?= tag, that echo's directly the following content.

+--------------+------------------+
| Command Line | Php/EchoTagUsage |
+--------------+------------------+
| clearPHP     |                  |
+--------------+------------------+
| Analyzers    | :ref:`Analyze`   |
+--------------+------------------+



.. _abstract-static-methods:

Abstract static methods
#######################


Methods cannot be both abstract and static. Static methods belong to a class, and will not be overridden by the child class. For normal methods, PHP will start at the object level, then go up the hierarchy to find the method. With static, you have to mention the name, or use Late Static Binding, with self or static. Hence, it is useless to have an abstract static method : it should be a simple static method.

A child class is able to declare a method with the same name than a static method in the parent, but those two methods will stay independant.

+--------------+------------------------+
| Command Line | Classes/AbstractStatic |
+--------------+------------------------+
| clearPHP     |                        |
+--------------+------------------------+
| Analyzers    | :ref:`Analyze`         |
+--------------+------------------------+



.. _access-protected-structures:

Access protected structures
###########################


It is not allowed to access protected properties or methods from outside the class or its relatives.

+--------------+-------------------------+
| Command Line | Classes/AccessProtected |
+--------------+-------------------------+
| clearPHP     |                         |
+--------------+-------------------------+
| Analyzers    | :ref:`Analyze`          |
+--------------+-------------------------+



.. _accessing-private:

Accessing private
#################


List of calls to private properties/methods that will compile but yield some fatal error upon execution.

+--------------+-----------------------+
| Command Line | Classes/AccessPrivate |
+--------------+-----------------------+
| clearPHP     |                       |
+--------------+-----------------------+
| Analyzers    | :ref:`Analyze`        |
+--------------+-----------------------+



.. _adding-zero:

Adding Zero
###########


Adding 0 is useless. 

If it is used to type cast a value to integer, then casting (integer) is clearer. 

In (0 - $x) structures, 0 may be omitted.

+--------------+-----------------------------------------------------------------------------------------------+
| Command Line | Structures/AddZero                                                                            |
+--------------+-----------------------------------------------------------------------------------------------+
| clearPHP     | `no-useless-math <https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-math.md>`__ |
+--------------+-----------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                |
+--------------+-----------------------------------------------------------------------------------------------+



.. _aliases-usage:

Aliases usage
#############


Some functions have several names, and both may be used the same way. However, one of the names is the main name, and the others are aliases. Aliases may be removed or change or dropped in the future. Even if this is not forecast, it is good practice to use the main name, instead of the aliases.

+--------------+-------------------------------------------------------------------------------------+
| Command Line | Functions/AliasesUsage                                                              |
+--------------+-------------------------------------------------------------------------------------+
| clearPHP     | `no-aliases <https://github.com/dseguy/clearPHP/tree/master/rules/no-aliases.md>`__ |
+--------------+-------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                      |
+--------------+-------------------------------------------------------------------------------------+



.. _altering-foreach-without-reference:

Altering Foreach Without Reference
##################################


When using a foreach loop that modifies the original source, it is recommended to use referenced variables, rather than access the original value with $source[$index]. 

Using references is then must faster, and easier to read. 

.. code-block:: php

   <?php
   foreach($source as $key => &$value) {
       $value = newValue($value, $key);
   }
   ?>

+--------------+-----------------------------------------------------------------------------------------------------------------------------------+
| Command Line | Structures/AlteringForeachWithoutReference                                                                                        |
+--------------+-----------------------------------------------------------------------------------------------------------------------------------+
| clearPHP     | `use-reference-to-alter-in-foreach <https://github.com/dseguy/clearPHP/tree/master/rules/use-reference-to-alter-in-foreach.md>`__ |
+--------------+-----------------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                                                    |
+--------------+-----------------------------------------------------------------------------------------------------------------------------------+



.. _ambiguous-index:

Ambiguous Index
###############


List of all indexes that are defined in the same array, with different types. 

Example : $x[1] = 1; $x['1'] = 2; 

They are indeed distinct, but may lead to confusion.

+--------------+----------------------+
| Command Line | Arrays/AmbiguousKeys |
+--------------+----------------------+
| clearPHP     |                      |
+--------------+----------------------+
| Analyzers    | :ref:`Analyze`       |
+--------------+----------------------+



.. _anonymous-classes:

Anonymous Classes
#################


Mark anonymous classes.

+--------------+------------------------------------------------------------------------------------------------------------+
| Command Line | Classes/Anonymous                                                                                          |
+--------------+------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                            |
+--------------+------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+--------------+------------------------------------------------------------------------------------------------------------+



.. _argument-should-be-typehinted:

Argument should be typehinted
#############################


When a method expects objects as argument, those arguments should be typehinted, so as to provide early warning that a wrong object is being sent to the method.

The analyzer will detect situations where a class, or the keywords 'array' or 'callable'. 

Closure arguments are omitted.

+--------------+-----------------------------------------------------------------------------------------------+
| Command Line | Functions/ShouldBeTypehinted                                                                  |
+--------------+-----------------------------------------------------------------------------------------------+
| clearPHP     | `always-typehint <https://github.com/dseguy/clearPHP/tree/master/rules/always-typehint.md>`__ |
+--------------+-----------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                |
+--------------+-----------------------------------------------------------------------------------------------+



.. _assign-default-to-properties:

Assign Default To Properties
############################


Properties may be assigned default values at declaration time. Such values may be later modified, if needed. 

Default values will save some instructions in the constructor, and makes the value obvious in the code.

+--------------+---------------------------------------------------------------------------------------------------------------------------+
| Command Line | Classes/MakeDefault                                                                                                       |
+--------------+---------------------------------------------------------------------------------------------------------------------------+
| clearPHP     | `use-properties-default-values <https://github.com/dseguy/clearPHP/tree/master/rules/use-properties-default-values.md>`__ |
+--------------+---------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                                            |
+--------------+---------------------------------------------------------------------------------------------------------------------------+



.. _avoid-parenthesis:

Avoid Parenthesis
#################


Avoid Parenthesis for language construct. Languages constructs are a few PHP native elements, that looks like functions but are not. 

Among other distinction, those elements cannot be directly used as variable function call, and they may be used with or without parenthesis.

The usage of parenthesis actually give some feeling of confort, it won't prevent PHP from combining those argument with any later operators, leading to unexpected results.

Even if most of the time, usage of parenthesis is legit, it is recommended to avoid them.

+--------------+------------------------------------+
| Command Line | Structures/PrintWithoutParenthesis |
+--------------+------------------------------------+
| clearPHP     |                                    |
+--------------+------------------------------------+
| Analyzers    | :ref:`Analyze`                     |
+--------------+------------------------------------+



.. _avoid-those-crypto:

Avoid Those Crypto
##################


The following cryptographic algorithms are considered unsecure, and should be replaced with new and more performant algorithms. 

MD2, MD4, MD5, SHA0, SHA1, CRC, DES, 3DES, RC2, RC4. 

When possible, avoid using them, may it be as PHP functions, or hashing function configurations (mcrypt, hash...).

+--------------+---------------------------+
| Command Line | Security/AvoidThoseCrypto |
+--------------+---------------------------+
| clearPHP     |                           |
+--------------+---------------------------+
| Analyzers    | :ref:`Security`           |
+--------------+---------------------------+



.. _avoid-array\_unique:

Avoid array\_unique
###################


The native function array\_unique is much slower than using other alternative, such as array\_count\_values(), array\_flip/array\_keys, or even a foreach() loops.

+--------------+--------------------------+
| Command Line | Structures/NoArrayUnique |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Analyze`           |
+--------------+--------------------------+



.. _binary-glossary:

Binary Glossary
###############


List of all the integer values using the binary format, such as 0b10 or 0B0101.

+--------------+---------------------------+
| Command Line | Type/Binary               |
+--------------+---------------------------+
| clearPHP     |                           |
+--------------+---------------------------+
| Analyzers    | :ref:`CompatibilityPHP53` |
+--------------+---------------------------+



.. _break-outside-loop:

Break Outside Loop
##################


Starting with PHP 7, breaks or continue that are outside a loop (for, foreach, do...while, while) or a switch() statement won't compile anymore.

+--------------+----------------------------------------------------------------------+
| Command Line | Structures/BreakOutsideLoop                                          |
+--------------+----------------------------------------------------------------------+
| clearPHP     |                                                                      |
+--------------+----------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71` |
+--------------+----------------------------------------------------------------------+



.. _break-with-0:

Break With 0
############


Cannot break 0, as this makes no sense. Break 1 is the minimum, and is the default value.

+--------------+-------------------------------------------+
| Command Line | Structures/Break0                         |
+--------------+-------------------------------------------+
| clearPHP     |                                           |
+--------------+-------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP53` |
+--------------+-------------------------------------------+



.. _break-with-non-integer:

Break With Non Integer
######################


When using a break, the argument of the operator should be a positive non-null integer, and nothing else.

+--------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+
| Command Line | Structures/BreakNonInteger                                                                                                                            |
+--------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                                                                       |
+--------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP71` |
+--------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _buried-assignation:

Buried Assignation
##################


Those assignations are buried in the code, and placed in unexpected situations. They will be difficult to spot, and may be confusing. It is advised to place them in a more visible place.

+--------------+------------------------------+
| Command Line | Structures/BuriedAssignation |
+--------------+------------------------------+
| clearPHP     |                              |
+--------------+------------------------------+
| Analyzers    | :ref:`Analyze`               |
+--------------+------------------------------+



.. _calltime-pass-by-reference:

Calltime Pass By Reference
##########################


PHP doesn't like anymore when the value is turned into a reference at the moment of function call. Either the function use a reference in its signature, either the reference won't pass.

+--------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+
| Command Line | Structures/CalltimePassByReference                                                                                                                    |
+--------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                                                                       |
+--------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP71` |
+--------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _case-after-default:

Case After Default
##################


Default must be the last case in the switch. Any case after 'default' will be unreachable.

+--------------+-----------------------------+
| Command Line | Structures/CaseAfterDefault |
+--------------+-----------------------------+
| clearPHP     |                             |
+--------------+-----------------------------+
| Analyzers    | :ref:`Analyze`              |
+--------------+-----------------------------+



.. _case-for-parent,-static-and-self:

Case For Parent, Static And Self
################################


Until PHP 5.5, the special Parent, Static and Self keywords needed to be lowercase to be useable. Otherwise, they would yield a 'PHP Fatal error:  Class 'PARENT' not found'.

Until PHP 5.5, non-lowercase version of those keywords are generating a bug.

+--------------+----------------------------------------------------------------------+
| Command Line | Php/CaseForPSS                                                       |
+--------------+----------------------------------------------------------------------+
| clearPHP     |                                                                      |
+--------------+----------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP53` |
+--------------+----------------------------------------------------------------------+



.. _catch-overwrite-variable:

Catch Overwrite Variable
########################


The try...catch structure uses some variables that also in use in this scope. In case of a caught exception, the exception will be put in the catch variable, and overwrite the current value, loosing some data.

It is recommended to use another name for these catch variables.

+--------------+-----------------------------------------------------------------------------------------------------+
| Command Line | Structures/CatchShadowsVariable                                                                     |
+--------------+-----------------------------------------------------------------------------------------------------+
| clearPHP     | `no-catch-overwrite <https://github.com/dseguy/clearPHP/tree/master/rules/no-catch-overwrite.md>`__ |
+--------------+-----------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                      |
+--------------+-----------------------------------------------------------------------------------------------------+



.. _class-const-with-array:

Class Const With Array
######################


Constant defined with const keyword may be arrays but only stating with PHP 5.6. Define never accept arrays : it only accepts scalar values.

+--------------+---------------------------------------------------------------------------------+
| Command Line | Php/ClassConstWithArray                                                         |
+--------------+---------------------------------------------------------------------------------+
| clearPHP     |                                                                                 |
+--------------+---------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55` |
+--------------+---------------------------------------------------------------------------------+



.. _class,-interface-or-trait-with-identical-names:

Class, Interface or Trait With Identical Names
##############################################


The following names are used at the same time for classes, interfaces or traits. For example, 

class a {}
interface a {}
trait a {}

Even if they are in different namespaces, this makes them easy to confuse. Besides, it is recommended to have markers to differentiate classes from interfaces from traits.

+--------------+---------------------+
| Command Line | Classes/CitSameName |
+--------------+---------------------+
| clearPHP     |                     |
+--------------+---------------------+
| Analyzers    | :ref:`Analyze`      |
+--------------+---------------------+



.. _classes-mutually-extending-each-other:

Classes Mutually Extending Each Other
#####################################


Those classes are extending each other, creating an extension loop. PHP will yield a fatal error at running time, even if it is compiling the code.

+--------------+-------------------------+
| Command Line | Classes/MutualExtension |
+--------------+-------------------------+
| clearPHP     |                         |
+--------------+-------------------------+
| Analyzers    | :ref:`Analyze`          |
+--------------+-------------------------+



.. _close-tags:

Close Tags
##########


PHP manual recommends that script should be left open, without the final closing ?>. This way, one will avoid the infamous bug 'Header already sent', associated with left-over spaces, that are lying after this closing tag.

+--------------+-------------------------------------------------------------------------------------------------------------+
| Command Line | Php/CloseTags                                                                                               |
+--------------+-------------------------------------------------------------------------------------------------------------+
| clearPHP     | `leave-last-closing-out <https://github.com/dseguy/clearPHP/tree/master/rules/leave-last-closing-out.md>`__ |
+--------------+-------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                              |
+--------------+-------------------------------------------------------------------------------------------------------------+



.. _closure-may-use-$this:

Closure May Use $this
#####################


When closure were introduced in PHP, they couldn't use the $this variable, making is cumbersome to access local properties when the closure was created within an object. 

This is not the case anymore since PHP 5.4.

+--------------+-------------------------------------------+
| Command Line | Php/ClosureThisSupport                    |
+--------------+-------------------------------------------+
| clearPHP     |                                           |
+--------------+-------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP53` |
+--------------+-------------------------------------------+



.. _compare-hash:

Compare Hash
############


When comparing hash values, it is important to use the strict comparison : === or !==. 

In a number of situations, the hash value will start with '0e', and PHP will understand that the comparison involves integers : it will then convert the strings into numbers, and it may end up converting them to 0.

Here is an example 

.. code-block:: php

   <?php
   // more at https://blog.whitehatsec.com/magic-hashes/
   $hashed\_password = 0e462097431906509000000000000;
   if (hash('md5','240610708',false) == $hashed\_password) {
     print Matched.\n;
   }
   ?>


You may also use password\_hash and password\_verify.

+--------------+-----------------------------------------------------------------------------------------------------+
| Command Line | Security/CompareHash                                                                                |
+--------------+-----------------------------------------------------------------------------------------------------+
| clearPHP     | `strict-comparisons <https://github.com/dseguy/clearPHP/tree/master/rules/strict-comparisons.md>`__ |
+--------------+-----------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Security`                                                                                     |
+--------------+-----------------------------------------------------------------------------------------------------+



.. _compared-comparison:

Compared comparison
###################


Usually, comparison are sufficient, and it is rare to have to compare the result of comparison. Check if this two-stage comparison is really needed.

+--------------+-------------------------------+
| Command Line | Structures/ComparedComparison |
+--------------+-------------------------------+
| clearPHP     |                               |
+--------------+-------------------------------+
| Analyzers    | :ref:`Analyze`                |
+--------------+-------------------------------+



.. _concrete-visibility:

Concrete Visibility
###################


Methods that implements an interface in a class must be public.

+--------------+-------------------------------+
| Command Line | Interfaces/ConcreteVisibility |
+--------------+-------------------------------+
| clearPHP     |                               |
+--------------+-------------------------------+
| Analyzers    | :ref:`Analyze`                |
+--------------+-------------------------------+



.. _const-with-array:

Const With Array
################


The const keyword supports array since PHP 5.6.

+--------------+---------------------------------------------------------------------------------+
| Command Line | Php/ConstWithArray                                                              |
+--------------+---------------------------------------------------------------------------------+
| clearPHP     |                                                                                 |
+--------------+---------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55` |
+--------------+---------------------------------------------------------------------------------+



.. _constant-class:

Constant Class
##############


A class or an interface only made up of constants. Constants usually have to be used in conjunction of some behavior (methods, class...) and never alone. 

As such, they should be PHP constants (build with define or const), or included in a class with other methods and properties.

+--------------+-----------------------+
| Command Line | Classes/ConstantClass |
+--------------+-----------------------+
| clearPHP     |                       |
+--------------+-----------------------+
| Analyzers    | :ref:`Analyze`        |
+--------------+-----------------------+



.. _constant-scalar-expression:

Constant Scalar Expression
##########################


Since PHP 5.6, it is possible to use expression with Constants and simple operators in places where one define default values.

+--------------+------------------------------+
| Command Line | Php/ConstantScalarExpression |
+--------------+------------------------------+
| clearPHP     |                              |
+--------------+------------------------------+
| Analyzers    | none                         |
+--------------+------------------------------+



.. _constant-scalar-expressions:

Constant Scalar Expressions
###########################


Starting with PHP 5.6, it is possible to define constant that are the result of expressions.

Those expressions (using simple operators) may only manipulate other constants, and all values must be known at compile time. 

This is not compatible with previous versions.

+--------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Command Line | Structures/ConstantScalarExpression                                                                                                                              |
+--------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                                                                                  |
+--------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55` |
+--------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _constants-created-outside-its-namespace:

Constants Created Outside Its Namespace
#######################################


Using the define() function, it is possible to create constant outside their namespace, but using the fully qualified namespace.

However, this makes the code confusing and difficult to debug. It is recommended to move the constant definition to its namespace.

+--------------+--------------------------------------+
| Command Line | Constants/CreatedOutsideItsNamespace |
+--------------+--------------------------------------+
| clearPHP     |                                      |
+--------------+--------------------------------------+
| Analyzers    | :ref:`Analyze`                       |
+--------------+--------------------------------------+



.. _constants-with-strange-names:

Constants With Strange Names
############################


List of constants being defined with names that are incompatible with PHP standards. 

For example, define('ABC!', 1); The constant ABC! will not be accessible via the PHP syntax, such as $x = ABC!; but only with the function constant('ABC!');. It may also be tested with defined('ABC!');.

+--------------+--------------------------------+
| Command Line | Constants/ConstantStrangeNames |
+--------------+--------------------------------+
| clearPHP     |                                |
+--------------+--------------------------------+
| Analyzers    | :ref:`Analyze`                 |
+--------------+--------------------------------+



.. _could-be-class-constant:

Could Be Class Constant
#######################


The following property is defined and used, but never modified. This may be transformed into a constant.

Starting with PHP 5.6, even array() may be defined as constants.

+--------------+------------------------------+
| Command Line | Classes/CouldBeClassConstant |
+--------------+------------------------------+
| clearPHP     |                              |
+--------------+------------------------------+
| Analyzers    | :ref:`Analyze`               |
+--------------+------------------------------+



.. _could-be-static:

Could Be Static
###############


This global is only used in one function or method. It may be called 'static', instead of global. This will allow you to keep the value between call to the function, but will not be accessible outside this function.

.. code-block:: php

   <?php
   function x() {
       static $variableIsReservedForX; // only accessible within x(), even between calls.
       global $variableIsGlobal;       //      accessible everywhere in the application
   }
   ?>

+--------------+--------------------------+
| Command Line | Structures/CouldBeStatic |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Analyze`           |
+--------------+--------------------------+



.. _could-use-short-assignation:

Could Use Short Assignation
###########################


Some operators have a 'do-and-assign' version, that looks like a compacted version for = and the operator. 

$x = $x + 2; may be written $x += 2;

This approach is good for readability, and saves some memory in the process. 

List of those operators : +=, -=, \*=, /=, %=, \*\*=, .=, &=, \|=, ^=, >>=, <<=

+--------------+-------------------------------------------------------------------------------------------------------------+
| Command Line | Structures/CouldUseShortAssignation                                                                         |
+--------------+-------------------------------------------------------------------------------------------------------------+
| clearPHP     | `use-short-assignations <https://github.com/dseguy/clearPHP/tree/master/rules/use-short-assignations.md>`__ |
+--------------+-------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Performances`                                                                         |
+--------------+-------------------------------------------------------------------------------------------------------------+



.. _could-use-self:

Could use self
##############


Self keywords refers to the current class, or any of its parents. Using it is just as fast as the full classname, it is as readable and it is will not be changed upon class or namespace change.

+--------------+-----------------------+
| Command Line | Classes/ShouldUseSelf |
+--------------+-----------------------+
| clearPHP     |                       |
+--------------+-----------------------+
| Analyzers    | :ref:`Analyze`        |
+--------------+-----------------------+



.. _dangling-array-references:

Dangling Array References
#########################


It is highly recommended to unset blind variables when they are set up as references after a loop. 

When omitting this step, the next loop that will also require this variable will deal with garbage values, and produce unexpected results.

Add unset( $as\_variable) after the loop.

+--------------+-----------------------------------------------------------------------------------------------------------+
| Command Line | Structures/DanglingArrayReferences                                                                        |
+--------------+-----------------------------------------------------------------------------------------------------------+
| clearPHP     | `no-dangling-reference <https://github.com/dseguy/clearPHP/tree/master/rules/no-dangling-reference.md>`__ |
+--------------+-----------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                            |
+--------------+-----------------------------------------------------------------------------------------------------------+



.. _deep-definitions:

Deep Definitions
################


Structures, such as functions, classes, interfaces, traits, etc. may be defined anywhere in the code, including inside functions. This is legit code for PHP. 

Since the availability of \_\_autoload, there is no need for that kind of code. Structures should be defined, and accessible to the autoloading. Inclusion and deep definitions should be avoided, as they compell code to load some definitions, while autoloading will only load them if needed. 

Functions are excluded from autoload, but shall be gathered in libraries, and not hidden inside other code.

Constants definitions are tolerated inside functions : they may be used for avoiding repeat, or noting the usage of such function.

+--------------+---------------------------+
| Command Line | Functions/DeepDefinitions |
+--------------+---------------------------+
| clearPHP     |                           |
+--------------+---------------------------+
| Analyzers    | :ref:`Analyze`            |
+--------------+---------------------------+



.. _define-with-array:

Define With Array
#################


PHP 7.0 has the ability to define an array as a constant, using the define() native call. This was not possible until that version, only with the const keyword.

+--------------+------------------------------------------------------------------------------------------------------------+
| Command Line | Php/DefineWithArray                                                                                        |
+--------------+------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                            |
+--------------+------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+--------------+------------------------------------------------------------------------------------------------------------+



.. _deprecated-code:

Deprecated code
###############


The following functions have been deprecated in PHP. Whatever the version you are using, it is recommended to stop using them and replace them with a durable equivalent.

+--------------+-------------------------------------------------------------------------------------------+
| Command Line | Php/Deprecated                                                                            |
+--------------+-------------------------------------------------------------------------------------------+
| clearPHP     | `no-deprecated <https://github.com/dseguy/clearPHP/tree/master/rules/no-deprecated.md>`__ |
+--------------+-------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                            |
+--------------+-------------------------------------------------------------------------------------------+



.. _dereferencing-string-and-arrays:

Dereferencing String And Arrays
###############################


PHP 5.5 introduced the direct dereferencing of strings and array. No need anymore for an intermediate variable between a string and array (or any expression generating such value) and accessing an index.

$x = array(4,5,6); 
$y = $x[2] ; // is 6

May be replaced by 
$y = array(4,5,6)[2];
$y = [4,5,6][2];

+--------------+------------------------------------------------------+
| Command Line | Structures/DereferencingAS                           |
+--------------+------------------------------------------------------+
| clearPHP     |                                                      |
+--------------+------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54` |
+--------------+------------------------------------------------------+



.. _direct-injection:

Direct Injection
################


The following code act directly upon PHP incoming variables like $\_GET and $\_POST. This make those snippet very unsafe.

+--------------+--------------------------+
| Command Line | Security/DirectInjection |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Security`          |
+--------------+--------------------------+



.. _don't-change-incomings:

Don't Change Incomings
######################


PHP hands over a lot of information using special variables like $\_GET, $\_POST, etc... Modifying those variables and those values inside de variables means that the original content will be lost, while it will still look like raw data, and, as such, will be untrustworthy.

It is recommended to put the modified values in another variable, and keep the original one intact.

+--------------+--------------------------------------+
| Command Line | Structures/NoChangeIncomingVariables |
+--------------+--------------------------------------+
| clearPHP     |                                      |
+--------------+--------------------------------------+
| Analyzers    | :ref:`Analyze`                       |
+--------------+--------------------------------------+



.. _double-assignation:

Double Assignation
##################


This is when a same container (variable, property, array index) are assigned with values twice in a row. One of them is probably a debug instruction, that was forgotten.

+--------------+------------------------------+
| Command Line | Structures/DoubleAssignation |
+--------------+------------------------------+
| clearPHP     |                              |
+--------------+------------------------------+
| Analyzers    | :ref:`Analyze`               |
+--------------+------------------------------+



.. _double-instruction:

Double Instruction
##################


Twice the same call in a row. This is worth a check.

+--------------+------------------------------+
| Command Line | Structures/DoubleInstruction |
+--------------+------------------------------+
| clearPHP     |                              |
+--------------+------------------------------+
| Analyzers    | :ref:`Analyze`               |
+--------------+------------------------------+



.. _echo-with-concat:

Echo With Concat
################


Optimize your echo's by not concatenating at echo time, but serving all argument separated. This will save PHP a memory copy.
If values (literals and variables) are small enough, this won't have impact. Otherwise, this is less work and less memory waste.

echo $a, ' b ', $c;

instead of

echo  $a . ' b ' . $c;
echo $a b $c;

+--------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Command Line | Structures/EchoWithConcat                                                                                                             |
+--------------+---------------------------------------------------------------------------------------------------------------------------------------+
| clearPHP     | `no-unnecessary-string-concatenation <https://github.com/dseguy/clearPHP/tree/master/rules/no-unnecessary-string-concatenation.md>`__ |
+--------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Performances`, :ref:`Analyze`                                                                                                   |
+--------------+---------------------------------------------------------------------------------------------------------------------------------------+



.. _echo-concatenation:

Echo concatenation
##################


Echo accepts an arbitrary number of argument, and will automatically concatenate all incoming arguments. It is not necessary to concatenate values when calling echo and it will save a few commands.

+--------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Command Line | Structures/EchoArguments                                                                                                              |
+--------------+---------------------------------------------------------------------------------------------------------------------------------------+
| clearPHP     | `no-unnecessary-string-concatenation <https://github.com/dseguy/clearPHP/tree/master/rules/no-unnecessary-string-concatenation.md>`__ |
+--------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Performances`                                                                                                                   |
+--------------+---------------------------------------------------------------------------------------------------------------------------------------+



.. _else-if-versus-elseif:

Else If Versus Elseif
#####################


The keyword elseif SHOULD be used instead of else if so that all control keywords look like single words. (Directly quoted from the PHP-FIG documentation).

+--------------+-------------------------+
| Command Line | Structures/ElseIfElseif |
+--------------+-------------------------+
| clearPHP     |                         |
+--------------+-------------------------+
| Analyzers    | :ref:`Analyze`          |
+--------------+-------------------------+



.. _empty-classes:

Empty Classes
#############


List of empty classes. Classes that are directly derived from an exception are not considered here.

+--------------+--------------------+
| Command Line | Classes/EmptyClass |
+--------------+--------------------+
| clearPHP     |                    |
+--------------+--------------------+
| Analyzers    | :ref:`Analyze`     |
+--------------+--------------------+



.. _empty-function:

Empty Function
##############


Function or method whose body is empty. Such functions or methods are rarely useful. As a bare minimum, the function should return some useful value, even if constant.

+--------------+-------------------------+
| Command Line | Functions/EmptyFunction |
+--------------+-------------------------+
| clearPHP     |                         |
+--------------+-------------------------+
| Analyzers    | :ref:`Analyze`          |
+--------------+-------------------------+



.. _empty-instructions:

Empty Instructions
##################


Empty instructions are part of the code that have no instructions. This may be trailing semi-colon or empty blocks for if-then structures.

$condition = 3;;;;
if ($condition) { }

+--------------+----------------------------------------------+
| Command Line | Structures/EmptyLines                        |
+--------------+----------------------------------------------+
| clearPHP     |                                              |
+--------------+----------------------------------------------+
| Analyzers    | :ref:`Dead code <dead-code>`, :ref:`Analyze` |
+--------------+----------------------------------------------+



.. _empty-interfaces:

Empty Interfaces
################


Empty interfaces. Interfaces should have some function defined, and not be totally empty.

+--------------+---------------------------+
| Command Line | Interfaces/EmptyInterface |
+--------------+---------------------------+
| clearPHP     |                           |
+--------------+---------------------------+
| Analyzers    | :ref:`Analyze`            |
+--------------+---------------------------+



.. _empty-list:

Empty List
##########


Empty list() are not allowed anymore in PHP 7. There must be at least one variable in the list call.

+--------------+----------------------------------------------------------------------+
| Command Line | Php/EmptyList                                                        |
+--------------+----------------------------------------------------------------------+
| clearPHP     |                                                                      |
+--------------+----------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71` |
+--------------+----------------------------------------------------------------------+



.. _empty-namespace:

Empty Namespace
###############


Declaring a namespace in the code and not using it for structure declarations (classes, interfaces, etc...) or global instructions is useless.

+--------------+-----------------------------------------------------------------------------------------------------+
| Command Line | Namespaces/EmptyNamespace                                                                           |
+--------------+-----------------------------------------------------------------------------------------------------+
| clearPHP     | `no-empty-namespace <https://github.com/dseguy/clearPHP/tree/master/rules/no-empty-namespace.md>`__ |
+--------------+-----------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Dead code <dead-code>`                                                        |
+--------------+-----------------------------------------------------------------------------------------------------+



.. _empty-try-catch:

Empty Try Catch
###############


The code does try, then catch errors but do no act upon the error. 

At worse, the error should be logged somewhere, so as to measure the actual usage of the log.

catch( Exception $e) should be banned, as they will simply ignore any error.

+--------------+--------------------------+
| Command Line | Structures/EmptyTryCatch |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Analyze`           |
+--------------+--------------------------+



.. _empty-with-expression:

Empty With Expression
#####################


The function 'empty()' doesn't accept expressions until PHP 5.5. Until then, it is necessary to store the result of the expression in a variable and then, test it with empty().

+--------------+------------------------------------------------------------------------------------------------------------+
| Command Line | Structures/EmptyWithExpression                                                                             |
+--------------+------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                            |
+--------------+------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP71` |
+--------------+------------------------------------------------------------------------------------------------------------+



.. _empty-traits:

Empty traits
############


List of all empty trait defined in the code. May be they are RFU.

+--------------+-------------------+
| Command Line | Traits/EmptyTrait |
+--------------+-------------------+
| clearPHP     |                   |
+--------------+-------------------+
| Analyzers    | :ref:`Analyze`    |
+--------------+-------------------+



.. _eval-without-try:

Eval Without Try
################


Eval() emits a ParseError Exception with PHP 7 and later. Catching this exception is the recommended way to handle errors while using the eval function.

+--------------+----------------------------------------------------------------------------------------------------------------------------+
| Command Line | Structures/EvalWithoutTry                                                                                                  |
+--------------+----------------------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                                            |
+--------------+----------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+--------------+----------------------------------------------------------------------------------------------------------------------------+



.. _eval()-usage:

Eval() Usage
############


Using eval is bad for performances (compilation time), for caches (it won't be compiled), and for security (if it includes external data).

Most of the time, it is possible to replace the code by some standard PHP, like variable variable for accessing a variable for which you have the name.
At worse, including a pre-generated file will be faster.

+--------------+-------------------------------------------------------------------------------+
| Command Line | Structures/EvalUsage                                                          |
+--------------+-------------------------------------------------------------------------------+
| clearPHP     | `no-eval <https://github.com/dseguy/clearPHP/tree/master/rules/no-eval.md>`__ |
+--------------+-------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Performances`                                           |
+--------------+-------------------------------------------------------------------------------+



.. _exit()-usage:

Exit() Usage
############


Using exit or die() in the code makes the code untestable (it will break unit tests). Morover, if there is no reason or string to display, it may take a long time to spot where the application is stuck. 

Try exiting the function/class, or throw exception that may be caught later in the code.

+--------------+-------------------------------------------------------------------------------+
| Command Line | Structures/ExitUsage                                                          |
+--------------+-------------------------------------------------------------------------------+
| clearPHP     | `no-exit <https://github.com/dseguy/clearPHP/tree/master/rules/no-exit.md>`__ |
+--------------+-------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                |
+--------------+-------------------------------------------------------------------------------+



.. _exponent-usage:

Exponent usage
##############


Usage of the \*\* operator or \*\*=, to make exponents.

+--------------+---------------------------------------------------------------------------------+
| Command Line | Php/ExponentUsage                                                               |
+--------------+---------------------------------------------------------------------------------+
| clearPHP     |                                                                                 |
+--------------+---------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55` |
+--------------+---------------------------------------------------------------------------------+



.. _extension-fann:

Extension fann
##############


ext/fann

+--------------+--------------------+
| Command Line | Extensions/Extfann |
+--------------+--------------------+
| clearPHP     |                    |
+--------------+--------------------+
| Analyzers    | :ref:`Analyze`     |
+--------------+--------------------+



.. _followed-injections:

Followed injections
###################


There is a link between those function and some of the sensitive PHP functions. This may lead to Injections of various kind.

+--------------+--------------------------+
| Command Line | Security/RemoteInjection |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Security`          |
+--------------+--------------------------+



.. _for-using-functioncall:

For Using Functioncall
######################


It is advised to avoid functioncall in the for() statement. For example, $nb = count($array); for($i = 0; $i < $nb; $i++) {} is faster than for($i = 0; $i < count($array); $i++) {}.

+--------------+---------------------------------------------------------------------------------------------------------------+
| Command Line | Structures/ForWithFunctioncall                                                                                |
+--------------+---------------------------------------------------------------------------------------------------------------+
| clearPHP     | `no-functioncall-in-loop <https://github.com/dseguy/clearPHP/tree/master/rules/no-functioncall-in-loop.md>`__ |
+--------------+---------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Performances`                                                                           |
+--------------+---------------------------------------------------------------------------------------------------------------+



.. _foreach-dont-change-pointer:

Foreach Dont Change Pointer
###########################


In PHP 7.0, the foreach loop won't change the internal pointer of the array, but will work on a copy. So, applying array pointer's functions such as current or next to the source array won't have the same behavior than in PHP 5.

+--------------+------------------------------------------------------+
| Command Line | Php/ForeachDontChangePointer                         |
+--------------+------------------------------------------------------+
| clearPHP     |                                                      |
+--------------+------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71` |
+--------------+------------------------------------------------------+



.. _foreach-needs-reference-array:

Foreach Needs Reference Array
#############################


When using foreach with a reference as value, the source must be a referenced array, which is a variable (or array or property or static property). 
When the array is the result of an expression, the array is not kept in memory after the foreach loop, and any change made with & are lost.

This will do nothing

.. code-block:: php

   <?php
       foreach(array(1,2,3) as &$value) {
           $value \*= 2;
       }
   ?>


This will have an actual effect

.. code-block:: php

   <?php
       $array = array(1,2,3);
       foreach($array as &$value) {
           $value \*= 2;
       }
   ?>

+--------------+----------------------------------------+
| Command Line | Structures/ForeachNeedReferencedSource |
+--------------+----------------------------------------+
| clearPHP     |                                        |
+--------------+----------------------------------------+
| Analyzers    | :ref:`Analyze`                         |
+--------------+----------------------------------------+



.. _foreach-reference-is-not-modified:

Foreach Reference Is Not Modified
#################################


Foreach statement may loop using a reference, especially when the loop has to change values of the array it is looping on. In the spotted loop, reference are used but never modified. They may be removed.

+--------------+------------------------------------------+
| Command Line | Structures/ForeachReferenceIsNotModified |
+--------------+------------------------------------------+
| clearPHP     |                                          |
+--------------+------------------------------------------+
| Analyzers    | :ref:`Analyze`                           |
+--------------+------------------------------------------+



.. _foreach-with-list():

Foreach With list()
###################


PHP 5.5 introduced the ability to use list in foreach loops. This was not possible in the earlier versions.

.. code-block:: php

   <?php
       foreach($array as list($a, $b)) { 
           // do something 
       }
   ?>


Previously, it was compulsory to extract the data from the blind array : 

.. code-block:: php

   <?php
       foreach($array as $c) { 
           list($a, $b) = $c;
           // do something 
       }
   ?>

+--------------+------------------------------------------------------+
| Command Line | Structures/ForeachWithList                           |
+--------------+------------------------------------------------------+
| clearPHP     |                                                      |
+--------------+------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54` |
+--------------+------------------------------------------------------+



.. _forgotten-visibility:

Forgotten Visibility
####################


Some classes elements (constant, property, method) are missing their explicit visibility. By default, it is public.

It should at least be mentioned as public, or may be reviewed as protected or private.

+--------------+-------------------------------------------------------------------------------------------------------------+
| Command Line | Classes/NonPpp                                                                                              |
+--------------+-------------------------------------------------------------------------------------------------------------+
| clearPHP     | `always-have-visibility <https://github.com/dseguy/clearPHP/tree/master/rules/always-have-visibility.md>`__ |
+--------------+-------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                              |
+--------------+-------------------------------------------------------------------------------------------------------------+



.. _forgotten-whitespace:

Forgotten Whitespace
####################


Those are white space that are at either end of a script : at the beginning or the end. 

Usually, such white space are forgotten, and may end up summoning the infamous 'headers already sent' error. It is better to remove them.

+--------------+--------------------------------+
| Command Line | Structures/ForgottenWhiteSpace |
+--------------+--------------------------------+
| clearPHP     |                                |
+--------------+--------------------------------+
| Analyzers    | :ref:`Analyze`                 |
+--------------+--------------------------------+



.. _fully-qualified-constants:

Fully Qualified Constants
#########################


When defining constants with define() function, it is possible to include the actual namespace : 

define('a\b\c', 1); 

However, the name should be fully qualified without the initial \. Here, \a\b\c constant will never be accessible as a namespace constant, though it will be accessible via the constant() function.

Also, the namespace will be absolute, and not a relative namespace of the current one.

+--------------+-----------------------------------+
| Command Line | Namespaces/ConstantFullyQualified |
+--------------+-----------------------------------+
| clearPHP     |                                   |
+--------------+-----------------------------------+
| Analyzers    | :ref:`Analyze`                    |
+--------------+-----------------------------------+



.. _function-subscripting,-old-style:

Function Subscripting, Old Style
################################


Since PHP 5.4, it is now possible use function results as an array, and access directly its element : 

$x = f()[1];

instead of spreading this on two lines : 

$tmp = f();
$x = $tmp[1];

+--------------+------------------------------------+
| Command Line | Structures/FunctionPreSubscripting |
+--------------+------------------------------------+
| clearPHP     |                                    |
+--------------+------------------------------------+
| Analyzers    | :ref:`Analyze`                     |
+--------------+------------------------------------+



.. _function-subscripting:

Function subscripting
#####################


This is a new PHP 5.4 feature, where one may use the result of a method directly as an array, given that the method actually returns an array. 

This was not possible until PHP 5.4. Is used to be necessary to put the result in a variable, and then access the desired index.

+--------------+---------------------------------+
| Command Line | Structures/FunctionSubscripting |
+--------------+---------------------------------+
| clearPHP     |                                 |
+--------------+---------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`       |
+--------------+---------------------------------+



.. _functions-removed-in-php-5.4:

Functions Removed In PHP 5.4
############################


Those functions were removed in PHP 5.4.

+--------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+
| Command Line | Php/Php54RemovedFunctions                                                                                                                             |
+--------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                                                                       |
+--------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP71` |
+--------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _functions-removed-in-php-5.5:

Functions Removed In PHP 5.5
############################


Those functions were removed in PHP 5.5.

+--------------+------------------------------------------------------------------------------------------------------------+
| Command Line | Php/Php55RemovedFunctions                                                                                  |
+--------------+------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                            |
+--------------+------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP71` |
+--------------+------------------------------------------------------------------------------------------------------------+



.. _functions-in-loop-calls:

Functions in loop calls
#######################


The following functions call each-other in a loop fashion : A -> B -> A.

When those functions have no other interaction, the code is useless and should be dropped.

Loops of size 2, 3 and 4 are supported.

+--------------+-------------------------------------+
| Command Line | Functions/LoopCalling               |
+--------------+-------------------------------------+
| clearPHP     |                                     |
+--------------+-------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Performances` |
+--------------+-------------------------------------+



.. _global-inside-loop:

Global Inside Loop
##################


The global keyword should be out of loops. It will be evaluated each loop, slowing the whole process.

+--------------+------------------------------+
| Command Line | Structures/GlobalOutsideLoop |
+--------------+------------------------------+
| clearPHP     |                              |
+--------------+------------------------------+
| Analyzers    | :ref:`Performances`          |
+--------------+------------------------------+



.. _global-usage:

Global usage
############


List usage of globals variables, with global keywords or direct access to $GLOBALS.

It is recommended to avoid using global variables, at it makes it very difficult to track changes in values across the whole application.

+--------------+-----------------------------------------------------------------------------------+
| Command Line | Structures/GlobalUsage                                                            |
+--------------+-----------------------------------------------------------------------------------+
| clearPHP     | `no-global <https://github.com/dseguy/clearPHP/tree/master/rules/no-global.md>`__ |
+--------------+-----------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                    |
+--------------+-----------------------------------------------------------------------------------+



.. _hardcoded-passwords:

Hardcoded passwords
###################


Hardcoding passwords is a bad idea. Not only it make the code difficult to change, but it is an information leak. It is better to hide this kind of information out of the code.

+--------------+---------------------------------------------------------------------------------------------------------------+
| Command Line | Functions/HardcodedPasswords                                                                                  |
+--------------+---------------------------------------------------------------------------------------------------------------+
| clearPHP     | `no-hardcoded-credential <https://github.com/dseguy/clearPHP/tree/master/rules/no-hardcoded-credential.md>`__ |
+--------------+---------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                                |
+--------------+---------------------------------------------------------------------------------------------------------------+



.. _hash-algorithms:

Hash Algorithms
###############


There is a long but limited list of hashing algorithm available to PHP. The one found below doesn't seem to be existing.

+--------------+----------------+
| Command Line | Php/HashAlgos  |
+--------------+----------------+
| clearPHP     |                |
+--------------+----------------+
| Analyzers    | :ref:`Analyze` |
+--------------+----------------+



.. _hash-algorithms-incompatible-with-php-5.3:

Hash Algorithms incompatible with PHP 5.3
#########################################


List of hash algorithms incompatible with PHP 5.3. They were introduced in newer version, and, as such, are not available with older versions.

+--------------+---------------------------+
| Command Line | Php/HashAlgos53           |
+--------------+---------------------------+
| clearPHP     |                           |
+--------------+---------------------------+
| Analyzers    | :ref:`CompatibilityPHP53` |
+--------------+---------------------------+



.. _hash-algorithms-incompatible-with-php-5.4/5:

Hash Algorithms incompatible with PHP 5.4/5
###########################################


List of hash algorithms incompatible with PHP 5.4 and 5.5. They were introduced in newer version, or removed in PHP 5.4. As such, they are not available with older versions.

+--------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Command Line | Php/HashAlgos54                                                                                                                       |
+--------------+---------------------------------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                                                       |
+--------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP71` |
+--------------+---------------------------------------------------------------------------------------------------------------------------------------+



.. _hexadecimal-in-string:

Hexadecimal In String
#####################


Mark strings that may be confused with hexadecimal.

+--------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Command Line | Type/HexadecimalString                                                                                                                                           |
+--------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                                                                                  |
+--------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP71` |
+--------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _htmlentities-calls:

Htmlentities Calls
##################


htmlentities() and htmlspecialchars() are used to prevent injecting special characters in HTML code. As a bare minimum, they take a string and encode it for HTML.

The second argument of the functions is the type of protection. The protection may apply to quotes or not, to HTML4 or 5, etc. It is highly recommended to set it explicitely.

The third argument of the functions is the encoding of the string. In PHP 5.3, it as 'ISO-8859-1', in 5.4, was 'UTF-8', and in 5.6, it is now default\_charset, a php.ini configuration that has the default value of 'UTF-8'. It is highly recommended to set this argument too, to avoid distortions from the configuration.

Also, note that arguments 2 and 3 are constants and string (respectively), and should be issued from the list of values available in the manual. Other values than those will make PHP use the default values.

+--------------+-----------------------------+
| Command Line | Structures/Htmlentitiescall |
+--------------+-----------------------------+
| clearPHP     |                             |
+--------------+-----------------------------+
| Analyzers    | :ref:`Analyze`              |
+--------------+-----------------------------+



.. _implement-is-for-interface:

Implement is for interface
##########################


When deriving classes, implements should be used for interfaces, and extends with classes.

+--------------+---------------------------------+
| Command Line | Classes/ImplementIsForInterface |
+--------------+---------------------------------+
| clearPHP     |                                 |
+--------------+---------------------------------+
| Analyzers    | :ref:`Analyze`                  |
+--------------+---------------------------------+



.. _implicit-global:

Implicit global
###############


Global variables, that are used in local scope with global Keyword, but are not declared as Global in the global scope. They may be mistaken with distinct values, while, in PHP, variables in the global scope are truely global.

+--------------+---------------------------+
| Command Line | Structures/ImplicitGlobal |
+--------------+---------------------------+
| clearPHP     |                           |
+--------------+---------------------------+
| Analyzers    | :ref:`Analyze`            |
+--------------+---------------------------+



.. _incompilable-files:

Incompilable Files
##################


Files that cannot be compiled, and, as such, be run by PHP. Scripts are linted against PHP versions 5.2, 5.3, 5.4, 5.5, 5.6, 7.0-dev and 7.1. 

This is usually undesirable, as all code must compile before being executed. It may simply be that such files are not compilable because they are not yet ready for an upcoming PHP version.

Code that is incompilable with older PHP versions means that the code is breaking backward compatibility : good or bad is project decision.

+--------------+-----------------------------------------------------------------------------------------------+
| Command Line | Php/Incompilable                                                                              |
+--------------+-----------------------------------------------------------------------------------------------+
| clearPHP     | `no-incompilable <https://github.com/dseguy/clearPHP/tree/master/rules/no-incompilable.md>`__ |
+--------------+-----------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                |
+--------------+-----------------------------------------------------------------------------------------------+



.. _indices-are-int-or-string:

Indices Are Int Or String
#########################


Indices in an array notation such as $array['indice'] should be integers or string. Boolean, null or float will be converted to their integer or string equivalent.

Even integers inside strings will be converted, though not all of them : $array['8'] and $array[8] are the same, though $array['08'] is not. 

As a general rule of thumb, only use integers or strings that don\'t look like integers.

+--------------+----------------------------------+
| Command Line | Structures/IndicesAreIntOrString |
+--------------+----------------------------------+
| clearPHP     |                                  |
+--------------+----------------------------------+
| Analyzers    | :ref:`Analyze`                   |
+--------------+----------------------------------+



.. _instantiating-abstract-class:

Instantiating Abstract Class
############################


Those code will raise a PHP fatal error at execution time : 'Cannot instantiate abstract class'. The classes are actually abstract classes, and should be derived into a concrete class to be instantiated.

+--------------+------------------------------------+
| Command Line | Classes/InstantiatingAbstractClass |
+--------------+------------------------------------+
| clearPHP     |                                    |
+--------------+------------------------------------+
| Analyzers    | :ref:`Analyze`                     |
+--------------+------------------------------------+



.. _invalid-constant-name:

Invalid constant name
#####################


According to PHP's manual, constant names, ' A valid constant name starts with a letter or underscore, followed by any number of letters, numbers, or underscores.'.

Constant, when defined using 'define()' function, must follow this regex : /[a-zA-Z\_\x7f-\xff][a-zA-Z0-9\_\x7f-\xff]\*/

+--------------+-----------------------+
| Command Line | Constants/InvalidName |
+--------------+-----------------------+
| clearPHP     |                       |
+--------------+-----------------------+
| Analyzers    | :ref:`Analyze`        |
+--------------+-----------------------+



.. _isset-with-constant:

Isset With Constant
###################


Until PHP 7, it was possible to use arrays as constants, but it was not possible to test them with isset.

.. code-block:: php

   <?php
   const X = [1,2,3];
   
   if (isset(X[4])) {}
   ?>


This would yield an error : 

Fatal error: Cannot use isset() on the result of an expression (you can use "null !== expression" instead) in test.php on line 7

This is a backward incompatibility.

+--------------+------------------------------------------------------------------------------------------------------------+
| Command Line | Structures/IssetWithConstant                                                                               |
+--------------+------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                            |
+--------------+------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+--------------+------------------------------------------------------------------------------------------------------------+



.. _join-file():

Join file()
###########


Applying join (or implode) to the result of file will provide the same results than file\_get\_contents(), but at a higher cost of memory and processing.

Always use file\_get\_contents() to get the content of a file as a string.

+--------------+-----------------------+
| Command Line | Performances/JoinFile |
+--------------+-----------------------+
| clearPHP     |                       |
+--------------+-----------------------+
| Analyzers    | :ref:`Performances`   |
+--------------+-----------------------+



.. _list-with-appends:

List With Appends
#################


List() behavior has changed in PHP 7.0 and it has impact on the indexing when list is used with the [] operator.

+--------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Command Line | Php/ListWithAppends                                                                                                                                                              |
+--------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                                                                                                  |
+--------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP71` |
+--------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _locally-unused-property:

Locally Unused Property
#######################


Those properties are defined in a class, and this class doesn't have any method that makes use of them. 

While this is syntacticly correct, it is unusual that defined ressources are used in a child class. It may be worth moving the definition to another class, or to move accessing methods to the class.

+--------------+----------------------------------------------+
| Command Line | Classes/LocallyUnusedProperty                |
+--------------+----------------------------------------------+
| clearPHP     |                                              |
+--------------+----------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Dead code <dead-code>` |
+--------------+----------------------------------------------+



.. _logical-should-use-&&,-||,-^:

Logical should use &&, \|\|, ^
##############################


Logical operators come in two flavors :  and / &&, \|\| / or, ^ / xor. However, they are not exchangeable, as && and and have different precedence. 

It is recommended to use the symbol operators, rather than the letter ones.

+--------------+---------------------------------------------------------------------------------------------------+
| Command Line | Php/LogicalInLetters                                                                              |
+--------------+---------------------------------------------------------------------------------------------------+
| clearPHP     | `no-letter-logical <https://github.com/dseguy/clearPHP/tree/master/rules/no-letter-logical.md>`__ |
+--------------+---------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                    |
+--------------+---------------------------------------------------------------------------------------------------+



.. _lone-blocks:

Lone blocks
###########


Blocks are compulsory when defining a structure, such as a class or a function. They are most often used with flow control instructions, like if then or switch. 

Blocks are also valid syntax that group several instructions together, though it has no effect at all, except confuse the reader. Most often, it is a ruin from a previous flow control instruction, whose condition was removed or commented. They should be removed.

+--------------+----------------------+
| Command Line | Structures/LoneBlock |
+--------------+----------------------+
| clearPHP     |                      |
+--------------+----------------------+
| Analyzers    | :ref:`Analyze`       |
+--------------+----------------------+



.. _lost-references:

Lost References
###############


When assigning a referenced variable with another reference, the initial reference is lost, while the intend was to transfer the content. 

Do not reassign a reference with another reference. Assign new content to the reference to change its value.

+--------------+--------------------------+
| Command Line | Variables/LostReferences |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Analyze`           |
+--------------+--------------------------+



.. _magic-visibility:

Magic Visibility
################


The magic methods must have public visibility and cannot be static

+--------------+----------------------------------------------------------------------------------------------------------------------------+
| Command Line | Classes/toStringPss                                                                                                        |
+--------------+----------------------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                                            |
+--------------+----------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+--------------+----------------------------------------------------------------------------------------------------------------------------+



.. _malformed-octal:

Malformed Octal
###############


Those numbers starts with a 0, so they are using the PHP octal convention. Therefore, one can't use 8 or 9 figures in those numbers, as they don't belong to the octal base. The resulting number will be truncated at the first erroneous figure. For example, 090 is actually 0, and 02689 is actually 22. 

Also, note that very large octal, usually with more than 21 figures, will be turned into a real number and undergo a reduction in precision.

+--------------+---------------------+
| Command Line | Type/MalformedOctal |
+--------------+---------------------+
| clearPHP     |                     |
+--------------+---------------------+
| Analyzers    | :ref:`Analyze`      |
+--------------+---------------------+



.. _methodcall-on-new:

Methodcall On New
#################


This was added in PHP 5.4+

+--------------+---------------------------+
| Command Line | Php/MethodCallOnNew       |
+--------------+---------------------------+
| clearPHP     |                           |
+--------------+---------------------------+
| Analyzers    | :ref:`CompatibilityPHP53` |
+--------------+---------------------------+



.. _mixed-keys:

Mixed Keys
##########


When defining default values in arrays, it is recommended to avoid mixing constant and literals, as PHP may mistake them and overwrite a few of them.

Either switch to a newer version of PHP (5.5 or newer), or make sure the resulting array is the one you expect. If not, reorder the definitions.

+--------------+------------------------------------------------------+
| Command Line | Arrays/MixedKeys                                     |
+--------------+------------------------------------------------------+
| clearPHP     |                                                      |
+--------------+------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54` |
+--------------+------------------------------------------------------+



.. _multiple-class-declarations:

Multiple Class Declarations
###########################


It is possible to declare several times the same class in the code. PHP will not notice it until execution time, since declarations may be conditional. 

It is recommended to avoid declaring several times the same class in the code. At least, separate them with namespaces, they are for here for that purpose.

+--------------+------------------------------+
| Command Line | Classes/MultipleDeclarations |
+--------------+------------------------------+
| clearPHP     |                              |
+--------------+------------------------------+
| Analyzers    | :ref:`Analyze`               |
+--------------+------------------------------+



.. _multiple-constant-definition:

Multiple Constant Definition
############################


Some constants are defined several times in your code. This will lead to a fatal error, if they are defined during the same execution.

+--------------+--------------------------------------+
| Command Line | Constants/MultipleConstantDefinition |
+--------------+--------------------------------------+
| clearPHP     |                                      |
+--------------+--------------------------------------+
| Analyzers    | :ref:`Analyze`                       |
+--------------+--------------------------------------+



.. _multiple-definition-of-the-same-argument:

Multiple Definition of the same argument
########################################


A method's signature is holding twice (or more) the same argument. For example, function x ($a, $a) { ... }. 

This is accepted as is by PHP, and the last parameter's value will be assigned to the variable : 

function x ($a, $a) { print $a; };
x(1,2); => will display 2

However, this is not common programming practise : all arguments should be named differently.

+--------------+---------------------------------------------------------------------------------------------------------+
| Command Line | Functions/MultipleSameArguments                                                                         |
+--------------+---------------------------------------------------------------------------------------------------------+
| clearPHP     | `all-unique-arguments <https://github.com/dseguy/clearPHP/tree/master/rules/all-unique-arguments.md>`__ |
+--------------+---------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71`                                    |
+--------------+---------------------------------------------------------------------------------------------------------+



.. _multiple-index-definition:

Multiple Index Definition
#########################


List of all indexes that are defined multiple times in the same array. 

Example : $x = array(1 => 2, 2 => 3,  1 => 3);

They are indeed overwriting each other. This is most probably a typo.

+--------------+------------------------------+
| Command Line | Arrays/MultipleIdenticalKeys |
+--------------+------------------------------+
| clearPHP     |                              |
+--------------+------------------------------+
| Analyzers    | :ref:`Analyze`               |
+--------------+------------------------------+



.. _multiples-identical-case:

Multiples Identical Case
########################


Some cases are defined multiple times, but only one will be processed. Check the list of cases, and remove the extra one.

+--------------+---------------------------------------------------------------------------------------------------+
| Command Line | Structures/MultipleDefinedCase                                                                    |
+--------------+---------------------------------------------------------------------------------------------------+
| clearPHP     | `no-duplicate-case <https://github.com/dseguy/clearPHP/tree/master/rules/no-duplicate-case.md>`__ |
+--------------+---------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                    |
+--------------+---------------------------------------------------------------------------------------------------+



.. _multiply-by-one:

Multiply By One
###############


Multiplying by 1 is useless. 

If it is used to type cast a value to number, then casting (integer) or (real) is clearer.

+--------------+-----------------------------------------------------------------------------------------------+
| Command Line | Structures/MultiplyByOne                                                                      |
+--------------+-----------------------------------------------------------------------------------------------+
| clearPHP     | `no-useless-math <https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-math.md>`__ |
+--------------+-----------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                |
+--------------+-----------------------------------------------------------------------------------------------+



.. _must-return-methods:

Must Return Methods
###################


Those methods are expected to return a value that will be used later. Without return, they are useless.

+--------------+----------------------+
| Command Line | Functions/MustReturn |
+--------------+----------------------+
| clearPHP     |                      |
+--------------+----------------------+
| Analyzers    | :ref:`Analyze`       |
+--------------+----------------------+



.. _namespace-with-fully-qualified-name:

Namespace with fully qualified name
###################################


The 'namespace' keyword has actually 2 usages : one is for declaring namespace, such as namespace A\B\C, use as first instruction in the script.

It may also mean 'current namespace' : for example, namespace\A\B\C represents the constant C, in the sub-namespace A\B of the current namespace (which is whatever you want).

The PHP compiler makes no difference between 'namespace \A\B\C', and 'namespace\A\B\C'. In each case, it will try to locate the constant C in the namespace \A\B, and will generate a fatal error if it can't find it.

+--------------+------------------------------------+
| Command Line | Namespaces/NamespaceFullyQualified |
+--------------+------------------------------------+
| clearPHP     |                                    |
+--------------+------------------------------------+
| Analyzers    | :ref:`Analyze`                     |
+--------------+------------------------------------+



.. _nested-ternary:

Nested Ternary
##############


Ternary operators ($a == 1 ? $b : $c) are a convenient instruction to apply some condition, and avoid a if() structure when it is simple (like in a one liner). 

However, ternary operators tends to make the syntax very difficult to read when they are nested. It is then recommended to use an if() structure, and make the whole code readable.

+--------------+---------------------------------------------------------------------------------------------------+
| Command Line | Structures/NestedTernary                                                                          |
+--------------+---------------------------------------------------------------------------------------------------+
| clearPHP     | `no-nested-ternary <https://github.com/dseguy/clearPHP/tree/master/rules/no-nested-ternary.md>`__ |
+--------------+---------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                    |
+--------------+---------------------------------------------------------------------------------------------------+



.. _never-used-properties:

Never Used Properties
#####################


Properties that are never used. They are defined, but never actually used.

+--------------+---------------------------+
| Command Line | Classes/PropertyNeverUsed |
+--------------+---------------------------+
| clearPHP     |                           |
+--------------+---------------------------+
| Analyzers    | :ref:`Analyze`            |
+--------------+---------------------------+



.. _new-functions-in-php-5.4:

New functions in PHP 5.4
########################


PHP introduced new functions in PHP 5.4. If you have already defined functions with such names, you will get a conflict when trying to upgrade. It is advised to change those functions' name.

+--------------+------------------------------------------------------+
| Command Line | Php/Php54NewFunctions                                |
+--------------+------------------------------------------------------+
| clearPHP     |                                                      |
+--------------+------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP71` |
+--------------+------------------------------------------------------+



.. _new-functions-in-php-5.5:

New functions in PHP 5.5
########################


PHP introduced new functions in PHP 5.5. If you have already defined functions with such names, you will get a conflict when trying to upgrade. It is advised to change those functions' name.

+--------------+---------------------------------------------------------------------------------+
| Command Line | Php/Php55NewFunctions                                                           |
+--------------+---------------------------------------------------------------------------------+
| clearPHP     |                                                                                 |
+--------------+---------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP71` |
+--------------+---------------------------------------------------------------------------------+



.. _new-functions-in-php-5.6:

New functions in PHP 5.6
########################


PHP introduced new functions in PHP 5.6. If you have already defined functions with such names, you will get a conflict when trying to upgrade. It is advised to change those functions' name.

+--------------+---------------------------------------------------------------------------------+
| Command Line | Php/Php56NewFunctions                                                           |
+--------------+---------------------------------------------------------------------------------+
| clearPHP     |                                                                                 |
+--------------+---------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55` |
+--------------+---------------------------------------------------------------------------------+



.. _no-direct-call-to-magicmethod:

No Direct Call To MagicMethod
#############################


PHP magic methods, such as \_\_get(), \_\_set(), ... are supposed to bed used in an object environnement, and not with direct call. 

For example, print $x->\_\_get('a'); should be written $x->a;. 

Accessing those methods in a static way is also discouraged.

+--------------+---------------------------------+
| Command Line | Classes/DirectCallToMagicMethod |
+--------------+---------------------------------+
| clearPHP     |                                 |
+--------------+---------------------------------+
| Analyzers    | :ref:`Analyze`                  |
+--------------+---------------------------------+



.. _no-direct-usage:

No Direct Usage
###############


The results of the following functions shouldn't be used directly, but checked first. 

For example, glob() returns an array, unless some error happens, in which case it returns a boolean (false). In such case, however rare it is, plugging glob() directly in a foreach() loops will yield errors.

.. code-block:: php

   <?php
       // Used without check : 
       foreach(glob('.') as $file) { /\* do Something \*/ }.
       
       // Used without check : 
       $files = glob('.');
       if (!is\_array($files)) {
           foreach($files as $file) { /\* do Something \*/ }.
       }
   ?>

+--------------+--------------------------+
| Command Line | Structures/NoDirectUsage |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Analyze`           |
+--------------+--------------------------+



.. _no-hardcoded-ip:

No Hardcoded Ip
###############


Do not leave hard coded IP in your code.

+--------------+--------------------------+
| Command Line | Structures/NoHardcodedIp |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Analyze`           |
+--------------+--------------------------+



.. _no-hardcoded-path:

No Hardcoded Path
#################


It is not recommended to have literals when reaching for files. Either use \_\_FILE\_\_ and \_\_DIR\_\_ to make the path relative to the current file, or add some DOC\_ROOT as a configuration constant that will allow you to move your script later.

+--------------+---------------------------------------------------------------------------------------------------+
| Command Line | Structures/NoHardcodedPath                                                                        |
+--------------+---------------------------------------------------------------------------------------------------+
| clearPHP     | `no-hardcoded-path <https://github.com/dseguy/clearPHP/tree/master/rules/no-hardcoded-path.md>`__ |
+--------------+---------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                    |
+--------------+---------------------------------------------------------------------------------------------------+



.. _no-hardcoded-port:

No Hardcoded Port
#################


When connecting to a remove serve, port is an important information. It is recommended to make this configurable (with constant or configuration), to as to be able to change this value without changing the code.

+--------------+----------------------------+
| Command Line | Structures/NoHardcodedPort |
+--------------+----------------------------+
| clearPHP     |                            |
+--------------+----------------------------+
| Analyzers    | :ref:`Analyze`             |
+--------------+----------------------------+



.. _no-implied-if:

No Implied If
#############


It is possible to emulate a 'if...then' structure by using the operators 'and' and 'or'. Since optimizations will be applied to them : 
when the left operand of 'and' is false, the right one is not executed, as its result is useless; 
when the left operand of 'or' is true, the right one is not executed, as its result is useless; 

However, such structures are confusing. It is easy to misread them as conditions, and ignore an important logic step. 

It is recommended to use a real 'if then' structures, to make the condition readable.

+--------------+-------------------------------------------------------------------------------------------+
| Command Line | Structures/ImpliedIf                                                                      |
+--------------+-------------------------------------------------------------------------------------------+
| clearPHP     | `no-implied-if <https://github.com/dseguy/clearPHP/tree/master/rules/no-implied-if.md>`__ |
+--------------+-------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                            |
+--------------+-------------------------------------------------------------------------------------------+



.. _no-list-with-string:

No List With String
###################


list() can't be used anymore to access particular offset in a string. This should be done with substr() or $string[$offset] syntax.

+--------------+------------------------------------------------------------------------------------------------------------+
| Command Line | Php/NoListWithString                                                                                       |
+--------------+------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                            |
+--------------+------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+--------------+------------------------------------------------------------------------------------------------------------+



.. _no-parenthesis-for-language-construct:

No Parenthesis For Language Construct
#####################################


Some PHP language constructs, such are include, print, echo don't need parenthesis. They will handle parenthesis, but it is may lead to strange situations. 

It it better to avoid using parenthesis with echo, print, return, throw, include and require (and \_once).

+--------------+-------------------------------------------------------------------------------------------------------------------------------------------+
| Command Line | Structures/NoParenthesisForLanguageConstruct                                                                                              |
+--------------+-------------------------------------------------------------------------------------------------------------------------------------------+
| clearPHP     | `no-parenthesis-for-language-construct <https://github.com/dseguy/clearPHP/tree/master/rules/no-parenthesis-for-language-construct.md>`__ |
+--------------+-------------------------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                                                            |
+--------------+-------------------------------------------------------------------------------------------------------------------------------------------+



.. _no-public-access:

No Public Access
################


Properties are declared with public access, but are never used publicly. May be they can be made protected or private.

+--------------+------------------------+
| Command Line | Classes/NoPublicAccess |
+--------------+------------------------+
| clearPHP     |                        |
+--------------+------------------------+
| Analyzers    | :ref:`Analyze`         |
+--------------+------------------------+



.. _no-real-comparison:

No Real Comparison
##################


Avoid comparing decimal numbers with ==, ===, !==, != : those numbers have an error margin which is random, and makes it very difficult to match even if the compared value is a literal. 

Use formulas like 'abs($value - 1.2) < 0.0001' to approximate values with a given precision.

+--------------+-----------------------------------------------------------------------------------------------------+
| Command Line | Type/NoRealComparison                                                                               |
+--------------+-----------------------------------------------------------------------------------------------------+
| clearPHP     | `no-real-comparison <https://github.com/dseguy/clearPHP/tree/master/rules/no-real-comparison.md>`__ |
+--------------+-----------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                      |
+--------------+-----------------------------------------------------------------------------------------------------+



.. _no-self-referencing-constant:

No Self Referencing Constant
############################


It is not possible to use 'self' when defining a constant in a class. It will yield a fatal error at runtime. 

.. code-block:: php

   <?php
       class a { 
           const C1 = 1; 
           const C2 = self::C1; 
           const C3 = a::C3; 
       }
   ?>


The code needs to reference the full class's name to do so, without using the current class's name. 

.. code-block:: php

   <?php
       class a { 
           const C1 = 1; 
           const C2 = a::C1; 
       }
   ?>

+--------------+-----------------------------------+
| Command Line | Classes/NoSelfReferencingConstant |
+--------------+-----------------------------------+
| clearPHP     |                                   |
+--------------+-----------------------------------+
| Analyzers    | :ref:`Analyze`                    |
+--------------+-----------------------------------+



.. _no-array\_merge-in-loops:

No array\_merge In Loops
########################


The function array\_merge() is memory intensive : every call will duplicate the arguments in memory, before merging them. 

Since arrays way be quite big, it is recommended to avoid using merge in a loop. Instead, one should use array\_merge with as many arguments as possible, making the merge a on time call.

This may be achieved easily with the variadic operator : array\_merge(...array\_collecting\_the\_arrays), or 
with call\_user\_func\_array('array\_merge', array\_collecting\_the\_arrays()). The Variadic is slightly faster than call\_user\_func\_array.

+--------------+-------------------------------------------------------------------------------------------------------------+
| Command Line | Performances/ArrayMergeInLoops                                                                              |
+--------------+-------------------------------------------------------------------------------------------------------------+
| clearPHP     | `no-array_merge-in-loop <https://github.com/dseguy/clearPHP/tree/master/rules/no-array_merge-in-loop.md>`__ |
+--------------+-------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Performances`                                                                         |
+--------------+-------------------------------------------------------------------------------------------------------------+



.. _non-ascii-variables:

Non Ascii variables
###################


PHP supports variables with '[a-zA-Z\_\x7f-\xff][a-zA-Z0-9\_\x7f-\xff]\*'. In practice, letters outside the scope of a-zA-Z0-9 are rare, and require more care when éditing the code or passing it from OS to OS.

+--------------+----------------------------+
| Command Line | Variables/VariableNonascii |
+--------------+----------------------------+
| clearPHP     |                            |
+--------------+----------------------------+
| Analyzers    | :ref:`Analyze`             |
+--------------+----------------------------+



.. _non-static-methods-called-in-a-static:

Non Static Methods Called In A Static
#####################################


Static methods have to be declared as such (using the static keyword). Then, 
one may call them without instantiating the object.

However, PHP doesn't check that a method is static or not : at any point, you may call one
method statically : 

.. code-block:: php

   <?php
       class x {
           static public function sm() { echo \_\_METHOD\_\_.\n; }
           public sm() { echo \_\_METHOD\_\_.\n; }
       } 
       
       x::sm(); // echo x::sm 
   ?>


It is a bad idea to call non-static method statically. Such method may make use of special
variable $this, which will be undefined. PHP will not check those calls at compile time,
nor at running time. 

It is recommended to fix this situation : make the method actually static, or use it only 
in object context.

+--------------+-------------------------------------------------------------------------------------------------+
| Command Line | Classes/NonStaticMethodsCalledStatic                                                            |
+--------------+-------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                 |
+--------------+-------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71` |
+--------------+-------------------------------------------------------------------------------------------------+



.. _non-constant-index-in-array:

Non-constant Index In Array
###########################


In '$array[index]', PHP cannot find index as a constant, but, as a default behavior, turns it into the string 'index'. 

This default behavior raise concerns when a corresponding constant is defined, either using define() or the const keyword (outside a class). The definition of the index constant will modify the behavior of the index, as it will now use the constant definition, and not the 'index' string. 

$array[index] = 1; // assign 1 to the element index in $array
define('index', 2);
$array[index] = 1; // now 1 to the element 2 in $array

It is recommended to make index a real string (with ' or "), or to define the corresponding constant to avoid any future surprise.

+--------------+-------------------------+
| Command Line | Arrays/NonConstantArray |
+--------------+-------------------------+
| clearPHP     |                         |
+--------------+-------------------------+
| Analyzers    | :ref:`Analyze`          |
+--------------+-------------------------+



.. _not-definitions-only:

Not Definitions Only
####################


Files should only include definitions (class, functions, traits, interfaces, constants), or global instructions, but not both. 

Within this context, globals, use, and namespaces instructions are not considered a warning.

+--------------+--------------------------+
| Command Line | Files/NotDefinitionsOnly |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Analyze`           |
+--------------+--------------------------+



.. _not-not:

Not Not
#######


This is a wrongly done type casting to boolean : !!($x) is (boolean) $x.

+--------------+-----------------------------------------------------------------------------------------------+
| Command Line | Structures/NotNot                                                                             |
+--------------+-----------------------------------------------------------------------------------------------+
| clearPHP     | `no-implied-cast <https://github.com/dseguy/clearPHP/tree/master/rules/no-implied-cast.md>`__ |
+--------------+-----------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                |
+--------------+-----------------------------------------------------------------------------------------------+



.. _not-substr-one:

Not Substr One
##############


There are two ways to access a byte in a string : substr($string, $pos, 1) or $v[$pos];

The second one is more readable. It may be up to four times faster, though it is a micro-optimization. 
It is recommended to use it. 

Beware that substr and $v[$pos] are similar, while mb\_substr() is not.

+--------------+-------------------------------------+
| Command Line | Structures/NoSubstrOne              |
+--------------+-------------------------------------+
| clearPHP     |                                     |
+--------------+-------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Performances` |
+--------------+-------------------------------------+



.. _null-on-new:

Null On New
###########


The following classes used to have a very specific behavior during instantiation : they were able to return NULL on new.

After issuing a 'new' with those classes, it was important to check if the returned object were null (sic) or not. No exception were thrown.

This inconsistency has been cleaned in PHP 7 : see https://wiki.php.net/rfc/internal\_constructor\_behaviour.

+--------------+----------------------------------------------------------------------------------------------------------------------------+
| Command Line | Classes/NullOnNew                                                                                                          |
+--------------+----------------------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                                            |
+--------------+----------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+--------------+----------------------------------------------------------------------------------------------------------------------------+



.. _objects-don't-need-references:

Objects Don't Need References
#############################


There is no need to create references for objects, as those are always passed by reference when used as arguments.

+--------------+-----------------------------------------------------------------------------------------------------------------+
| Command Line | Structures/ObjectReferences                                                                                     |
+--------------+-----------------------------------------------------------------------------------------------------------------+
| clearPHP     | `no-references-on-objects <https://github.com/dseguy/clearPHP/tree/master/rules/no-references-on-objects.md>`__ |
+--------------+-----------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                                  |
+--------------+-----------------------------------------------------------------------------------------------------------------+



.. _old-style-constructor:

Old Style Constructor
#####################


A long time ago, PHP classes used to have the method bearing the same name as the class acts as the constructor.

This is no more the case in PHP 5, which relies on \_\_construct() to do so. Having this old style constructor may bring in confusion, unless you are also supporting old time PHP 4.

Note that classes with methods bearing the class name, but inside a namespace are not following this convention, as this is not breaking backward compatibility. Those are excluded from the analyze.

+--------------+---------------------------------------------------------------------------------------------------------+
| Command Line | Classes/OldStyleConstructor                                                                             |
+--------------+---------------------------------------------------------------------------------------------------------+
| clearPHP     | `no-php4-class-syntax <https://github.com/dseguy/clearPHP/tree/master/rules/no-php4-class-syntax.md>`__ |
+--------------+---------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                          |
+--------------+---------------------------------------------------------------------------------------------------------+



.. _old-style-\_\_autoload:

Old style \_\_autoload
######################


Do not use the old \_\_autoload() function, but rather the new spl\_register\_autoload() function.

+--------------+-----------------------------------------------------------------------------------------------------+
| Command Line | Php/oldAutoloadUsage                                                                                |
+--------------+-----------------------------------------------------------------------------------------------------+
| clearPHP     | `use-smart-autoload <https://github.com/dseguy/clearPHP/tree/master/rules/use-smart-autoload.md>`__ |
+--------------+-----------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                      |
+--------------+-----------------------------------------------------------------------------------------------------+



.. _one-letter-functions:

One Letter Functions
####################


One letter functions seems to be really short for a meaningful name. This may happens for very high usage functions, so as to keep code short, but such functions should be rare.

+--------------+------------------------------+
| Command Line | Functions/OneLetterFunctions |
+--------------+------------------------------+
| clearPHP     |                              |
+--------------+------------------------------+
| Analyzers    | :ref:`Analyze`               |
+--------------+------------------------------+



.. _one-variable-string:

One variable String
###################


These strings only contains one variable (or function call, or methodcall, or array defererence). 

If the goal is to convert it to a string, use the type casting (string) operator : it is then clearer to understand the conversion. It is also marginally faster, though very little.

+--------------+-------------------------+
| Command Line | Type/OneVariableStrings |
+--------------+-------------------------+
| clearPHP     |                         |
+--------------+-------------------------+
| Analyzers    | :ref:`Analyze`          |
+--------------+-------------------------+



.. _only-variable-returned-by-reference:

Only Variable Returned By Reference
###################################


When a function returns a reference, one may only return variables, properties or static properties. Anything else will yield a warning.

+--------------+--------------------------------------------+
| Command Line | Structures/OnlyVariableReturnedByReference |
+--------------+--------------------------------------------+
| clearPHP     |                                            |
+--------------+--------------------------------------------+
| Analyzers    | :ref:`Analyze`                             |
+--------------+--------------------------------------------+



.. _or-die:

Or Die
######


Interrupting a script will leave the application with a blank page, will make your life miserable for testing. Just don't do that.

+--------------+-------------------------------------------------------------------------------------------+
| Command Line | Structures/OrDie                                                                          |
+--------------+-------------------------------------------------------------------------------------------+
| clearPHP     | `no-implied-if <https://github.com/dseguy/clearPHP/tree/master/rules/no-implied-if.md>`__ |
+--------------+-------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                            |
+--------------+-------------------------------------------------------------------------------------------+



.. _overwritten-exceptions:

Overwritten Exceptions
######################


In catch blocks, it is good practice not to overwrite the incoming exception, as information about the exception will be lost.

+--------------+-------------------------------+
| Command Line | Exceptions/OverwriteException |
+--------------+-------------------------------+
| clearPHP     |                               |
+--------------+-------------------------------+
| Analyzers    | :ref:`Analyze`                |
+--------------+-------------------------------+



.. _overwritten-literals:

Overwritten Literals
####################


In those methods, the same variable is assigned a literal twice. One of them is too much.

+--------------+-------------------------------+
| Command Line | Variables/OverwrittenLiterals |
+--------------+-------------------------------+
| clearPHP     |                               |
+--------------+-------------------------------+
| Analyzers    | :ref:`Analyze`                |
+--------------+-------------------------------+



.. _php-7.0-new-classes:

PHP 7.0 New Classes
###################


Those classes are now declared natively in PHP 7.0 and should not be declared in custom code.

+--------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Command Line | Php/Php70NewClasses                                                                                                                   |
+--------------+---------------------------------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                                                       |
+--------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP71` |
+--------------+---------------------------------------------------------------------------------------------------------------------------------------+



.. _php-7.0-new-functions:

PHP 7.0 New Functions
#####################


The following functions are now native functions in PHP 7.0. It is advised to change them before moving to this new version.

+--------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Command Line | Php/Php70NewFunctions                                                                                                                 |
+--------------+---------------------------------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                                                       |
+--------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP71` |
+--------------+---------------------------------------------------------------------------------------------------------------------------------------+



.. _php-7.0-new-interfaces:

PHP 7.0 New Interfaces
######################


The following interfaces are introduced in PHP 7.0. They shouldn't be defined in custom code.

+--------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Command Line | Php/Php70NewInterfaces                                                                                                                |
+--------------+---------------------------------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                                                       |
+--------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP71` |
+--------------+---------------------------------------------------------------------------------------------------------------------------------------+



.. _php-70-removed-directive:

PHP 70 Removed Directive
########################


List of directives that are removed in PHP 7.0.

+--------------+------------------------------------------------------+
| Command Line | Php/Php70RemovedDirective                            |
+--------------+------------------------------------------------------+
| clearPHP     |                                                      |
+--------------+------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71` |
+--------------+------------------------------------------------------+



.. _php-70-removed-functions:

PHP 70 Removed Functions
########################


The following PHP native functions were removed in PHP 7.0.

+--------------+------------------------------------------------------+
| Command Line | Php/Php70RemovedFunctions                            |
+--------------+------------------------------------------------------+
| clearPHP     |                                                      |
+--------------+------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71` |
+--------------+------------------------------------------------------+



.. _php-keywords-as-names:

PHP Keywords as Names
#####################


PHP has a set of reserved keywords. It is recommended not to use those keywords for names structures. 

PHP does check that a number of structures, such as classes, methods, interfaces... can't be named or called using one of the keywords. However, in a few other situations, no check are enforced. Using keywords in such situation is confusing.

+--------------+-------------------------------------------+
| Command Line | Php/ReservedNames                         |
+--------------+-------------------------------------------+
| clearPHP     |                                           |
+--------------+-------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP71` |
+--------------+-------------------------------------------+



.. _php5-indirect-variable-expression:

PHP5 Indirect Variable Expression
#################################


The following structures are evaluated differently in PHP 5 and 7. It is recommended to review them or switch to a less ambiguous syntax.

See also <a href="http://php.net/manual/en/migration70.incompatible.php">http://php.net/manual/en/migration70.incompatible.php</a>
<table>
<tr><td>Expression</td><td>PHP 5 interpretation</td><td>PHP 7 interpretation</td></tr>
<tr><td>$$foo['bar']['baz']</td><td>${$foo['bar']['baz']}</td><td>($$foo)['bar']['baz']</td></tr>
<tr><td>$foo->$bar['baz']</td><td>$foo->{$bar['baz']}</td><td>($foo->$bar)['baz']</td></tr>
<tr><td>$foo->$bar['baz']()</td><td>$foo->{$bar['baz']}()</td><td>($foo->$bar)['baz']()</td></tr>
<tr><td>Foo::$bar['baz']()</td><td>Foo::{$bar['baz']}()</td><td>(Foo::$bar)['baz']()</td></tr>
</table>

+--------------+------------------------------------------------------------------------------------------------------------+
| Command Line | Variables/Php5IndirectExpression                                                                           |
+--------------+------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                            |
+--------------+------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+--------------+------------------------------------------------------------------------------------------------------------+



.. _php7-dirname:

PHP7 Dirname
############


With PHP 7, dirname has a second argument that represents the number of parent folder to follow. This prevent us from using nested dirname() calls to reach an grand-parent direct.

+--------------+------------------------------------------------------------------------------------------------------------+
| Command Line | Structures/PHP7Dirname                                                                                     |
+--------------+------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                            |
+--------------+------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+--------------+------------------------------------------------------------------------------------------------------------+



.. _preg-option-e:

PREG Option e
#############


preg\_replaced had a /e option until PHP 7.0 which allowed the use of eval'ed expression as replacement. This has been dropped in PHP 7.0, for security reasons.

+--------------+---------------------------------------------------------------------------------------+
| Command Line | Structures/pregOptionE                                                                |
+--------------+---------------------------------------------------------------------------------------+
| clearPHP     |                                                                                       |
+--------------+---------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP70`, :ref:`Security`, :ref:`CompatibilityPHP71` |
+--------------+---------------------------------------------------------------------------------------+



.. _parent,-static-or-self-outside-class:

Parent, static or self outside class
####################################


Parent, static and self keywords must be used within a class or a trait. They make no sens outside a class or trait scope, as self and static refers to the current class and parent refers to one of parent above.

Static may be used in a function or a closure, but not globally.

+--------------+-------------------------+
| Command Line | Classes/PssWithoutClass |
+--------------+-------------------------+
| clearPHP     |                         |
+--------------+-------------------------+
| Analyzers    | :ref:`Analyze`          |
+--------------+-------------------------+



.. _parenthesis-as-parameter:

Parenthesis As Parameter
########################


Using parenthesis around parameters used to silent some internal check. This is not the case anymore in PHP 7, and should be fixed by removing the parenthesis and making the value a real reference.

+--------------+------------------------------------------------------+
| Command Line | Php/ParenthesisAsParameter                           |
+--------------+------------------------------------------------------+
| clearPHP     |                                                      |
+--------------+------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71` |
+--------------+------------------------------------------------------+



.. _php-7-indirect-expression:

Php 7 Indirect Expression
#########################


Those are variable indirect expressions that are interpreted differently between PHP 5 and PHP 7. You should check them so they don't behave strangely.

+--------------+------------------------------------------------------------------------------------------------------------+
| Command Line | Variables/Php7IndirectExpression                                                                           |
+--------------+------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                            |
+--------------+------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+--------------+------------------------------------------------------------------------------------------------------------+



.. _php7-relaxed-keyword:

Php7 Relaxed Keyword
####################


PHP Keywords may be used as classes, trait or interfaces elements (such as properties, constants or methods). 

This was not the case in PHP 5, and will yield parse errors.

+--------------+------------------------------------------------------------------------------------------------------------+
| Command Line | Php/Php7RelaxedKeyword                                                                                     |
+--------------+------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                            |
+--------------+------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+--------------+------------------------------------------------------------------------------------------------------------+



.. _phpinfo:

Phpinfo
#######


Phpinfo is a great function to learn about the current configuration of the server.

If left in the production code, it may lead to a critical leak, as any attacker gaining access to this data will know a lot about the server configuration.
It is advised to never leave that kind of instruction in a production code.

+--------------+-------------------------+
| Command Line | Structures/PhpinfoUsage |
+--------------+-------------------------+
| clearPHP     |                         |
+--------------+-------------------------+
| Analyzers    | :ref:`Analyze`          |
+--------------+-------------------------+



.. _pre-increment:

Pre-Increment
#############


When possible, use the pre-increment operator (++$i or --$i) instead of the post-increment operator ($i++ or $i--).

The latter needs an extra memory allocation that costs about 10% of performances.

+--------------+-------------------------------------+
| Command Line | Performances/PrePostIncrement       |
+--------------+-------------------------------------+
| clearPHP     |                                     |
+--------------+-------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Performances` |
+--------------+-------------------------------------+



.. _preprocess-arrays:

Preprocess Arrays
#################


Using long list of '$array[$key] = $value; for initializing arrays is significantly slower than the alternative of declaring them with the array() function. 

If the array has to be completed rather than created, it is also faster to use += when there are more than ten elements to add.

+--------------+-------------------------+
| Command Line | Arrays/ShouldPreprocess |
+--------------+-------------------------+
| clearPHP     |                         |
+--------------+-------------------------+
| Analyzers    | none                    |
+--------------+-------------------------+



.. _preprocessable:

Preprocessable
##############


The following expression are made of literals or already known values : they may be fully calculated before running PHP.

By doing so, this will reduce the amount of work of PHP.

+--------------+--------------------------------+
| Command Line | Structures/ShouldPreprocess    |
+--------------+--------------------------------+
| clearPHP     |                                |
+--------------+--------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Analyze` |
+--------------+--------------------------------+



.. _print-and-die:

Print And Die
#############


When stopping a script with die() and echo(), it is possible to provide a message as first argument, that will be displayed at execution. There is no need to make a specific call to print or echo.

+--------------+------------------------+
| Command Line | Structures/PrintAndDie |
+--------------+------------------------+
| clearPHP     |                        |
+--------------+------------------------+
| Analyzers    | :ref:`Analyze`         |
+--------------+------------------------+



.. _property/variable-confusion:

Property/Variable Confusion
###########################


Within a class, there is both a property and some variables bearing the same name. 

.. code-block:: php

   <?php
   class Object {
       private $x;
       
       function SetData() {
           $this->x = $x + 2;
       }
   }
   ?>


the property and the variable may easily be confused one for another and lead to a bug. 

Sometimes, when the property is going to be replaced by the incoming argument, or data based on that argument, this naming schema is made on purpose, indicating that the current argument will eventually end up in the property. When the argument has the same name as the property, no warning is reported.

+--------------+--------------------------------------+
| Command Line | Structures/PropertyVariableConfusion |
+--------------+--------------------------------------+
| clearPHP     |                                      |
+--------------+--------------------------------------+
| Analyzers    | :ref:`Analyze`                       |
+--------------+--------------------------------------+



.. _queries-in-loops:

Queries in loops
################


Querying an external database in a loop usually leads to performances problems. 

It is recommended to reduce the number of queries by making one query, and dispatching the results afterwards. 
This is not always possible.

+--------------+--------------------------+
| Command Line | Structures/QueriesInLoop |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Analyze`           |
+--------------+--------------------------+



.. _redeclared-php-functions:

Redeclared PHP Functions
########################


Function that bear the same name as a PHP function, and that are declared. This is possible when managing some backward compatibility (emulating some old function), or preparing for newer PHP version (emulating new upcoming function).

+--------------+---------------------------------+
| Command Line | Functions/RedeclaredPhpFunction |
+--------------+---------------------------------+
| clearPHP     |                                 |
+--------------+---------------------------------+
| Analyzers    | :ref:`Analyze`                  |
+--------------+---------------------------------+



.. _redefined-property:

Redefined Property
##################


Using heritage, it is possible to define several times the same property, at different levels of the hierarchy.

When this is the case, it is difficult to understand which class will actually handle the property. 

In the case of a private property, the different instances will stay distinct. In the case of protected or public properties, they will all share the same value. 

It is recommended to avoid redefining the same property in a hierarchy.

+--------------+---------------------------+
| Command Line | Classes/RedefinedProperty |
+--------------+---------------------------+
| clearPHP     |                           |
+--------------+---------------------------+
| Analyzers    | :ref:`Analyze`            |
+--------------+---------------------------+



.. _register-globals:

Register Globals
################


register\_globals was a PHP directive that dumped all incoming variables from GET, POST, COOKIE and FILES as global variables in the called scripts.
This lead to security failures, as the variables were often used but not filtered. 

Though it is less often found in more recent code, register\_globals is sometimes needed in legacy code, that haven't made the move to eradicate this style of coding.
Backward compatible pieces of code that mimic the register\_globals features usually create even greater security risks by being run after scripts startup. At that point, some important variables are already set, and may be overwritten by the incoming call, creating confusion in the script.

Mimicking register\_globals is achieved with variables variables, extract(), parse\_str() and import\_request\_variables() (Up to PHP 5.4).

+--------------+--------------------------+
| Command Line | Security/RegisterGlobals |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Security`          |
+--------------+--------------------------+



.. _relay-function:

Relay Function
##############


Relay functions (or method) are delegating the actual work to another function or method. They do not have any impact on the results, besides exposing another name for the same feature.

Relay functions are typical of transition API, where an old API have to be preserved until it is fully migrated. Then, they may be removed, so as to reduce confusion, and unclutter the API.

+--------------+-------------------------+
| Command Line | Functions/RelayFunction |
+--------------+-------------------------+
| clearPHP     |                         |
+--------------+-------------------------+
| Analyzers    | :ref:`Analyze`          |
+--------------+-------------------------+



.. _repeated-prints:

Repeated prints
###############


It is recommended to use concatenation instead of multiple calls to print or echo when outputting several blob of text.

+--------------+--------------------------+
| Command Line | Structures/RepeatedPrint |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Analyze`           |
+--------------+--------------------------+



.. _reserved-keywords-in-php-7:

Reserved Keywords In PHP 7
##########################


Php reserved names for class/trait/interface. They won't be available anymore in user space starting with PHP 7.

+--------------+------------------------------------------------------+
| Command Line | Php/ReservedKeywords7                                |
+--------------+------------------------------------------------------+
| clearPHP     |                                                      |
+--------------+------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71` |
+--------------+------------------------------------------------------+



.. _scalar-typehint-usage:

Scalar Typehint Usage
#####################


Spot usage of scalar type hint : int, float, boolean and string.

+--------------+------------------------------------------------------------------------------------------------------------+
| Command Line | Php/ScalarTypehintUsage                                                                                    |
+--------------+------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                            |
+--------------+------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+--------------+------------------------------------------------------------------------------------------------------------+



.. _sequences-in-for:

Sequences In For
################


For() instructions allows several instructions in each of its parameters. Then, the instruction separator is comma ',', not semi-colon, which is used for separating the 3 arguments.

for ($a = 0, $b = 0; $a < 10, $b < 20; $a++, $b += 3) {}

This loop will simultaneously increment $a and $b. It will stop only when the last of the central sequence reach a value of false : here, when $b reach 20 and $a will be 6. 

This structure is often unknown, and makes the for instruction quite difficult to read. It is also easy to oversee the multiples instructions, and omit one of them.
It is recommended not to use it.

+--------------+--------------------------+
| Command Line | Structures/SequenceInFor |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Analyze`           |
+--------------+--------------------------+



.. _setlocale-needs-constants:

Setlocale Needs Constants
#########################


The first argument of setlocale must be one of the valid constants, LC\_ALL, LC\_COLLATE, LC\_CTYPE, LC\_MONETARY, LC\_NUMERIC, LC\_TIME, LC\_MESSAGES.

The PHP 5 usage of strings (same name as above, enclosed in ' or ") is not legit anymore in PHP 7 and later.

+--------------+------------------------------------------------------+
| Command Line | Structures/SetlocaleNeedsConstants                   |
+--------------+------------------------------------------------------+
| clearPHP     |                                                      |
+--------------+------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71` |
+--------------+------------------------------------------------------+



.. _several-instructions-on-the-same-line:

Several Instructions On The Same Line
#####################################


Usually, instructions do not share their line : one instruction, one line. This is good for readability, and help at understanding the code. This is especially important when fast-reading the code to find some special situation, where such double-meaning line way have an impact.

+--------------+-----------------------------------+
| Command Line | Structures/OneLineTwoInstructions |
+--------------+-----------------------------------+
| clearPHP     |                                   |
+--------------+-----------------------------------+
| Analyzers    | :ref:`Analyze`                    |
+--------------+-----------------------------------+



.. _short-open-tags:

Short Open Tags
###############


Usage of short open tags is discouraged. The following files were found to be impacted by the short open tag directive at compilation time. They must be reviewed to ensure no &lt;? tags are found in the code.

+--------------+--------------------------+
| Command Line | Php/ShortOpenTagRequired |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Analyze`           |
+--------------+--------------------------+



.. _short-syntax-for-arrays:

Short syntax for arrays
#######################


List of all arrays written the new PHP 5.4 short syntax. They mean that it won't be possible to downgrade to PHP 5.3.

+--------------+---------------------------+
| Command Line | Arrays/ArrayNSUsage       |
+--------------+---------------------------+
| clearPHP     |                           |
+--------------+---------------------------+
| Analyzers    | :ref:`CompatibilityPHP53` |
+--------------+---------------------------+



.. _should-be-single-quote:

Should Be Single Quote
######################


Static content inside a string, that has no single quotes nor escape sequence (such as \n or \t),
 should be using single quote delimiter, instead of double quote. 

If you have too many of them, don't loose your time switching them all. If you have a few of them, it may be good for consistence.

+--------------+-----------------------------------------------------------------------------------------------+
| Command Line | Type/ShouldBeSingleQuote                                                                      |
+--------------+-----------------------------------------------------------------------------------------------+
| clearPHP     | `no-double-quote <https://github.com/dseguy/clearPHP/tree/master/rules/no-double-quote.md>`__ |
+--------------+-----------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                |
+--------------+-----------------------------------------------------------------------------------------------+



.. _should-chain-exception:

Should Chain Exception
######################


When catching an exception and rethrowing another one, it is recommended to chain the exception : this means providing the original exception, so that the final recipiend has a chance to track the origin of the problem. 
This doesn't change the thrown message, but provides more information.

Note : Chaining requires PHP > 5.3.0.

.. code-block:: php

   <?php
       try {
           throw new Exception('Exception 1', 1);
       } catch (\Exception $e) {
           throw new Exception('Exception 2', 2, $e); 
           // Chaining here. 
   
       }
   ?>

+--------------+---------------------------------+
| Command Line | Structures/ShouldChainException |
+--------------+---------------------------------+
| clearPHP     |                                 |
+--------------+---------------------------------+
| Analyzers    | :ref:`Analyze`                  |
+--------------+---------------------------------+



.. _should-typecast:

Should Typecast
###############


When typecasting, it is better to use the casting operator, such as (int) or (bool), instead of the slower functions such as intval or settype.

+--------------+---------------------+
| Command Line | Type/ShouldTypecast |
+--------------+---------------------+
| clearPHP     |                     |
+--------------+---------------------+
| Analyzers    | :ref:`Analyze`      |
+--------------+---------------------+



.. _should-use-$this:

Should Use $this
################


Classes' methods should use $this, or a static method or property (when they are static). 

Otherwise, the method doesn't belong to the object. It may be a function.

+--------------+-----------------------------------------------------------------------------------------+
| Command Line | Classes/ShouldUseThis                                                                   |
+--------------+-----------------------------------------------------------------------------------------+
| clearPHP     | `not-a-method <https://github.com/dseguy/clearPHP/tree/master/rules/not-a-method.md>`__ |
+--------------+-----------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                          |
+--------------+-----------------------------------------------------------------------------------------+



.. _should-use-constants:

Should Use Constants
####################


The following functions have related constants that should be used as arguments, instead of scalar literals, such as integers or strings.

For example, $lines = file('file.txt', 2); is less readable than $lines = file('file.txt', FILE\_IGNORE\_NEW\_LINES)

+--------------+------------------------------+
| Command Line | Functions/ShouldUseConstants |
+--------------+------------------------------+
| clearPHP     |                              |
+--------------+------------------------------+
| Analyzers    | :ref:`Analyze`               |
+--------------+------------------------------+



.. _should-use-prepared-statement:

Should Use Prepared Statement
#############################


Modern databases provides support for prepared statement : it separates the query from the processed data and highten significantly the security. 

Building queries with concatenations is not recommended, though not always avoidable. When possible, use prepared statements.

+--------------+-------------------------------------+
| Command Line | Security/ShouldUsePreparedStatement |
+--------------+-------------------------------------+
| clearPHP     |                                     |
+--------------+-------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Security`     |
+--------------+-------------------------------------+



.. _silently-cast-integer:

Silently Cast Integer
#####################


Those are integer literals that are cast to a float when running PHP. They are simply too big for the current PHP version, and PHP resort to make them a float, which has a much larger capacity but a lower precision.

Compare your literals to PHP\_MAX\_INT (typically 9223372036854775807) and PHP\_MIN\_INT (typically -9223372036854775808).
This applies to binary (0b10101...), octals (0123123...) and hexadecimals (0xfffff...) too.

+--------------+--------------------------+
| Command Line | Type/SilentlyCastInteger |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Analyze`           |
+--------------+--------------------------+



.. _simple-global-variable:

Simple Global Variable
######################


global keyword should only be used with simple variables (global $var), and not with complex or dynamic structures.

+--------------+------------------------------------------------------+
| Command Line | Php/GlobalWithoutSimpleVariable                      |
+--------------+------------------------------------------------------+
| clearPHP     |                                                      |
+--------------+------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71` |
+--------------+------------------------------------------------------+



.. _sleep-is-a-security-risk:

Sleep is a security risk
########################


Pausing the script for a specific amount of time means that the Web server is also making all related ressources sleep, such as database, sockets, session, etc. This may used to set up a DOS on the server.

+--------------+------------------+
| Command Line | Security/NoSleep |
+--------------+------------------+
| clearPHP     |                  |
+--------------+------------------+
| Analyzers    | :ref:`Security`  |
+--------------+------------------+



.. _slow-functions:

Slow Functions
##############


Avoid using those slow native PHP functions, and replace them with alternatives.

+--------------+---------------------------------------------------------------------------------------------------------------------+
| Command Line | Performances/SlowFunctions                                                                                          |
+--------------+---------------------------------------------------------------------------------------------------------------------+
| clearPHP     | `avoid-those-slow-functions <https://github.com/dseguy/clearPHP/tree/master/rules/avoid-those-slow-functions.md>`__ |
+--------------+---------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Performances`                                                                                                 |
+--------------+---------------------------------------------------------------------------------------------------------------------+



.. _static-loop:

Static Loop
###########


It looks like the following loops are static : the same code is executed each time, without taking into account loop variables.

It is possible to create loops that don't use any blind variables, and this is fairly rare.

+--------------+-----------------------+
| Command Line | Structures/StaticLoop |
+--------------+-----------------------+
| clearPHP     |                       |
+--------------+-----------------------+
| Analyzers    | :ref:`Analyze`        |
+--------------+-----------------------+



.. _static-methods-called-from-object:

Static Methods Called From Object
#################################


Static methods may be called without instantiating an object.
As such, they never interact with the special variable '$this', as they do not
depend on object existence. 

Besides this, static methods are normal methods that may be called directly from
object context, to perform some utility task. 

To maintain code readability, it is recommended to call static method in a static
way, rather than within object context.

.. code-block:: php

   <?php
       class x {
           static function y() {}
       }
       
       $z = new x();
       
       $z->y(); // Readability : no one knows it is a static call
       x::y();  // Readability : here we know
   ?>

+--------------+---------------------------------------+
| Command Line | Classes/StaticMethodsCalledFromObject |
+--------------+---------------------------------------+
| clearPHP     |                                       |
+--------------+---------------------------------------+
| Analyzers    | :ref:`Analyze`                        |
+--------------+---------------------------------------+



.. _static-methods-can't-contain-$this:

Static Methods Can't Contain $this
##################################


Static methods are also called 'class methods' : they may be called even if the class has no instantiated object. Thus, the local variable $this won't exist, PHP will set it to NULL as usual. 

Either, this is not a static method (simply remove the static keyword), or replace all $this mention by static properties Class::$property.

+--------------+---------------------------------------------------------------------------------------------+
| Command Line | Classes/StaticContainsThis                                                                  |
+--------------+---------------------------------------------------------------------------------------------+
| clearPHP     | `no-static-this <https://github.com/dseguy/clearPHP/tree/master/rules/no-static-this.md>`__ |
+--------------+---------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                              |
+--------------+---------------------------------------------------------------------------------------------+



.. _strict-comparison-with-booleans:

Strict comparison with booleans
###############################


Booleans may be easily mistaken with other values, especially when the function may return integer or boolean as a normal course of action. 

It is encouraged to use strict comparison === or !== when booleans are involved in a comparison.

+--------------+------------------------------------+
| Command Line | Structures/BooleanStrictComparison |
+--------------+------------------------------------+
| clearPHP     |                                    |
+--------------+------------------------------------+
| Analyzers    | :ref:`Analyze`                     |
+--------------+------------------------------------+



.. _string-may-hold-a-variable:

String May Hold A Variable
##########################


This is a list of string using single quotes and Nowdoc syntax : as such, they are treated as literals, and they won't be scanned to interpolate variables.

However, there are some potential variables in those strings, making it possible for an error : the variable was forgotten and will be published as such. It is worth checking the content and make sure those strings are not variables.

+--------------+--------------------------+
| Command Line | Type/StringHoldAVariable |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Analyze`           |
+--------------+--------------------------+



.. _strpos-comparison:

Strpos Comparison
#################


Strpos() returns a string position, starting at 0, or false, in case of failure. 

It is recommended to check the result of strpos with === or !==, so as to avoid confusing 0 and false. 
This analyzer list all the strpos function that are directly compared with == or !=.

+--------------+-----------------------------------------------------------------------------------------------------+
| Command Line | Structures/StrposCompare                                                                            |
+--------------+-----------------------------------------------------------------------------------------------------+
| clearPHP     | `strict-comparisons <https://github.com/dseguy/clearPHP/tree/master/rules/strict-comparisons.md>`__ |
+--------------+-----------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                      |
+--------------+-----------------------------------------------------------------------------------------------------+



.. _switch-to-switch:

Switch To Switch
################


The following structures are based on if / elseif / else. Since they have more than three conditions (not withstanding the final else), it is recommended to use the switch structure, so as to make this more readable.

+--------------+---------------------------+
| Command Line | Structures/SwitchToSwitch |
+--------------+---------------------------+
| clearPHP     |                           |
+--------------+---------------------------+
| Analyzers    | :ref:`Analyze`            |
+--------------+---------------------------+



.. _switch-with-too-many-default:

Switch With Too Many Default
############################


Switch statements should only hold one default, not more. Check the code and remove the extra default.

+--------------+--------------------------------------+
| Command Line | Structures/SwitchWithMultipleDefault |
+--------------+--------------------------------------+
| clearPHP     |                                      |
+--------------+--------------------------------------+
| Analyzers    | :ref:`Analyze`                       |
+--------------+--------------------------------------+



.. _switch-without-default:

Switch Without Default
######################


Switch statements hold a number of 'case' that cover all known situations, and a 'default' one which is executed when all other options are exhausted. 

Most of the time, Switch do need a default case, so as to catch the odd situation where the 'value is not what it was expected'. This is a good place to catch unexpected values, to set a default behavior.

+--------------+-------------------------------------------------------------------------------------------------------------------+
| Command Line | Structures/SwitchWithoutDefault                                                                                   |
+--------------+-------------------------------------------------------------------------------------------------------------------+
| clearPHP     | `no-switch-without-default <https://github.com/dseguy/clearPHP/tree/master/rules/no-switch-without-default.md>`__ |
+--------------+-------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                                    |
+--------------+-------------------------------------------------------------------------------------------------------------------+



.. _throws-an-assignement:

Throws An Assignement
#####################


It is possible to throw an exception, and, in the same time, assign this exception to a variable : throw $e = new() Exception().

However, $e will never be used, as the exception is thrown, and any following code is not executed. 

The assignement should be removed.

+--------------+----------------------------+
| Command Line | Structures/ThrowsAndAssign |
+--------------+----------------------------+
| clearPHP     |                            |
+--------------+----------------------------+
| Analyzers    | :ref:`Analyze`             |
+--------------+----------------------------+



.. _timestamp-difference:

Timestamp Difference
####################


Time() and microtime() shouldn't be used to calculate duration. 

Time() and microtime are subject to variation, depending on system clock variations, such as daylight saving time difference (every spring and fall, one hour variation), or leap seconds, happening on June, 30th or december 31th, as announcec by IERS.

When the difference may be rounded to a larger time unit (rounding the differnce to days, or several hours), the variations may be ignored safely.

If the difference may be very small, it requires a better way to mesure time difference, such as ticks, ext/hrtime, or including a check on the actual time zone (ini\_get(date.timezone)).

+--------------+--------------------------------+
| Command Line | Structures/TimestampDifference |
+--------------+--------------------------------+
| clearPHP     |                                |
+--------------+--------------------------------+
| Analyzers    | :ref:`Analyze`                 |
+--------------+--------------------------------+



.. _unchecked-resources:

Unchecked Resources
###################


Resources are created, but never checked before being used. This is not safe.

+--------------+-------------------------------------------------------------------------------------------------------------+
| Command Line | Structures/UncheckedResources                                                                               |
+--------------+-------------------------------------------------------------------------------------------------------------+
| clearPHP     | `no-unchecked-resources <https://github.com/dseguy/clearPHP/tree/master/rules/no-unchecked-resources.md>`__ |
+--------------+-------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                              |
+--------------+-------------------------------------------------------------------------------------------------------------+



.. _undefined-class-constants:

Undefined Class Constants
#########################


Class constants that are used, but never defined. This should yield a fatal error upon execution, but no feedback at compile level.

+--------------+--------------------------------+
| Command Line | Classes/UndefinedConstants     |
+--------------+--------------------------------+
| clearPHP     |                                |
+--------------+--------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Analyze` |
+--------------+--------------------------------+



.. _undefined-classes:

Undefined Classes
#################


Those classes were used in the code, but there is no way to find a definition of that class in the PHP code.

This may happens under normal conditions, if the application makes use of an unsupported extension, that defines extra classes; 
or if some external libraries, such as PEAR, are not provided during the analysis.

Otherwise, this should be checked.

+--------------+--------------------------+
| Command Line | Classes/UndefinedClasses |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Analyze`           |
+--------------+--------------------------+



.. _undefined-constants:

Undefined Constants
###################


Those constants are not defined in the code, and will raise errors, or use the fallback mechanism of being treated like a string. 

It is recommended to define them all, or to avoid using them.

+--------------+------------------------------+
| Command Line | Constants/UndefinedConstants |
+--------------+------------------------------+
| clearPHP     |                              |
+--------------+------------------------------+
| Analyzers    | none                         |
+--------------+------------------------------+



.. _undefined-interfaces:

Undefined Interfaces
####################


Typehint or instanceof that are relying on undefined interfaces (or classes) : they will always return false. Any condition based upon them are dead code.

+--------------+--------------------------------+
| Command Line | Interfaces/UndefinedInterfaces |
+--------------+--------------------------------+
| clearPHP     |                                |
+--------------+--------------------------------+
| Analyzers    | :ref:`Analyze`                 |
+--------------+--------------------------------+



.. _undefined-function:

Undefined function
##################


This function is not defined in the code. This means that the function is probably defined in a missing library, or in an extension. If not, this will yield a Fatal error at execution.

+--------------+------------------------------+
| Command Line | Functions/UndefinedFunctions |
+--------------+------------------------------+
| clearPHP     |                              |
+--------------+------------------------------+
| Analyzers    | :ref:`Analyze`               |
+--------------+------------------------------+



.. _undefined-parent:

Undefined parent
################


List of properties and methods that are accessed using 'parent' keyword but are not defined in the parent class. 

This will be compilable but will yield a fatal error during execution.

Note that if the parent is defined (extends someClass) but someClass is not available in the tested code (it may be in composer,
another dependency, or just not there) it will not be reported.

+--------------+---------------------------+
| Command Line | Classes/UndefinedParentMP |
+--------------+---------------------------+
| clearPHP     |                           |
+--------------+---------------------------+
| Analyzers    | :ref:`Analyze`            |
+--------------+---------------------------+



.. _undefined-properties:

Undefined properties
####################


List of properties that are not explicitely defined in the class, its parents or traits.

+--------------+---------------------------------------------------------------------------------------------------------------+
| Command Line | Classes/UndefinedProperty                                                                                     |
+--------------+---------------------------------------------------------------------------------------------------------------+
| clearPHP     | `no-undefined-properties <https://github.com/dseguy/clearPHP/tree/master/rules/no-undefined-properties.md>`__ |
+--------------+---------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                                |
+--------------+---------------------------------------------------------------------------------------------------------------+



.. _undefined-static\:\:-or-self\:\::

Undefined static:: or self::
############################


List of all undefined static and self properties and methods.

+--------------+---------------------------+
| Command Line | Classes/UndefinedStaticMP |
+--------------+---------------------------+
| clearPHP     |                           |
+--------------+---------------------------+
| Analyzers    | :ref:`Analyze`            |
+--------------+---------------------------+



.. _unicode-escape-partial:

Unicode Escape Partial
######################


PHP 7 introduces a new escape sequence for strings : \u{hex}. It is backward incompatible with previous PHP versions for two reasons : 

PHP 7 will recognize en replace those sequences, while PHP 5 keep them intact.
PHP 7 will chocke on partial Unicode Sequences, as it tries to understand them, but may fail. 

Is is recommended to check all those strings, and make sure they will behave correctly in PHP 7.

+--------------+------------------------------------------------------------------------------------------------------------+
| Command Line | Php/UnicodeEscapePartial                                                                                   |
+--------------+------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                            |
+--------------+------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+--------------+------------------------------------------------------------------------------------------------------------+



.. _unicode-escape-syntax:

Unicode Escape Syntax
#####################


Usage of the PHP 7 Unicode Escape syntax, with the \u{xxxxx} format.

+--------------+------------------------------------------------------------------------------------------------------------+
| Command Line | Php/UnicodeEscapeSyntax                                                                                    |
+--------------+------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                            |
+--------------+------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+--------------+------------------------------------------------------------------------------------------------------------+



.. _unpreprocessed-values:

Unpreprocessed values
#####################


PHP is good at manipulating data. However, it is also good to preprocess those values, and put them in the code directly as expected, rather than have PHP go the extra step and do it for you.

For example : 
$x = explode(',', 'a,b,c,d'); 

could be written 

$x = array('a', 'b', 'c', 'd');

and avoid preprocessing the string into an array first.

+--------------+---------------------------------------------------------------------------------------------------+
| Command Line | Structures/Unpreprocessed                                                                         |
+--------------+---------------------------------------------------------------------------------------------------+
| clearPHP     | `always-preprocess <https://github.com/dseguy/clearPHP/tree/master/rules/always-preprocess.md>`__ |
+--------------+---------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                    |
+--------------+---------------------------------------------------------------------------------------------------+



.. _unreachable-code:

Unreachable Code
################


Code located after throw, return, exit(), die(), break or continue cannot be reached, as the previous instruction will divert the engine to another part of the code. 

This is dead code, that may be removed.

+--------------+-----------------------------------------------------------------------------------------+
| Command Line | Structures/UnreachableCode                                                              |
+--------------+-----------------------------------------------------------------------------------------+
| clearPHP     | `no-dead-code <https://github.com/dseguy/clearPHP/tree/master/rules/no-dead-code.md>`__ |
+--------------+-----------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Dead code <dead-code>`                                            |
+--------------+-----------------------------------------------------------------------------------------+



.. _unresolved-catch:

Unresolved Catch
################


Classes in Catch expression may turn useless because the code was namespaced and the catch is set on Exception (no \).

Or, the expected class is not even an Exception : that is not needed for catching, but for throwing. Catching will only match the class, if it reaches it.

+--------------+-------------------------------------------------------------------------------------------------------+
| Command Line | Classes/UnresolvedCatch                                                                               |
+--------------+-------------------------------------------------------------------------------------------------------+
| clearPHP     | `no-unresolved-catch <https://github.com/dseguy/clearPHP/tree/master/rules/no-unresolved-catch.md>`__ |
+--------------+-------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Dead code <dead-code>`                                                                          |
+--------------+-------------------------------------------------------------------------------------------------------+



.. _unresolved-instanceof:

Unresolved Instanceof
#####################


Instanceof checks if an variable is of a specific class. However, if the reference class doesn't exists, because of a bug, a missed inclusion or a typo, the operator will always fail, without a warning. 

Make sure the following classes are well defined.

+--------------+-----------------------------------------------------------------------------------------------------------------+
| Command Line | Classes/UnresolvedInstanceof                                                                                    |
+--------------+-----------------------------------------------------------------------------------------------------------------+
| clearPHP     | `no-unresolved-instanceof <https://github.com/dseguy/clearPHP/tree/master/rules/no-unresolved-instanceof.md>`__ |
+--------------+-----------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Dead code <dead-code>`                                                                    |
+--------------+-----------------------------------------------------------------------------------------------------------------+



.. _unresolved-classes:

Unresolved classes
##################


The following classes are instantiated in the code, but their definition couldn't be found. 

Check for namespaces and aliases and make sure they are correctly configured.

+--------------+---------------------------+
| Command Line | Classes/UnresolvedClasses |
+--------------+---------------------------+
| clearPHP     |                           |
+--------------+---------------------------+
| Analyzers    | :ref:`Analyze`            |
+--------------+---------------------------+



.. _unresolved-use:

Unresolved use
##############


The following use instructions cannot be resolved to a class or a namespace. They should be dropped or fixed.

+--------------+---------------------------------------------------------------------------------------------------+
| Command Line | Namespaces/UnresolvedUse                                                                          |
+--------------+---------------------------------------------------------------------------------------------------+
| clearPHP     | `no-unresolved-use <https://github.com/dseguy/clearPHP/tree/master/rules/no-unresolved-use.md>`__ |
+--------------+---------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                    |
+--------------+---------------------------------------------------------------------------------------------------+



.. _unset-in-foreach:

Unset In Foreach
################


Unset applied to the variables of a foreach loop are useless, as they are mere copies and not the actual value. Even if the value is a reference, unsetting it will not have effect on the original array.

+--------------+----------------------------------------------+
| Command Line | Structures/UnsetInForeach                    |
+--------------+----------------------------------------------+
| clearPHP     |                                              |
+--------------+----------------------------------------------+
| Analyzers    | :ref:`Dead code <dead-code>`, :ref:`Analyze` |
+--------------+----------------------------------------------+



.. _unthrown-exception:

Unthrown Exception
##################


These are exceptions that are defined in the code but never thrown.

+--------------+-------------------------------------------------------------------------------------------------------------+
| Command Line | Exceptions/Unthrown                                                                                         |
+--------------+-------------------------------------------------------------------------------------------------------------+
| clearPHP     | `no-unthrown-exceptions <https://github.com/dseguy/clearPHP/tree/master/rules/no-unthrown-exceptions.md>`__ |
+--------------+-------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Dead code <dead-code>`                                                                |
+--------------+-------------------------------------------------------------------------------------------------------------+



.. _unused-arguments:

Unused Arguments
################


Those arguments are not used in the method or function.

+--------------+---------------------------+
| Command Line | Functions/UnusedArguments |
+--------------+---------------------------+
| clearPHP     |                           |
+--------------+---------------------------+
| Analyzers    | :ref:`Analyze`            |
+--------------+---------------------------+



.. _unused-global:

Unused Global
#############


List of global keyword, used in various functions but not actually used in the code. for example : 

.. code-block:: php

   <?php
       function foo() {
           global bar;
           
           return 1;
       }
   ?>

+--------------+-------------------------+
| Command Line | Structures/UnusedGlobal |
+--------------+-------------------------+
| clearPHP     |                         |
+--------------+-------------------------+
| Analyzers    | :ref:`Analyze`          |
+--------------+-------------------------+



.. _unused-interfaces:

Unused Interfaces
#################


Those interfaces are defined but not used. They should be removed.

+--------------+----------------------------------------------+
| Command Line | Interfaces/UnusedInterfaces                  |
+--------------+----------------------------------------------+
| clearPHP     |                                              |
+--------------+----------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Dead code <dead-code>` |
+--------------+----------------------------------------------+



.. _unused-label:

Unused Label
############


The following labels have been defined in the code, but they are not used. They may be removed.

+--------------+----------------------------------------------+
| Command Line | Structures/UnusedLabel                       |
+--------------+----------------------------------------------+
| clearPHP     |                                              |
+--------------+----------------------------------------------+
| Analyzers    | :ref:`Dead code <dead-code>`, :ref:`Analyze` |
+--------------+----------------------------------------------+



.. _unused-methods:

Unused Methods
##############


The following methods are never called as methods. They are probably dead code.

+--------------+----------------------------------------------+
| Command Line | Classes/UnusedMethods                        |
+--------------+----------------------------------------------+
| clearPHP     |                                              |
+--------------+----------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Dead code <dead-code>` |
+--------------+----------------------------------------------+



.. _unused-static-properties:

Unused Static Properties
########################


List of all static properties that are not used. This looks like dead code.

+--------------+----------------------------------------------+
| Command Line | Classes/UnusedPrivateProperty                |
+--------------+----------------------------------------------+
| clearPHP     |                                              |
+--------------+----------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Dead code <dead-code>` |
+--------------+----------------------------------------------+



.. _unused-trait:

Unused Trait
############


Those traits were not found in a class.

+--------------+--------------------+
| Command Line | Traits/UnusedTrait |
+--------------+--------------------+
| clearPHP     |                    |
+--------------+--------------------+
| Analyzers    | :ref:`Analyze`     |
+--------------+--------------------+



.. _unused-classes:

Unused classes
##############


The following classes are never used in the code.

+--------------+----------------------------------------------+
| Command Line | Classes/UnusedClass                          |
+--------------+----------------------------------------------+
| clearPHP     |                                              |
+--------------+----------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Dead code <dead-code>` |
+--------------+----------------------------------------------+



.. _unused-constants:

Unused constants
################


Those constants are defined in the code but never used. Defining unused constants will slow down the application, has they are executed and stored in PHP hashtables. 

It is recommended to comment them out, and only define them when it is necessary.

+--------------+----------------------------------------------+
| Command Line | Constants/UnusedConstants                    |
+--------------+----------------------------------------------+
| clearPHP     |                                              |
+--------------+----------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Dead code <dead-code>` |
+--------------+----------------------------------------------+



.. _unused-functions:

Unused functions
################


The functions below are unused. They look like deadcode.

+--------------+----------------------------------------------+
| Command Line | Functions/UnusedFunctions                    |
+--------------+----------------------------------------------+
| clearPHP     |                                              |
+--------------+----------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Dead code <dead-code>` |
+--------------+----------------------------------------------+



.. _unused-static-methods:

Unused static methods
#####################


List of all static methods that are not used. This looks like dead code.

+--------------+----------------------------------------------+
| Command Line | Classes/UnusedPrivateMethod                  |
+--------------+----------------------------------------------+
| clearPHP     |                                              |
+--------------+----------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Dead code <dead-code>` |
+--------------+----------------------------------------------+



.. _unused-use:

Unused use
##########


List of use statement that are not used in the following code : they may be removed, as they clutter the code and slows PHP by forcing it to search in this list for nothing.

+--------------+---------------------------------------------------------------------------------------------+
| Command Line | Namespaces/UnusedUse                                                                        |
+--------------+---------------------------------------------------------------------------------------------+
| clearPHP     | `no-useless-use <https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-use.md>`__ |
+--------------+---------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Dead code <dead-code>`                                                |
+--------------+---------------------------------------------------------------------------------------------+



.. _use-===-null:

Use === null
############


It is faster to use === null instead of is\_null().

+--------------+---------------------------------------------------------------------------------------------------------------------+
| Command Line | Php/IsnullVsEqualNull                                                                                               |
+--------------+---------------------------------------------------------------------------------------------------------------------+
| clearPHP     | `avoid-those-slow-functions <https://github.com/dseguy/clearPHP/tree/master/rules/avoid-those-slow-functions.md>`__ |
+--------------+---------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                                      |
+--------------+---------------------------------------------------------------------------------------------------------------------+



.. _use-const-and-functions:

Use Const And Functions
#######################


Since PHP 5.6 it is possible to import specific functions or constants from other namespaces.

+--------------+---------------------------------------------------------------------------------+
| Command Line | Namespaces/UseFunctionsConstants                                                |
+--------------+---------------------------------------------------------------------------------+
| clearPHP     |                                                                                 |
+--------------+---------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55` |
+--------------+---------------------------------------------------------------------------------+



.. _use-constant-as-arguments:

Use Constant As Arguments
#########################


Some methods and functions are defined to be used with constants as arguments. Those constants are made to be meaningful and readable, keeping the code maintenable. It is recommended to use such constants as soon as they are documented.

+--------------+----------------------------------+
| Command Line | Functions/UseConstantAsArguments |
+--------------+----------------------------------+
| clearPHP     |                                  |
+--------------+----------------------------------+
| Analyzers    | :ref:`Analyze`                   |
+--------------+----------------------------------+



.. _use-instanceof:

Use Instanceof
##############


get\_class() should be replaced with the 'instanceof' operator to check the class of an object.

+--------------+--------------------------+
| Command Line | Structures/UseInstanceof |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Analyze`           |
+--------------+--------------------------+



.. _use-object-api:

Use Object Api
##############


When PHP offers the alternative between procedural and OOP api for the same features, it is recommended to sue the OOP API. 

Often, this least to more compact code, as methods are shorter, and there is no need to bring the resource around. Lots of new extensions are directly written in OOP form too.

+--------------+---------------------------------------------------------------------------------------------+
| Command Line | Php/UseObjectApi                                                                            |
+--------------+---------------------------------------------------------------------------------------------+
| clearPHP     | `use-object-api <https://github.com/dseguy/clearPHP/tree/master/rules/use-object-api.md>`__ |
+--------------+---------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                              |
+--------------+---------------------------------------------------------------------------------------------+



.. _use-pathinfo:

Use Pathinfo
############


Is is recommended to use pathinfo() function instead of string manipulation functions to extract the various parts of a path. It is more efficient and readable.

If you're using path with UTF-8 characters, pathinfo will strip them. There, you might have to use string functions.

+--------------+-----------------+
| Command Line | Php/UsePathinfo |
+--------------+-----------------+
| clearPHP     |                 |
+--------------+-----------------+
| Analyzers    | :ref:`Analyze`  |
+--------------+-----------------+



.. _use-const:

Use const
#########


The const keyword may be used to define constant, just like the define() function. 

When defining a constant, it is recommended to use 'const' when the features of the constant are not dynamical (name or value are known at compile time). 
This way, constant will be defined at compile time, and not at execution time. 

define() function is useful for all other situations.

+--------------+----------------------------+
| Command Line | Constants/ConstRecommended |
+--------------+----------------------------+
| clearPHP     |                            |
+--------------+----------------------------+
| Analyzers    | :ref:`Analyze`             |
+--------------+----------------------------+



.. _use-password\_hash():

Use password\_hash()
####################


PHP 5.5 introduced password\_hash() and password\_check() to replace the use of crypt() to check password.

+--------------+------------------------------------------------------------------------------------------------------------+
| Command Line | Php/Password55                                                                                             |
+--------------+------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                            |
+--------------+------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP71` |
+--------------+------------------------------------------------------------------------------------------------------------+



.. _use-with-fully-qualified-name:

Use with fully qualified name
#############################


PHP manual recommends not to use fully qualified name (starting with \) when using the 'use' statement : they are "the leading backslash is unnecessary and not recommended, as import names must be fully qualified, and are not processed relative to the current namespace".

+--------------+------------------------------------+
| Command Line | Namespaces/UseWithFullyQualifiedNS |
+--------------+------------------------------------+
| clearPHP     |                                    |
+--------------+------------------------------------+
| Analyzers    | :ref:`Analyze`                     |
+--------------+------------------------------------+



.. _used-once-variables:

Used once variables
###################


This is the list of used once variables. 

Such variables are useless. Variables must be used at least twice : once for writing, once for reading, at least. It is recommended to remove them.

In special situations, variables may be used once : 

+ PHP predefined variables, as they are already initialized. They are omitted in this analyze.
+ Interface function's arguments, since the function has no body; They are omitted in this analyze.
+ Dynamically created variables ($$x, ${$this->y} or also using extract), as they are runtime values and can't be determined at static code time. They are reported for manual review.
+ Dynamically included files will provide in-scope extra variables.

The current analyzer count variables at the application level, and not at a method scope level.

+--------------+----------------------------+
| Command Line | Variables/VariableUsedOnce |
+--------------+----------------------------+
| clearPHP     |                            |
+--------------+----------------------------+
| Analyzers    | :ref:`Analyze`             |
+--------------+----------------------------+



.. _used-once-variables-(in-scope):

Used once variables (in scope)
##############################


This is the list of used once variables, broken down by scope. Those variable are used once in a function, a method, a class or a namespace. In any case, this means the variable is used only once, while it should be used at least twice.

+--------------+-------------------------------------------------------------------------------------------------------+
| Command Line | Variables/VariableUsedOnceByContext                                                                   |
+--------------+-------------------------------------------------------------------------------------------------------+
| clearPHP     | `no-unused-arguments <https://github.com/dseguy/clearPHP/tree/master/rules/no-unused-arguments.md>`__ |
+--------------+-------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                        |
+--------------+-------------------------------------------------------------------------------------------------------+



.. _useless-abstract-class:

Useless Abstract Class
######################


Those classes are marked 'abstract' and they are never extended. This way, they won't be instantiated nor used. 

Abstract classes that have only static methods are omitted here : one usage of such classes are Utilities classes, which only offer static methods.

+--------------+-------------------------+
| Command Line | Classes/UselessAbstract |
+--------------+-------------------------+
| clearPHP     |                         |
+--------------+-------------------------+
| Analyzers    | :ref:`Analyze`          |
+--------------+-------------------------+



.. _useless-brackets:

Useless Brackets
################


You may remove those brackets, they have no use here. It may be a left over of an old instruction, or a misunderstanding of the alternative syntax.

+--------------+----------------------------+
| Command Line | Structures/UselessBrackets |
+--------------+----------------------------+
| clearPHP     |                            |
+--------------+----------------------------+
| Analyzers    | :ref:`Analyze`             |
+--------------+----------------------------+



.. _useless-final:

Useless Final
#############


When a class is declared final, all of its methods are also final by default. There is no need to declare them individually final.

+--------------+-------------------------------------------------------------------------------------------------+
| Command Line | Classes/UselessFinal                                                                            |
+--------------+-------------------------------------------------------------------------------------------------+
| clearPHP     | `no-useless-final <https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-final.md>`__ |
+--------------+-------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                  |
+--------------+-------------------------------------------------------------------------------------------------+



.. _useless-global:

Useless Global
##############


The listed global below are useless : they are only used once.

Also, PHP has superglobals, a special team of variables that are always available, whatever the context. 
They are : $GLOBALS, $\_SERVER, $\_GET, $\_POST, $\_FILES, $\_COOKIE, $\_SESSION, $\_REQUEST and $\_ENV. 
Simply avoid using 'global $\_POST'.

+--------------+--------------------------+
| Command Line | Structures/UselessGlobal |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Analyze`           |
+--------------+--------------------------+



.. _useless-interfaces:

Useless Interfaces
##################


The interfaces below are defined and are implemented by some classes. 
However, they are never used to enforce objects's class in the code, using instanceof or a typehint. 
As they are currently used, those interfaces may be removed without change in behavior.

.. code-block:: php

   <?php
       // only defined interface but never enforced
       interface i {};
       class c implements i {} 
   ?>


Interfaces should be used in Typehint or with the instanceof operator. 

.. code-block:: php

   <?php
       interface i {};
       
       function foo(i $arg) { 
           // Now, $arg is always an 'i'
       }
       
       function bar($arg) { 
           if (!($arg instanceof i)) {
               // Now, $arg is always an 'i'
           }
       }
   ?>

+--------------+-----------------------------------------------------------------------------------------------------------+
| Command Line | Interfaces/UselessInterfaces                                                                              |
+--------------+-----------------------------------------------------------------------------------------------------------+
| clearPHP     | `no-useless-interfaces <https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-interfaces.md>`__ |
+--------------+-----------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                            |
+--------------+-----------------------------------------------------------------------------------------------------------+



.. _useless-parenthesis:

Useless Parenthesis
###################


Situations where parenthesis are not necessary, and may be removed.

+--------------+-------------------------------+
| Command Line | Structures/UselessParenthesis |
+--------------+-------------------------------+
| clearPHP     |                               |
+--------------+-------------------------------+
| Analyzers    | :ref:`Analyze`                |
+--------------+-------------------------------+



.. _useless-unset:

Useless Unset
#############


Unsetting variables may not be applicable with a certain type of variables. This is the list of such cases.

+--------------+-------------------------------------------------------------------------------------------------+
| Command Line | Structures/UselessUnset                                                                         |
+--------------+-------------------------------------------------------------------------------------------------+
| clearPHP     | `no-useless-unset <https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-unset.md>`__ |
+--------------+-------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                  |
+--------------+-------------------------------------------------------------------------------------------------+



.. _useless-constructor:

Useless constructor
###################


Class constructor that have empty bodies are useless. They may be removed.

+--------------+----------------------------+
| Command Line | Classes/UselessConstructor |
+--------------+----------------------------+
| clearPHP     |                            |
+--------------+----------------------------+
| Analyzers    | :ref:`Analyze`             |
+--------------+----------------------------+



.. _useless-instructions:

Useless instructions
####################


The instructions below are useless. For example, running '&lt;?php 1 + 1; ?&gt;' will do nothing, as the addition is actually performed, but not used : not displayed, not stored, not set. Just plain lost. 

The first level of the spotted instructions may be removed safely. For example, the analyzer will spot : '1 + $a++'; as a useless instruction. The addition is useless, but the plusplus is not.

+--------------+-------------------------------------------------------------------------------------------------------------+
| Command Line | Structures/UselessInstruction                                                                               |
+--------------+-------------------------------------------------------------------------------------------------------------+
| clearPHP     | `no-useless-instruction <https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-instruction.md>`__ |
+--------------+-------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                              |
+--------------+-------------------------------------------------------------------------------------------------------------+



.. _useless-return:

Useless return
##############


The spotted functions or methods have a return statement, but this statement is useless. This is the case for constructor and destructors, whose return value are ignored or inaccessible.

+--------------+-------------------------+
| Command Line | Functions/UselessReturn |
+--------------+-------------------------+
| clearPHP     |                         |
+--------------+-------------------------+
| Analyzers    | :ref:`Analyze`          |
+--------------+-------------------------+



.. _uses-default-values:

Uses Default Values
###################


Default values are provided to methods so as to make it convenient to use. However, with new versions, those values may change. For example, in PHP 5.4, html\_entities switched from Latin1 to UTF-8 default encoding.

As much as possible, it is recommended to use explicit values in those methods, so as to prevent from being surprise at a future PHP evolution.

+--------------+--------------------------------+
| Command Line | Functions/UsesDefaultArguments |
+--------------+--------------------------------+
| clearPHP     |                                |
+--------------+--------------------------------+
| Analyzers    | :ref:`Analyze`                 |
+--------------+--------------------------------+



.. _usort-sorting-in-php-7.0:

Usort Sorting In PHP 7.0
########################


Usort (and co) sorting has changed in PHP 7. Values that are equals (based on user-provided method) may be sorted differently than in PHP 5. 

If this sorting is important, it is advised to add extra comparison in the user-function and avoid returning 0 (thus, depending on default implementation).

+--------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Command Line | Php/UsortSorting                                                                                                                                                 |
+--------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                                                                                  |
+--------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP71` |
+--------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _var:

Var
###


Var was used in PHP 4 to mark properties as public. Nowadays, new keywords are available : public, protected, private. Var is equivalent to public.

+--------------+---------------------------------------------------------------------------------------------------------+
| Command Line | Classes/OldStyleVar                                                                                     |
+--------------+---------------------------------------------------------------------------------------------------------+
| clearPHP     | `no-php4-class-syntax <https://github.com/dseguy/clearPHP/tree/master/rules/no-php4-class-syntax.md>`__ |
+--------------+---------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                          |
+--------------+---------------------------------------------------------------------------------------------------------+



.. _variable-global:

Variable Global
###############


Variable global such as global $$foo->bar are valid in PHP 5.6, but no in PHP 7.0. They should be replaced with ${$foo->bar}.

+--------------+------------------------------------------------------------------------------------------------------------+
| Command Line | Structures/VariableGlobal                                                                                  |
+--------------+------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                            |
+--------------+------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+--------------+------------------------------------------------------------------------------------------------------------+



.. _while(list()-=-each()):

While(List() = Each())
######################


This code structure is quite old : it should be replace by the more modern and efficient foreach.

.. code-block:: php

   <?php
       foreach($array as $key => $value) {
           doSomethingWith($key) and $value;
       }
   ?>

+--------------+-------------------------------------+
| Command Line | Structures/WhileListEach            |
+--------------+-------------------------------------+
| clearPHP     |                                     |
+--------------+-------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`Performances` |
+--------------+-------------------------------------+



.. _written-only-variables:

Written Only Variables
######################


Those variables are being written, but never read. This way, they are useless and should be removed, or read at some point.

+--------------+-----------------------------------------------------------------------------------------------------+
| Command Line | Variables/WrittenOnlyVariable                                                                       |
+--------------+-----------------------------------------------------------------------------------------------------+
| clearPHP     | `no-unused-variable <https://github.com/dseguy/clearPHP/tree/master/rules/no-unused-variable.md>`__ |
+--------------+-----------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                      |
+--------------+-----------------------------------------------------------------------------------------------------+



.. _wrong-number-of-arguments:

Wrong Number Of Arguments
#########################


Those functioncalls are made with too many or too few arguments. Some of them will be dropped, or PHP will raise errors when values are missing.

It is recommended to check the signature of the methods, and fix the arguments.

+--------------+-------------------------------------------------------------------------------------------------------------+
| Command Line | Functions/WrongNumberOfArguments                                                                            |
+--------------+-------------------------------------------------------------------------------------------------------------+
| clearPHP     | `no-missing-argument.md <https://github.com/dseguy/clearPHP/tree/master/rules/no-missing-argument.md.md>`__ |
+--------------+-------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                                              |
+--------------+-------------------------------------------------------------------------------------------------------------+



.. _wrong-optional-parameter:

Wrong Optional parameter
########################


PHP parameters are optional when they defined with a default value, like this : function x($arg = 1) {...}.

When there are compulsory and optional parameters, the first ones should appear first, and the second should appear last : function x($arg, $arg2 = 2) {...}.

PHP will solve this problem at runtime, assign values in the same other, but will miss some of the default values and emits warnings. 

It is better to put all the optional parameters at the end of the method's signature.

+--------------+----------------------------------+
| Command Line | Functions/WrongOptionalParameter |
+--------------+----------------------------------+
| clearPHP     |                                  |
+--------------+----------------------------------+
| Analyzers    | :ref:`Analyze`                   |
+--------------+----------------------------------+



.. _wrong-parameter-type:

Wrong Parameter Type
####################


The expected parameter is not the correct type. Check PHP documentation to know which is the right format to be used.

+--------------+---------------------------+
| Command Line | Php/InternalParameterType |
+--------------+---------------------------+
| clearPHP     |                           |
+--------------+---------------------------+
| Analyzers    | :ref:`Analyze`            |
+--------------+---------------------------+



.. _\_\_debuginfo():

\_\_debugInfo()
###############


The magic function \_\_debugInfo() has been introduced in PHP 5.6. In the previous versions of PHP, this method is ignored and won't be called when debugging.

+--------------+---------------------------------------------------------------------------------+
| Command Line | Php/debugInfoUsage                                                              |
+--------------+---------------------------------------------------------------------------------+
| clearPHP     |                                                                                 |
+--------------+---------------------------------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55` |
+--------------+---------------------------------------------------------------------------------+



.. _\_\_tostring()-throws-exception:

\_\_toString() Throws Exception
###############################


Magical method \_\_toString() can't throw exceptions, according to the world.

+--------------+------------------------------------+
| Command Line | Structures/toStringThrowsException |
+--------------+------------------------------------+
| clearPHP     |                                    |
+--------------+------------------------------------+
| Analyzers    | :ref:`Analyze`                     |
+--------------+------------------------------------+



.. _crypt-without-salt:

crypt without salt
##################


PHP 5.6 and later require a salt, while previous versions didn't require it. Salt is a simple string, that is usually only known by the application.

+--------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+
| Command Line | Structures/CryptWithoutSalt                                                                                                                           |
+--------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                                                                       |
+--------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP71` |
+--------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _error\_reporting()-with-integers:

error\_reporting() With Integers
################################


Using named constants with error\_reporting is strongly encouraged to ensure compatibility for future versions. As error levels are added, the range of integers increases, so older integer-based error levels will not always behave as expected. (Adapted from the documentation)

+--------------+--------------------------------------+
| Command Line | Structures/ErrorReportingWithInteger |
+--------------+--------------------------------------+
| clearPHP     |                                      |
+--------------+--------------------------------------+
| Analyzers    | :ref:`Analyze`                       |
+--------------+--------------------------------------+



.. _ext/apc:

ext/apc
#######


Extension APC

+--------------+----------------------------------------------------------------------------------------------------------------------------+
| Command Line | Extensions/Extapc                                                                                                          |
+--------------+----------------------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                                            |
+--------------+----------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP71` |
+--------------+----------------------------------------------------------------------------------------------------------------------------+



.. _ext/dba:

ext/dba
#######


Extension ext/dba

+--------------+---------------------------+
| Command Line | Extensions/Extdba         |
+--------------+---------------------------+
| clearPHP     |                           |
+--------------+---------------------------+
| Analyzers    | :ref:`CompatibilityPHP53` |
+--------------+---------------------------+



.. _ext/ereg:

ext/ereg
########


Extension ext/ereg

+--------------+------------------------------------------------------+
| Command Line | Extensions/Extereg                                   |
+--------------+------------------------------------------------------+
| clearPHP     |                                                      |
+--------------+------------------------------------------------------+
| Analyzers    | :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71` |
+--------------+------------------------------------------------------+



.. _ext/fdf:

ext/fdf
#######


Extension ext/fdf

+--------------+-------------------------------------------+
| Command Line | Extensions/Extfdf                         |
+--------------+-------------------------------------------+
| clearPHP     |                                           |
+--------------+-------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP53` |
+--------------+-------------------------------------------+



.. _ext/ming:

ext/ming
########


Extension ext/ming, to create swf files with PHP.

+--------------+---------------------------+
| Command Line | Extensions/Extming        |
+--------------+---------------------------+
| clearPHP     |                           |
+--------------+---------------------------+
| Analyzers    | :ref:`CompatibilityPHP53` |
+--------------+---------------------------+



.. _ext/mysql:

ext/mysql
#########


Extension ext/mysql

+--------------+----------------------------------------------------------------------------------------------------------------------------+
| Command Line | Extensions/Extmysql                                                                                                        |
+--------------+----------------------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                                            |
+--------------+----------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP71` |
+--------------+----------------------------------------------------------------------------------------------------------------------------+



.. _ext/sqlite:

ext/sqlite
##########


Extension ext/sqlite3

+--------------+----------------------+
| Command Line | Extensions/Extsqlite |
+--------------+----------------------+
| clearPHP     |                      |
+--------------+----------------------+
| Analyzers    | :ref:`Analyze`       |
+--------------+----------------------+



.. _func\_get\_arg()-modified:

func\_get\_arg() Modified
#########################


func\_get\_arg() and func\_get\_args() used to report the calling value of the argument until PHP 7. Since PHP 7, it is reporting the value of the argument at calling time, which may have been modified by a previous instruction. 

.. code-block:: php

   <?php
   
   function x($a) {
       $a++;
       print func\_get\_arg(0);
   }
   
   x(0);
   ?>


This code will display 1 in PHP 7, and 0 in PHP 5.

+--------------+----------------------------------------------------------------------+
| Command Line | Functions/funcGetArgModified                                         |
+--------------+----------------------------------------------------------------------+
| clearPHP     |                                                                      |
+--------------+----------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71` |
+--------------+----------------------------------------------------------------------+



.. _include\_once()-usage:

include\_once() Usage
#####################


All the \_once inclusion functions should be avoided for performances reasons.

Try using auto\_load() for loading classes, or using include() and make it possible to include several times the same file without errors.

+--------------+----------------------+
| Command Line | Structures/OnceUsage |
+--------------+----------------------+
| clearPHP     |                      |
+--------------+----------------------+
| Analyzers    | :ref:`Analyze`       |
+--------------+----------------------+



.. _list()-may-omit-variables:

list() May Omit Variables
#########################


list() is the only PHP function that accepts to have omitted arguments. If the following code makes no usage of a listed variable, just omit it. 

.. code-block:: php

   <?php
       list ($a, , $b) = array(1, 2, 3);
   ?>


$b will be 3, and the 2 value will be omitted. This is cleaner, and save some memory.

+--------------+--------------------------+
| Command Line | Structures/ListOmissions |
+--------------+--------------------------+
| clearPHP     |                          |
+--------------+--------------------------+
| Analyzers    | :ref:`Analyze`           |
+--------------+--------------------------+



.. _mcrypt\_create\_iv-with-default-values:

mcrypt\_create\_iv with default values
######################################


mcrypt\_create\_iv used to have MCRYPT\_DEV\_RANDOM as default values, and in PHP 5.6, it now uses MCRYPT\_DEV\_URANDOM.

If the code doesn't have a second argument, it relies on the default value. It is recommended to set explicitely the value, so has to avoid problems while migrating.

+--------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+
| Command Line | Structures/McryptcreateivWithoutOption                                                                                                                |
+--------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+
| clearPHP     |                                                                                                                                                       |
+--------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP71` |
+--------------+-------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _parse\_str-warning:

parse\_str Warning
##################


The parse\_str function will parse a query string and assign the resulting variables to the local scope. This may create a unexpected number of variables, and even overwrite the one existing.

Always use an empty variable a second parameter to parse\_str, so as to collect the incoming values, and then, filter them in that array.

+--------------+------------------------------------+
| Command Line | Security/parseUrlWithoutParameters |
+--------------+------------------------------------+
| clearPHP     |                                    |
+--------------+------------------------------------+
| Analyzers    | :ref:`Security`                    |
+--------------+------------------------------------+



.. _var\_dump()...-usage:

var\_dump()... Usage
####################


var\_dump(), print\_r() or var\_export() are debugging functions, that should not be left in any production code. 

They may be tolerated during development time, but must be removed so as not to have any chance to be run in production.

+--------------+-------------------------------------------------------------------------------------------+
| Command Line | Structures/VardumpUsage                                                                   |
+--------------+-------------------------------------------------------------------------------------------+
| clearPHP     | `no-debug-code <https://github.com/dseguy/clearPHP/tree/master/rules/no-debug-code.md>`__ |
+--------------+-------------------------------------------------------------------------------------------+
| Analyzers    | :ref:`Analyze`                                                                            |
+--------------+-------------------------------------------------------------------------------------------+



