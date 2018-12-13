.. _Rules:

Rules list
----------

Introduction
############

.. comment: The rest of the document is automatically generated. Don't modify it manually. 
.. comment: Rules details
.. comment: Generation date : Thu, 13 Dec 2018 14:28:56 +0000
.. comment: Generation hash : 3867868aaaaef0d874f4f4634a4cfb5c0e4ccd72


.. _$http\_raw\_post\_data-usage:

$HTTP_RAW_POST_DATA Usage
#########################


`$HTTP_RAW_POST_DATA <http://php.net/manual/en/reserved.variables.httprawpostdata.php>`_ is deprecated, and should be replaced by php://input. 

`$HTTP_RAW_POST_DATA <http://php.net/manual/en/reserved.variables.httprawpostdata.php>`_ is deprecated since PHP 5.6.

It is possible to ready by setting always_populate_raw_post_data to -1.

.. code-block:: php

   <?php
   
   // PHP 5.5 and older
   $postdata = $HTTP_RAW_POST_DATA;
   
   // PHP 5.6 and more recent
   $postdata = file_get_contents(php://input);
   
   ?>


See also `$HTTP_RAW_POST_DATA variable <http://php.net/manual/en/reserved.variables.httprawpostdata.php>`_.

+-------------+---------------------------+
| Short name  | Php/RawPostDataUsage      |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP56` |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Slow (1 hour)             |
+-------------+---------------------------+



.. _$this-belongs-to-classes-or-traits:

$this Belongs To Classes Or Traits
##################################


`$this <http://php.net/manual/en/language.oop5.basic.php>`_ variable represents only the current object. 

It is a pseudo-variable, and should be used within class's or trait's methods (except for static) and not outside.

PHP 7.1 is stricter and check for `$this <http://php.net/manual/en/language.oop5.basic.php>`_ at several positions. Some are found by static analysis, some are dynamic analysis.

.. code-block:: php

   <?php
   
   // as an argument
   function foo($this) {
       // Using global
       global $this;
       // Using static (not a property)
       static $this;
       
       // Can't unset it
       unset($this);
       
       try {
           // inside a foreach
           foreach($a as $this) {  }
           foreach($a as $this => $b) {  }
           foreach($a as $b => $this) {  }
       } catch (Exception $this) {
           // inside a catch
       }
       
       // with Variable Variable
       $a = this;
       $$a = 42;
   }
   
   class foo {
       function bar() {
           // Using references
           $a =& $this;
           $a = 42;
           
           // Using extract(), parse_str() or similar functions
           extract([this => 42]);  // throw new Error(Cannot re-assign $this)
           var_dump($this);
       }
   
       static function __call($name, $args) {
           // Using __call
           var_dump($this); // prints object(C)#1 (0) {}, php-7.0 printed NULL
           $this->test();   // prints ops
       }
   
   }
   ?>

+-------------+--------------------------+
| Short name  | Classes/ThisIsForClasses |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Major                    |
+-------------+--------------------------+
| Time To Fix | Quick (30 mins)          |
+-------------+--------------------------+



.. _$this-is-not-an-array:

$this Is Not An Array
#####################


`$this <http://php.net/manual/en/language.oop5.basic.php>`_ variable represents the current object and it is not an array. 

This is unless the class (or its parents) has the `ArrayAccess <http://php.net/manual/en/class.arrayaccess.php>`_ interface, or extends ArrayObjec` or `SimpleXMLElement <http://php.net/manual/en/class.simplexmlelement.php>`_.

.. code-block:: php

   <?php
   
   // $this is an array
   class Foo extends ArrayAccess {
       function bar() {
           ++$this[3];
       }
   }
   
   // $this is not an array
   class Foo2 {
       function bar() {
           ++$this[3];
       }
   }
   
   ?>


See also `ArrayAccess <http://php.net/manual/en/class.arrayaccess.php>`_,
         `ArrayObject <http://php.net/manual/en/class.arrayobject.php>`_ and 
         `The Basics <http://php.net/manual/en/language.oop5.basic.php>`_.


Suggestions
^^^^^^^^^^^

* Extends ArrayObject, or a class that extends it, to use $this as an array too.
* Implements ArrayAccess to use $this as an array too.
* Use a property in the current class to store the data, instead of $this directly.

+-------------+--------------------------+
| Short name  | Classes/ThisIsNotAnArray |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Major                    |
+-------------+--------------------------+
| Time To Fix | Quick (30 mins)          |
+-------------+--------------------------+



.. _$this-is-not-for-static-methods:

$this Is Not For Static Methods
###############################


Static methods shouldn't use `$this <http://php.net/manual/en/language.oop5.basic.php>`_ variable.

`$this <http://php.net/manual/en/language.oop5.basic.php>`_ variable represents an object, the current object. It is not compatible with a static method, which may operate without any object. 

While executing a static method, `$this <http://php.net/manual/en/language.oop5.basic.php>`_ is actually set to `NULL <http://php.net/manual/en/language.types.null.php>`_.

.. code-block:: php

   <?php
   
   class foo {
       static $staticProperty = 1;
   
       // Static methods should use static properties
       static public function count() {
           return self::$staticProperty++;
       }
       
       // Static methods can't use $this
       static public function bar() {
           return $this->a;   // No $this usage in a static method
       }
   }
   
   ?>


See also `Static Keyword <http://php.net/manual/en/language.oop5.static.php>`_.


Suggestions
^^^^^^^^^^^

* Remove the static keyword on the method, and update all calls to this method to use $this
* Remove the usage of $this in the method, replacing it with static properties
* Make $this an argument (and change its name) : then, make the method a function

+-------------+---------------------------------------------------------------------------------------------+
| Short name  | Classes/ThisIsNotForStatic                                                                  |
+-------------+---------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                              |
+-------------+---------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                       |
+-------------+---------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                             |
+-------------+---------------------------------------------------------------------------------------------+
| ClearPHP    | `no-static-this <https://github.com/dseguy/clearPHP/tree/master/rules/no-static-this.md>`__ |
+-------------+---------------------------------------------------------------------------------------------+



.. _**-for-exponent:

** For Exponent
###############


PHP has the operator `** <http://php.net/manual/en/language.operators.arithmetic.php>`_ to provide exponents, instead of the slower function `pow() <http://www.php.net/pow>`_. This operator was introduced in PHP 5.6.

.. code-block:: php

   <?php
       $cube = pow(2, 3); // 8
   
       $cubeInPHP56 = 2 ** 3; // 8
   ?>


If the code needs to be backward compatible to 5.5 or less, don't use the new operator.

See also `Arithmetic Operators <http://php.net/manual/en/language.operators.arithmetic.php>`_.

+-------------+------------------------------+
| Short name  | Php/NewExponent              |
+-------------+------------------------------+
| Themes      | :ref:`Suggestions`           |
+-------------+------------------------------+
| Php Version | With PHP 5.6 and more recent |
+-------------+------------------------------+
| Severity    | Minor                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _\:\:class:

\:\:class
#########


PHP has a special class constant to hold the name of the class : ``class`` keyword. It represents the class name that is used in the left part of the operator.

Using ``\:\:class`` is safer than relying on a string. It does adapt if the class's name or its namespace is changed'. It is also faster, though it is a micro-optimisation. 

It is introduced in PHP 5.5.

.. code-block:: php

   <?php
   
   use A\B\C as UsedName;
   
   class foo {
       public function bar( ) {
           echo ClassName::class; 
           echo UsedName::class; 
       }
   }
   
   $f = new Foo( );
   $f->bar( );
   // displays ClassName 
   // displays A\B\C 
   
   ?>


Be aware that ``\:\:class`` is a replacement for `__CLASS__ <http://php.net/manual/en/language.constants.predefined.php>`_ magic constant. 

See also `Class Constant <http://php.net/manual/en/language.oop5.constants.php>`_.


Suggestions
^^^^^^^^^^^

* Use \:\:class whenever possible. That exclude any dynamic call.

+-------------+------------------------------------------------------+
| Short name  | Php/StaticclassUsage                                 |
+-------------+------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54` |
+-------------+------------------------------------------------------+
| Php Version | With PHP 5.5 and more recent                         |
+-------------+------------------------------------------------------+
| Severity    | Major                                                |
+-------------+------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                        |
+-------------+------------------------------------------------------+



.. _@-operator:

@ Operator
##########


`@ <http://php.net/manual/en/language.operators.errorcontrol.php>`_ is the 'no scream' operator : it suppresses error output. 

.. code-block:: php

   <?php
   
   // Set x with incoming value, or else null. 
   $x = @$_GET['x'];
   
   ?>


This operator is actually very slow : it will process the error all the way up, and finally decide not to display it. It is often faster to check the conditions first, then run the method without ``@``.

You may also set display_error to 0 in the ``php.ini`` : this will avoid user's error display, but will keep the error in the PHP logs, for later processing. 

The only situation where ``@`` is useful is when a native PHP function displays errors messages when error happens and there is no way to check it from the code. 

This is the case with `fopen() <http://www.php.net/fopen>`_, `stream_socket_server() <http://www.php.net/stream_socket_server>`_, `token_get_all() <http://www.php.net/token_get_all>`_. 

See also `Error Control Operators <http://php.net/manual/en/language.operators.errorcontrol.php>`_.

+-------------+---------------------------------------------------------------------------------------+
| Short name  | Structures/Noscream                                                                   |
+-------------+---------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                        |
+-------------+---------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                 |
+-------------+---------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                       |
+-------------+---------------------------------------------------------------------------------------+
| ClearPHP    | `no-noscream <https://github.com/dseguy/clearPHP/tree/master/rules/no-noscream.md>`__ |
+-------------+---------------------------------------------------------------------------------------+



.. _abstract-or-implements:

Abstract Or Implements
######################


A class must implements all abstract methods of it parent, or be abstract too. 

While PHP lints this code, it won't execute it and stop with a Fatal Error : ``Class BA contains 1 abstract method and must therefore be declared abstract or implement the remaining methods (A\:\:aFoo)``.

.. code-block:: php

   <?php
   
   abstract class Foo { 
       abstract function FooBar();
   }
   
   // This is in another file : php -l would detect it right away
   
   class FooFoo extends Foo { 
       // The method is not defined. 
       // The class must be abstract, just like Foo
   }
   
   ?>


See also `Class Abstraction <http://php.net/abstract>`_.


Suggestions
^^^^^^^^^^^

* Implements all the abstract methods of the class
* Make the class abstract

+-------------+-------------------------------------------+
| Short name  | Classes/AbstractOrImplements              |
+-------------+-------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`LintButWontExec`    |
+-------------+-------------------------------------------+
| Severity    | Major                                     |
+-------------+-------------------------------------------+
| Time To Fix | Quick (30 mins)                           |
+-------------+-------------------------------------------+
| Examples    | :ref:`zurmo-classes-abstractorimplements` |
+-------------+-------------------------------------------+



.. _abstract-static-methods:

Abstract Static Methods
#######################


Methods cannot be both abstract and static. Static methods belong to a class, and will not be overridden by the child class. For normal methods, PHP will start at the object level, then go up the hierarchy to find the method. With static, it is necessary to mention the name, or use Late Static Binding, with self or static. Hence, it is useless to have an abstract static method : it should be a static method.

A child class is able to declare a method with the same name than a static method in the parent, but those two methods will stay independent. 

This is not the case anymore in PHP 7.0+.

.. code-block:: php

   <?php
   
   abstract class foo {
       // This is not possible
       static abstract function bar() ;
   }
   
   ?>


See also `Why does PHP 5.2+ disallow abstract static class methods? <https://stackoverflow.com/questions/999066/why-does-php-5-2-disallow-abstract-static-class-methods>`_.

+-------------+------------------------+
| Short name  | Classes/AbstractStatic |
+-------------+------------------------+
| Themes      | :ref:`Analyze`         |
+-------------+------------------------+
| Php Version | With PHP 7.0 and older |
+-------------+------------------------+
| Severity    | Minor                  |
+-------------+------------------------+
| Time To Fix | Quick (30 mins)        |
+-------------+------------------------+



.. _access-protected-structures:

Access Protected Structures
###########################


It is not allowed to access protected properties or methods from outside the class or its relatives.

.. code-block:: php

   <?php
   
   class foo {
       protected $bar = 1;
   }
   
   $foo = new Foo();
   $foo->bar = 2;
   
   ?>

+-------------+-------------------------+
| Short name  | Classes/AccessProtected |
+-------------+-------------------------+
| Themes      | :ref:`Analyze`          |
+-------------+-------------------------+
| Severity    | Major                   |
+-------------+-------------------------+
| Time To Fix | Quick (30 mins)         |
+-------------+-------------------------+



.. _accessing-private:

Accessing Private
#################


List of calls to private properties/methods that will compile but yield some fatal error upon execution.

.. code-block:: php

   <?php
   
   class a {
       private $a;
   }
   
   class b extends a {
       function c() {
           $this->a;
       }
   }
   
   ?>

+-------------+-----------------------+
| Short name  | Classes/AccessPrivate |
+-------------+-----------------------+
| Themes      | :ref:`Analyze`        |
+-------------+-----------------------+
| Severity    | Major                 |
+-------------+-----------------------+
| Time To Fix | Quick (30 mins)       |
+-------------+-----------------------+



.. _add-default-value:

Add Default Value
#################


Parameter in methods definition may receive a default value. This allows the called method to set a value when the parameter is omitted. 

.. code-block:: php

   <?php
   
   function foo($i) {
       if (!is_integer($i)) {
           $i = 0;
       }
   }
   
   ?>

+-------------+---------------------------+
| Short name  | Functions/AddDefaultValue |
+-------------+---------------------------+
| Themes      | :ref:`Suggestions`        |
+-------------+---------------------------+
| Severity    | Minor                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _adding-zero:

Adding Zero
###########


Adding 0 is useless, as 0 is the neutral element for addition. In PHP, it triggers a cast to integer. 

It is recommended to make the cast explicit with (int) 

.. code-block:: php

   <?php
   
   // Explicit cast
   $a = (int) foo();
   
   // Useless addition
   $a = foo() + 0;
   $a = 0 + foo();
   
   // Also works with minus
   $b = 0 - $c; // drop the 0, but keep the minus
   $b = $c - 0; // drop the 0 and the minus
   
   $a += 0;
   $a -= 0;
   
   ?>


If it is used to type cast a value to integer, then casting (integer) is clearer. 


Suggestions
^^^^^^^^^^^

* Remove the + 0
* Use an explicit type casting operator (int)

+-------------+-----------------------------------------------------------------------------------------------+
| Short name  | Structures/AddZero                                                                            |
+-------------+-----------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                |
+-------------+-----------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                         |
+-------------+-----------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                              |
+-------------+-----------------------------------------------------------------------------------------------+
| ClearPHP    | `no-useless-math <https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-math.md>`__ |
+-------------+-----------------------------------------------------------------------------------------------+
| Examples    | :ref:`thelia-structures-addzero`, :ref:`openemr-structures-addzero`                           |
+-------------+-----------------------------------------------------------------------------------------------+



.. _aliases-usage:

Aliases Usage
#############


PHP manual recommends to avoid function aliases.

Some functions have several names, and both may be used the same way. However, one of the names is the main name, and the others are aliases. Aliases may be removed or change or dropped in the future. Even if this is not forecast, it is good practice to use the main name, instead of the aliases. 

.. code-block:: php

   <?php
   
   // official way to count an array
   $n = count($array);
   
   // official way to count an array
   $n = sizeof($array);
   
   ?>


Aliases are compiled in PHP, and do not provide any performances over the normal function. 

Aliases are more likely to be removed later, but they have been around for a long time.

See documentation : `List of function aliases <http://php.net/manual/en/aliases.php>`_.

+-------------+-------------------------------------------------------------------------------------+
| Short name  | Functions/AliasesUsage                                                              |
+-------------+-------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                      |
+-------------+-------------------------------------------------------------------------------------+
| Severity    | Minor                                                                               |
+-------------+-------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                     |
+-------------+-------------------------------------------------------------------------------------+
| ClearPHP    | `no-aliases <https://github.com/dseguy/clearPHP/tree/master/rules/no-aliases.md>`__ |
+-------------+-------------------------------------------------------------------------------------+



.. _all-uppercase-variables:

All Uppercase Variables
#######################


Usually, global variables are all in uppercase, so as to differentiate them easily. Though, this is not always the case, with examples like $argc, $argv or $http_response_header.

When using custom variables, try to use lowercase ``$variables``, ``$camelCase``, ``$sturdyCase`` or ``$snake_case``.

.. code-block:: php

   <?php
   
   // PHP super global, also identified by the initial _
   $localVariable = $_POST;
   
   // PHP globals
   $localVariable = $GLOBALS['HTTPS'];
   
   ?>


See also `Predefined Variables <http://php.net/manual/en/reserved.variables.php>`_.

+-------------+------------------------------------------------+
| Short name  | Variables/VariableUppercase                    |
+-------------+------------------------------------------------+
| Themes      | :ref:`Coding Conventions <coding-conventions>` |
+-------------+------------------------------------------------+
| Severity    | Minor                                          |
+-------------+------------------------------------------------+
| Time To Fix | Slow (1 hour)                                  |
+-------------+------------------------------------------------+



.. _already-parents-interface:

Already Parents Interface
#########################


The same interface is implemented by a class and one of its children. 

That way, the child doesn't need to implement the interface, nor define its methods to be an instance of the interface. 

.. code-block:: php

   <?php
   
   interface i { 
       function i();
   }
   
   class A implements i {
       function i() {
           return __METHOD__;
       }
   }
   
   // This implements is useless. 
   class AB extends A implements i {
       // No definition for function i()
   }
   
   // Implements i is understated
   class AB extends A {
       // redefinition of the i method
       function i() {
           return __METHOD__.' ';
       }
   }
   
   $x = new AB;
   var_dump($x instanceof i);
   // true
   
   $x = new AC;
   var_dump($x instanceof i);
   // true
   
   ?>

+-------------+------------------------------------+
| Short name  | Interfaces/AlreadyParentsInterface |
+-------------+------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Suggestions` |
+-------------+------------------------------------+
| Severity    | Minor                              |
+-------------+------------------------------------+
| Time To Fix | Instant (5 mins)                   |
+-------------+------------------------------------+



.. _altering-foreach-without-reference:

Altering Foreach Without Reference
##################################


`Foreach() <http://php.net/manual/en/control-structures.foreach.php>`_ loop that should use a reference. 

When using a foreach loop that modifies the original source, it is recommended to use referenced variables, rather than access the original value with $source[$index]. 

Using references is then must faster, and easier to read. 

.. code-block:: php

   <?php
   
   // Using references in foreach
   foreach($source as $key => &$value) {
       $value = newValue($value, $key);
   }
   
   // Avoid foreach : use array_map
   $source = array_walk($source, 'newValue');
       // Here, $key MUST be the second argument or newValue
   
   // Slow version to update the array
   foreach($source as $key => &$value) {
       $source[$key] = newValue($value, $key);
   }
   ?>


You may also use `array_walk() <http://www.php.net/array_walk>`_ or `array_map() <http://www.php.net/array_map>`_ (when $key is not used) to avoid the use of foreach.

See also `Foreach <http://php.net/manual/en/control-structures.foreach.php>`_.

Suggestions
^^^^^^^^^^^

* Add the reference on the modified blind variable, and avoid accessing the source array

+-------------+-----------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Structures/AlteringForeachWithoutReference                                                                                        |
+-------------+-----------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                                                    |
+-------------+-----------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                                             |
+-------------+-----------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                                   |
+-------------+-----------------------------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `use-reference-to-alter-in-foreach <https://github.com/dseguy/clearPHP/tree/master/rules/use-reference-to-alter-in-foreach.md>`__ |
+-------------+-----------------------------------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`wordpress-structures-alteringforeachwithoutreference`                                                                       |
+-------------+-----------------------------------------------------------------------------------------------------------------------------------+



.. _alternative-syntax-consistence:

Alternative Syntax Consistence
##############################


PHP allows for two syntax : the alternative syntax, and the classic syntax. 

The classic syntax is almost always used. When used, the alternative syntax is used in templates. 

This analysis reports files that are using both syntax at the same time. This is confusing.

.. code-block:: php

   <?php
   
   // Mixing both syntax is confusing.
   foreach($array as $item) : 
       if ($item > 1) {
           print $item elements\n;
       } else {
           print $item element\n;
       }
   endforeach;
   
   ?>

+-------------+-----------------------------------------+
| Short name  | Structures/AlternativeConsistenceByFile |
+-------------+-----------------------------------------+
| Themes      | :ref:`Analyze`                          |
+-------------+-----------------------------------------+
| Severity    | Major                                   |
+-------------+-----------------------------------------+
| Time To Fix | Quick (30 mins)                         |
+-------------+-----------------------------------------+



.. _always-anchor-regex:

Always Anchor Regex
###################


Unanchored regex finds the requested pattern, and leaves room for malicious content. 

Without ``^`` and ``$``, the regex searches for any pattern that satisfies the criteria, leaving any unused part of the string available for arbitrary content. It is recommended to use both anchor

.. code-block:: php

   <?php
   
   $birthday = getSomeDate($_GET);
   
   // Permissive version : $birthday = '1970-01-01<script>xss();</script>';
   if (!preg_match('/\d{4}-\d{2}-\d{2}/', $birthday) {
       error('Wrong data format for your birthday!');
   }
   
   // Restrictive version : $birthday = '1970-01-01';
   if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $birthday) {
       error('Wrong data format for your birthday!');
   }
   
   echo 'Your birthday is on '.$birthday;
   
   ?>


Note that $ may be a line ending, still leaving room after it for injection.

.. code-block:: php

   <?php
   
   $birthday = '1970-01-01'.PHP_EOL.'<script>xss();</script>';
   
   ?>


This analysis reports false positive when the regex is used to search a pattern in a much larger string. Check if this rule doesn't apply, though.

See also `CWE-625: Permissive Regular Expression <https://cwe.mitre.org/data/definitions/625.html>`_.

+-------------+----------------------+
| Short name  | Security/AnchorRegex |
+-------------+----------------------+
| Themes      | :ref:`Security`      |
+-------------+----------------------+
| Severity    | Major                |
+-------------+----------------------+
| Time To Fix | Instant (5 mins)     |
+-------------+----------------------+



.. _always-positive-comparison:

Always Positive Comparison
##########################


Some PHP native functions, such as `count() <http://www.php.net/count>`_, strlen(), or `abs() <http://www.php.net/abs>`_ only returns positive or null values. 

When comparing them to 0, the following expressions are always true and should be avoided. 

.. code-block:: php

   <?php
   
   $a = [1, 2, 3];
   
   var_dump(count($a) >= 0);
   var_dump(count($a) < 0); 
   
   ?>

+-------------+--------------------------+
| Short name  | Structures/NeverNegative |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Major                    |
+-------------+--------------------------+
| Time To Fix | Instant (5 mins)         |
+-------------+--------------------------+



.. _ambiguous-array-index:

Ambiguous Array Index
#####################


Those indexes are defined with different types, in the same array. 

Array indices only accept integers and strings, so any other type of literal is reported. 

.. code-block:: php

   <?php
   
   $x = [ 1  => 1,
         '1' => 2,
         1.0 => 3,
         true => 4];
   // $x only contains one element : 1 => 4
   
   // Still wrong, immediate typecast to 1
   $x[1.0]  = 5; 
   $x[true] = 6; 
   
   ?>


They are indeed distinct, but may lead to confusion. 


Suggestions
^^^^^^^^^^^

* Only use string or integer as key for an array.

+-------------+----------------------+
| Short name  | Arrays/AmbiguousKeys |
+-------------+----------------------+
| Themes      | :ref:`Analyze`       |
+-------------+----------------------+
| Severity    | Minor                |
+-------------+----------------------+
| Time To Fix | Quick (30 mins)      |
+-------------+----------------------+



.. _ambiguous-static:

Ambiguous Static
################


Methods or properties with the same name, are defined static in one class, and not static in another. This is error prone, as it requires a good knowledge of the code to make it static or not. 

Try to keep the methods simple and unique. Consider renaming the methods and properties to distinguish them easily. A method and a static method have probably different responsibilities.

.. code-block:: php

   <?php
   
   class a {
       function mixedStaticMethod() {}
   }
   
   class b {
       static function mixedStaticMethod() {}
   }
   
   /... a lot more code later .../
   
   $c->mixedStaticMethod();
   // or 
   $c::mixedStaticMethod();
   
   ?>

+-------------+-------------------------+
| Short name  | Classes/AmbiguousStatic |
+-------------+-------------------------+
| Themes      | :ref:`Analyze`          |
+-------------+-------------------------+
| Severity    | Minor                   |
+-------------+-------------------------+
| Time To Fix | Slow (1 hour)           |
+-------------+-------------------------+



.. _ambiguous-visibilities:

Ambiguous Visibilities
######################


The properties have the same name, but have different visibilities, across different classes. 

While it is legit to have a property with the same name in different classes, it may easily lead to confusion. As soon as the context is need to understand if the property is accessible or not, the readability suffers.

It is recommended to handle the same properties in the same way across classes, even when the classes are not related. 

.. code-block:: php

   <?php
   
   class person {
       public $name;
       private $address;
   }
   
   class gangster {
       private $name;
       public $nickname;
       private $address;
   }
   
   $someone = Human::load(123);
   echo 'Hello, '.$someone->name;
   
   ?>

+-------------+-------------------------------+
| Short name  | Classes/AmbiguousVisibilities |
+-------------+-------------------------------+
| Themes      | :ref:`Analyze`                |
+-------------+-------------------------------+
| Severity    | Minor                         |
+-------------+-------------------------------+
| Time To Fix | Slow (1 hour)                 |
+-------------+-------------------------------+



.. _anonymous-classes:

Anonymous Classes
#################


Anonymous classes.

.. code-block:: php

   <?php
   
   // Anonymous class, available since PHP 7.0
   $object = new class { function __construct() { echo __METHOD__; } };
   
   ?>

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Classes/Anonymous                                                                                          |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and more recent                                                                               |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                              |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _argument-should-be-typehinted:

Argument Should Be Typehinted
#############################


When a method expects objects as argument, those arguments should be typehinted. This way, it provides early warning that a wrong object is being sent to the method.

The analyzer will detect situations where a class, or the keywords 'array' or 'callable'. 

.. code-block:: php

   <?php
   
   // What are the possible classes that have a 'foo' method? 
   function foo($bar) {
       return $bar->foo();
   }
   
   ?>


`Closure <http://php.net/manual/en/class.closure.php>`_ arguments are omitted.

See also `Type declarations <http://php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration>`_.

+-------------+-----------------------------------------------------------------------------------------------+
| Short name  | Functions/ShouldBeTypehinted                                                                  |
+-------------+-----------------------------------------------------------------------------------------------+
| Themes      | :ref:`Suggestions`                                                                            |
+-------------+-----------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                         |
+-------------+-----------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                 |
+-------------+-----------------------------------------------------------------------------------------------+
| ClearPHP    | `always-typehint <https://github.com/dseguy/clearPHP/tree/master/rules/always-typehint.md>`__ |
+-------------+-----------------------------------------------------------------------------------------------+



.. _assert-function-is-reserved:

Assert Function Is Reserved
###########################


Avoid defining an ``assert`` function in namespaces. 

While they work fine when the assertions are active (``zend.assertions=1``), calls to unqualified ``assert`` are optimized away when assertions are not active. 

Since PHP 7.3, a fatal error is emitted : ``Defining a custom `assert() <http://www.php.net/assert>`_ function is deprecated, as the function has special semantics``.

.. code-block:: php

   <?php
   //      Run this with zend.assertions=1 and 
   // Then run this with zend.assertions=0
   
   namespace Test {
       function assert() {
           global $foo;
   
           $foo = true;
       }
   }
   
   namespace Test {
       assert();
   
       var_dump(isset($foo));
   }
   
   ?>


See also `assert <http://php.net/assert>`_ and 
         `User-defined assert function is optimized away with zend.assertions=-1 <https://bugs.php.net/bug.php?id=75445>`_.

+-------------+-------------------------------------------+
| Short name  | Php/AssertFunctionIsReserved              |
+-------------+-------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`CompatibilityPHP73` |
+-------------+-------------------------------------------+
| Severity    | Critical                                  |
+-------------+-------------------------------------------+
| Time To Fix | Slow (1 hour)                             |
+-------------+-------------------------------------------+



.. _assign-default-to-properties:

Assign Default To Properties
############################


Properties may be assigned default values at declaration time. Such values may be later modified, if needed. 

.. code-block:: php

   <?php
   
   class foo {
       private $propertyWithDefault = 1;
       private $propertyWithoutDefault;
       private $propertyThatCantHaveDefault;
       
       public function __construct() {
           // Skip this extra line, and give the default value above
           $this->propertyWithoutDefault = 1;
   
           // Static expressions are available to set up simple computation at definition time.
           $this->propertyWithoutDefault = OtherClass::CONSTANT + 1;
   
           // Arrays, just like scalars, may be set at definition time
           $this->propertyWithoutDefault = [1,2,3];
   
           // Objects or resources can't be made default. That is OK.
           $this->propertyThatCantHaveDefault = fopen('/path/to/file.txt');
           $this->propertyThatCantHaveDefault = new Fileinfo();
       }
   }
   
   ?>


Default values will save some instructions in the constructor, and makes the value obvious in the code.

+-------------+---------------------------------------------------------------------------------------------------------------------------+
| Short name  | Classes/MakeDefault                                                                                                       |
+-------------+---------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                                            |
+-------------+---------------------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                                     |
+-------------+---------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                                          |
+-------------+---------------------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `use-properties-default-values <https://github.com/dseguy/clearPHP/tree/master/rules/use-properties-default-values.md>`__ |
+-------------+---------------------------------------------------------------------------------------------------------------------------+



.. _assign-with-and:

Assign With And
###############


The lettered logical operators yield to assignation. It may collect less information than expected.

It is recommended to use the &&, ^ and || operators, instead of and, or and xor, to prevent confusion.

.. code-block:: php

   <?php
   
   // The expected behavior is 
   // The following are equivalent
    $a =  $b  && $c;
    $a = ($b && $c);
   
   // The unexpected behavior is 
   // The following are equivalent
    $a = $b  and $c;
   ($a = $b) and $c;
   
   ?>


See also `Operator precedence <http://php.net/manual/en/language.operators.precedence.php>`_.

+-------------+-------------------------------+
| Short name  | Php/AssignAnd                 |
+-------------+-------------------------------+
| Themes      | :ref:`Analyze`                |
+-------------+-------------------------------+
| Severity    | Critical                      |
+-------------+-------------------------------+
| Time To Fix | Quick (30 mins)               |
+-------------+-------------------------------+
| Examples    | :ref:`xataface-php-assignand` |
+-------------+-------------------------------+



.. _assigned-twice:

Assigned Twice
##############


The same variable is assigned twice in the same function.

While this is possible and quite common, it is also a good practice to avoid changing a value from one literal to another. It is far better to assign the new value to 

Incremental changes to a variables are not reported here.

.. code-block:: php

   <?php
   
   function foo() {
       // incremental changes of $a;
       $a = 'a';
       $a++;
       $a = uppercase($a);
       
       $b = 1;
       $c = bar($b);
       // B changed its purpose. Why not call it $d? 
       $b = array(1,2,3);
       
       // This is some forgotten debug
       $e = $config->getSomeList();
       $e = array('OneElement');
   }
   
   ?>

+-------------+-------------------------------+
| Short name  | Variables/AssignedTwiceOrMore |
+-------------+-------------------------------+
| Themes      | :ref:`Analyze`                |
+-------------+-------------------------------+
| Severity    | Minor                         |
+-------------+-------------------------------+
| Time To Fix | Quick (30 mins)               |
+-------------+-------------------------------+



.. _avoid-concat-in-loop:

Avoid Concat In Loop
####################


Concatenations inside a loop generate a lot of temporary variables. They are accumulated and tend to raise the memory usage, leading to slower performances.

It is recommended to store the values in an array, and then use `implode() <http://www.php.net/implode>`_ on that array to make the concatenation at once. The effect is positive when the source array has at least 50 elements. 

.. code-block:: php

   <?php
   
   // Concatenation in one operation
   $tmp = array();
   foreach(data_source() as $data) {
       $tmp[] = $data;
   }
   $final = implode('', $tmp);
   
   // Concatenation in many operations
   foreach(data_source() as $data) {
       $final .= $data;
   }
   
   ?>


The same doesn't apply to addition and multiplication, with `array_sum() <http://www.php.net/array_sum>`_ and array_multiply(), as those operations work on the current memory allocation, and don't need to allocate new memory at each step. 

See also `PHP 7 performance improvements (3/5): Encapsed strings optimization <https://blog.blackfire.io/php-7-performance-improvements-encapsed-strings-optimization.html>`_.


Suggestions
^^^^^^^^^^^

* Collect all pieces in an array, then implode() the array in one call.

+-------------+------------------------------------------------------------------------------------------+
| Short name  | Performances/NoConcatInLoop                                                              |
+-------------+------------------------------------------------------------------------------------------+
| Themes      | :ref:`Performances`                                                                      |
+-------------+------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                    |
+-------------+------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                            |
+-------------+------------------------------------------------------------------------------------------+
| Examples    | :ref:`suitecrm-performances-noconcatinloop`, :ref:`thinkphp-performances-noconcatinloop` |
+-------------+------------------------------------------------------------------------------------------+



.. _avoid-large-array-assignation:

Avoid Large Array Assignation
#############################


Avoid setting large arrays to local variables. This is done every time the function is called.

There are different ways to avoid this : inject the array, build the array once. Using an constant or even a global variable is faster.

The effect on small arrays (less than 10 elements) is not significant. Arrays with 10 elements or more are reported here. The effect is also more important on functions that are called often, or within loops.

.. code-block:: php

   <?php
   
   // with constants, for functions
   const ARRAY = array(1,2,3,4,5,6,7,8,9,10,11);
   function foo() {
       $array = ARRAY;
       //more code
   }
   
   // with class constants, for methods 
   class x {
       const ARRAY = array(1,2,3,4,5,6,7,8,9,10,11);
       function foo() {
           $array = self::ARRAY;
           //more code
       }
   }
   
   // with properties, for methods 
   class x {
       private $array = array(1,2,3,4,5,6,7,8,9,10,11);
       
       function foo() {
           $array = $this->array;
           //more code
       }
   }
   
   // injection, leveraging default values
   function foo($array = array(1,2,3,4,5,6,7,8,9,10,11)) {
       //more code
   }
   
   // local cache with static
   function foo() {
       static $array;
       if ($array === null) {
           $array = array(1,2,3,4,5,6,7,8,9,10,11);
       }
       
       //more code
   }
   
   // Avoid creating the same array all the time in a function
   class x {
       function foo() {
           // assign to non local variable is OK. 
           // Here, to a property, though it may be better in a __construct or as default values
           $this->s = array(1,2,3,4,5,6,7,8,9,10,11);
   
           // This is wasting resources, as it is done each time. 
           $array = array(1,2,3,4,5,6,7,8,9,10,11);
       }
   }
   
   ?>

+-------------+------------------------------------+
| Short name  | Structures/NoAssignationInFunction |
+-------------+------------------------------------+
| Themes      | :ref:`Performances`                |
+-------------+------------------------------------+
| Severity    | Minor                              |
+-------------+------------------------------------+
| Time To Fix | Slow (1 hour)                      |
+-------------+------------------------------------+



.. _avoid-optional-properties:

Avoid Optional Properties
#########################


Avoid optional properties, to prevent littering the code with existence checks. 

When a property has to be checked once for existence, it is safer to check it each time. This leads to a decrease in readability.

Either make sure the property is set with an actual object rather than with null, or use a void object. A void object offers the same interface than the expected object, but does nothing. It allows calling its methods, without running into a Fatal error, nor testing it. 

.. code-block:: php

   <?php
   
   // Example is courtesy 'The Coding Machine' : it has been adapted from its original form. See link below.
   
   class MyMailer {
       private $logger;
   
       public function __construct(LoggerInterface $logger = null) {
           $this->logger = $logger;
       }
   
       private function sendMail(Mail $mail) {
           // Since $this->logger may be null, it must be tested anytime it is used.
           if ($this->logger) {
               $this->logger->info('Mail successfully sent.');
           }
       }
   }
   
   ?>


See also `Avoid optional services as much as possible <http://bestpractices.thecodingmachine.com/php/design_beautiful_classes_and_methods.html#avoid-optional-services-as-much-as-possible>`_,
         `The Null Object Pattern – Polymorphism in Domain Models <https://www.sitepoint.com/the-null-object-pattern-polymorphism-in-domain-models/>`_, and 
         `Practical PHP Refactoring: Introduce Null Object <https://dzone.com/articles/practical-php-refactoring-26>`_.

+-------------+---------------------------------+
| Short name  | Classes/AvoidOptionalProperties |
+-------------+---------------------------------+
| Themes      | :ref:`Analyze`                  |
+-------------+---------------------------------+
| Severity    | Major                           |
+-------------+---------------------------------+
| Time To Fix | Slow (1 hour)                   |
+-------------+---------------------------------+



.. _avoid-parenthesis:

Avoid Parenthesis
#################


Avoid Parenthesis for language construct. Languages constructs are a few PHP native elements, that looks like functions but are not. 

Among other distinction, those elements cannot be directly used as variable function call, and they may be used with or without parenthesis.

.. code-block:: php

   <?php
   
   // normal usage of include
   include 'file.php';
   
   // This looks like a function and is not
   include('file2.php');
   
   ?>


The usage of parenthesis actually give some feeling of comfort, it won't prevent PHP from combining those argument with any later operators, leading to unexpected results.

Even if most of the time, usage of parenthesis is legit, it is recommended to avoid them.

+-------------+------------------------------------+
| Short name  | Structures/PrintWithoutParenthesis |
+-------------+------------------------------------+
| Themes      | :ref:`Analyze`                     |
+-------------+------------------------------------+
| Severity    | Minor                              |
+-------------+------------------------------------+
| Time To Fix | Quick (30 mins)                    |
+-------------+------------------------------------+



.. _avoid-real:

Avoid Real
##########


PHP has two float data type : real and double. ``real`` is rarely used, and might be deprecated in PHP 7.4.

To prepare code, avoid using `is_real() <http://www.php.net/is_real>`_ and the ``(real)`` typecast.

.. code-block:: php

   <?php
   
   // safe way to check for float
   if (!is_float($a)) {
       $a = (float) $a;
   }
   
   // Avoid doing that
   if (!is_real($a)) {
       $a = (real) $a;
   }
   
   ?>


See also `PHP RFC: Deprecations for PHP 7.4 <https://wiki.php.net/rfc/deprecations_php_7_4>`_.



Suggestions
^^^^^^^^^^^

* Replace is_real() by is_float()
* Replace (real) by (float)

+-------------+--------------------+
| Short name  | Php/AvoidReal      |
+-------------+--------------------+
| Themes      | :ref:`Suggestions` |
+-------------+--------------------+
| Severity    | Minor              |
+-------------+--------------------+
| Time To Fix | Quick (30 mins)    |
+-------------+--------------------+



.. _avoid-self-in-interface:

Avoid Self In Interface
#######################


Self and Parent are tricky when used in an interface. 

``self`` refers to the current interface or its extended parents : as long as the constant is defined in the interface family, this is valid.  On the other hand, when ``self`` refers to the current class, the resolution of names will happen at execution time, leading to confusing results. 

``parent`` has the same behavior than ``self``, except that it doesn't accept to be used inside an interface, as it will yield an error. This is one of those error that lint but won't execute in certain conditions.

``Static`` can't be used in an interface, as it needs to be resolved at call time anyway.

.. code-block:: php

   <?php
   
   interface i extends ii {
       // This 'self' is valid : it refers to the interface i
       public const I = self::I2 + 2;
   
       // This 'self' is also valid, as it refers to interface ii, which is a part of interface i
       public const I2 = self::IP + 4; 
   
       // This makes interface i dependant on the host class
       public const I3 = parent::A;
   }
   
   ?>


See also `Scope Resolution Operator (\:\:) ¶ <http://php.net/manual/en/language.oop5.paamayim-nekudotayim.php>`_.


Suggestions
^^^^^^^^^^^

* Use a fully qualified namespace instead of self
* Use a locally defined constant, so self is a valid reference

+-------------+---------------------------------+
| Short name  | Interfaces/AvoidSelfInInterface |
+-------------+---------------------------------+
| Themes      | :ref:`ClassReview`              |
+-------------+---------------------------------+
| Severity    | Critical                        |
+-------------+---------------------------------+
| Time To Fix | Slow (1 hour)                   |
+-------------+---------------------------------+



.. _avoid-those-hash-functions:

Avoid Those Hash Functions
##########################


The following cryptographic algorithms are considered unsecure, and should be replaced with new and more performant algorithms. 

``MD2``, ``MD4``, ``MD5``, ``SHA0``, ``SHA1``, ``CRC``, ``DES``, ``3DES``, ``RC2``, ``RC4``. 

When possible, avoid using them, may it be as PHP functions, or hashing function configurations (mcrypt, hash...).

.. code-block:: php

   <?php
   
   // Weak cryptographic algorithm
   echo md5('The quick brown fox jumped over the lazy dog.');
   
   // Weak crypotgraphic algorthim, used with a modern PHP extension (easier to update)
   echo hash('md5', 'The quick brown fox jumped over the lazy dog.');
   
   // Strong crypotgraphic algorthim, used with a modern PHP extension
   echo hash('sha156', 'The quick brown fox jumped over the lazy dog.');
   
   ?>


Weak cryptography is commonly used for hashing values when caching them. In such cases, security is not a primary concern. However, it may later become such, when hackers get access to the cache folders, or if the cached identifier is published. As a preventive protection, it is recommended to always use a secure hashing function.

See also `Secure Hash Algorithms <https://en.wikipedia.org/wiki/Secure_Hash_Algorithms>`_.

+-------------+---------------------------+
| Short name  | Security/AvoidThoseCrypto |
+-------------+---------------------------+
| Themes      | :ref:`Security`           |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _avoid-using-stdclass:

Avoid Using stdClass
####################


``stdClass`` is the default class for PHP. It is instantiated when PHP needs to return a object, but no class is specifically available.

It is recommended to avoid instantiating this class, nor use it is any way.

.. code-block:: php

   <?php
   
   $json = '{a:1,b:2,c:3}';
   $object = json_decode($json);
   // $object is a stdClass, as returned by json_decode
   
   // Fast building of $o
   $a = [];
   $a['a'] = 1;
   $a['b'] = 2;
   $a['c'] = 3;
   json_encode( (object) $a);
   
   // Slow building of $o
   $o = new stdClass();
   $o->a = 1;
   $o->b = 2;
   $o->c = 3;
   json_encode($o);
   
   ?>


If you need a ``stdClass`` object, it is faster to build it as an array, then cast it, than instantiate ``stdClass``. This is a micro-optimisation.

+-------------+-----------------+
| Short name  | Php/UseStdclass |
+-------------+-----------------+
| Themes      | :ref:`Analyze`  |
+-------------+-----------------+
| Severity    | Minor           |
+-------------+-----------------+
| Time To Fix | Slow (1 hour)   |
+-------------+-----------------+



.. _avoid-array\_push():

Avoid array_push()
##################


`array_push() <http://www.php.net/array_push>`_ is slower than the [] operator.

This is also true if the [] operator is called several times, while `array_push() <http://www.php.net/array_push>`_ may be called only once. 
And using count after the push is also faster than collecting `array_push() <http://www.php.net/array_push>`_ return value. 

.. code-block:: php

   <?php
   
   $a = [1,2,3];
   // Fast version
   $a[] = 4;
   
   $a[] = 5;
   $a[] = 6;
   $a[] = 7;
   $count = count($a);
   
   // Slow version
   array_push($a, 4);
   $count = array_push($a, 5,6,7);
   
   // Multiple version : 
   $a[] = 1;
   $a[] = 2;
   $a[] = 3;
   array_push($a, 1, 2, 3);
   
   
   ?>


This is a micro-optimisation.

+-------------+-----------------------------+
| Short name  | Performances/AvoidArrayPush |
+-------------+-----------------------------+
| Themes      | :ref:`Performances`         |
+-------------+-----------------------------+
| Severity    | Minor                       |
+-------------+-----------------------------+
| Time To Fix | Instant (5 mins)            |
+-------------+-----------------------------+



.. _avoid-array\_unique():

Avoid array_unique()
####################


The native function `array_unique() <http://www.php.net/array_unique>`_ is much slower than using other alternative, such as `array_count_values() <http://www.php.net/array_count_values>`_, `array_flip() <http://www.php.net/array_flip>`_/`array_keys() <http://www.php.net/array_keys>`_, or even a `foreach() <http://php.net/manual/en/control-structures.foreach.php>`_ loops. 

.. code-block:: php

   <?php
   
   // using array_unique()
   $uniques = array_unique($someValues);
   
   // When values are strings or integers
   $uniques = array_keys(array_count_values($someValues));
   $uniques = array_flip(array_flip($someValues))
   
   //even some loops are faster.
   $uniques = [];
   foreach($someValues as $s) {
       if (!in_array($uniques, $s)) {
           $uniques[] $s;
       }
   }
   
   ?>

+-------------+--------------------------+
| Short name  | Structures/NoArrayUnique |
+-------------+--------------------------+
| Themes      | :ref:`Performances`      |
+-------------+--------------------------+
| Severity    | Minor                    |
+-------------+--------------------------+
| Time To Fix | Quick (30 mins)          |
+-------------+--------------------------+



.. _avoid-get\_class():

Avoid get_class()
#################


``get_class()`` should be replaced with the ``instanceof`` operator to check the class of an object. 

``get_class()`` only compares the full namespace name of the object's class, while ``instanceof`` actually resolves the name, using the local namespace and aliases.

.. code-block:: php

   <?php
   
       use Stdclass as baseClass;
       
       function foo($arg) {
           // Slow and prone to namespace errors
           if (get_class($arg) === 'Stdclass') {
               // doSomething()
           }
       }
   
       function bar($arg) {
           // Faster, and uses aliases.
           if ($arg instanceof baseClass) {
               // doSomething()
           }
       }
   ?>


See also `get_class <http://php.net/get_class>`_ and 
         `Type Operators <http://php.net/`instanceof <http://php.net/manual/en/language.operators.type.php>`_>`_.

+-------------+--------------------------------+
| Short name  | Structures/UseInstanceof       |
+-------------+--------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Analyze` |
+-------------+--------------------------------+
| Severity    | Minor                          |
+-------------+--------------------------------+
| Time To Fix | Quick (30 mins)                |
+-------------+--------------------------------+



.. _avoid-glob()-usage:

Avoid glob() Usage
##################


`glob() <http://www.php.net/glob>`_ and `scandir() <http://www.php.net/scandir>`_ sorts results by default. When that kind of sorting is not needed, save some time by requesting ``NOSORT`` with those functions.

Besides, whenever possible, use `scandir() <http://www.php.net/scandir>`_ instead of `glob() <http://www.php.net/glob>`_. 

.. code-block:: php

   <?php
   
   // Scandir without sorting is the fastest. 
   scandir('docs/', SCANDIR_SORT_NONE);
   
   // Scandir sorts files by default. Same as above, but with sorting
   scandir('docs/');
   
   // glob sorts files by default. Same as below, but no sorting
   glob('docs/*', GLOB_NOSORT);
   
   // glob sorts files by default. This is the slowest version
   glob('docs/*');
   
   ?>


Using `opendir() <http://www.php.net/opendir>`_ and a while loop may be even faster. 

This analysis skips `scandir() <http://www.php.net/scandir>`_ and `glob() <http://www.php.net/glob>`_ if they are explicitely configured with flags (aka, sorting is explicitely needed).

`glob() <http://www.php.net/glob>`_ accepts wildchar, such as ``*``, that may not easily replaced with `scandir() <http://www.php.net/scandir>`_ or `opendir() <http://www.php.net/opendir>`_.

See also `Putting glob to the test <https://www.phparch.com/2010/04/putting-glob-to-the-test/>`_.



Suggestions
^^^^^^^^^^^

* Use FilesystemIterator, DirectoryIterator classes.
* Use RegexIterator to filter any unwanted results from FilesystemIterator.

+-------------+------------------------------------------------------------------------+
| Short name  | Performances/NoGlob                                                    |
+-------------+------------------------------------------------------------------------+
| Themes      | :ref:`Performances`                                                    |
+-------------+------------------------------------------------------------------------+
| Severity    | Major                                                                  |
+-------------+------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                        |
+-------------+------------------------------------------------------------------------+
| Examples    | :ref:`phinx-performances-noglob`, :ref:`nextcloud-performances-noglob` |
+-------------+------------------------------------------------------------------------+



.. _avoid-set\_error\_handler-$context-argument:

Avoid set_error_handler $context Argument
#########################################


Avoid configuring `set_error_handler() <http://www.php.net/set_error_handler>`_ with a method that accepts 5 arguments. The last argument, $errcontext, is deprecated since PHP 7.2, and will be removed later.

.. code-block:: php

   <?php
   
   // setting error_handler with an incorrect closure
   set_error_handler(function($errno, $errstr, $errfile, $errline) {});
   
   // setting error_handler with an incorrect closure
   set_error_handler(function($errno, $errstr, $errfile, $errline, $errcontext) {});
   
   ?>


See also `set_error_handler() <http://www.php.net/set_error_handler>`_;

+-------------+------------------------------------+
| Short name  | Php/AvoidSetErrorHandlerContextArg |
+-------------+------------------------------------+
| Themes      | :ref:`CompatibilityPHP72`          |
+-------------+------------------------------------+
| Severity    | Major                              |
+-------------+------------------------------------+
| Time To Fix | Slow (1 hour)                      |
+-------------+------------------------------------+



.. _avoid-sleep()/usleep():

Avoid sleep()/usleep()
######################


`sleep() <http://www.php.net/sleep>`_ and `usleep() <http://www.php.net/usleep>`_ help saturate the web server. 

Pausing the script for a specific amount of time means that the Web server is also making all related resources sleep, such as database, sockets, session, etc. This may used to set up a DOS on the server.  

.. code-block:: php

   <?php
   
   $begin = microtime(true);
   checkLogin($user, $password);
   $end   = microtime(true);
   
   // Making all login checks looks the same
   usleep(1000000 - ($end - $begin) * 1000000); 
   
   // Any hit on this page now uses 1 second, no matter if load is high or not
   // Is it now possible to saturate the webserver in 1 s ? 
   
   ?>


As much as possible, avoid delaying the end of the script. 

`sleep() <http://www.php.net/sleep>`_ and `usleep() <http://www.php.net/usleep>`_ have less impact in commandline (``CLI``).

+-------------+------------------+
| Short name  | Security/NoSleep |
+-------------+------------------+
| Themes      | :ref:`Security`  |
+-------------+------------------+
| Severity    | Minor            |
+-------------+------------------+
| Time To Fix | Quick (30 mins)  |
+-------------+------------------+



.. _bad-constants-names:

Bad Constants Names
###################


PHP's manual recommends that developer do not use constants with the convention ``__NAME__``. Those are reserved for PHP future use. 

For example, ``__TRAIT__`` recently appeared in PHP, as a magic constant. In the future, other may appear. 

.. code-block:: php

   <?php
   
   const __MY_APP_CONST__ = 1;
   
   const __MY_APP_CONST__ = 1;
   
   define('__MY_OTHER_APP_CONST__', 2);
   
   ?>


The analyzer will report any constant which name is ``__.*.__``, or even ``_.*_`` (only one underscore).

See also `Constants <http://php.net/manual/en/language.constants.php>`_.


Suggestions
^^^^^^^^^^^

* Avoid using names that doesn't comply with PHP's convention

+-------------+-----------------------------------------------------------------------------------------+
| Short name  | Constants/BadConstantnames                                                              |
+-------------+-----------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                          |
+-------------+-----------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                   |
+-------------+-----------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                           |
+-------------+-----------------------------------------------------------------------------------------+
| Examples    | :ref:`prestashop-constants-badconstantnames`, :ref:`zencart-constants-badconstantnames` |
+-------------+-----------------------------------------------------------------------------------------+



.. _bail-out-early:

Bail Out Early
##############


When using conditions, it is recommended to quit in the current context, and avoid else clause altogether. 

The main benefit is to make clear the method applies a condition, and stop immediately when it is not satisfied. 
The main sequence is then focused on the actual code. 

This works with the ``break``, ``continue``, ``throw`` and ``goto`` keywords too, depending on situations.

.. code-block:: php

   <?php
   
   // Bailing out early, low level of indentation
   function foo1($a) {
       if ($a > 0) {
           return false;
       } 
       
       $a++;
       return $a;
   }
   
   // Works with continue too
   foreach($array as $a => $b) {
       if ($a > 0) {
           continue false;
       } 
       
       $a++;
       return $a;
   }
   
   // No need for else
   function foo2($a) {
       if ($a > 0) {
           return false;
       } else {
           $a++;
       }
       
       return $a;
   }
   
   // No need for else : return goes into then. 
   function foo3($a) {
       if ($a < 0) {
           $a++;
       } else {
           return false;
       }
       
       return $a;
   }
   
   // Make a return early, and make the condition visible.
   function foo3($a) {
       if ($a < 0) {
           $a++;
           methodcall();
           functioncall();
       } 
   }
   
   ?>


See also `Avoid nesting too deeply and return early (part 1) <https://github.com/jupeter/clean-code-php#avoid-nesting-too-deeply-and-return-early-part-1>`_ and 
         `Avoid nesting too deeply and return early (part 2) <https://github.com/jupeter/clean-code-php#avoid-nesting-too-deeply-and-return-early-part-2>`_.



Suggestions
^^^^^^^^^^^

* Detect errors, and then, return as soon as possible.
* When a if...then branches are unbalanced, test for the small branch, finish it with return. Then keep the other branch as the main code.

+-------------+--------------------------------------------------------------------------------+
| Short name  | Structures/BailOutEarly                                                        |
+-------------+--------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                 |
+-------------+--------------------------------------------------------------------------------+
| Severity    | Minor                                                                          |
+-------------+--------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                |
+-------------+--------------------------------------------------------------------------------+
| Examples    | :ref:`openemr-structures-bailoutearly`, :ref:`opencfp-structures-bailoutearly` |
+-------------+--------------------------------------------------------------------------------+



.. _binary-glossary:

Binary Glossary
###############


List of all the integer values using the binary format.

.. code-block:: php

   <?php
   
   $a = 0b10;
   $b = 0B0101;
   
   ?>

+-------------+------------------------------+
| Short name  | Type/Binary                  |
+-------------+------------------------------+
| Themes      | :ref:`CompatibilityPHP53`    |
+-------------+------------------------------+
| Php Version | With PHP 5.4 and more recent |
+-------------+------------------------------+
| Severity    | Major                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _bracketless-blocks:

Bracketless Blocks
##################


PHP allows one liners as `for() <http://php.net/manual/en/control-structures.for.php>`_, `foreach() <http://php.net/manual/en/control-structures.foreach.php>`_, `while() <http://php.net/manual/en/control-structures.while.php>`_, do/`while() <http://php.net/manual/en/control-structures.while.php>`_ loops, or as then/else expressions. 

It is generally considered a bad practice, as readability is lower and there are non-negligible risk of excluding from the loop the next instruction.

.. code-block:: php

   <?php
   
   // Legit one liner
   foreach(range('a', 'z') as $letter) ++$letterCount;
   
   // More readable version, even for a one liner.
   foreach(range('a', 'z') as $letter) {
       ++$letterCount;
   }
   
   ?>


`switch() <http://php.net/manual/en/control-structures.switch.php>`_ cannot be without bracket.

+-------------+------------------------------------------------+
| Short name  | Structures/Bracketless                         |
+-------------+------------------------------------------------+
| Themes      | :ref:`Coding Conventions <coding-conventions>` |
+-------------+------------------------------------------------+
| Severity    | Minor                                          |
+-------------+------------------------------------------------+
| Time To Fix | Instant (5 mins)                               |
+-------------+------------------------------------------------+



.. _break-outside-loop:

Break Outside Loop
##################


Starting with PHP 7, `break <http://php.net/manual/en/control-structures.break.php>`_ or `continue <http://php.net/manual/en/control-structures.continue.php>`_ that are outside a loop (for, `foreach() <http://php.net/manual/en/control-structures.foreach.php>`_, do...`while() <http://php.net/manual/en/control-structures.while.php>`_, `while()) <http://php.net/manual/en/control-structures.while.php>`_ or a `switch() <http://php.net/manual/en/control-structures.switch.php>`_ statement won't compile anymore.

It is not possible anymore to include a piece of code inside a loop that will then `break <http://php.net/manual/en/control-structures.break.php>`_.

.. code-block:: php

   <?php
   
       // outside a loop : This won't compile
       break 1; 
       
       foreach($array as $a) {
           break 1; // Compile OK
   
           break 2; // This won't compile, as this break is in one loop, and not 2
       }
   
       foreach($array as $a) {
           foreach($array2 as $a2) {
               break 2; // OK in PHP 5 and 7
           }
       }
   ?>

+-------------+-------------------------------------------+
| Short name  | Structures/BreakOutsideLoop               |
+-------------+-------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`CompatibilityPHP70` |
+-------------+-------------------------------------------+
| Php Version | With PHP 7.0 and older                    |
+-------------+-------------------------------------------+
| Severity    | Major                                     |
+-------------+-------------------------------------------+
| Time To Fix | Slow (1 hour)                             |
+-------------+-------------------------------------------+



.. _break-with-0:

Break With 0
############


Cannot `break <http://php.net/manual/en/control-structures.break.php>`_ 0, as this makes no sense. `Break <http://php.net/manual/en/control-structures.break.php>`_ 1 is the minimum, and is the default value.

.. code-block:: php

   <?php
       // Can't break 0. Must be 1 or more, depending on the level of nesting.
       for($i = 0; $i < 10; $i++) {
           break 0;
       }
   
       for($i = 0; $i < 10; $i++) {
           for($j = 0; $j < 10; $j++) {
               break 2;
           }
       }
   
   ?>

+-------------+---------------------------+
| Short name  | Structures/Break0         |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP53` |
+-------------+---------------------------+
| Php Version | With PHP 5.4 and older    |
+-------------+---------------------------+
| Severity    | Minor                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _break-with-non-integer:

Break With Non Integer
######################


When using a `break <http://php.net/manual/en/control-structures.break.php>`_, the argument of the operator must be a positive non-null integer literal or be omitted.

Other values were acceptable in PHP 5.3 and previous version, but this is now reported as an error.

.. code-block:: php

   <?php
       // Can't break $a, even if it contains an integer.
       $a = 1;
       for($i = 0; $i < 10; $i++) {
           break $a;
       }
   
       // can't break on float
       for($i = 0; $i < 10; $i++) {
           for($j = 0; $j < 10; $j++) {
               break 2.2;
           }
       }
   
   ?>

+-------------+----------------------------+
| Short name  | Structures/BreakNonInteger |
+-------------+----------------------------+
| Themes      | :ref:`CompatibilityPHP54`  |
+-------------+----------------------------+
| Php Version | With PHP 5.4 and older     |
+-------------+----------------------------+
| Severity    | Minor                      |
+-------------+----------------------------+
| Time To Fix | Quick (30 mins)            |
+-------------+----------------------------+



.. _buried-assignation:

Buried Assignation
##################


Those assignations are buried in the code, and placed in unexpected situations. 

They are difficult to spot, and may be confusing. It is advised to place them in a more visible place.

.. code-block:: php

   <?php
   
   // $b may be assigned before processing $a
   $a = $c && ($b = 2);
   
   // legit syntax, but the double assignation is not obvious.
   for($i = 2, $j = 3; $j < 10; $j++) {
       
   }
   ?>




Suggestions
^^^^^^^^^^^

* Extract the assignation and set it on its own line, prior to the current expression.
* Check if the local variable is necessary

+-------------+---------------------------------------------------------------------------------------+
| Short name  | Structures/BuriedAssignation                                                          |
+-------------+---------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                        |
+-------------+---------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                 |
+-------------+---------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                         |
+-------------+---------------------------------------------------------------------------------------+
| Examples    | :ref:`xoops-structures-buriedassignation`, :ref:`mautic-structures-buriedassignation` |
+-------------+---------------------------------------------------------------------------------------+



.. _cache-variable-outside-loop:

Cache Variable Outside Loop
###########################


Avoid recalculating constant values inside the loop.

Do the calculation once, outside the loops, and then reuse the value each time. 

One of the classic example if doing ``count($array)`` in a ``for`` loop : since the source is constant during the loop, the result of `count() <http://www.php.net/count>`_ is always the same. 

.. code-block:: php

   <?php
   
   $path = '/some/path';
   $fullpath = realpath("$path/more/dirs/");
   foreach($files as $file) {
       // Only moving parts are used in the loop
       copy($file, $fullpath.$file);
   }
   
   $path = '/some/path';
   foreach($files as $file) {
       // $fullpath is calculated each loop
       $fullpath = realpath("$path/more/dirs/");
       copy($file, $fullpath.$file);
   }
   
   ?>


Depending on the load of the called method, this may increase the speed of the loop from little to enormously.

+------------+---------------------------------------+
| Short name | Performances/CacheVariableOutsideLoop |
+------------+---------------------------------------+
| Themes     | :ref:`Performances`                   |
+------------+---------------------------------------+



.. _callback-needs-return:

Callback Needs Return
#####################


When used with `array_map() <http://www.php.net/array_map>`_ functions, the callback must return something. This return may be in the form of a ``return`` statement, a global variable or a parameter with a reference. All those solutions extract information from the callback. 

.. code-block:: php

   <?php
   
   // This filters each element
   $filtered = array_filter($array, function ($x) {return $x == 2; });
   
   // This return void for every element
   $filtered = array_filter($array, function ($x) {return ; });
   
   // costly array_sum()
   $sum = 0;
   $filtered = array_filter($array, function ($x) use (&$sum) {$sum += $x; });
   
   // costly array_sum()
   global $sum = 0;
   $filtered = array_filter($array, function () {global $sum; $sum += $x; });
   
   ?>


See also `array_map <http://php.net/array_map>`_.

+-------------+-------------------------------+
| Short name  | Functions/CallbackNeedsReturn |
+-------------+-------------------------------+
| Themes      | :ref:`Analyze`                |
+-------------+-------------------------------+
| Severity    | Major                         |
+-------------+-------------------------------+
| Time To Fix | Instant (5 mins)              |
+-------------+-------------------------------+



.. _calltime-pass-by-reference:

Calltime Pass By Reference
##########################


PHP doesn't allow when a value is turned into a reference at functioncall, since PHP 5.4. 

Either the function use a reference in its signature, either the reference won't pass.

.. code-block:: php

   <?php
   
   function foo($name) {
       $arg = ucfirst(strtolower($name));
       echo 'Hello '.$arg;
   }
   
   $a = 'name';
   foo(&$a);
   
   ?>

+-------------+------------------------------------+
| Short name  | Structures/CalltimePassByReference |
+-------------+------------------------------------+
| Themes      | :ref:`CompatibilityPHP54`          |
+-------------+------------------------------------+
| Php Version | With PHP 5.4 and older             |
+-------------+------------------------------------+
| Severity    | Minor                              |
+-------------+------------------------------------+
| Time To Fix | Quick (30 mins)                    |
+-------------+------------------------------------+



.. _can't-count-non-countable:

Can't Count Non-Countable
#########################


`Count() <http://www.php.net/count>`_ emits an error when it tries to count scalars or objects what don't implement Countable interface.

.. code-block:: php

   <?php
   
   // Normal usage
   $a = array(1,2,3,4);
   echo count($a).items\n;
   
   // Error emiting usage
   $a = '1234';
   echo count($a).chars\n;
   
   // Error emiting usage
   echo count($unsetVar).elements\n;
   
   ?>


See also `Warn when counting non-countable types <http://php.net/manual/en/migration72.incompatible.php#migration72.incompatible.warn-on-non-countable-types>`_.

+-------------+---------------------------------+
| Short name  | Structures/CanCountNonCountable |
+-------------+---------------------------------+
| Themes      | :ref:`CompatibilityPHP72`       |
+-------------+---------------------------------+
| Severity    | Major                           |
+-------------+---------------------------------+
| Time To Fix | Quick (30 mins)                 |
+-------------+---------------------------------+



.. _can't-disable-class:

Can't Disable Class
###################


This is the list of potentially dangerous PHP class being used in the code, such as \Phar. 

.. code-block:: php

   <?php
   
   // This script uses ftp_connect(), therefore, this function shouldn't be disabled. 
   $phar = new Phar();
   
   ?>


This analysis is the base for suggesting values for the ``disable_classes`` directive.

+-------------+---------------------------+
| Short name  | Security/CantDisableClass |
+-------------+---------------------------+
| Themes      | :ref:`Security`           |
+-------------+---------------------------+
| Severity    | Minor                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _can't-extend-final:

Can't Extend Final
##################


It is not possible to extend final classes. 

Since PHP fails with a fatal error, this means that the extending class is probably not used in the rest of the code. Check for dead code.

.. code-block:: php

   <?php
       // File Foo
       final class foo {
           public final function bar() {
               // doSomething
           }
       }
   ?>


In a separate file : 

.. code-block:: php

   <?php
       // File Bar
       class bar extends foo {
       
       }
   ?>

+-------------+----------------------------------------------+
| Short name  | Classes/CantExtendFinal                      |
+-------------+----------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Dead code <dead-code>` |
+-------------+----------------------------------------------+
| Severity    | Critical                                     |
+-------------+----------------------------------------------+
| Time To Fix | Instant (5 mins)                             |
+-------------+----------------------------------------------+



.. _can't-throw-throwable:

Can't Throw Throwable
#####################


Classes extending ``Throwable`` can't be thrown. The same applies to interfaces. 

Although this code lints, PHP throws a Fatal error when executing or including it : ``Class fooThrowable cannot implement interface `Throwable <http://php.net/manual/en/class.throwable.php>`_, extend Exception or Error instead``.

.. code-block:: php

   <?php
   
   // This is the way to go
   class fooException extends \Exception { }
   
   // This is not possible and a lot of work
   class fooThrowable implements \throwable { }
   
   ?>


See also `Throwable <http://php.net/manual/en/class.throwable.php>`_,
         `Exception <http://php.net/manual/en/class.exception.php>`_ and
         `Error <http://php.net/manual/en/class.error.php>`_.

+-------------+----------------------------------------+
| Short name  | Exceptions/CantThrow                   |
+-------------+----------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`LintButWontExec` |
+-------------+----------------------------------------+
| Severity    | Minor                                  |
+-------------+----------------------------------------+
| Time To Fix | Slow (1 hour)                          |
+-------------+----------------------------------------+



.. _cant-inherit-abstract-method:

Cant Inherit Abstract Method
############################


Inheriting abstract methods was made available in PHP 7.2. In previous versions, it emitted a fatal error.

.. code-block:: php

   <?php
   
   abstract class A           { abstract function bar(stdClass $x);  }
   abstract class B extends A { abstract function bar($x): stdClass; }
   
   //   Fatal error: Can't inherit abstract function A::bar()
   ?>


See also `PHP RFC: Allow abstract function override <https://wiki.php.net/rfc/allow-abstract-function-override>`_.

+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Classes/CantInheritAbstractMethod                                                                                                                                |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.2 and more recent                                                                                                                                     |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Critical                                                                                                                                                         |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                                                                  |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _cant-instantiate-class:

Cant Instantiate Class
######################


When constructor is not public, it is not possible to instantiate such a class. Either this is a conception choice, or there are factories to handle that. Either way, it is not possible to call new on such class. 

PHP reports an error similar to this one : 'Call to private Y\:\:`__construct() <http://php.net/manual/en/language.oop5.decon.php>`_ from invalid context'.

.. code-block:: php

   <?php
   
   //This is the way to go
   $x = X::factory();
   
   //This is not possible
   $x = new X();
   
   class X {
       //This is also the case with proctected __construct
       private function __construct() {}
   
       static public function factory() {
           return new X();
       }
   }
   
   ?>


See also `In a PHP5 class, when does a private constructor get called? <https://stackoverflow.com/questions/26079/in-a-php5-class-when-does-a-private-constructor-get-called>`_,
         `Named Constructors in PHP <http://verraes.net/2014/06/named-constructors-in-php/>`_ and 
         `PHP Constructor Best Practices And The Prototype Pattern <http://ralphschindler.com/2012/03/09/php-constructor-best-practices-and-the-prototype-pattern>`_.

+-------------+------------------------------+
| Short name  | Classes/CantInstantiateClass |
+-------------+------------------------------+
| Themes      | :ref:`Analyze`               |
+-------------+------------------------------+
| Severity    | Critical                     |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _cant-use-return-value-in-write-context:

Cant Use Return Value In Write Context
######################################


`empty() <http://www.php.net/empty>`_ used to work only on data containers, such as variables. Until PHP 5.5, it was not possible to use directly expressions, such as functioncalls, inside an `empty() <http://www.php.net/empty>`_ function call : they were met with a 'Can't use function return value in write context' fatal error. 

.. code-block:: php

   <?php
   
   function foo($boolean) {
       return $boolean;
   }
   
   // Valid since PHP 5.5
   echo empty(foo(true)) : 'true' : 'false';
   
   ?>


This also applies to methodcalls, static or not.

See also `Cant Use Return Value In Write Context <https://stackoverflow.com/questions/1075534/cant-use-method-return-value-in-write-context>`_.

+-------------+------------------------------------------------------+
| Short name  | Php/CantUseReturnValueInWriteContext                 |
+-------------+------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54` |
+-------------+------------------------------------------------------+
| Php Version | With PHP 5.5 and more recent                         |
+-------------+------------------------------------------------------+
| Severity    | Major                                                |
+-------------+------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                      |
+-------------+------------------------------------------------------+



.. _case-insensitive-constants:

Case Insensitive Constants
##########################


PHP constants may be case insensitive, when defined with `define() <http://www.php.net/define>`_ and the third argument.

This feature is deprecated since PHP 7.3 and will be removed in PHP 8.0.

.. code-block:: php

   <?php
   
   // case sensitive
   define('A', 1);
   
   // case insensitive
   define('B', 1, true);
   
   echo A;
   // This is not possible
   //echo a;
   
   // both possible
   echo B;
   echo b;
   
   ?>


See also `define <http://php.net/manual/en/function.define.php>`_.

+-------------+------------------------------------+
| Short name  | Constants/CaseInsensitiveConstants |
+-------------+------------------------------------+
| Themes      | :ref:`CompatibilityPHP73`          |
+-------------+------------------------------------+
| Severity    | Critical                           |
+-------------+------------------------------------+
| Time To Fix | Slow (1 hour)                      |
+-------------+------------------------------------+



.. _cast-to-boolean:

Cast To Boolean
###############


This expression may be reduced by casting to boolean type. 

.. code-block:: php

   <?php
   
   $variable = $condition == 'met' ? 1 : 0;
   // Same as 
   $variable = (bool) $condition == 'met';
   
   $variable = $condition == 'met' ? 0 : 1;
   // Same as (Note the condition inversion)
   $variable = (bool) $condition != 'met';
   // also, with an indentical condition
   $variable = !(bool) $condition == 'met';
   
   // This also works with straight booleans expressions
   $variable = $condition == 'met' ? true : false;
   // Same as 
   $variable = $condition == 'met';
   
   ?>




Suggestions
^^^^^^^^^^^

* Remove the old expression and use ``(bool)`` operator instead
* Change the target values from true/false, or 0/1 to non-binary values, like strings or integers beyond 0 and 1.
* Complete the current branches with other commands

+-------------+-------------------------------------------------------------------------------------+
| Short name  | Structures/CastToBoolean                                                            |
+-------------+-------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                      |
+-------------+-------------------------------------------------------------------------------------+
| Severity    | Minor                                                                               |
+-------------+-------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                    |
+-------------+-------------------------------------------------------------------------------------+
| Examples    | :ref:`mediawiki-structures-casttoboolean`, :ref:`dolibarr-structures-casttoboolean` |
+-------------+-------------------------------------------------------------------------------------+



.. _catch-overwrite-variable:

Catch Overwrite Variable
########################


The try/catch structure uses some variables that are also in use in this scope. In case of a caught exception, the exception will be put in the catch variable, and overwrite the current value, loosing some data.

.. code-block:: php

   <?php
   
   // variables and caught exceptions are distinct
   $argument = 1;
   try {
       methodThatMayRaiseException($argument);
   } (Exception $e) {
       // here, $e has been changed to an exception.
   }
   
   // variables and caught exceptions are overlapping
   $e = 1;
   try {
       methodThatMayRaiseException();
   } (Exception $e) {
       // here, $e has been changed to an exception.
   }
   
   ?>


It is recommended to use another name for these catch variables.

Suggestions
^^^^^^^^^^^

* Use a standard : only use $e (or else) to catch exceptions. Avoid using them for anything else, parameter, property or local variable.
* Change the variable, and keep the caught exception

+-------------+-----------------------------------------------------------------------------------------------------+
| Short name  | Structures/CatchShadowsVariable                                                                     |
+-------------+-----------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                      |
+-------------+-----------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                               |
+-------------+-----------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                    |
+-------------+-----------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-catch-overwrite <https://github.com/dseguy/clearPHP/tree/master/rules/no-catch-overwrite.md>`__ |
+-------------+-----------------------------------------------------------------------------------------------------+
| Examples    | :ref:`phpipam-structures-catchshadowsvariable`, :ref:`suitecrm-structures-catchshadowsvariable`     |
+-------------+-----------------------------------------------------------------------------------------------------+



.. _check-all-types:

Check All Types
###############


When checking for time, avoid using else. Mention explicitly all tested type, and raise an exception when reaching else.

PHP has a short list of scalar types : null, boolean, integer, real, strings, object, resource and array. When a variable is not holding one the the type, then it may be of any other type. 

Most of the time, when using a simple `is_string() <http://www.php.net/is_string>`_ / else test, this is relying on the conception of the code. By construction, the arguments may be one of two types : array or string. 

What happens often is that in case of failure in the code (database not working, another class not checking its results), a third type is pushed to the structure, and it ends up breaking the execution. 

The safe way is to check the various types all the time, and use the default case (here, the else) to throw exception() or test an assertion and handle the special case.

.. code-block:: php

   <?php
   
   // hasty version
   if (is_array($argument)) {
       $out = $argument;
   } else {
       // Here, $argument is NOT an array. What if it is an object ? or a NULL ? 
       $out = array($argument);
   }
   
   // Safe type checking : do not assume that 'not an array' means that it is the other expected type.
   if (is_array($argument)) {
       $out = $argument;
   } elseif (is_string($argument)) {
       $out = array($argument);
   } else {
       assert(false, '$argument is not an array nor a string, as expected!');
   }
   
   ?>


Using `is_callable() <http://www.php.net/is_callable>`_, is_iterable() with this structure is fine : when variable is callable or not, while a variable is an integer or else. 

Using a type test without else is also accepted here. This is a special treatment for this test, and all others are ignored. This aspect may vary depending on situations and projects.

+-------------+--------------------------+
| Short name  | Structures/CheckAllTypes |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Major                    |
+-------------+--------------------------+
| Time To Fix | Quick (30 mins)          |
+-------------+--------------------------+



.. _check-json:

Check JSON
##########


Check errors whenever JSON is encoded or decoded. 

In particular, ``NULL`` is a valid decoded JSON response. If you want to avoid mistaking `NULL <http://php.net/manual/en/language.types.null.php>`_ for an error, it is recommended to call ``json_last_error``.

.. code-block:: php

   <?php
   
   $encoded = json_encode($incoming);
   // Unless JSON must contains some non-null data, this mistakes NULL and error
   if(json_last_error() != JSON_ERROR_NONE) {
       die('Error when encoding JSON');
   }
   
   $decoded = json_decode($incoming);
   // Unless JSON must contains some non-null data, this mistakes NULL and error
   if($decoded === null) {
       die('ERROR');
   }
   
   ?>


See also `Option to make json_encode and json_decode throw exceptions on errors <https://ayesh.me/Upgrade-PHP-7.3#json-exceptions>`_, 
         `json_last_error <http://php.net/json_last_error>`_.

+-------------+----------------------+
| Short name  | Structures/CheckJson |
+-------------+----------------------+
| Themes      | :ref:`Analyze`       |
+-------------+----------------------+
| Severity    | Major                |
+-------------+----------------------+
| Time To Fix | Quick (30 mins)      |
+-------------+----------------------+



.. _child-class-removes-typehint:

Child Class Removes Typehint
############################


PHP 7.2 introduced the ability to remove a typehint when overloading a method. This is not valid code for older versions.

.. code-block:: php

   <?php
   
   class foo {
       function foobar(foo $a) {}
   }
   
   class bar extends foo {
       function foobar($a) {}
   }
   
   ?>

+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Classes/ChildRemoveTypehint                                                                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.2 and more recent                                                                                                                                     |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                                                                            |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                                                                  |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _class-const-with-array:

Class Const With Array
######################


Constant defined with const keyword may be arrays but only stating with PHP 5.6. Define never accept arrays : it only accepts scalar values.

+-------------+---------------------------------------------------------------------------------+
| Short name  | Php/ClassConstWithArray                                                         |
+-------------+---------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55` |
+-------------+---------------------------------------------------------------------------------+
| Php Version | With PHP 5.5 and more recent                                                    |
+-------------+---------------------------------------------------------------------------------+
| Severity    | Critical                                                                        |
+-------------+---------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                   |
+-------------+---------------------------------------------------------------------------------+



.. _class-could-be-final:

Class Could Be Final
####################


Any class that has no extension should be ``final`` by default.

As stated by ``Matthias Noback`` : ``If a class is not marked final, it has at least one subclass``.

Prevent your classes from being subclassed by making them ``final``. Sometimes, classes are not meant or thought to be derivable.

.. code-block:: php

   <?php
   
   class x {}            // This class is extended
   class y extends x {}  // This class is extended
   class z extends y {}  // This class is not extended
   
   final class z2 extends y {}  // This class is not extended
   
   ?>


See also `Negative architecture, and assumptions about code <https://matthiasnoback.nl/2018/08/negative-architecture-and-assumptions-about-code/>`_.

+-------------+------------------------------------+
| Short name  | Classes/CouldBeFinal               |
+-------------+------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`ClassReview` |
+-------------+------------------------------------+
| Severity    | Minor                              |
+-------------+------------------------------------+
| Time To Fix | Quick (30 mins)                    |
+-------------+------------------------------------+



.. _class-function-confusion:

Class Function Confusion
########################


Avoid classes and functions bearing the same name. 

When functions and classes bear the same name, calling them may be confusing. This may also lead to forgotten 'new' keyword.

.. code-block:: php

   <?php
   
   class foo {}
   
   function foo() {}
   
   // Forgetting the 'new' operator is easy
   $object = new foo();
   $object = foo();
   
   ?>

+-------------+----------------------------+
| Short name  | Php/ClassFunctionConfusion |
+-------------+----------------------------+
| Themes      | :ref:`Analyze`             |
+-------------+----------------------------+
| Severity    | Minor                      |
+-------------+----------------------------+
| Time To Fix | Slow (1 hour)              |
+-------------+----------------------------+



.. _class-name-case-difference:

Class Name Case Difference
##########################


The spotted classes are used with a different case than their definition. While PHP accepts this, it makes the code harder to read. 

It may also be a violation of coding conventions.

.. code-block:: php

   <?php
   
   // This use statement has wrong case for origin.
   use Foo as X;
   
   // Definition of the class
   class foo {}
   
   // Those instantiations have wrong case
   new FOO();
   new X();
   
   ?>


See also `PHP class name constant case sensitivity and PSR-11 <https://gist.github.com/bcremer/9e8d6903ae38a25784fb1985967c6056>`_.

+-------------+----------------------------------------------------------------+
| Short name  | Classes/WrongCase                                              |
+-------------+----------------------------------------------------------------+
| Themes      | :ref:`Coding Conventions <coding-conventions>`, :ref:`Analyze` |
+-------------+----------------------------------------------------------------+
| Severity    | Minor                                                          |
+-------------+----------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                               |
+-------------+----------------------------------------------------------------+



.. _class-should-be-final-by-ocramius:

Class Should Be Final By Ocramius
#################################


'Make your classes always final, if they implement an interface, and no other public methods are defined'.

When a class should be final, as explained by ``Ocramius`` (``Marco Pivetta``).

.. code-block:: php

   <?php
   
   interface i1 {
       function i1() ;
   }
   
   // Class should final, as its public methods are in an interface
   class finalClass implements i1 {
       // public interface 
       function i1 () {}
       
       // private method
       private function a1 () {}
   }
   
   ?>


See also `When to declare classes final <http://ocramius.github.io/blog/when-to-declare-classes-final/>`_.

+-------------+-------------------------+
| Short name  | Classes/FinalByOcramius |
+-------------+-------------------------+
| Themes      | :ref:`Analyze`          |
+-------------+-------------------------+
| Severity    | Minor                   |
+-------------+-------------------------+
| Time To Fix | Slow (1 hour)           |
+-------------+-------------------------+



.. _class,-interface-or-trait-with-identical-names:

Class, Interface Or Trait With Identical Names
##############################################


The following names are used at the same time for classes, interfaces or traits. For example, 

.. code-block:: php

   <?php
       class a     { /* some definitions */ }
       interface a { /* some definitions */ }
       trait a     { /* some definitions */ }
   ?>


Even if they are in different namespaces, this makes them easy to confuse. Besides, it is recommended to have markers to differentiate classes from interfaces from traits.

+-------------+---------------------+
| Short name  | Classes/CitSameName |
+-------------+---------------------+
| Themes      | :ref:`Analyze`      |
+-------------+---------------------+
| Severity    | Minor               |
+-------------+---------------------+
| Time To Fix | Quick (30 mins)     |
+-------------+---------------------+



.. _classes-mutually-extending-each-other:

Classes Mutually Extending Each Other
#####################################


Those classes are extending each other, creating an extension loop. PHP will yield a fatal error at running time, even if it is compiling the code.

.. code-block:: php

   <?php
   
   // This code is lintable but won't run
   class Foo extends Bar { }
   class Bar extends Foo { }
   
   // The loop may be quite large
   class Foo extends Bar { }
   class Bar extends Bar2 { }
   class Bar2 extends Foo { }
   
   ?>

+-------------+----------------------------------------+
| Short name  | Classes/MutualExtension                |
+-------------+----------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`LintButWontExec` |
+-------------+----------------------------------------+
| Severity    | Major                                  |
+-------------+----------------------------------------+
| Time To Fix | Quick (30 mins)                        |
+-------------+----------------------------------------+



.. _close-tags:

Close Tags
##########


PHP manual recommends that script should be left open, without the final closing ?>. This way, one will avoid the infamous bug 'Header already sent', associated with left-over spaces, that are lying after this closing tag.

+-------------+-------------------------------------------------------------------------------------------------------------+
| Short name  | Php/CloseTags                                                                                               |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Coding Conventions <coding-conventions>`                                                              |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                       |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                            |
+-------------+-------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `leave-last-closing-out <https://github.com/dseguy/clearPHP/tree/master/rules/leave-last-closing-out.md>`__ |
+-------------+-------------------------------------------------------------------------------------------------------------+



.. _closure-could-be-a-callback:

Closure Could Be A Callback
###########################


`Closure <http://php.net/manual/en/class.closure.php>`_ could be simplified to a callback. Callbacks are strings or arrays.

A simple closure that only returns arguments relayed to another function or method, could be reduced to a simpler expression. They 

`Closure <http://php.net/manual/en/class.closure.php>`_ may be simplified with a string, for functioncall, with an array for methodcalls and static methodcalls. 

Performances : simplifying a closure tends to reduce the call time by 50%. 

.. code-block:: php

   <?php
   
   // Simple and faster call to strtoupper
   $filtered = array_map('strtoupper', $array);
   
   // Here the closure doesn't add any feature over strtoupper
   $filtered = array_map(function ($x) { return strtoupper($x);}, $array);
   
   // Methodcall example 
   $filtered = array_map(function ($x) { return $x->strtoupper() ;}, $array);
   
   // Static methodcall example 
   $filtered = array_map(function ($x) { return $x::strtoupper() ;}, $array);
   
   ?>


See also `Closure class <http://php.net/closure>`_ and 
         `Callbacks / Callables <http://php.net/manual/en/language.types.callable.php>`_.

+-------------+-----------------------------------------+
| Short name  | Functions/Closure2String                |
+-------------+-----------------------------------------+
| Themes      | :ref:`Suggestions`, :ref:`Performances` |
+-------------+-----------------------------------------+
| Severity    | Minor                                   |
+-------------+-----------------------------------------+
| Time To Fix | Quick (30 mins)                         |
+-------------+-----------------------------------------+



.. _closure-may-use-$this:

Closure May Use $this
#####################


`$this <http://php.net/manual/en/language.oop5.basic.php>`_ is automatically accessible to closures.

When closures were introduced in PHP, they couldn't use the `$this <http://php.net/manual/en/language.oop5.basic.php>`_ variable, making is cumbersome to access local properties when the closure was created within an object. 

.. code-block:: php

   <?php
   
   // Invalid code in PHP 5.4 and less
   class Test
   {
       public function testing()
       {
           return function() {
               var_dump($this);
           };
       }
   }
   
   $object = new Test;
   $function = $object->testing();
   $function();
       
   ?>


This is not the case anymore since PHP 5.4.

See also `Anonymous Functions <http://php.net/manual/en/functions.anonymous.php>`_.

+-------------+---------------------------+
| Short name  | Php/ClosureThisSupport    |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP53` |
+-------------+---------------------------+
| Php Version | With PHP 5.4 and older    |
+-------------+---------------------------+
| Severity    | Minor                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _common-alternatives:

Common Alternatives
###################


In the following conditional structures, expressions were found that are common to both 'then' and 'else'. It may be interesting, though not always possible, to put them both out of the conditional, and reduce line count. 

.. code-block:: php

   <?php
   if ($c == 5) {
       $b = strtolower($b[2]); 
       $a++;
   } else {
       $b = strtolower($b[2]); 
       $b++;
   }
   ?>


may be rewritten in : 

.. code-block:: php

   <?php
   
   $b = strtolower($b[2]); 
   if ($c == 5) {
       $a++;
   } else {
       $b++;
   }
   
   ?>

+-------------+-------------------------------+
| Short name  | Structures/CommonAlternatives |
+-------------+-------------------------------+
| Themes      | :ref:`Analyze`                |
+-------------+-------------------------------+
| Severity    | Major                         |
+-------------+-------------------------------+
| Time To Fix | Instant (5 mins)              |
+-------------+-------------------------------+



.. _compact-inexistant-variable:

Compact Inexistant Variable
###########################


Compact doesn't warn when it tries to work on an inexisting variable. It just ignores the variable.

.. code-block:: php

   <?php
   
   function foo($b = 2) {
       $a = 1;
       // $c doesn't exists, and is not compacted.
       return compact('a', 'b', 'c');
   }
   ?>


For performances reasons, this analysis only works inside methods and functions.

See also `compact <http://www.php.net/compact>`_ and 
         `PHP RFC: Make compact function reports undefined passed variables <https://wiki.php.net/rfc/compact>`_.

+-------------+-----------------------------------------------+
| Short name  | Php/CompactInexistant                         |
+-------------+-----------------------------------------------+
| Themes      | :ref:`Suggestions`, :ref:`CompatibilityPHP73` |
+-------------+-----------------------------------------------+
| Severity    | Major                                         |
+-------------+-----------------------------------------------+
| Time To Fix | Quick (30 mins)                               |
+-------------+-----------------------------------------------+



.. _compare-hash:

Compare Hash
############


When comparing hash values, it is important to use the strict comparison : ``===`` or ``!==``. 

In a number of situations, the hash value will start with ``0e``, and PHP will understand that the comparison involves integers : it will then convert the strings into numbers, and it may end up converting them to 0.

Here is an example 

.. code-block:: php

   <?php
   
   // The two following passwords hashes matches, while they are not the same. 
   $hashed_password = 0e462097431906509000000000000;
   if (hash('md5','240610708',false) == $hashed_password) {
     print 'Matched.'.PHP_EOL;
   }
   
   // hash returns a string, that is mistaken with 0 by PHP
   // The strength of the hashing algorithm is not a problem
   if (hash('ripemd160','20583002034',false) == '0') {
     print 'Matched.'.PHP_EOL;
   }
   
   if (hash('md5','240610708',false) !== $hashed_password) {
     print 'NOT Matched.'.PHP_EOL;
   }
   
   // Display true
   var_dump(md5('240610708') == md5('QNKCDZO') );
   
   ?>


You may also use `password_hash() <http://www.php.net/password_hash>`_ and `password_verify() <http://www.php.net/password_verify>`_ : they work together without integer conversion problems, and they can't be confused with a number.

See also `Magic Hashes <https://blog.whitehatsec.com/magic-hashes/>`_ and 
         `md5('240610708') == md5('QNKCDZO') <https://news.ycombinator.com/item?id=9484757>`_.

+-------------+-----------------------------------------------------------------------------------------------------+
| Short name  | Security/CompareHash                                                                                |
+-------------+-----------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Security`                                                                                     |
+-------------+-----------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                               |
+-------------+-----------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                     |
+-------------+-----------------------------------------------------------------------------------------------------+
| ClearPHP    | `strict-comparisons <https://github.com/dseguy/clearPHP/tree/master/rules/strict-comparisons.md>`__ |
+-------------+-----------------------------------------------------------------------------------------------------+
| Examples    | :ref:`traq-security-comparehash`, :ref:`livezilla-security-comparehash`                             |
+-------------+-----------------------------------------------------------------------------------------------------+



.. _compared-comparison:

Compared Comparison
###################


Usually, comparison are sufficient, and it is rare to have to compare the result of comparison. Check if this two-stage comparison is really needed.

.. code-block:: php

   <?php
   
   if ($a === strpos($string, $needle) > 2) {}
   
   // the expression above apply precedence : 
   // it is equivalent to : 
   if (($a === strpos($string, $needle)) > 2) {}
   
   ?>


See also `Operators Precedence <http://php.net/manual/en/language.operators.precedence.php>`_.

+-------------+-------------------------------+
| Short name  | Structures/ComparedComparison |
+-------------+-------------------------------+
| Themes      | :ref:`Analyze`                |
+-------------+-------------------------------+
| Severity    | Major                         |
+-------------+-------------------------------+
| Time To Fix | Quick (30 mins)               |
+-------------+-------------------------------+



.. _concrete-visibility:

Concrete Visibility
###################


Methods that implements an interface in a class must be public. 

PHP doesn't lint this, unless the interface and the class are in the same file. At execution, it stops immediately with a Fatal error : 'Access level to c\:\:iPrivate() must be public (as in class i) ';

.. code-block:: php

   <?php
   
   interface i {
       function iPrivate() ;
       function iProtected() ;
       function iPublic() ;
   }
   
   class c implements i {
       // Methods that implements an interface in a class must be public.  
       private function iPrivate() {}
       protected function iProtected() {}
       public function iPublic() {}
   }
   
   ?>


See also `Interfaces <http://php.net/manual/en/language.oop5.interfaces.php>`_.

+-------------+----------------------------------------+
| Short name  | Interfaces/ConcreteVisibility          |
+-------------+----------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`LintButWontExec` |
+-------------+----------------------------------------+
| Severity    | Major                                  |
+-------------+----------------------------------------+
| Time To Fix | Instant (5 mins)                       |
+-------------+----------------------------------------+



.. _configure-extract:

Configure Extract
#################


The `extract() <http://www.php.net/extract>`_ function overwrites local variables when left unconfigured.

Extract imports variables from an array into the local scope. In case of a conflict, that is when a local variable already exists, it overwrites the previous variable.

In fact, `extract() <http://www.php.net/extract>`_ may be configured to handle the situation differently : it may skip the conflicting variable, prefix it, prefix it only if it exists, only import overwriting variables... It may also import them as references to the original values.

This analysis reports `extract() <http://www.php.net/extract>`_ when it is not configured explicitly. If overwriting is the intended objective, it is not reported.

.. code-block:: php

   <?php
   
   // ignore overwriting variables
   extract($array, EXTR_SKIP);
   
   // prefix all variables explicitly variables with 'php_'
   extract($array, EXTR_PREFIX_ALL, 'php_');
   
   // overwrites explicitly variables
   extract($array, EXTR_OVERWRITE);
   
   // overwrites implicitely variables : do we really want that? 
   extract($array, EXTR_OVERWRITE);
   
   ?>


Always avoid using `extract() <http://www.php.net/extract>`_ on untrusted sources, such as ``$_GET``, ``$_POST``, ``$_FILES``, or even databases records.

See also `extract <http://php.net/extract>`_.

+-------------+---------------------------+
| Short name  | Security/ConfigureExtract |
+-------------+---------------------------+
| Themes      | :ref:`Security`           |
+-------------+---------------------------+
| Severity    | Minor                     |
+-------------+---------------------------+
| Time To Fix | Instant (5 mins)          |
+-------------+---------------------------+



.. _const-visibility-usage:

Const Visibility Usage
######################


Visibility for class constant controls the accessibility to class constant.

A public constant may be used anywhere in the code; a protected constant usage is restricted to the class and its relatives; a private constant is restricted to itself.

This feature was introduced in PHP 7.1. It is recommended to use explicit visibility, and, whenever possible, make the visibility private.

.. code-block:: php

   <?php
   
   class x {
       public const a = 1;
       protected const b = 2;
       private const c = 3;
       const d = 4;
   }
   
   interface i {
       public const a = 1;
         const d = 4;
   }
   
   ?>


See also `Class Constants <http://php.net/manual/en/language.oop5.constants.php>`_ and 
         `PHP RFC: Support Class Constant Visibility <https://wiki.php.net/rfc/class_const_visibility>`_.

+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Classes/ConstVisibilityUsage                                                                                                          |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.1 and more recent                                                                                                          |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                                                 |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                                                         |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+



.. _const-with-array:

Const With Array
################


The const keyword supports array. This feature was added in PHP 5.6. 

The array must be filled with other constants. It may also be build using the '+' operator. 

.. code-block:: php

   <?php
   
   const PRIMES = [2, 3, 5, 7];
   
   class X {
       const TWENTY_THREE = 23;
       const MORE_PRIMES = PRIMES + [11, 13, 17, 19];
       const EVEN_MORE_PRIMES = self::MORE_PRIMES + [self::TWENTY_THREE];
   }
   
   ?>


See also `Class Constants <http://php.net/manual/en/language.oop5.constants.php>`_ and 
         `Constants Syntax <http://php.net/manual/en/language.constants.syntax.php>`_.

+-------------+---------------------------------------------------------------------------------+
| Short name  | Php/ConstWithArray                                                              |
+-------------+---------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55` |
+-------------+---------------------------------------------------------------------------------+
| Php Version | With PHP 5.5 and more recent                                                    |
+-------------+---------------------------------------------------------------------------------+
| Severity    | Major                                                                           |
+-------------+---------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                   |
+-------------+---------------------------------------------------------------------------------+



.. _constant-class:

Constant Class
##############


A class or an interface only made up of constants. Constants usually have to be used in conjunction of some behavior (methods, class...) and never alone. 

.. code-block:: php

   <?php
   
   class ConstantClass {
       const KBIT = 1000;
       const MBIT = self::KBIT * 1000;
       const GBIT = self::MBIT * 1000;
       const PBIT = self::GBIT * 1000;
   }
   
   ?>


As such, they should be PHP constants (build with define or const), or included in a class with other methods and properties. 

See also `PHP Classes containing only constants <https://stackoverflow.com/questions/16838266/php-classes-containing-only-constants>`_.

+-------------+-----------------------+
| Short name  | Classes/ConstantClass |
+-------------+-----------------------+
| Themes      | :ref:`Analyze`        |
+-------------+-----------------------+
| Severity    | Minor                 |
+-------------+-----------------------+
| Time To Fix | Slow (1 hour)         |
+-------------+-----------------------+



.. _constant-comparison:

Constant Comparison
###################


Constant to the left or right is a favorite. 

Comparisons are commutative : they may be $a == B or B == $a. The analyzed code show less than 10% of one of the two : for consistency reasons, it is recommended to make them all the same. 

Putting the constant on the left is also called 'Yoda Comparison', as it mimics the famous characters style of speech. It prevents errors like 'B = $a' where the comparison is turned into an assignation. 

The natural way is to put the constant on the right. It is often less surprising. 

Every comparison operator is used when finding the favorite.

.. code-block:: php

   <?php
   
   // 
   if ($a === B) { doSomething(); }
   if ($c > D) { doSomething(); }
   if ($e !== G) { doSomething(); }
   do { doSomething(); } while ($f === B);
   while ($a === B) { doSomething(); }
   
   // be consistent
   if (B === $a) {}
   
   // Compari
   if (B <= $a) {}
   
   ?>

+-------------+------------------------------------------------+
| Short name  | Structures/ConstantComparisonConsistance       |
+-------------+------------------------------------------------+
| Themes      | :ref:`Coding Conventions <coding-conventions>` |
+-------------+------------------------------------------------+
| Severity    | Minor                                          |
+-------------+------------------------------------------------+
| Time To Fix | Quick (30 mins)                                |
+-------------+------------------------------------------------+



.. _constant-scalar-expressions:

Constant Scalar Expressions
###########################


Define constant with the result of static expressions. This means that constants may be defined with the const keyword, with the help of various operators but without any functioncalls. 

This feature was introduced in PHP 5.6. It also supports array(), and expressions in arrays.

Those expressions (using simple operators) may only manipulate other constants, and all values must be known at compile time. 

.. code-block:: php

   <?php
   
   // simple definition
   const A = 1;
   
   // constant scalar expression
   const B = A * 3;
   
   // constant scalar expression
   const C = [A ** 3, '3' => B];
   
   ?>


See also `Constant Scalar Expressions <https://wiki.php.net/rfc/const_scalar_exprs>`_.

+-------------+---------------------------------------------------------------------------------+
| Short name  | Structures/ConstantScalarExpression                                             |
+-------------+---------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55` |
+-------------+---------------------------------------------------------------------------------+
| Php Version | With PHP 5.6 and more recent                                                    |
+-------------+---------------------------------------------------------------------------------+
| Severity    | Major                                                                           |
+-------------+---------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                 |
+-------------+---------------------------------------------------------------------------------+



.. _constants-created-outside-its-namespace:

Constants Created Outside Its Namespace
#######################################


Constants Created Outside Its Namespace.

Using the `define() <http://www.php.net/define>`_ function, it is possible to create constant outside their namespace, but using the fully qualified namespace.

.. code-block:: php

   <?php
   
   namespace A\B {
       // define A\B\C as 1
       define('C', 1);
   }
   
   namespace D\E {
       // define A\B\C as 1, while outside the A\B namespace
       define('A\B\C', 1);
   }
   
   ?>


However, this makes the code confusing and difficult to debug. It is recommended to move the constant definition to its namespace.

+-------------+--------------------------------------+
| Short name  | Constants/CreatedOutsideItsNamespace |
+-------------+--------------------------------------+
| Themes      | :ref:`Analyze`                       |
+-------------+--------------------------------------+
| Severity    | Minor                                |
+-------------+--------------------------------------+
| Time To Fix | Slow (1 hour)                        |
+-------------+--------------------------------------+



.. _constants-with-strange-names:

Constants With Strange Names
############################


List of constants being defined with names that are incompatible with PHP standards. 

.. code-block:: php

   <?php
   
   // Define a valid PHP constant
   define('ABC', 1); 
   const ABCD = 2; 
   
   // Define an invalid PHP constant
   define('ABC!', 1); 
   echo defined('ABC!') ? constant('ABC!') : 'Undefined';
   
   // Const doesn't allow illegal names
   
   ?>


See also `PHP Constants <http://php.net/manual/en/language.constants.php>`_.

+-------------+--------------------------------+
| Short name  | Constants/ConstantStrangeNames |
+-------------+--------------------------------+
| Themes      | :ref:`Analyze`                 |
+-------------+--------------------------------+
| Severity    | Minor                          |
+-------------+--------------------------------+
| Time To Fix | Slow (1 hour)                  |
+-------------+--------------------------------+



.. _continue-is-for-loop:

Continue Is For Loop
####################


`break <http://php.net/manual/en/control-structures.break.php>`_ and `continue <http://php.net/manual/en/control-structures.continue.php>`_ are very similar in PHP : they both `break <http://php.net/manual/en/control-structures.break.php>`_ out of loop or switch. Yet, `continue <http://php.net/manual/en/control-structures.continue.php>`_ should be reserved for loops.

Since PHP 7.3, the execution will emit a warning when finding a `continue <http://php.net/manual/en/control-structures.continue.php>`_ inside a switch inside a loop : '"`continue <http://php.net/manual/en/control-structures.continue.php>`_" targeting switch is equivalent to "`break <http://php.net/manual/en/control-structures.break.php>`_". Did you mean to use "`continue <http://php.net/manual/en/control-structures.continue.php>`_ 2"?'

.. code-block:: php

   <?php
   
   while ($foo) {
       switch ($bar) {
           case 'baz':
               continue; // In PHP: Behaves like 'break;'
                         // In C:   Behaves like 'continue 2;'
       }
   }
   
   ?>


See also `Deprecate and remove `continue <http://php.net/manual/en/control-structures.continue.php>`_ targeting switch <https://wiki.php.net/rfc/continue_on_switch_deprecation>`_.

+-------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Structures/ContinueIsForLoop                                                                                                                                                                                                           |
+-------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71`, :ref:`CompatibilityPHP72`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP73` |
+-------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                                                                                                                                                  |
+-------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                                                                                                                                        |
+-------------+----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _could-be-abstract-class:

Could Be Abstract Class
#######################


An abstract class is never instantiated, and has children class that are. As such, a 'parent' class that is never instantiated by itself, but has its own children instantiated could be marked as abstract. 

That will prevent new code to try to instantiate it.

.. code-block:: php

   <?php
   
   // Example code would actually be split over multiple files.
   
   
   // That class could be abstract
   class motherClass {}
   
   // Those classes shouldn't be abstract
   class firstChildren extends motherClass {}
   class secondChildren extends motherClass {}
   class thirdChildren extends motherClass {}
   
   new firstChildren();
   new secondChildren();
   new thirdChildren();
   
   //Not a single : new motherClass()
   
   ?>


See also `Class Abstraction <http://php.net/abstract>`_
         `Abstract classes and methods <https://phpenthusiast.com/object-oriented-php-tutorials/abstract-classes-and-methods>`_.


Suggestions
^^^^^^^^^^^

* Make this class an abstract class

+-------------+-------------------------------------------------------------------------------------------+
| Short name  | Classes/CouldBeAbstractClass                                                              |
+-------------+-------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`ClassReview`                                                        |
+-------------+-------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                     |
+-------------+-------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                           |
+-------------+-------------------------------------------------------------------------------------------+
| Examples    | :ref:`edusoho-classes-couldbeabstractclass`, :ref:`shopware-classes-couldbeabstractclass` |
+-------------+-------------------------------------------------------------------------------------------+



.. _could-be-class-constant:

Could Be Class Constant
#######################


When a property is defined and read, but never modified, it may be a constant. 

.. code-block:: php

   <?php
   
   class foo {
       // $this->bar is never modified. 
       private $bar = 1;
       
       // $this->foofoo is modified, at least once
       private $foofoo = 2;
       
       function method($a) {
           $this->foofoo = $this->bar + $a + $this->foofoo;
           
           return $this->foofoo;
       }
       
   }
   
   ?>


Starting with PHP 5.6, even array() may be defined as constants.

+-------------+------------------------------+
| Short name  | Classes/CouldBeClassConstant |
+-------------+------------------------------+
| Themes      | :ref:`ClassReview`           |
+-------------+------------------------------+
| Severity    | Minor                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _could-be-else:

Could Be Else
#############


Merge opposition conditions into one if/then structure.

When two if/then structures follow each other, using a condition and its opposite, they may be merged into one.

.. code-block:: php

   <?php
   
   // Short version
   if ($a == 1) {
       $b = 2;
   } else {
       $b = 1;
   }
   
   // Long version
   if ($a == 1) {
       $b = 2;
   }
   
   if ($a != 1) {
       $b = 3;
   }
   
   ?>

+-------------+------------------------+
| Short name  | Structures/CouldBeElse |
+-------------+------------------------+
| Themes      | :ref:`Analyze`         |
+-------------+------------------------+
| Severity    | Minor                  |
+-------------+------------------------+
| Time To Fix | Instant (5 mins)       |
+-------------+------------------------+



.. _could-be-private-class-constant:

Could Be Private Class Constant
###############################


Class constant may use ``private`` visibility. 

Since PHP 7.1, constants may also have a public/protected/private visibility. This restrict their usage to anywhere, class and children or class. 

As a general rule, it is recommended to make constant ``private`` by default, and to relax this restriction as needed. PHP makes them public by default.

.. code-block:: php

   <?php
   
   class foo {
       // pre-7.1 style
       const PRE_71_CONSTANT = 1;
       
       // post-7.1 style
       private const PRIVATE_CONSTANT = 2;
       public const PUBLIC_CONSTANT = 3;
       
       function bar() {
           // PRIVATE CONSTANT may only be used in its class
           echo self::PRIVATE_CONSTANT;
       }
   }
   
   // Other constants may be used anywhere
   function x($a = foo::PUBLIC_CONSTANT) {
       echo $a.' '.foo:PRE_71_CONSTANT;
   }
   
   ?>


Constant shall stay ``public`` when the code has to be compatible with PHP 7.0 and older. 

They also have to be public in the case of component : some of those constants have to be used by external actors, in order to configure the component.

See also `Class Constants <http://php.net/manual/en/language.oop5.constants.php>`_.

+-------------+----------------------------------------------+
| Short name  | Classes/CouldBePrivateConstante              |
+-------------+----------------------------------------------+
| Themes      | :ref:`ClassReview`                           |
+-------------+----------------------------------------------+
| Severity    | Minor                                        |
+-------------+----------------------------------------------+
| Time To Fix | Quick (30 mins)                              |
+-------------+----------------------------------------------+
| Examples    | :ref:`phinx-classes-couldbeprivateconstante` |
+-------------+----------------------------------------------+



.. _could-be-protected-class-constant:

Could Be Protected Class Constant
#################################


Class constant may use 'protected' visibility. 

Since PHP 7.1, constants may also have a public/protected/private visibility. This restrict their usage to anywhere, class and children or class. 

As a general rule, it is recommended to make constant 'private' by default, and to relax this restriction as needed. PHP makes them public by default.

.. code-block:: php

   <?php
   
   class foo {
       // pre-7.1 style
       const PRE_71_CONSTANT = 1;
       
       // post-7.1 style
       protected const PROTECTED_CONSTANT = 2;
       public const PUBLIC_CONSTANT = 3;
   }
   
   class foo2 extends foo {
       function bar() {
           // PROTECTED_CONSTANT may only be used in its class or its children
           echo self::PROTECTED_CONSTANT;
       }
   }
   
   class foo3 extends foo {
       function bar() {
           // PROTECTED_CONSTANT may only be used in its class or any of its children
           echo self::PROTECTED_CONSTANT;
       }
   }
   
   // Other constants may be used anywhere
   function x($a = foo::PUBLIC_CONSTANT) {
       echo $a.' '.foo:PRE_71_CONSTANT;
   }
   
   ?>

+-------------+----------------------------------+
| Short name  | Classes/CouldBeProtectedConstant |
+-------------+----------------------------------+
| Themes      | :ref:`ClassReview`               |
+-------------+----------------------------------+
| Severity    | Minor                            |
+-------------+----------------------------------+
| Time To Fix | Quick (30 mins)                  |
+-------------+----------------------------------+



.. _could-be-protected-method:

Could Be Protected Method
#########################


Those methods are declared public, but are never used publicly. They may be made protected. 

.. code-block:: php

   <?php
   
   class foo {
       // Public, and used publicly
       public publicMethod() {}
   
       // Public, but never used outside the class or its children
       public protectedMethod() {}
       
       private function bar() {
           $this->protectedMethod();
       }
   }
   
   $foo = new Foo();
   $foo->publicMethod();
   
   ?>


These properties may even be made private.

+-------------+--------------------------------+
| Short name  | Classes/CouldBeProtectedMethod |
+-------------+--------------------------------+
| Themes      | :ref:`ClassReview`             |
+-------------+--------------------------------+
| Severity    | Minor                          |
+-------------+--------------------------------+
| Time To Fix | Quick (30 mins)                |
+-------------+--------------------------------+



.. _could-be-protected-property:

Could Be Protected Property
###########################


Those properties are declared public, but are never used publicly. They may be made protected. 

.. code-block:: php

   <?php
   
   class foo {
       // Public, and used publicly
       public $publicProperty;
       // Public, but never used outside the class or its children
       public $protectedProperty;
       
       function bar() {
           $this->protectedProperty = 1;
       }
   }
   
   $foo = new Foo();
   $foo->publicProperty = 3;
   
   ?>


This property may even be made private.

+-------------+----------------------------------+
| Short name  | Classes/CouldBeProtectedProperty |
+-------------+----------------------------------+
| Themes      | :ref:`ClassReview`               |
+-------------+----------------------------------+
| Severity    | Minor                            |
+-------------+----------------------------------+
| Time To Fix | Slow (1 hour)                    |
+-------------+----------------------------------+



.. _could-be-static:

Could Be Static
###############


This global is only used in one function or method. It may be called 'static', instead of global. This allows you to keep the value between call to the function, but will not be accessible outside this function.

.. code-block:: php

   <?php
   function foo( ) {
       static $variableIsReservedForX; // only accessible within foo( ), even between calls.
       global $variableIsGlobal;       //      accessible everywhere in the application
   }
   ?>

+-------------+---------------------------------------------------------------------------------+
| Short name  | Structures/CouldBeStatic                                                        |
+-------------+---------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`ClassReview`, :ref:`Analyze`, :ref:`ClassReview`          |
+-------------+---------------------------------------------------------------------------------+
| Severity    | Major                                                                           |
+-------------+---------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                 |
+-------------+---------------------------------------------------------------------------------+
| Examples    | :ref:`dolphin-structures-couldbestatic`, :ref:`contao-structures-couldbestatic` |
+-------------+---------------------------------------------------------------------------------+



.. _could-be-static-closure:

Could Be Static Closure
#######################


`Closure <http://php.net/manual/en/class.closure.php>`_ may be static, and prevent the import of ``$this``. 



.. code-block:: php

   <?php
   
   class Foo
   {
       function __construct()
       {
   
           // Not possible to use $this
           $func = static function() {
               var_dump($this);
           };
           $func();
   
           // Normal import of $this
           $closure = function() {
               var_dump($this);
           };
       }
   };
   new Foo();
   
   ?>


See also `Anonymous functions <http://php.net/manual/en/functions.anonymous.php>`_ and 
         `Static anonymous functions <http://php.net/manual/en/functions.anonymous.php#functions.anonymous-functions.static>`_.

+-------------+--------------------------------+
| Short name  | Functions/CouldBeStaticClosure |
+-------------+--------------------------------+
| Themes      | :ref:`Suggestions`             |
+-------------+--------------------------------+
| Severity    | Minor                          |
+-------------+--------------------------------+
| Time To Fix | Quick (30 mins)                |
+-------------+--------------------------------+



.. _could-be-typehinted-callable:

Could Be Typehinted Callable
############################


Those arguments may use the callable Typehint. 

'callable' is a PHP keyword that represents callback functions. Those may be used in dynamic function call, like $function(); or as callback functions, like with `array_map() <http://www.php.net/array_map>`_;

callable may be a string representing a function name or a static call (including \:\:), an array with two elements, (a class or object, and a method), or a closure.

When arguments are used to call a function, but are not marked with 'callable', they are reported by this analysis.

.. code-block:: php

   <?php
   
   function foo(callable $callable) {
       // very simple callback
       return $callable();
   }
   
   function foo2($array, $callable) {
       // very simple callback
       return array_map($array, $callable);
   }
   
   ?>


See also `Callback / callable <http://php.net/manual/en/language.types.callable.php>`_.



Suggestions
^^^^^^^^^^^

* Add the typehint callable
* Use the function is_callable() inside the method if 'callable' is too strong.

+-------------+---------------------------------------------------------------------------------------+
| Short name  | Functions/CouldBeCallable                                                             |
+-------------+---------------------------------------------------------------------------------------+
| Themes      | :ref:`Suggestions`                                                                    |
+-------------+---------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                 |
+-------------+---------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                       |
+-------------+---------------------------------------------------------------------------------------+
| Examples    | :ref:`magento-functions-couldbecallable`, :ref:`prestashop-functions-couldbecallable` |
+-------------+---------------------------------------------------------------------------------------+



.. _could-make-a-function:

Could Make A Function
#####################


When a function is called across the code with the same arguments often enough, it should be turned into a local API. 

This approach is similar to turning literals into constants : it centralize the value, it helps refactoring by updating it. It also makes the code more readable. Moreover, it often highlight common grounds between remote code locations. 

The analysis looks for functions calls, and checks the arguments. When the calls occurs more than 4 times, it is reported. 

.. code-block:: php

   <?php
   
   // str_replace is used to clean '&' from strings. 
   // It should be upgraded to a central function
   function foo($arg ) {
       $arg = str_replace('&', '', $arg);
       // do something with $arg
   }
   
   class y {
       function bar($database ) {
           $value = $database->queryName();
           $value = str_replace('&', '', $value);
           // $value = removeAmpersand($value);
           // do something with $arg2
       }
   }
   
   // helper function
   function removeAmpersand($string) {
       return str_replace('&', '', $string);
   }
   
   ?>

+-------------+------------------------------------+
| Short name  | Functions/CouldCentralize          |
+-------------+------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Suggestions` |
+-------------+------------------------------------+
| Severity    | Minor                              |
+-------------+------------------------------------+
| Time To Fix | Slow (1 hour)                      |
+-------------+------------------------------------+



.. _could-return-void:

Could Return Void
#################


The following functions may bear the void return typehint. 

.. code-block:: php

   <?php
   
   // This can be Void
   function foo(&$a) {
       ++$a;
       return; 
   }
   
   // This can't be Void
   function bar($a) {
       ++$a;
       return $a;  
   }
   
   ?>


See also `Returning values <http://php.net/manual/en/functions.returning-values.php>`_ and 
         `Void Return Type <https://wiki.php.net/rfc/void_return_type>`_.

+-------------+---------------------------+
| Short name  | Functions/CouldReturnVoid |
+-------------+---------------------------+
| Themes      | :ref:`Suggestions`        |
+-------------+---------------------------+
| Severity    | Minor                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _could-typehint:

Could Typehint
##############


Arguments that are tested with `instanceof <http://php.net/manual/en/language.operators.type.php>`_ gain from making it a Typehint.

.. code-block:: php

   <?php
   
   function foo($a, $b) {
       // $a is tested for B with instanceof. 
       if (!$a instanceof B) {
           return;
       }
       
       // More code
   }
   
   function foo(B $a, $b) {
       // May omit the initial test
       
       // More code
   }
   
   ?>

+-------------+-------------------------+
| Short name  | Functions/CouldTypehint |
+-------------+-------------------------+
| Themes      | :ref:`Suggestions`      |
+-------------+-------------------------+
| Severity    | Minor                   |
+-------------+-------------------------+
| Time To Fix | Quick (30 mins)         |
+-------------+-------------------------+



.. _could-use-alias:

Could Use Alias
###############


This long name may be reduced by using an available alias.

.. code-block:: php

   <?php
   
   use a\b\c;
   
   // This may be reduced with the above alias
   new a\b\c\d();
   
   // This too
   new a\b\c\d\e\f();
   
   // This yet again
   new a\b\c();
   
   ?>

+-------------+--------------------------+
| Short name  | Namespaces/CouldUseAlias |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Minor                    |
+-------------+--------------------------+
| Time To Fix | Quick (30 mins)          |
+-------------+--------------------------+



.. _could-use-compact:

Could Use Compact
#################


`Compact() <http://www.php.net/compact>`_ turns a group of variables into an array. It may be used to simplify expressions. 

.. code-block:: php

   <?php
   
   $a = 1;
   $b = 2;
   
   // Compact call
   $array = compact('a', 'b');
   
   $array === [1, 2];
   
   // Detailing all the keys and their value
   $array = ['a' => $a, 'b' => $b];
   
   ?>


Note that compact accepts any string, and any undefined variable is not set, without a warning.

See also `compact <http://www.php.net/compact>`_.

+-------------+----------------------------+
| Short name  | Structures/CouldUseCompact |
+-------------+----------------------------+
| Themes      | :ref:`Suggestions`         |
+-------------+----------------------------+
| Severity    | Minor                      |
+-------------+----------------------------+
| Time To Fix | Quick (30 mins)            |
+-------------+----------------------------+



.. _could-use-short-assignation:

Could Use Short Assignation
###########################


Use short assignment operator, to speed up code, and keep syntax clear.  

Some operators, like * or +, have a compact and fast 'do-and-assign' version. They looks like a compacted version for = and the operator. This syntax is good for readability, and saves some memory in the process. 

Depending on the operator, not all permutations of arguments are possible. 

Addition and short assignation of addition have a different set of features when applied to arrays. Do not exchange one another in that case.

.. code-block:: php

   <?php
   
   $a = 10 + $a;
   $a += 10;
   
   $b = $b - 1;
   $b -= 1;
   
   $c = $c * 2;
   $c *= 2;
   
   $d = $d / 3;
   $d /= 3;
   
   $e = $e % 4;
   $e %= 4;
   
   $f = $f | 5;
   $f |= 5;
   
   $g = $g & 6;
   $g &= 6;
   
   $h = $h ^ 7;
   $h ^= 7;
   
   $i = $i >> 8;
   $i >>= 8;
   
   $j = $j << 9;
   $j <<= 9;
   
   ?>


Short operators are faster than the extended version, though it is a micro-optimization.

See also `Assignation Operators <http://php.net/manual/en/language.operators.assignment.php>`_.

+-------------+-------------------------------------------------------------------------------------------------------------+
| Short name  | Structures/CouldUseShortAssignation                                                                         |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Performances`                                                                         |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                       |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                            |
+-------------+-------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `use-short-assignations <https://github.com/dseguy/clearPHP/tree/master/rules/use-short-assignations.md>`__ |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`churchcrm-structures-coulduseshortassignation`, :ref:`thelia-structures-coulduseshortassignation`     |
+-------------+-------------------------------------------------------------------------------------------------------------+



.. _could-use-try:

Could Use Try
#############


Some commands may raise exceptions. It is recommended to use the try/catch block to intercept those exceptions, and process them.

* / : ``DivisionByZeroError``
* % : ``DivisionByZeroError``
* intdiv() : ``DivisionByZeroError``
* << : ``ArithmeticError``
* >> : ``ArithmeticError``
* Phar\:\:mungserver : ``PharException``
* Phar\:\:webphar : ``PharException``

See also `Predefined Exceptions <http://php.net/manual/en/reserved.exceptions.php>`_,
         `PharException <http://php.net/manual/en/class.pharexception.php>`_.

+-------------+------------------------+
| Short name  | Exceptions/CouldUseTry |
+-------------+------------------------+
| Themes      | :ref:`Suggestions`     |
+-------------+------------------------+
| Severity    | Minor                  |
+-------------+------------------------+
| Time To Fix | Quick (30 mins)        |
+-------------+------------------------+



.. _could-use-\_\_dir\_\_:

Could Use __DIR__
#################


Use `__DIR__ <http://php.net/manual/en/language.constants.predefined.php>`_ constant to access the current file's parent directory. 

Avoid using `dirname() <http://www.php.net/dirname>`_ on `__FILE__ <http://php.net/manual/en/language.constants.predefined.php>`_.

.. code-block:: php

   <?php
   
   // Better way
   $fp = fopen(__DIR__.'/myfile.txt', 'r');
   
   // compatible, but slow way
   $fp = fopen(dirname(__FILE__).'/myfile.txt', 'r');
   
   // Since PHP 5.3
   assert(dirname(__FILE__) == __DIR__);
   
   ?>


`__DIR__ <http://php.net/manual/en/language.constants.predefined.php>`_ has been introduced in PHP 5.3.0.

See also `Magic Constants <http://php.net/manual/en/language.constants.predefined.php>`_.

+-------------+------------------------------------+
| Short name  | Structures/CouldUseDir             |
+-------------+------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Suggestions` |
+-------------+------------------------------------+
| Severity    | Major                              |
+-------------+------------------------------------+
| Time To Fix | Quick (30 mins)                    |
+-------------+------------------------------------+



.. _could-use-array\_fill\_keys:

Could Use array_fill_keys
#########################


`array_fill_keys() <http://www.php.net/array_fill_keys>`_ is a native PHP function that creates an array from keys. It gets the list of keys, and a constant value to assign to each keys.

This is twice faster than doing the same with a loop.

.. code-block:: php

   <?php
   
   $array = range('a', 'z');
   
   // Fast way to build the array
   $b = array_fill_key($a, 0);
   
   // Slow way to build the array
   foreach($array as $a) {
       $b[$a] = 0;
   }
   
   ?>


See also `array_fill_keys <http://php.net/array_fill_keys>`_.

+-------------+----------------------------------+
| Short name  | Structures/CouldUseArrayFillKeys |
+-------------+----------------------------------+
| Themes      | :ref:`Suggestions`               |
+-------------+----------------------------------+
| Severity    | Minor                            |
+-------------+----------------------------------+
| Time To Fix | Slow (1 hour)                    |
+-------------+----------------------------------+



.. _could-use-array\_unique:

Could Use array_unique
######################


Use `array_unique() <http://www.php.net/array_unique>`_ to collect unique elements from an array.

Always try to use native PHP functions, instead of rebuilding them with custom PHP code.

.. code-block:: php

   <?php
   
       $unique = array();
       foreach ($array as $b) {
           if (!in_array($b, $unique)) {
               /*  May be more code */
               $unique[] = $b;
           }
       }
   ?>


See also `array_unique <http://php.net/array_unique>`_.


Suggestions
^^^^^^^^^^^

* Turn the foreach() and its condition into a call to array_unique()
* Extract the condition from the foreach() and add a separate call to array_unique()

+-------------+-----------------------------------------------------------------------------------------------+
| Short name  | Structures/CouldUseArrayUnique                                                                |
+-------------+-----------------------------------------------------------------------------------------------+
| Themes      | :ref:`Suggestions`                                                                            |
+-------------+-----------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                         |
+-------------+-----------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                               |
+-------------+-----------------------------------------------------------------------------------------------+
| Examples    | :ref:`dolibarr-structures-couldusearrayunique`, :ref:`openemr-structures-couldusearrayunique` |
+-------------+-----------------------------------------------------------------------------------------------+



.. _could-use-self:

Could Use self
##############


``self`` keyword refers to the current class, or any of its parents. Using it is just as fast as the full class name, it is as readable and it is will not be changed upon class or namespace change.

It is also routinely used in traits : there, ``self`` represents the class in which the trait is used, or the trait itself. 

.. code-block:: php

   <?php
   
   class x {
       const FOO = 1;
       
       public function bar() {
           return self::FOO;
   // same as return x::FOO;
       }
   }
   
   ?>


See also `Scope Resolution Operator (\:\:) <http://php.net/manual/en/language.oop5.paamayim-nekudotayim.php>`_.

+-------------+------------------------------------+
| Short name  | Classes/ShouldUseSelf              |
+-------------+------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Suggestions` |
+-------------+------------------------------------+
| Severity    | Minor                              |
+-------------+------------------------------------+
| Time To Fix | Instant (5 mins)                   |
+-------------+------------------------------------+



.. _could-use-str\_repeat():

Could Use str_repeat()
######################


Use `str_repeat() <http://www.php.net/str_repeat>`_ or `str_pad() <http://www.php.net/str_pad>`_ instead of making a loop.

Making a loop to repeat the same concatenation is actually much longer than using `str_repeat() <http://www.php.net/str_repeat>`_. As soon as the loop repeats more than twice, `str_repeat() <http://www.php.net/str_repeat>`_ is much faster. With arrays of 30, the difference is significant, though the whole operation is short by itself. 

.. code-block:: php

   <?php
   
   // This adds 7 'e' to $x
   $x .= str_repeat('e', 7);
   
   // This is the same as above, 
   for($a = 3; $a < 10; ++$a) {
       $x .= 'e';
   }
   
   // here, $default must contains 7 elements to be equivalent to the previous code
   foreach($default as $c) {
       $x .= 'e';
   }
   
   ?>

+-------------+------------------------------+
| Short name  | Structures/CouldUseStrrepeat |
+-------------+------------------------------+
| Themes      | :ref:`Analyze`               |
+-------------+------------------------------+
| Severity    | Minor                        |
+-------------+------------------------------+
| Time To Fix | Slow (1 hour)                |
+-------------+------------------------------+



.. _crc32()-might-be-negative:

Crc32() Might Be Negative
#########################


`crc32() <http://www.php.net/crc32>`_ may return a negative number, on 32 bits platforms.

According to the manual : Because PHP\'s integer type is signed many ``CRC32`` checksums will result in negative integers on 32 bits platforms. On 64 bits installations, all `crc32() <http://www.php.net/crc32>`_ results will be positive integers though.

.. code-block:: php

   <?php
   
   // display the checksum with %u, to make it unsigned
   echo sprintf('%u', crc32($str));
   
   // turn the checksum into an unsigned hexadecimal
   echo dechex(crc32($str));
   
   // avoid concatenating crc32 to a string, as it may be negative on 32bits platforms 
   echo 'prefix'.crc32($str);
   
   ?>


See also `crc32() <http://www.php.net/crc32>`_.

+-------------+--------------------------+
| Short name  | Php/Crc32MightBeNegative |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Major                    |
+-------------+--------------------------+
| Time To Fix | Slow (1 hour)            |
+-------------+--------------------------+



.. _curly-arrays:

Curly Arrays
############


PHP supports the curly brackets for arrays.

It is possible to access individual elements in an array by using its offset between square brackets [] or curly brackets {}. 

.. code-block:: php

   <?php
   
   $array = ['a', 'b', 'c', 'd', 'e'];
   
   print $array[2]; // displays 'b';
   print $array{3}; // displays 'c';
   
   
   ?>


Curly brackets are seldom used, and will probably confuse or surprise the reader. It is recommended not to used them.

See also `Array <http://php.net/manual/en/language.types.array.php>`_.

+-------------+------------------------------------------------+
| Short name  | Arrays/CurlyArrays                             |
+-------------+------------------------------------------------+
| Themes      | :ref:`Coding Conventions <coding-conventions>` |
+-------------+------------------------------------------------+
| Severity    | Minor                                          |
+-------------+------------------------------------------------+
| Time To Fix | Instant (5 mins)                               |
+-------------+------------------------------------------------+



.. _dangling-array-references:

Dangling Array References
#########################


Always unset a referenced-variable used in a loop.

It is highly recommended to unset blind variables when they are set up as references after a loop. 

.. code-block:: php

   <?php
   
   $array = array(1,2,3,4);
   
   foreach($array as &$a) {
       $a += 1;
   }
   // This only unset the reference, not the value
   unset($a);
   
   
   
   
   // Dangling array problem
   foreach($array as &$a) {
       $a += 1;
   }
   //$array === array(3,4,5,6);
   
   // This does nothing (apparently)
   // $a is already a reference, even if it doesn't show here.
   foreach($array as $a) {}
   //$array === array(3,4,5,5);
   
   ?>


When omitting this step, the next loop that will also require this variable will deal with garbage values, and produce unexpected results.

See also : `No Dangling Reference <https://github.com/dseguy/clearPHP/blob/master/rules/no-dangling-reference.md>`_, 
           `PHP foreach pass-by-reference: Do it right, or better not at all <https://coderwall.com/p/qx3fpa/php-foreach-pass-by-reference-do-it-right-or-better-not-at-all>`_,
           `How does PHP 'foreach' actually work? <https://stackoverflow.com/questions/10057671/how-does-php-foreach-actually-work/14854568#14854568>`_,
           `References and foreach <https://schlueters.de/blog/archives/141-references-and-foreach.html>`_.


Suggestions
^^^^^^^^^^^

* Avoid using the reference altogether : sometimes, the reference is not needed.
* Add unset() right after the loop, to avoid reusing the reference.

+-------------+-----------------------------------------------------------------------------------------------------------+
| Short name  | Structures/DanglingArrayReferences                                                                        |
+-------------+-----------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                            |
+-------------+-----------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                     |
+-------------+-----------------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                          |
+-------------+-----------------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-dangling-reference <https://github.com/dseguy/clearPHP/tree/master/rules/no-dangling-reference.md>`__ |
+-------------+-----------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`typo3-structures-danglingarrayreferences`, :ref:`sugarcrm-structures-danglingarrayreferences`       |
+-------------+-----------------------------------------------------------------------------------------------------------+



.. _deep-definitions:

Deep Definitions
################


Structures, such as functions, classes, interfaces, traits, etc. may be defined anywhere in the code, including inside functions. This is legit code for PHP. 

Since the availability of ``__autoload``, there is no need for that kind of code. Structures should be defined, and accessible to the autoloading. Inclusion and deep definitions should be avoided, as they compel code to load some definitions, while autoloading will only load them if needed. 

.. code-block:: php

   <?php
   
   class X {
       function init() {
           // myFunction is defined when and only if X::init() is called.
           if (!function_exists('myFunction'){
               function myFunction($a) {
                   return $a + 1;
               }
           })
       }
   }
   
   ?>


Functions are excluded from autoload, but shall be gathered in libraries, and not hidden inside other code.

Constants definitions are tolerated inside functions : they may be used for avoiding repeat, or noting the usage of such function. 

See also `Autoloading Classe <http://php.net/manual/en/language.oop5.autoload.php>`_.

+-------------+------------------------------------------+
| Short name  | Functions/DeepDefinitions                |
+-------------+------------------------------------------+
| Themes      | :ref:`Analyze`                           |
+-------------+------------------------------------------+
| Severity    | Major                                    |
+-------------+------------------------------------------+
| Time To Fix | Slow (1 hour)                            |
+-------------+------------------------------------------+
| Examples    | :ref:`dolphin-functions-deepdefinitions` |
+-------------+------------------------------------------+



.. _define-with-array:

Define With Array
#################


PHP 7.0 has the ability to define an array as a constant, using the `define() <http://www.php.net/define>`_ native call. This was not possible until that version, only with the const keyword.

.. code-block:: php

   <?php
   
   //Defining an array as a constant
   define('MY_PRIMES', [2, 3, 5, 7, 11]);
   
   ?>

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Php/DefineWithArray                                                                                        |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and more recent                                                                               |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Critical                                                                                                   |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                              |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _dependant-trait:

Dependant Trait
###############


Traits should be autonomous. It is recommended to avoid depending on methods or properties that should be in the using class.

The following traits make usage of methods and properties, static or not, that are not defined in the trait. This means the host class must provide those methods and properties, but there is no way to enforce this. 

This may also lead to dead code : when the trait is removed, the host class have unused properties and methods.

.. code-block:: php

   <?php
   
   // autonomous trait : all it needs is within the trait
   trait t {
       private $p = 0;
       
       function foo() {
           return ++$this->p;
       }
   }
   
   // dependant trait : the host class needs to provide some properties or methods
   trait t2 {
       function foo() {
           return ++$this->p;
       }
   }
   
   class x {
       use t2;
       
       private $p = 0;
   }
   ?>

+-------------+-----------------------+
| Short name  | Traits/DependantTrait |
+-------------+-----------------------+
| Themes      | :ref:`Analyze`        |
+-------------+-----------------------+
| Severity    | Minor                 |
+-------------+-----------------------+
| Time To Fix | Slow (1 hour)         |
+-------------+-----------------------+



.. _deprecated-functions:

Deprecated Functions
####################


The following functions are deprecated. It is recommended to stop using them now and replace them with a durable equivalent. 

Note that these functions may be still usable : they generate warning that help tracking their usage in the log. To eradicate their usage, watch the logs, and update any deprecated warning. This way, the code won't be stuck when the function is actually removed from PHP.

.. code-block:: php

   <?php
   
   // This is the current function
   list($day, $month, $year) = explode('/', '08/06/1995');
   
   // This is deprecated
   list($day, $month, $year) = split('/', '08/06/1995');
   
   ?>

+-------------+-------------------------------------------------------------------------------------------+
| Short name  | Php/Deprecated                                                                            |
+-------------+-------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                            |
+-------------+-------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                     |
+-------------+-------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                           |
+-------------+-------------------------------------------------------------------------------------------+
| ClearPHP    | `no-deprecated <https://github.com/dseguy/clearPHP/tree/master/rules/no-deprecated.md>`__ |
+-------------+-------------------------------------------------------------------------------------------+



.. _dereferencing-string-and-arrays:

Dereferencing String And Arrays
###############################


PHP allows the direct dereferencing of strings and arrays. 

This was added in PHP 5.5. There is no need anymore for an intermediate variable between a string and array (or any expression generating such value) and accessing an index.

.. code-block:: php

   <?php
   $x = array(4,5,6); 
   $y = $x[2] ; // is 6
   
   May be replaced by 
   $y = array(4,5,6)[2];
   $y = [4,5,6][2];
   ?>

+-------------+------------------------------------------------------+
| Short name  | Structures/DereferencingAS                           |
+-------------+------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54` |
+-------------+------------------------------------------------------+
| Php Version | With PHP 5.3 and older                               |
+-------------+------------------------------------------------------+
| Severity    | Major                                                |
+-------------+------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                      |
+-------------+------------------------------------------------------+



.. _detect-current-class:

Detect Current Class
####################


Detecting the current class should be done with \:\:class operator.

`__CLASS__ <http://php.net/manual/en/language.constants.predefined.php>`_ may be replaced by ``self\:\:class``. 
get_called_class() may be replaced by ``static\:\:class``. 

`__CLASS__ <http://php.net/manual/en/language.constants.predefined.php>`_ and get_called_class() are set to be deprecated in PHP 7.4. 

.. code-block:: php

   <?php
   
   class X {
       function foo() {
           echo __CLASS__.\n;          // X
           echo self::class.\n;        // X
           
           echo get_called_class().\n;  // Y
           echo static::class.\n;       // Y
       }
   }
   
   class Y extends X {}
   
   $y = new Y();
   $y->foo();
   
   ?>


See also `PHP RFC: Deprecations for PHP 7.4 <https://wiki.php.net/rfc/deprecations_php_7_4>`_.

+-------------+-----------------------------------------------+
| Short name  | Php/DetectCurrentClass                        |
+-------------+-----------------------------------------------+
| Themes      | :ref:`Suggestions`, :ref:`CompatibilityPHP74` |
+-------------+-----------------------------------------------+
| Php Version | With PHP 8.0 and older                        |
+-------------+-----------------------------------------------+



.. _direct-call-to-\_\_clone:

Direct Call To __clone
######################


Direct call to magic method `__clone() <http://php.net/manual/en/language.oop5.magic.php>`_ was forbidden. It is allowed since PHP 7.0. 

From the RFC : `Doing calls like $obj->`__clone( <http://php.net/manual/en/language.oop5.magic.php>`_ ) is now allowed. This was the only magic method that had a compile-time check preventing some calls to it, which doesn't make sense. If we allow all other magic methods to be called, there's no reason to forbid this one`.

.. code-block:: php

   <?php
   
       class Foo {
           function __clone() {}
       }
       
       $a = new Foo;
       $a->__clone();
   ?>


See also `Directly calling `__clone <http://php.net/manual/en/language.oop5.magic.php>`_ is allowed <https://wiki.php.net/rfc/abstract_syntax_tree#directly_calling_clone_is_allowed>`_.

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Php/DirectCallToClone                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and more recent                                                                               |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Critical                                                                                                   |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                              |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _direct-injection:

Direct Injection
################


The following code act directly upon PHP incoming variables like ``$_GET`` and ``$_POST``. This makes those snippets very unsafe.

.. code-block:: php

   <?php
   
   // Direct injection
   echo Hello.$_GET['user']., welcome.;
   
   // less direct injection
   foo($_GET['user']);
   function foo($user) {
       echo Hello.$user., welcome.;
   }
   
   ?>


See also `Cross-Site Scripting (XSS) <https://phpsecurity.readthedocs.io/en/latest/Cross-Site-Scripting-(XSS).html>`_



Suggestions
^^^^^^^^^^^

* Validate input : make sure the incoming data are what you expect from them.
* Escape output : prepare outgoing data for the next system to use.

+-------------+--------------------------+
| Short name  | Security/DirectInjection |
+-------------+--------------------------+
| Themes      | :ref:`Security`          |
+-------------+--------------------------+
| Severity    | Major                    |
+-------------+--------------------------+
| Time To Fix | Quick (30 mins)          |
+-------------+--------------------------+



.. _directly-use-file:

Directly Use File
#################


Some PHP functions have a close cousin that work directly on files : use them. This is faster and less code to write.

* `md5() <http://www.php.net/md5>`_ => `md5_file() <http://www.php.net/md5_file>`_
* `highlight_string() <http://www.php.net/highlight_string>`_ => `highlight_file() <http://www.php.net/highlight_file>`_, `show_source() <http://www.php.net/show_source>`_
* parsekit_compile_string() => parsekit_compile_file()
* `parse_ini_string() <http://www.php.net/parse_ini_string>`_ => `parse_ini_file() <http://www.php.net/parse_ini_file>`_
* `sha1() <http://www.php.net/sha1>`_ => `sha1_file() <http://www.php.net/sha1_file>`_
* simplexml_load_string() => simplexml_load_file()
* yaml_parse() => yaml_parse_file()
* hash() => hash_file()
* hash_hmac() => hash_mac_file()
* hash_update() => hash_update_file()
* recode() => recode_file()
* recode_string() => recode_file()


.. code-block:: php

   <?php
   
   // Good way
   $file_hash = hash_file('sha512', 'example.txt');
   
   // Slow way
   $file_hash = hash('sha512', file_get_contents('example.txt'));
   
   ?>


See also `hash_file <http://php.net/manual/en/function.hash-file.php>`_. 


Suggestions
^^^^^^^^^^^

* Use the _file() version of those functions

+-------------+----------------------------+
| Short name  | Structures/DirectlyUseFile |
+-------------+----------------------------+
| Themes      | :ref:`Suggestions`         |
+-------------+----------------------------+
| Severity    | Minor                      |
+-------------+----------------------------+
| Time To Fix | Instant (5 mins)           |
+-------------+----------------------------+



.. _do-in-base:

Do In Base
##########


Use SQL expression to compute aggregates. 

.. code-block:: php

   <?php
   
   // Efficient way
   $res = $db->query('SELECT sum(e) AS sumE FROM table WHERE condition');
   
   // The sum is already done
   $row = $res->fetchArray();
   $c += $row['sumE'];
   
   // Slow way
   $res = $db->query('SELECT e FROM table WHERE condition');
   
   // This aggregates the column e in a slow way
   while($row = $res->fetchArray()) { 
       $c += $row['e'];
   }
   
   ?>

+-------------+-----------------------+
| Short name  | Performances/DoInBase |
+-------------+-----------------------+
| Themes      | :ref:`Performances`   |
+-------------+-----------------------+
| Severity    | Major                 |
+-------------+-----------------------+
| Time To Fix | Quick (30 mins)       |
+-------------+-----------------------+



.. _don't-change-incomings:

Don't Change Incomings
######################


PHP hands over a lot of information using special variables like `$_GET <http://php.net/manual/en/reserved.variables.get.php>`_, `$_POST <http://php.net/manual/en/reserved.variables.post.php>`_, etc... Modifying those variables and those values inside variables means that the original content is lost, while it will still look like raw data, and, as such, will be untrustworthy.

.. code-block:: php

   <?php
   
   // filtering and keeping the incoming value. 
   $_DATA'id'] = (int) $_GET['id'];
   
   // filtering and changing the incoming value. 
   $_GET['id'] = strtolower($_GET['id']);
   
   ?>


It is recommended to put the modified values in another variable, and keep the original one intact.

+-------------+--------------------------------------+
| Short name  | Structures/NoChangeIncomingVariables |
+-------------+--------------------------------------+
| Themes      | :ref:`Analyze`                       |
+-------------+--------------------------------------+
| Severity    | Minor                                |
+-------------+--------------------------------------+
| Time To Fix | Slow (1 hour)                        |
+-------------+--------------------------------------+



.. _don't-echo-error:

Don't Echo Error
################


It is recommended to avoid displaying error messages directly to the browser.

PHP's uses the ``display_errors`` directive to control display of errors to the browser. This must be kept to ``off`` when in production.

.. code-block:: php

   <?php
   
   // Inside a 'or' test
   mysql_connect('localhost', $user, $pass) or die(mysql_error());
   
   // Inside a if test
   $result = pg_query( $db, $query );
   if( !$result )
   {
   	echo Erreur SQL: . pg_error();
   	exit;
   }
   
   // Changing PHP configuration
   ini_set('display_errors', 1);
   // This is also a security error : 'false' means actually true.
   ini_set('display_errors', 'false');
   
   ?>


Error messages should be logged, but not displayed. 

See also `Error reporting <https://php.earth/docs/security/intro#error-reporting>`_ and 
         `List of php.ini directives <http://php.net/manual/en/ini.list.php>`_.

+-------------+--------------------------------------------------------------------------------------+
| Short name  | Security/DontEchoError                                                               |
+-------------+--------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Security`                                                      |
+-------------+--------------------------------------------------------------------------------------+
| Severity    | Critical                                                                             |
+-------------+--------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                     |
+-------------+--------------------------------------------------------------------------------------+
| Examples    | :ref:`churchcrm-security-dontechoerror`, :ref:`phpdocumentor-security-dontechoerror` |
+-------------+--------------------------------------------------------------------------------------+



.. _don't-loop-on-yield:

Don't Loop On Yield
###################


Use ``yield from``, instead of looping on a generator with ``yield``.

``yield from`` delegate the yielding to another generator, and keep calling that generator until it is finished. It also works with implicit generator datastructure, like arrays.

.. code-block:: php

   <?php
   
   function generator() {
       for($i = 0; $i < 10; ++$i) {
           yield $i;
       }
   }
   
   function delegatingGenerator() {
       yield from generator();
   }
   
   // Too much code here
   function generator2() {
       foreach(generator() as $g) {
           yield $g;
       }
   }
   
   ?>


There is a performance gain when delegating, over looping manually on the generator.

See also `Generator delegation via yield from <http://php.net/manual/en/language.generators.syntax.php#control-structures.yield.from>`_.


Suggestions
^^^^^^^^^^^

* Use `yield from` instead of the whole foreach() loop

+-------------+----------------------------+
| Short name  | Structures/DontLoopOnYield |
+-------------+----------------------------+
| Themes      | :ref:`Suggestions`         |
+-------------+----------------------------+
| Severity    | Minor                      |
+-------------+----------------------------+
| Time To Fix | Quick (30 mins)            |
+-------------+----------------------------+



.. _don't-read-and-write-in-one-expression:

Don't Read And Write In One Expression
######################################


Avoid giving value and using it at the same time, in one expression. This is an undefined behavior of PHP, and may change without warning.

One of those changes happens between PHP 7.2 and 7.3 : 

.. code-block:: php

   <?php
   
   $arr = [1];
   $ref =& $arr[0];
   var_dump($arr[0] + ($arr[0] = 2));
   // PHP 7.2: int(4)
   // PHP 7.3: int(3)
   
   ?>


See also `UPGRADING 7.3 <https://github.com/php/php-src/blob/PHP-7.3/UPGRADING#L83-L95>`_.


Suggestions
^^^^^^^^^^^

* Split the expression in two separate expressions

+-------------+--------------------------------------------+
| Short name  | Structures/DontReadAndWriteInOneExpression |
+-------------+--------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`CompatibilityPHP73`  |
+-------------+--------------------------------------------+
| Severity    | Critical                                   |
+-------------+--------------------------------------------+
| Time To Fix | Quick (30 mins)                            |
+-------------+--------------------------------------------+



.. _don't-send-$this-in-constructor:

Don't Send $this In Constructor
###############################


Don't use ``$this`` as an argument while in the `__construct() <http://php.net/manual/en/language.oop5.decon.php>`_. Until the constructor is finished, the object is not finished, and may be in an unstable state. Providing it to another code may lead to error. 

This is true when the receiving structure puts the incoming object immediately to work, and don't store it for later use. 

.. code-block:: php

   <?php
   
   // $this is only provided when Foo is constructed
   class Foo {
       private $bar = null;
       private $data = array();
       
       static public function build($data) {
           $foo = new Foo($data);
           // Can't build in one call. Must make it separate.
           $foo->finalize();
       }
   
       private function __construct($data) {
           // $this is provided too early
           $this->data = $data;
       }
       
       function finalize() {
           $this->bar = new Bar($this);
       }
   }
   
   // $this is provided too early, leading to error in Bar
   class Foo2 extends Foo {
       private $bar = null;
       private $data = array();
       
       function __construct($data) {
           // $this is provided too early
           $this->bar = new Bar($this);
           $this->data = $data;
       }
   }
   
   class Bar {
       function __construct(Foo $foo) {
           // the cache is now initialized with a wrong 
           $this->cache = $foo->getIt();
       }
   }
   
   ?>


See also `Don't pass this out of a constructor <http://www.javapractices.com/topic/TopicAction.do?Id=252>`_.



Suggestions
^^^^^^^^^^^

* Finish the constructor first, then call an external object.
* Sending $this should be made accessible in a separate method, so external objects may call it.
* Sending the current may be the responsibility of the method creating the object.

+-------------+-------------------------------------------------------------------------------------------------------+
| Short name  | Classes/DontSendThisInConstructor                                                                     |
+-------------+-------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                        |
+-------------+-------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                 |
+-------------+-------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                         |
+-------------+-------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`woocommerce-classes-dontsendthisinconstructor`, :ref:`contao-classes-dontsendthisinconstructor` |
+-------------+-------------------------------------------------------------------------------------------------------+



.. _don't-unset-properties:

Don't Unset Properties
######################


Avoid unsetting properties. They would go undefined, and raise more warnings. 

When getting rid of a property, assign it to null. This keeps the property in the object, yet allows existence check without errors.

.. code-block:: php

   <?php
   
   class Foo {
       public $a = 1;
   }
   
   $a = new Foo();
   
   var_dump((array) $a) ;
   // la propriété est reportée, et null
   // ['a' => null]
   
   unset($a->a);
   
   var_dump((array) $a) ;
   //Empty []
   
   // Check if a property exists
   var_dump($a->b === null);
   
   // Same result as above, but with a warning
   var_dump($a->c === null);
   
   ?>


This analysis works on properties and static properties. It also reports magic properties being unset.

Thanks for `Benoit Burnichon <https://twitter.com/BenoitBurnichon>`_ for the original idea.



Suggestions
^^^^^^^^^^^

* Never unset properties : set it to null or its default value instead
* Make the property an array, and set/unset its index

+-------------+--------------------------------------------------------------------------------------+
| Short name  | Classes/DontUnsetProperties                                                          |
+-------------+--------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                       |
+-------------+--------------------------------------------------------------------------------------+
| Severity    | Major                                                                                |
+-------------+--------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                        |
+-------------+--------------------------------------------------------------------------------------+
| Examples    | :ref:`vanilla-classes-dontunsetproperties`, :ref:`typo3-classes-dontunsetproperties` |
+-------------+--------------------------------------------------------------------------------------+



.. _dont-change-the-blind-var:

Dont Change The Blind Var
#########################


When using a `foreach() <http://php.net/manual/en/control-structures.foreach.php>`_, the blind variables hold a copy of the original value. It is confusing to modify them, as it seems that the original value may be changed.

When actually changing the original value, use the reference in the foreach definition to make it obvious, and save the final reassignation.

When the value has to be prepared before usage, then save the filtered value in a separate variable. This makes the clean value obvious, and preserve the original value for a future usage.

.. code-block:: php

   <?php
   
   // $bar is duplicated and kept 
   $foo = [1, 2, 3];
   foreach($foo as $bar) {
       // $bar is updated but its original value is kept
       $nextBar = $bar + 1;
       print $bar . ' => ' . ($nextBar) . PHP_EOL;
       foobar($nextBar);
   }
   
   // $bar is updated and lost
   $foo = [1, 2, 3];
   foreach($foo as $bar) {
       // $bar is updated but its final value is lost
       print $bar . ' => ' . (++$bar) . PHP_EOL;
       // Now that $bar is reused, it is easy to confuse its value
       foobar($bar);
   }
   
   // $bar is updated and kept
   $foo = [1, 2, 3];
   foreach($foo as &$bar) {
       // $bar is updated and keept
       print $bar . ' => ' . (++$bar) . PHP_EOL;
       foobar($bar);
   }
   
   ?>

+-------------+-------------------------------+
| Short name  | Structures/DontChangeBlindKey |
+-------------+-------------------------------+
| Themes      | :ref:`Analyze`                |
+-------------+-------------------------------+
| Severity    | Minor                         |
+-------------+-------------------------------+
| Time To Fix | Quick (30 mins)               |
+-------------+-------------------------------+



.. _dont-mix-++:

Dont Mix ++
###########


++ operators have two distinct behaviors, and should be used in isolation.

When mixed with a larger expression, it is difficult to read, and may lead to unwanted behaviors.

.. code-block:: php

   <?php
   
       // Clear and defined behavior
       $i++;
       $a[$i] = $i;
   
       // $i is modified twice 
       $i = --$i + 1; 
   ?>


See also `EXP30-C. Do not depend on the order of evaluation for side effects <https://wiki.sei.cmu.edu/confluence/display/c/EXP30-C.+Do+not+depend+on+the+order+of+evaluation+for+side+effects>`_.

+-------------+----------------------------+
| Short name  | Structures/DontMixPlusPlus |
+-------------+----------------------------+
| Themes      | :ref:`Analyze`             |
+-------------+----------------------------+
| Severity    | Minor                      |
+-------------+----------------------------+
| Time To Fix | Instant (5 mins)           |
+-------------+----------------------------+



.. _double-assignation:

Double Assignation
##################


This happens when a container (variable, property, array index) is assigned with values twice in a row. One of them is probably a debug instruction, that was forgotten. 

.. code-block:: php

   <?php
   
   // Normal assignation
   $a = 1;
   
   // Double assignation
   $b = 2;
   $b = 3;
   
   ?>

+-------------+------------------------------+
| Short name  | Structures/DoubleAssignation |
+-------------+------------------------------+
| Themes      | :ref:`Analyze`               |
+-------------+------------------------------+
| Severity    | Major                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _double-instructions:

Double Instructions
###################


Twice the same call in a row. This is worth a check.

.. code-block:: php

   <?php
   
   ?>

+-------------+------------------------------+
| Short name  | Structures/DoubleInstruction |
+-------------+------------------------------+
| Themes      | :ref:`Analyze`               |
+-------------+------------------------------+
| Severity    | Minor                        |
+-------------+------------------------------+
| Time To Fix | Instant (5 mins)             |
+-------------+------------------------------+



.. _double-array\_flip():

Double array_flip()
###################


Avoid double `array_flip() <http://www.php.net/array_flip>`_ to gain speed. While `array_flip() <http://www.php.net/array_flip>`_ alone is usually useful, a double `array_flip() <http://www.php.net/array_flip>`_ usually is made to handle values and keys. 

.. code-block:: php

   <?php
   
   // without array_flip
   function foo($array, $value) {
       $key = array_search($array, $value);
       
       if ($key !== false) {
           unset($array[$key]);
       }
       
       return $array;
   }
   
   // double array_flip
   // array_flip() usage means that $array's values are all unique
   function foo($array, $value) {
       $flipped = array_flip($value);
       unset($flipped[$value]);
       return array_flip($flipped);
   }
   
   ?>

+-------------+-----------------------------------------------+
| Short name  | Performances/DoubleArrayFlip                  |
+-------------+-----------------------------------------------+
| Themes      | :ref:`Performances`                           |
+-------------+-----------------------------------------------+
| Severity    | Major                                         |
+-------------+-----------------------------------------------+
| Time To Fix | Quick (30 mins)                               |
+-------------+-----------------------------------------------+
| Examples    | :ref:`nextcloud-performances-doublearrayflip` |
+-------------+-----------------------------------------------+



.. _drop-else-after-return:

Drop Else After Return
######################


Avoid else clause when the then clause returns, but not the else. And vice-versa.

This way, the else block disappears, and is now the main sequence of the function. 

This is also true if else has a return, and then not. When doing so, don't forget to reverse the condition. 

.. code-block:: php

   <?php
   
   // drop the else
   if ($a) {
       return $a;
   } else {
       doSomething();
   }
   
   // drop the then
   if ($b) {
       doSomething();
   } else {
       return $a;
   }
   
   // return in else and then
   if ($a3) {
       return $a;
   } else {
       $b = doSomething();
       return $b;
   }
   
   ?>

+-------------+------------------------------------+
| Short name  | Structures/DropElseAfterReturn     |
+-------------+------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Suggestions` |
+-------------+------------------------------------+
| Severity    | Minor                              |
+-------------+------------------------------------+
| Time To Fix | Quick (30 mins)                    |
+-------------+------------------------------------+



.. _drop-substr-last-arg:

Drop Substr Last Arg
####################


Substr() works till the end of the string when the last argument is omitted. There is no need to calculate string size to make this work.

.. code-block:: php

   <?php
   
   $string = 'abcdef';
   
   // Extract the end of the string
   $cde = substr($string, 2);
   
   // Too much work
   $cde = substr($string, 2, strlen($string));
   
   ?>


See also `substr <http://www.php.net/substr>`_.


Suggestions
^^^^^^^^^^^

* Use negative length
* Omit the last argument to get the string till its end

+-------------+----------------------------------------------------------------------------------+
| Short name  | Structures/SubstrLastArg                                                         |
+-------------+----------------------------------------------------------------------------------+
| Themes      | :ref:`Suggestions`                                                               |
+-------------+----------------------------------------------------------------------------------+
| Severity    | Minor                                                                            |
+-------------+----------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                  |
+-------------+----------------------------------------------------------------------------------+
| Examples    | :ref:`suitecrm-structures-substrlastarg`, :ref:`tine20-structures-substrlastarg` |
+-------------+----------------------------------------------------------------------------------+



.. _dynamic-library-loading:

Dynamic Library Loading
#######################


Loading a variable dynamically requires a lot of care in the preparation of the library name. 

In case of injection in the variable, the dynamic loading of a library gives a lot of power to an intruder. 

.. code-block:: php

   <?php
   
       // dynamically loading a library
   	dl($library. PHP_SHLIB_SUFFIX);
   
       // dynamically loading ext/vips
   	dl('vips.' . PHP_SHLIB_SUFFIX);
   
   ?>


See also `dl <http://www.php.net/dl>`_.

+-------------+--------------------+
| Short name  | Security/DynamicDl |
+-------------+--------------------+
| Themes      | :ref:`Security`    |
+-------------+--------------------+
| Severity    | Major              |
+-------------+--------------------+
| Time To Fix | Slow (1 hour)      |
+-------------+--------------------+



.. _echo-or-print:

Echo Or Print
#############


Echo and print have the same functional use. <?= and `printf() <http://www.php.net/printf>`_ are also considered in this analysis. 

There seems to be a choice that is not enforced : one form is dominant, (> 90%) while the others are rare. 

The analyzed code has less than 10% of one of the three : for consistency reasons, it is recommended to make them all the same. 

It happens that print, echo or <?= are used depending on coding style and files. One file may be consistently using print, while the others are all using echo. 

.. code-block:: php

   <?php
   
   echo 'a';
   echo 'b';
   echo 'c';
   echo 'd';
   echo 'e';
   echo 'f';
   echo 'g';
   echo 'h';
   echo 'i';
   echo 'j';
   echo 'k';
   
   // This should probably be written 'echo';
   print 'l';
   
   ?>

+-------------+------------------------------------------------+
| Short name  | Structures/EchoPrintConsistance                |
+-------------+------------------------------------------------+
| Themes      | :ref:`Coding Conventions <coding-conventions>` |
+-------------+------------------------------------------------+
| Severity    | Minor                                          |
+-------------+------------------------------------------------+
| Time To Fix | Quick (30 mins)                                |
+-------------+------------------------------------------------+



.. _echo-with-concat:

Echo With Concat
################


Optimize your ``echo``'s by not concatenating at ``echo`` time, but serving all argument separated. This will save PHP a memory copy.

If values, literals and variables, are small enough, this won't have visible impact. Otherwise, this is less work and less memory waste.

.. code-block:: php

   <?php
     echo $a, ' b ', $c;
   ?>


instead of

.. code-block:: php

   <?php
     echo  $a . ' b ' . $c;
     echo $a b $c;
   ?>

+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Structures/EchoWithConcat                                                                                                             |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Performances`, :ref:`Analyze`, :ref:`Suggestions`                                                                               |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                                                 |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                                       |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-unnecessary-string-concatenation <https://github.com/dseguy/clearPHP/tree/master/rules/no-unnecessary-string-concatenation.md>`__ |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+



.. _ellipsis-usage:

Ellipsis Usage
##############


Usage of the ellipsis keyword. The keyword is three dots : `... <http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list>`_ . It is also named variadic or splat operator.

It may be in function definitions, either in functioncalls.

`... <http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list>`_ allows for packing or unpacking arguments into an array.

.. code-block:: php

   <?php
   
   $args = [1, 2, 3];
   foo(...$args); 
   // Identical to foo(1,2,3);
   
   function bar(...$a) {
       // Identical to : $a = func_get_args();
   }
   ?>


See also `PHP RFC: Syntax for variadic functions <https://wiki.php.net/rfc/variadics>`_,
         `PHP 5.6 and the Splat Operator <https://lornajane.net/posts/2014/php-5-6-and-the-splat-operator>`_, and
         `Variable-length argument lists <http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list>`_.

+-------------+---------------------------------------------------------------------------------+
| Short name  | Php/EllipsisUsage                                                               |
+-------------+---------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55` |
+-------------+---------------------------------------------------------------------------------+
| Php Version | With PHP 5.6 and more recent                                                    |
+-------------+---------------------------------------------------------------------------------+
| Severity    | Major                                                                           |
+-------------+---------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                   |
+-------------+---------------------------------------------------------------------------------+



.. _else-if-versus-elseif:

Else If Versus Elseif
#####################


Always use elseif instead of else and if. 

"The keyword elseif SHOULD be used instead of else if so that all control keywords look like single words". Quoted from the PHP-FIG documentation

.. code-block:: php

   <?php
   
   // Using elseif 
   if ($a == 1) { doSomething(); }
   elseif ($a == 2) { doSomethingElseIf(); }
   else { doSomethingElse(); }
   
   // Using else if 
   if ($a == 1) { doSomething(); }
   else if ($a == 2) { doSomethingElseIf(); }
   else { doSomethingElse(); }
   
   // Using else if, no {}
   if ($a == 1)  doSomething(); 
   else if ($a == 2) doSomethingElseIf(); 
   else  doSomethingElse(); 
   
   ?>

.

+-------------+-------------------------+
| Short name  | Structures/ElseIfElseif |
+-------------+-------------------------+
| Themes      | :ref:`Analyze`          |
+-------------+-------------------------+
| Severity    | Minor                   |
+-------------+-------------------------+
| Time To Fix | Quick (30 mins)         |
+-------------+-------------------------+



.. _empty-blocks:

Empty Blocks
############


Full empty block, part of a control structures. 

It is recommended to remove those blocks, so as to reduce confusion in the code. 

.. code-block:: php

   <?php
   
   foreach($foo as $bar) ; // This block seems erroneous
       $foobar++;
   
   if ($a === $b) {
       doSomething();
   } else {
       // Empty block. Remove this
   }
   
   // Blocks containing only empty expressions are also detected
   for($i = 0; $i < 10; $i++) {
       ;
   }
   
   // Although namespaces are not control structures, they are reported here
   namespace A;
   namespace B;
   
   ?>

+-------------+------------------------+
| Short name  | Structures/EmptyBlocks |
+-------------+------------------------+
| Themes      | :ref:`Analyze`         |
+-------------+------------------------+
| Severity    | Minor                  |
+-------------+------------------------+
| Time To Fix | Instant (5 mins)       |
+-------------+------------------------+



.. _empty-classes:

Empty Classes
#############


Classes that do no define anything at all. This is probably dead code.

Classes that are directly derived from an exception are omitted.

.. code-block:: php

   <?php
   
   //Empty class
   class foo extends bar {}
   
   //Not an empty class
   class foo2 extends bar {
       const FOO = 2;
   }
   
   //Not an empty class, as derived from Exception
   class barException extends \Exception {}
   
   ?>



Suggestions
^^^^^^^^^^^

* Remove an empty class :it is probably dead code.
* Add some code to the class to make it concrete.

+-------------+-------------------------------------+
| Short name  | Classes/EmptyClass                  |
+-------------+-------------------------------------+
| Themes      | :ref:`Analyze`                      |
+-------------+-------------------------------------+
| Severity    | Minor                               |
+-------------+-------------------------------------+
| Time To Fix | Quick (30 mins)                     |
+-------------+-------------------------------------+
| Examples    | :ref:`wordpress-classes-emptyclass` |
+-------------+-------------------------------------+



.. _empty-function:

Empty Function
##############


Function or method whose body is empty. 

Such functions or methods are rarely useful. As a bare minimum, the function should return some useful value, even if constant.

.. code-block:: php

   <?php
   
   // classic empty function
   function emptyFunction() {}
   
   class bar {
       // classic empty method
       function emptyMethod() {}
   
       // classic empty function
       function emptyMethodWithParent() {}
   }
   
   class barbar extends bar {
       // NOT an empty method : it overwrites the parent method
       function emptyMethodWithParent() {}
   }
   
   ?>

+-------------+-------------------------+
| Short name  | Functions/EmptyFunction |
+-------------+-------------------------+
| Themes      | :ref:`Analyze`          |
+-------------+-------------------------+
| Severity    | Minor                   |
+-------------+-------------------------+
| Time To Fix | Quick (30 mins)         |
+-------------+-------------------------+



.. _empty-instructions:

Empty Instructions
##################


Empty instructions are part of the code that have no instructions. 

This may be trailing semi-colon or empty blocks for if-then structures.

Comments that explains the reason of the situation are not taken into account.

.. code-block:: php

   <?php
       $condition = 3;;;;
       if ($condition) { } 
   ?>

+-------------+----------------------------------------------+
| Short name  | Structures/EmptyLines                        |
+-------------+----------------------------------------------+
| Themes      | :ref:`Dead code <dead-code>`, :ref:`Analyze` |
+-------------+----------------------------------------------+
| Severity    | Minor                                        |
+-------------+----------------------------------------------+
| Time To Fix | Instant (5 mins)                             |
+-------------+----------------------------------------------+



.. _empty-interfaces:

Empty Interfaces
################


Empty interfaces are a code smell. Interfaces should contains at least a method or a constant, and not be totally empty.

.. code-block:: php

   <?php
   
   // an empty interface
   interface empty {}
   
   // an normal interface
   interface normal {
       public function i() ;
   }
   
   // a constants interface
   interface constantsOnly {
       const FOO = 1;
   }
   
   ?>


See also `Empty interfaces are bad practice <https://r.je/empty-interfaces-bad-practice.html>`_ and `Blog : Are empty interfaces code smell? <https://hackernoon.com/are-interfaces-code-smell-bd19abc266d3>`_.

+-------------+---------------------------+
| Short name  | Interfaces/EmptyInterface |
+-------------+---------------------------+
| Themes      | :ref:`Analyze`            |
+-------------+---------------------------+
| Severity    | Minor                     |
+-------------+---------------------------+
| Time To Fix | Instant (5 mins)          |
+-------------+---------------------------+



.. _empty-list:

Empty List
##########


Empty list() are not allowed anymore in PHP 7. There must be at least one variable in the list call.

.. code-block:: php

   <?php
   
   //Not accepted since PHP 7.0
   list() = array(1,2,3);
   
   //Still valid PHP code
   list(,$x) = array(1,2,3);
   
   ?>

+-------------+-------------------------------------------+
| Short name  | Php/EmptyList                             |
+-------------+-------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`CompatibilityPHP70` |
+-------------+-------------------------------------------+
| Php Version | With PHP 7.0 and older                    |
+-------------+-------------------------------------------+
| Severity    | Major                                     |
+-------------+-------------------------------------------+
| Time To Fix | Quick (30 mins)                           |
+-------------+-------------------------------------------+



.. _empty-namespace:

Empty Namespace
###############


Declaring a namespace in the code and not using it for structure declarations or global instructions is useless.

Using simple style : 

.. code-block:: php

   <?php
   
   namespace Y;
   
   class foo {}
   
   
   namespace X;
   // This is useless
   
   ?>


Using bracket-style syntax : 

.. code-block:: php

   <?php
   
   namespace X {
       // This is useless
   }
   
   namespace Y {
   
       class foo {}
   
   }
   
   ?>

+-------------+-----------------------------------------------------------------------------------------------------+
| Short name  | Namespaces/EmptyNamespace                                                                           |
+-------------+-----------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Dead code <dead-code>`                                                        |
+-------------+-----------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                               |
+-------------+-----------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                    |
+-------------+-----------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-empty-namespace <https://github.com/dseguy/clearPHP/tree/master/rules/no-empty-namespace.md>`__ |
+-------------+-----------------------------------------------------------------------------------------------------+



.. _empty-slots-in-arrays:

Empty Slots In Arrays
#####################


PHP tolerates the last element of an array to be empty.

.. code-block:: php

   <?php
       $a = array( 1, 2, 3, );
       $b =      [ 4, 5, ];
   ?>

+-------------+------------------------------------------------+
| Short name  | Arrays/EmptySlots                              |
+-------------+------------------------------------------------+
| Themes      | :ref:`Coding Conventions <coding-conventions>` |
+-------------+------------------------------------------------+
| Severity    | Minor                                          |
+-------------+------------------------------------------------+
| Time To Fix | Instant (5 mins)                               |
+-------------+------------------------------------------------+



.. _empty-traits:

Empty Traits
############


List of all empty trait defined in the code. 

.. code-block:: php

   <?php
   
   // empty trait
   trait t { }
   
   // Another empty trait
   trait t2 {
       use t; 
   }
   
   ?>


Such traits may be reserved for future use. They may also be forgotten, and dead code.

+-------------+-------------------+
| Short name  | Traits/EmptyTrait |
+-------------+-------------------+
| Themes      | :ref:`Analyze`    |
+-------------+-------------------+
| Severity    | Minor             |
+-------------+-------------------+
| Time To Fix | Instant (5 mins)  |
+-------------+-------------------+



.. _empty-try-catch:

Empty Try Catch
###############


The code does try, then catch errors but do no act upon the error. 

.. code-block:: php

   <?php
   
   try { 
       doSomething();
   } catch (Throwable $e) {
       // ignore this
   }
   
   ?>


At worst, the error should be logged, so as to measure the actual usage of the catch expression.

``catch( Exception $e)`` (PHP 5) or ``catch(`Throwable <http://php.net/manual/en/class.throwable.php>`_ $e)`` with empty catch block should be banned. They ignore any error and proceed as if nothing happened. At worst, the event should be logged for future analysis. 

See also `Empty Catch Clause <http://wiki.c2.com/?EmptyCatchClause>`_.

+-------------+-----------------------------------------------------------------------------------+
| Short name  | Structures/EmptyTryCatch                                                          |
+-------------+-----------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                    |
+-------------+-----------------------------------------------------------------------------------+
| Severity    | Minor                                                                             |
+-------------+-----------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                   |
+-------------+-----------------------------------------------------------------------------------+
| Examples    | :ref:`livezilla-structures-emptytrycatch`, :ref:`mautic-structures-emptytrycatch` |
+-------------+-----------------------------------------------------------------------------------+



.. _empty-with-expression:

Empty With Expression
#####################


`empty() <http://www.php.net/empty>`_ doesn't accept expressions until PHP 5.5. Until then, it is necessary to store the result of the expression in a variable and then, test it with `empty() <http://www.php.net/empty>`_.

.. code-block:: php

   <?php
   
   // PHP 5.5+ empty() usage
   if (empty(strtolower($b . $c))) {
       doSomethingWithoutA();
   }
   
   // Compatible empty() usage
   $a = strtolower($b . $c);
   if (empty($a)) {
       doSomethingWithoutA();
   }
   
   ?>

+-------------+--------------------------------+
| Short name  | Structures/EmptyWithExpression |
+-------------+--------------------------------+
| Themes      | :ref:`Suggestions`             |
+-------------+--------------------------------+
| Php Version | With PHP 5.5 and more recent   |
+-------------+--------------------------------+
| Severity    | Major                          |
+-------------+--------------------------------+
| Time To Fix | Quick (30 mins)                |
+-------------+--------------------------------+



.. _encoded-simple-letters:

Encoded Simple Letters
######################


Some simple letters are written in escape sequence. 

Usually, escape sequences are made to encode unusual characters. Using escape sequences for simple characters, like letters or numbers is suspicious.

This analysis also detect unicode codepoint with superfluous leading zeros.

.. code-block:: php

   <?php
   
   // This escape sequence makes eval hard to spot
   $a = ev1l;
   $a('php_info();');
   
   // With a PHP 7.0 unicode code point sequence
   $a = ev\u{000041}l;
   $a('php_info();');
   
   // With a PHP 5.0+ hexadecimal sequence
   $a = ev\x41l;
   $a('php_info();');
   
   ?>

+-------------+-------------------------+
| Short name  | Security/EncodedLetters |
+-------------+-------------------------+
| Themes      | :ref:`Security`         |
+-------------+-------------------------+
| Severity    | Minor                   |
+-------------+-------------------------+
| Time To Fix | Quick (30 mins)         |
+-------------+-------------------------+



.. _eval()-usage:

Eval() Usage
############


Using `eval() <http://www.php.net/eval>`_ is evil. 

Using `eval() <http://www.php.net/eval>`_ is bad for performances (compilation time), for caches (it won't be compiled), and for security (if it includes external data).

.. code-block:: php

   <?php
       // Avoid using incoming data to build the eval() expression : any filtering error leads to PHP injection
       $mathExpression = $_GET['mathExpression']; 
       $mathExpression = preg_replace('#[^0-9+\-*/\(/)]#is', '', $mathExpression); // expecting 1+2
       $literalCode = '$a = '.$mathExpression.';';
       eval($literalCode);
       echo $a;
   
       // If eval'ed code is known at compile time, it is best to put it inline
       $literalCode = 'phpinfo();';
       eval($literalCode);
   
   ?>


Most of the time, it is possible to replace the code by some standard PHP, like variable variable for accessing a variable for which you have the name.
At worse, including a pregenerated file is faster and cacheable. 

There are several situations where `eval() <http://www.php.net/eval>`_ is actually the only solution : 

For PHP 7.0 and later, it is important to put `eval() <http://www.php.net/eval>`_ in a try..catch expression.

See also `eval <http://www.php.net/eval>`_ and 
         `The Land Where PHP  Uses `eval() <http://www.php.net/eval>`_ <https://www.exakat.io/land-where-php-uses-eval/>`_.


Suggestions
^^^^^^^^^^^

* Use a dynamic feature of PHP to replace the eval'ed code
* Store the code on the disk, and use include
* Replace create_function() with a closure!

+-------------+-------------------------------------------------------------------------------+
| Short name  | Structures/EvalUsage                                                          |
+-------------+-------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Performances`, :ref:`Security`                          |
+-------------+-------------------------------------------------------------------------------+
| Severity    | Major                                                                         |
+-------------+-------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                               |
+-------------+-------------------------------------------------------------------------------+
| ClearPHP    | `no-eval <https://github.com/dseguy/clearPHP/tree/master/rules/no-eval.md>`__ |
+-------------+-------------------------------------------------------------------------------+
| Examples    | :ref:`xoops-structures-evalusage`, :ref:`mautic-structures-evalusage`         |
+-------------+-------------------------------------------------------------------------------+



.. _exception-order:

Exception Order
###############


When catching exception, the most specialized exceptions must be in the early catch, and the most general exceptions must be in the later catch. Otherwise, the general catches intercept the exception, and the more specialized will not be read.

.. code-block:: php

   <?php
   
   class A extends \Exception {}
   class B extends A {}
   
   try {
       throw new A();
   } 
   catch(A $a1) { }
   catch(B $b2 ) { 
       // Never reached, as previous Catch is catching the early worm
   }
   
   ?>

+------------+---------------------------------------------+
| Short name | Exceptions/AlreadyCaught                    |
+------------+---------------------------------------------+
| Themes     | :ref:`Dead code <dead-code>`                |
+------------+---------------------------------------------+
| Examples   | :ref:`woocommerce-exceptions-alreadycaught` |
+------------+---------------------------------------------+



.. _exit()-usage:

Exit() Usage
############


Using `exit <http://www.php.net/exit>`_ or `die() <http://www.php.net/die>`_ in the code makes the code untestable (it will `break <http://php.net/manual/en/control-structures.break.php>`_ unit tests). Moreover, if there is no reason or string to display, it may take a long time to spot where the application is stuck. 

.. code-block:: php

   <?php
   
   // Throw an exception, that may be caught somewhere
   throw new \Exception('error');
   
   // Dying with error message. 
   die('error');
   
   function foo() {
       //exiting the function but not dying
       if (somethingWrong()) {
           return true;
       }
   }
   ?>


Try exiting the function/class with return, or throw exception that may be caught later in the code.

+-------------+-------------------------------------------------------------------------------+
| Short name  | Structures/ExitUsage                                                          |
+-------------+-------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                |
+-------------+-------------------------------------------------------------------------------+
| Severity    | Major                                                                         |
+-------------+-------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                               |
+-------------+-------------------------------------------------------------------------------+
| ClearPHP    | `no-exit <https://github.com/dseguy/clearPHP/tree/master/rules/no-exit.md>`__ |
+-------------+-------------------------------------------------------------------------------+



.. _exponent-usage:

Exponent Usage
##############


Usage of the `** <http://php.net/manual/en/language.operators.arithmetic.php>`_ operator or \*\*\=, to make exponents.

.. code-block:: php

   <?php
   
   $eight = 2 ** 3;
   
   $sixteen = 4;
   $sixteen \*\*\= 2;
   
   ?>


See also `Arithmetic Operators <http://php.net/manual/en/language.operators.arithmetic.php>`_.

+-------------+---------------------------------------------------------------------------------+
| Short name  | Php/ExponentUsage                                                               |
+-------------+---------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55` |
+-------------+---------------------------------------------------------------------------------+
| Php Version | With PHP 5.6 and more recent                                                    |
+-------------+---------------------------------------------------------------------------------+
| Severity    | Major                                                                           |
+-------------+---------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                |
+-------------+---------------------------------------------------------------------------------+



.. _failed-substr-comparison:

Failed Substr Comparison
########################


The extracted string must be of the size of the compared string.

This is also true for negative lengths.

.. code-block:: php

   <?php
   
   // Possible comparison
   if (substr($a, 0, 3) === 'abc') { }
   if (substr($b, 4, 3) === 'abc') { }
   
   // Always failing
   if (substr($a, 0, 3) === 'ab') { }
   if (substr($a, 3, -3) === 'ab') { }
   
   // Omitted in this analysis
   if (substr($a, 0, 3) !== 'ab') { }
   
   ?>

+-------------+------------------------------------------------------------------------------------------------------+
| Short name  | Structures/FailingSubstrComparison                                                                   |
+-------------+------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                       |
+-------------+------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                |
+-------------+------------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                     |
+-------------+------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`zurmo-structures-failingsubstrcomparison`, :ref:`mediawiki-structures-failingsubstrcomparison` |
+-------------+------------------------------------------------------------------------------------------------------+



.. _fetch-one-row-format:

Fetch One Row Format
####################


When reading results with ext/Sqlite3, it is recommended to explicitely request SQLITE3_NUM or SQLITE3_ASSOC, while avoiding the default value and SQLITE3_BOTH.

.. code-block:: php

   <?php
   
   $res = $database->query($query);
   
   // Fastest version, but less readable
   $row = $res->fetchArray(\SQLITE3_NUM);
   // Almost the fastest version, and more readable
   $row = $res->fetchArray(\SQLITE3_ASSOC);
   
   // Default version. Quite slow
   $row = $res->fetchArray();
   
   // Worse case
   $row = $res->fetchArray(\SQLITE3_BOTH);
   
   ?>


This is a micro-optimisation. The difference may be visible with 200k rows fetches, and measurable with 10k.

+-------------+--------------------------------+
| Short name  | Performances/FetchOneRowFormat |
+-------------+--------------------------------+
| Themes      | :ref:`Performances`            |
+-------------+--------------------------------+
| Severity    | Minor                          |
+-------------+--------------------------------+
| Time To Fix | Instant (5 mins)               |
+-------------+--------------------------------+



.. _final-class-usage:

Final Class Usage
#################


List of all final classes being used.

final may be applied to classes and methods.

.. code-block:: php

   <?php
   class BaseClass {
      public function test() {
          echo 'BaseClass::test() called'.PHP_EOL;
      }
      
      final public function moreTesting() {
          echo 'BaseClass::moreTesting() called'.PHP_EOL;
      }
   }
   
   class ChildClass extends BaseClass {
      public function moreTesting() {
          echo 'ChildClass::moreTesting() called'.PHP_EOL;
      }
   }
   // Results in Fatal error: Cannot override final method BaseClass::moreTesting()
   ?>


See also `Final Keyword <http://php.net/manual/en/language.oop5.final.php>`_.

+------------+--------------------------------------------+
| Short name | Classes/Finalclass                         |
+------------+--------------------------------------------+
| Themes     | :ref:`ClassReview`, :ref:`LintButWontExec` |
+------------+--------------------------------------------+



.. _final-methods-usage:

Final Methods Usage
###################


List of all final methods being used.

final may be applied to classes and methods.

.. code-block:: php

   <?php
   class BaseClass {
      public function test() {
          echo 'BaseClass::test() called'.PHP_EOL;
      }
      
      final public function moreTesting() {
          echo 'BaseClass::moreTesting() called'.PHP_EOL;
      }
   }
   
   class ChildClass extends BaseClass {
      public function moreTesting() {
          echo 'ChildClass::moreTesting() called'.PHP_EOL;
      }
   }
   // Results in Fatal error: Cannot override final method BaseClass::moreTesting()
   ?>


See also `Final Keyword <http://php.net/manual/en/language.oop5.final.php>`_.

+------------+--------------------------------------------+
| Short name | Classes/Finalmethod                        |
+------------+--------------------------------------------+
| Themes     | :ref:`LintButWontExec`, :ref:`ClassReview` |
+------------+--------------------------------------------+



.. _find-key-directly:

Find Key Directly
#################


No need for a `foreach() <http://php.net/manual/en/control-structures.foreach.php>`_ to search for a key. 

PHP offers two solutions : `array_search() <http://www.php.net/array_search>`_ and `array_keys() <http://www.php.net/array_keys>`_. `Array_search() <http://www.php.net/array_search>`_ finds the first key that fits a value, and array_keys returns all the keys. 

.. code-block:: php

   <?php
   
   $array = ['a', 'b', 'c', 'd', 'e'];
   
   print array_search($array, 'c'); 
   // print 2 => 'c';
   
   print_r(array_keys($array, 'c')); 
   // print 2 => 'c';
   
   ?>


See also `array_search <http://php.net/array_search>`_ and `array_keys <http://php.net/array_keys>`_.

+-------------+----------------------------+
| Short name  | Structures/GoToKeyDirectly |
+-------------+----------------------------+
| Themes      | :ref:`Suggestions`         |
+-------------+----------------------------+
| Severity    | Major                      |
+-------------+----------------------------+
| Time To Fix | Instant (5 mins)           |
+-------------+----------------------------+



.. _flexible-heredoc:

Flexible Heredoc
################


Flexible syntax for Heredoc. 

The new flexible syntax for heredoc and nowdoc enable the closing marker to be indented, and remove the new line requirement after the closing marker.

It was introduced in PHP 7.3.

.. code-block:: php

   <?php
   
   // PHP 7.3 and newer
   foo($a = <<<END
       
       flexible syntax
       with extra indentation
       
       END);
       
   // All PHP versions
   $a = <<<END
       
       Normal syntax
       
   END;
       
       
   ?>


This syntax is backward incompatible : once adopted in the code, previous versions won't compile it.

See also `Heredoc <http://php.net/manual/en/language.types.string.php#language.types.string.syntax.heredoc>`_ and 
         `Flexible Heredoc and Nowdoc Syntaxes <https://wiki.php.net/rfc/flexible_heredoc_nowdoc_syntaxes>`_.

+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Php/FlexibleHeredoc                                                                                                                                                                         |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71`, :ref:`CompatibilityPHP72`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.3 and more recent                                                                                                                                                                |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Critical                                                                                                                                                                                    |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                                                                                                            |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _for-using-functioncall:

For Using Functioncall
######################


It is recommended to avoid functioncall in the `for() <http://php.net/manual/en/control-structures.for.php>`_ statement. 

.. code-block:: php

   <?php
   
   // Fastest way
   $nb = count($array); 
   for($i = 0; $i < $nb; ++$i) {
       doSomething($i);
   } 
   
   // Same as above, but slow
   for($i = 0; $i < count($array); ++$i) {
       doSomething($i);
   } 
   
   // Same as above, but slow
   foreach($portions as &$portion) {
       // here, array_sum() doesn't depends on the $grade. It should be out of the loop
       $portion = $portion / array_sum($portions);
   } 
   
   $total = array_sum($portion);
   foreach($portion as &$portion) {
       $portion = $portion / $total;
   } 
   
   ?>


This is true with any kind of functioncall that returns the same value throughout the loop.

+-------------+---------------------------------------------------------------------------------------------------------------+
| Short name  | Structures/ForWithFunctioncall                                                                                |
+-------------+---------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Performances`                                                                                           |
+-------------+---------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                         |
+-------------+---------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                                 |
+-------------+---------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-functioncall-in-loop <https://github.com/dseguy/clearPHP/tree/master/rules/no-functioncall-in-loop.md>`__ |
+-------------+---------------------------------------------------------------------------------------------------------------+



.. _foreach-don't-change-pointer:

Foreach Don't Change Pointer
############################


``foreach`` loops use their own internal cursor.

A ``foreach`` loop won't change the internal pointer of the array, as it works on a copy of the source. Hence, applying array pointer's functions such as `current() <http://www.php.net/current>`_ or `next() <http://www.php.net/next>`_ to the source array won't have the same behavior in PHP 5 than PHP 7.

This only applies when a `foreach() <http://php.net/manual/en/control-structures.foreach.php>`_ by reference is used.

.. code-block:: php

   <?php
   
   $numbers = range(1, 10);
   next($numbers);
   foreach($numbers as &$number){
       print $number;
       print current($numbers).\n; // Always 
   }
   
   ?>


See also `foreach no longer changes the internal array pointer <http://php.net/manual/en/migration70.incompatible.php#migration70.incompatible.foreach.array-pointer>`_.

+-------------+------------------------------+
| Short name  | Php/ForeachDontChangePointer |
+-------------+------------------------------+
| Themes      | :ref:`CompatibilityPHP70`    |
+-------------+------------------------------+
| Php Version | With PHP 7.0 and older       |
+-------------+------------------------------+
| Severity    | Major                        |
+-------------+------------------------------+
| Time To Fix | Slow (1 hour)                |
+-------------+------------------------------+



.. _foreach-needs-reference-array:

Foreach Needs Reference Array
#############################


When using foreach with a reference as value, the source must be a referenced array, which is a variable (or array or property or static property). 
When the array is the result of an expression, the array is not kept in memory after the foreach loop, and any change made with & are lost.

This will do nothing

.. code-block:: php

   <?php
       foreach(array(1,2,3) as &$value) {
           $value *= 2;
       }
   ?>


This will have an actual effect

.. code-block:: php

   <?php
       $array = array(1,2,3);
       foreach($array as &$value) {
           $value *= 2;
       }
   ?>

+-------------+----------------------------------------+
| Short name  | Structures/ForeachNeedReferencedSource |
+-------------+----------------------------------------+
| Themes      | :ref:`Analyze`                         |
+-------------+----------------------------------------+
| Severity    | Minor                                  |
+-------------+----------------------------------------+
| Time To Fix | Quick (30 mins)                        |
+-------------+----------------------------------------+



.. _foreach-on-object:

Foreach On Object
#################


Foreach on object looks like a typo. This is particularly true when both object and member are variables.

Foreach on an object member is a legit PHP syntax, though it is very rare : blind variables rarely have to be securing in an object to be processed.

.. code-block:: php

   <?php
   
   // Looks suspicious
   foreach($array as $o -> $b) { 
       doSomething();
   }
   
   // This is the real thing
   foreach($array as $o => $b) { 
       doSomething();
   }
   
   ?>

+-------------+-------------------+
| Short name  | Php/ForeachObject |
+-------------+-------------------+
| Themes      | :ref:`Analyze`    |
+-------------+-------------------+
| Severity    | Major             |
+-------------+-------------------+
| Time To Fix | Instant (5 mins)  |
+-------------+-------------------+



.. _foreach-reference-is-not-modified:

Foreach Reference Is Not Modified
#################################


Foreach statement may loop using a reference, especially when the loop has to change values of the array it is looping on. 

In the spotted loop, reference are used but never modified. They may be removed.

.. code-block:: php

   <?php
   
   $letters = range('a', 'z');
   
   // $letter is not used here
   foreach($letters as &$letter) {
       $alphabet .= $letter;
   }
   
   // $letter is actually used here
   foreach($letters as &$letter) {
       $letter = strtoupper($letter);
   }
   
   ?>

+-------------+------------------------------------------+
| Short name  | Structures/ForeachReferenceIsNotModified |
+-------------+------------------------------------------+
| Themes      | :ref:`Analyze`                           |
+-------------+------------------------------------------+
| Severity    | Minor                                    |
+-------------+------------------------------------------+
| Time To Fix | Quick (30 mins)                          |
+-------------+------------------------------------------+



.. _foreach-with-list():

Foreach With list()
###################


Foreach loops have the ability to use list as blind variables. This syntax assign directly array elements to various variables. 

PHP 5.5 introduced the usage of list in `foreach() <http://php.net/manual/en/control-structures.foreach.php>`_ loops. Until PHP 7.1, it was not possible to use non-numerical arrays as list() wouldn't support string-indexed arrays.

.. code-block:: php

   <?php
       // PHP 5.5 and later, with numerically-indexed arrays
       foreach($array as list($a, $b)) { 
           // do something 
       }
   
   
       // PHP 7.1 and later, with arrays
       foreach($array as list('col1' => $a, 'col3' => $b)) { // 'col2 is ignored'
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

+-------------+------------------------------------------------------+
| Short name  | Structures/ForeachWithList                           |
+-------------+------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54` |
+-------------+------------------------------------------------------+
| Php Version | With PHP 5.5 and more recent                         |
+-------------+------------------------------------------------------+
| Severity    | Minor                                                |
+-------------+------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                        |
+-------------+------------------------------------------------------+



.. _forgotten-interface:

Forgotten Interface
###################


The following classes have been found implementing an interface's methods, though it doesn't explicitly implements this interface. This may have been forgotten.

.. code-block:: php

   <?php
   
   interface i {
       function i(); 
   }
   
   // i is not implemented and declared
   class foo {
       function i() {}
       function j() {}
   }
   
   // i is implemented and declared
   class foo implements i {
       function i() {}
       function j() {}
   }
   
   ?>

+-------------+------------------------------+
| Short name  | Interfaces/CouldUseInterface |
+-------------+------------------------------+
| Themes      | :ref:`Analyze`               |
+-------------+------------------------------+
| Severity    | Minor                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _forgotten-thrown:

Forgotten Thrown
################


An exception is instantiated, but not thrown. 

.. code-block:: php

   <?php
   
   class MyException extends \Exception { }
   
   if ($error !== false) {
       // This looks like 'throw' was omitted
       new MyException();
   }
   
   ?>

+-------------+----------------------------+
| Short name  | Exceptions/ForgottenThrown |
+-------------+----------------------------+
| Themes      | :ref:`Analyze`             |
+-------------+----------------------------+
| Severity    | Major                      |
+-------------+----------------------------+
| Time To Fix | Instant (5 mins)           |
+-------------+----------------------------+



.. _forgotten-visibility:

Forgotten Visibility
####################


Some classes elements (property, method, constant) are missing their explicit visibility.

By default, it is public. It should at least be mentioned as public, or may be reviewed as protected or private. 

Class constants support also visibility since PHP 7.1.

final, static and abstract are not counted as visibility. Only public, private and protected. The PHP 4 var keyword is counted as undefined.

Traits, classes and interfaces are checked. 

.. code-block:: php

   <?php
   
   // Explicit visibility
   class X {
       protected sconst NO_VISIBILITY_CONST = 1; // For PHP 7.2 and later
   
       private $noVisibilityProperty = 2; 
       
       public function Method() {}
   }
   
   // Missing visibility
   class X {
       const NO_VISIBILITY_CONST = 1; // For PHP 7.2 and later
   
       var $noVisibilityProperty = 2; // Only with var
       
       function NoVisibilityForMethod() {}
   }
   
   ?>


See also `Visibility <http://php.net/manual/en/language.oop5.visibility.php>`_ and `Understanding The Concept Of Visibility In Object Oriented PHP <https://torquemag.io/2016/05/understanding-concept-visibility-object-oriented-php/>`_.

+-------------+-------------------------------------------------------------------------------------------------------------+
| Short name  | Classes/NonPpp                                                                                              |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                              |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                       |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                            |
+-------------+-------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `always-have-visibility <https://github.com/dseguy/clearPHP/tree/master/rules/always-have-visibility.md>`__ |
+-------------+-------------------------------------------------------------------------------------------------------------+



.. _forgotten-whitespace:

Forgotten Whitespace
####################


Forgotten whitespaces only bring misery to the code.

White spaces have been left at either end of a file : before the PHP opening tag, or after the closing tag. 

Usually, such whitespaces are forgotten, and may end up summoning the infamous 'headers already sent' error. It is better to remove them. 

.. code-block:: php

   <?php
       // This script has no forgotten whitespace, not at the beginning
       function foo() {}
   
       // This script has no forgotten whitespace, not at the end
   ?>


See also `How to fix Headers already sent error in PHP <http://stackoverflow.com/questions/8028957/how-to-fix-headers-already-sent-error-in-php>`_.


Suggestions
^^^^^^^^^^^

* Remove all whitespaces before and after a script. This doesn't apply to template, which may need to use those spaces.
* Remove the final tag, to prevent any whitespace to be forgotten at the end of the file. This doesn't apply to the opening PHP tag, which is always necessary.

+-------------+--------------------------------+
| Short name  | Structures/ForgottenWhiteSpace |
+-------------+--------------------------------+
| Themes      | :ref:`Analyze`                 |
+-------------+--------------------------------+
| Severity    | Minor                          |
+-------------+--------------------------------+
| Time To Fix | Instant (5 mins)               |
+-------------+--------------------------------+



.. _fully-qualified-constants:

Fully Qualified Constants
#########################


Constants defined with their namespace.

When defining constants with `define() <http://www.php.net/define>`_ function, it is possible to include the actual namespace : 

.. code-block:: php

   <?php
   
   define('a\b\c', 1); 
   
   ?>


However, the name should be fully qualified without the initial \. Here, \a\b\c constant will never be accessible as a namespace constant, though it will be accessible via the `constant() <http://www.php.net/constant>`_ function.

Also, the namespace will be absolute, and not a relative namespace of the current one. 

Suggestions
^^^^^^^^^^^

* Drop the initial \ when creating constants with define() : for example, use trim($x, '\'), which removes anti-slashes before and after.

+-------------+-----------------------------------+
| Short name  | Namespaces/ConstantFullyQualified |
+-------------+-----------------------------------+
| Themes      | :ref:`Analyze`                    |
+-------------+-----------------------------------+
| Severity    | Minor                             |
+-------------+-----------------------------------+
| Time To Fix | Slow (1 hour)                     |
+-------------+-----------------------------------+



.. _function-subscripting:

Function Subscripting
#####################


It is possible to use the result of a methodcall directly as an array, without storing the result in a temporary variable.

This works, given that the method actually returns an array. 

This syntax was not possible until PHP 5.4. Until then, it was compulsory to store the result in a variable first. Although this is now superfluous, it has been a standard syntax in PHP, and is still being used.

.. code-block:: php

   <?php
   
   function foo() {
       return array(1 => 'a', 'b', 'c');
   }
   
   echo foo()[1]; // displays 'a';
   
   // Function subscripting, the old way
   function foo() {
       return array(1 => 'a', 'b', 'c');
   }
   
   $x = foo();
   echo $x[1]; // displays 'a';
   
   ?>


Storing the result in a variable is still useful if the result is actually used more than once.

+-------------+---------------------------------+
| Short name  | Structures/FunctionSubscripting |
+-------------+---------------------------------+
| Themes      | :ref:`CompatibilityPHP53`       |
+-------------+---------------------------------+
| Php Version | With PHP 5.4 and more recent    |
+-------------+---------------------------------+
| Severity    | Minor                           |
+-------------+---------------------------------+
| Time To Fix | Instant (5 mins)                |
+-------------+---------------------------------+



.. _function-subscripting,-old-style:

Function Subscripting, Old Style
################################


Since PHP 5.4, it is now possible use function results as an array, and access directly its element : 

.. code-block:: php

   <?php
   
   function foo() {
       return array(1 => 'a', 'b', 'c');
   }
   
   echo foo()[1]; // displays 'a';
   
   // Function subscripting, the old way
   function foo() {
       return array(1 => 'a', 'b', 'c');
   }
   
   $x = foo();
   echo $x[1]; // displays 'a';
   
   ?>

+-------------+------------------------------------+
| Short name  | Structures/FunctionPreSubscripting |
+-------------+------------------------------------+
| Themes      | :ref:`Analyze`                     |
+-------------+------------------------------------+
| Php Version | With PHP 5.4 and more recent       |
+-------------+------------------------------------+
| Severity    | Minor                              |
+-------------+------------------------------------+
| Time To Fix | Instant (5 mins)                   |
+-------------+------------------------------------+



.. _functions-removed-in-php-5.4:

Functions Removed In PHP 5.4
############################


Those functions were removed in PHP 5.4.

.. code-block:: php

   <?php
   
   // Deprecated as of PHP 5.4.0
   $link = mysql_connect('localhost', 'mysql_user', 'mysql_password');
   $db_list = mysql_list_dbs($link);
   
   while ($row = mysql_fetch_object($db_list)) {
        echo $row->Database . "\n";
   }
   
   ?>


See also `Deprecated features in PHP 5.4.x <http://php.net/manual/en/migration54.deprecated.php>`_.

+-------------+---------------------------+
| Short name  | Php/Php54RemovedFunctions |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP54` |
+-------------+---------------------------+
| Php Version | With PHP 5.4 and older    |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _functions-removed-in-php-5.5:

Functions Removed In PHP 5.5
############################


Those functions were removed in PHP 5.5.

+ `php_logo_guid() <http://www.php.net/php_logo_guid>`_
+ `php_egg_logo_guid() <http://www.php.net/php_egg_logo_guid>`_
+ `php_real_logo_guid() <http://www.php.net/php_real_logo_guid>`_
+ `zend_logo_guid() <http://www.php.net/zend_logo_guid>`_
+ mcrypt_cbc()
+ mcrypt_cfb()
+ mcrypt_ecb()
+ mcrypt_ofb()

.. code-block:: php

   <?php
   
   echo '<img src="' . $_SERVER['PHP_SELF'] .
        '?=' . php_logo_guid() . '" alt="PHP Logo !" />';
   
   ?>


See also `Deprecated features in PHP 5.5.x <http://php.net/manual/fr/migration55.deprecated.php>`_.

+-------------+---------------------------+
| Short name  | Php/Php55RemovedFunctions |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP55` |
+-------------+---------------------------+
| Php Version | With PHP 5.5 and older    |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _getting-last-element:

Getting Last Element
####################


Getting the last element of an array relies on array_key_last().

array_key_last() was added in PHP 7.3. Before that, 

.. code-block:: php

   <?php
   
   $array = ['a' => 1, 'b' => 2, 'c' => 3];
   
   // Best solutions, by far
   $last = $array[array_key_last($array)];
   
   // Best solutions, just as fast as each other
   $last = $array[count($array) - 1];
   $last = end($array);
   
   // Bad solutions
   
   // popping, but restoring the value. 
   $last = array_pop($array);
   $array[] = $last; 
   
   // array_unshift would be even worse
   
   // reversing array
   $last = array_reverse($array)[0];
   
   // slicing the array
   $last = array_slice($array, -1)[0]',
   $last = current(array_slice($array, -1));
   );
   
   ?>

+-------------+---------------------------+
| Short name  | Arrays/GettingLastElement |
+-------------+---------------------------+
| Themes      | :ref:`Performances`       |
+-------------+---------------------------+
| Severity    | Minor                     |
+-------------+---------------------------+
| Time To Fix | Instant (5 mins)          |
+-------------+---------------------------+



.. _global-inside-loop:

Global Inside Loop
##################


The global keyword must be out of loops. It is evaluated each loop, slowing the whole process.

.. code-block:: php

   <?php
   
   // Good idea, global is used once
   global $total;
   foreach($a as $b) {
       $total += $b;
   }
   
   // Bad idea, this is slow.
   foreach($a as $b) {
       global $total;
       $total += $b;
   }
   ?>

+------------+------------------------------+
| Short name | Structures/GlobalOutsideLoop |
+------------+------------------------------+
| Themes     | :ref:`Performances`          |
+------------+------------------------------+



.. _global-usage:

Global Usage
############


List usage of globals variables, with global keywords or direct access to $GLOBALS.

.. code-block:: php

   <?php
   $a = 1; /* global scope */ 
   
   function test()
   { 
       echo $a; /* reference to local scope variable */ 
   } 
   
   test();
   
   ?>

It is recommended to avoid using global variables, at it makes it very difficult to track changes in values across the whole application. 

See also `Variable scope <http://php.net/manual/en/language.variables.scope.php>`_.

+-------------+-----------------------------------------------------------------------------------+
| Short name  | Structures/GlobalUsage                                                            |
+-------------+-----------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                    |
+-------------+-----------------------------------------------------------------------------------+
| Severity    | Minor                                                                             |
+-------------+-----------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                     |
+-------------+-----------------------------------------------------------------------------------+
| ClearPHP    | `no-global <https://github.com/dseguy/clearPHP/tree/master/rules/no-global.md>`__ |
+-------------+-----------------------------------------------------------------------------------+



.. _group-use-declaration:

Group Use Declaration
#####################


The group use declaration is used in the code.

.. code-block:: php

   <?php
   
   // Adapted from the RFC documentation 
   // Pre PHP 7 code
   use some\name_space\ClassA;
   use some\name_space\ClassB;
   use some\name_space\ClassC as C;
   
   use function some\name_space\fn_a;
   use function some\name_space\fn_b;
   use function some\name_space\fn_c;
   
   use const some\name_space\ConstA;
   use const some\name_space\ConstB;
   use const some\name_space\ConstC;
   
   // PHP 7+ code
   use some\name_space\{ClassA, ClassB, ClassC as C};
   use function some\name_space\{fn_a, fn_b, fn_c};
   use const some\name_space\{ConstA, ConstB, ConstC};
   
   ?>


See also `Group Use Declaration RFC <https://wiki.php.net/rfc/group_use_declarations>`_ and `Using namespaces: Aliasing/Importing <http://php.net/manual/en/language.namespaces.importing.php>`_.

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Php/GroupUseDeclaration                                                                                    |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                           |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _group-use-trailing-comma:

Group Use Trailing Comma
########################


The usage of a final empty slot in array() was allowed with use statements. This works in PHP 7.2 and more recent.

Although this empty instruction is ignored at execution, this allows for clean presentation of code, and short diff when committing in a VCS.

.. code-block:: php

   <?php
   
   // Valid in PHP 7.2 and more recent.
   use a\b\{c, 
            d, 
            e, 
            f,
           };
   
   // This won't compile in 7.1 and older.
   
   ?>


See also `Trailing Commas In List Syntax <https://wiki.php.net/rfc/list-syntax-trailing-commas>`_ and `Revisit trailing commas in function arguments <https://www.mail-archive.com/internals@lists.php.net/msg81138.html>`_.

+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Php/GroupUseTrailingComma                                                                                                                                        |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.2 and more recent                                                                                                                                     |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                                                                            |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                                                                                 |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _hardcoded-passwords:

Hardcoded Passwords
###################


Hardcoded passwords in the code. 

Hardcoding passwords is a bad idea. Not only it make the code difficult to change, but it is an information leak. It is better to hide this kind of information out of the code.

.. code-block:: php

   <?php
   
   $ftp_server = '300.1.2.3';   // 
   $conn_id = ftp_connect($ftp_server); 
   
   // login with username and password
   $login_result = ftp_login($conn_id, 'login', 'password'); 
   
   ?>

+-------------+---------------------------------------------------------------------------------------------------------------+
| Short name  | Functions/HardcodedPasswords                                                                                  |
+-------------+---------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Security`                                                                               |
+-------------+---------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                         |
+-------------+---------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                                 |
+-------------+---------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-hardcoded-credential <https://github.com/dseguy/clearPHP/tree/master/rules/no-hardcoded-credential.md>`__ |
+-------------+---------------------------------------------------------------------------------------------------------------+



.. _hash-algorithms:

Hash Algorithms
###############


There is a long but limited list of hashing algorithm available to PHP. The one found doesn't seem to be existing.

.. code-block:: php

   <?php
   
   // This hash has existed in PHP. Check with hash_algos() if it is available on your system. 
   echo hash('ripmed160', 'The quick brown fox jumped over the lazy dog.');
   
   // This hash doesn't exist
   echo hash('ripemd160', 'The quick brown fox jumped over the lazy dog.');
   
   ?>


See also `hash_algos <http://php.net/hash_algos>`_.



Suggestions
^^^^^^^^^^^

* Use a hash algorithm that is available on several PHP versions
* Fix the name of the hash algorithm

+-------------+----------------+
| Short name  | Php/HashAlgos  |
+-------------+----------------+
| Themes      | :ref:`Analyze` |
+-------------+----------------+
| Severity    | Major          |
+-------------+----------------+
| Time To Fix | Slow (1 hour)  |
+-------------+----------------+



.. _hash-algorithms-incompatible-with-php-5.3:

Hash Algorithms Incompatible With PHP 5.3
#########################################


List of hash algorithms incompatible with PHP 5.3.

.. code-block:: php

   <?php
   
   // Compatible only with 5.3 and more recent
   echo hash('md2', 'The quick brown fox jumped over the lazy dog.');
   
   // Always compatible
   echo hash('ripemd320', 'The quick brown fox jumped over the lazy dog.');
   
   ?>


See also `hash_algos <http://php.net/hash_algos>`_.

+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Php/HashAlgos53                                                                                                                                                                             |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP72` |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                                                                                                       |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                                                                                                               |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _hash-algorithms-incompatible-with-php-5.4/5.5:

Hash Algorithms Incompatible With PHP 5.4/5.5
#############################################


List of hash algorithms incompatible with PHP 5.4 and 5.5.

.. code-block:: php

   <?php
   
   // Compatible only with 5.4 and more recent
   echo hash('fnv132', 'The quick brown fox jumped over the lazy dog.');
   
   // Always compatible
   echo hash('ripemd320', 'The quick brown fox jumped over the lazy dog.');
   
   ?>


See also `hash_algos <http://php.net/hash_algos>`_.

+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Php/HashAlgos54                                                                                                                                                  |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP72` |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 5.4 and older                                                                                                                                           |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                                                                            |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                                                                                    |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _hash-algorithms-incompatible-with-php-7.1-:

Hash Algorithms Incompatible With PHP 7.1-
##########################################


List of hash algorithms incompatible with PHP 7.1 and more recent. At the moment of writing, this is compatible up to 7.3. 

The hash algorithms were introduced in PHP 7.1. 

.. code-block:: php

   <?php
   
   // Compatible only with 7.1 and more recent
   echo hash('sha512/224', 'The quick brown fox jumped over the lazy dog.');
   
   // Always compatible
   echo hash('ripemd320', 'The quick brown fox jumped over the lazy dog.');
   
   ?>


See also `hash_algos <http://php.net/hash_algos>`_.

+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Php/HashAlgos71                                                                                                                       |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.1 and older                                                                                                                |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                                                 |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                                                         |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+



.. _hash-will-use-objects:

Hash Will Use Objects
#####################


The `ext/hash extension <http://www.php.net/manual/en/book.hash.php>`_ used resources, and is being upgraded to use resources. 

.. code-block:: php

   <?php
   
   // Post 7.2 code 
       $hash = hash_init('sha256');
       if (!is_object($hash)) {
           trigger_error('error');
       }
       hash_update($hash, $message);
   
   // Pre-7.2 code
       $hash = hash_init('md5');
       if (!is_resource($hash)) {
           trigger_error('error');
       }
       hash_update($hash, $message);
   
   ?>


See also `Move ext/hash from resources to objects <http://php.net/manual/en/migration72.incompatible.php#migration72.incompatible.hash-ext-to-objects>`_.

+-------------+---------------------------+
| Short name  | Php/HashUsesObjects       |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP72` |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _heredoc-delimiter:

Heredoc Delimiter
#################


Heredoc and Nowdoc expressions may use a variety of delimiters. 

There seems to be a standard delimiter in the code, and some exceptions : one or several forms are dominant (> 90%), while the others are rare. 

The analyzed code has less than 10% of the rare delimiters. For consistency reasons, it is recommended to make them all the same. 

Generally, one or two delimiters are used, with generic value. It is recommended to use a humanly readable delimiter : SQL, HTML, XML, GREMLIN, etc. This helps readability in the code.

.. code-block:: php

   <?php
   
   echo <<<SQL
   SELECT * FROM table1;
   SQL;
   
   echo <<<SQL
   SELECT * FROM table2;
   SQL;
   
   echo <<<SQL
   SELECT * FROM table3;
   SQL;
   
   echo <<<SQL
   SELECT * FROM table4;
   SQL;
   
   echo <<<SQL
   SELECT * FROM table5;
   SQL;
   
   echo <<<SQL
   SELECT * FROM table11;
   SQL;
   
   echo <<<SQL
   SELECT * FROM table12;
   SQL;
   
   echo <<<SQL
   SELECT * FROM table13;
   SQL;
   
   // Nowdoc
   echo <<<'SQL'
   SELECT * FROM table14;
   SQL;
   
   echo <<<SQL
   SELECT * FROM table15;
   SQL;
   
   
   echo <<<HEREDOC
   SELECT * FROM table215;
   HEREDOC;
   
   ?>

+------------+------------------------------------------------+
| Short name | Structures/HeredocDelimiterFavorite            |
+------------+------------------------------------------------+
| Themes     | :ref:`Coding Conventions <coding-conventions>` |
+------------+------------------------------------------------+



.. _hexadecimal-in-string:

Hexadecimal In String
#####################


Mark strings that may be confused with hexadecimal. 

Until PHP 7.0, PHP recognizes hexadecimal numbers inside strings, and converts them accordingly. 

PHP 7.0 and until 7.1, converts the string to 0, silently. 

PHP 7.1 and later, emits a 'A non-numeric value encountered' warning, and convert the string to 0. 

.. code-block:: php

   <?php
       $a = '0x0030';
       print $a + 1;
       // Print 49
   
       $c = '0x0030zyc';
       print $c + 1;
       // Print 49
   
       $b = 'b0x0030';
       print $b + 1;
       // Print 0
   ?>

+-------------+------------------------------------------------------+
| Short name  | Type/HexadecimalString                               |
+-------------+------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71` |
+-------------+------------------------------------------------------+
| Severity    | Major                                                |
+-------------+------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                        |
+-------------+------------------------------------------------------+



.. _hidden-use-expression:

Hidden Use Expression
#####################


The use expression for namespaces should always be at the beginning of the namespace block. 

It is where everyone expect them, and it is less confusing than having them at various levels.

.. code-block:: php

   <?php
   
   // This is visible 
   use A;
   
   class B {}
   
   // This is hidden 
   use C as D;
   
   class E extends D {
       use traitT; // This is a use for a trait
   
       function foo() {
           // This is a use for a closure
           return function ($a) use ($b) {}
       }
   }
   
   ?>

+-------------+----------------------+
| Short name  | Namespaces/HiddenUse |
+-------------+----------------------+
| Themes      | :ref:`Analyze`       |
+-------------+----------------------+
| Severity    | Minor                |
+-------------+----------------------+
| Time To Fix | Instant (5 mins)     |
+-------------+----------------------+



.. _htmlentities-calls:

Htmlentities Calls
##################


`htmlentities() <http://www.php.net/htmlentities>`_ and `htmlspecialchars() <http://www.php.net/htmlspecialchars>`_ are used to prevent injecting special characters in HTML code. As a bare minimum, they take a string and encode it for HTML.

The second argument of the functions is the type of protection. The protection may apply to quotes or not, to HTML 4 or 5, etc. It is highly recommended to set it explicitly.

The third argument of the functions is the encoding of the string. In PHP 5.3, it is ISO-8859-1, in 5.4, was ``UTF-8``, and in 5.6, it is now default_charset, a ``php.ini`` configuration that has the default value of ``UTF-8``. It is highly recommended to set this argument too, to avoid distortions from the configuration.

.. code-block:: php

   <?php
   $str = 'A quote is <b>bold</b>';
   
   // Outputs, without depending on the php.ini: A &#039;quote&#039; is &lt;b&gt;bold&lt;/b&gt; 
   echo htmlentities($str, ENT_QUOTES, 'UTF-8');
   
   // Outputs, while depending on the php.ini: A quote is &lt;b&gt;bold&lt;/b&gt;
   echo htmlentities($str);
   
   ?>


Also, note that arguments 2 and 3 are constants and string (respectively), and should be issued from the list of values available in the manual. Other values than those will make PHP use the default values. 

See also `htmlentities <http://www.php.net/htmlentities>`_ and `htmlspecialchars <http://www.php.net/htmlspecialchars>`_.

+-------------+-----------------------------+
| Short name  | Structures/Htmlentitiescall |
+-------------+-----------------------------+
| Themes      | :ref:`Analyze`              |
+-------------+-----------------------------+
| Severity    | Major                       |
+-------------+-----------------------------+
| Time To Fix | Instant (5 mins)            |
+-------------+-----------------------------+



.. _identical-conditions:

Identical Conditions
####################


These logical expressions contain members that are identical. 

This means those expressions may be simplified. 

.. code-block:: php

   <?php
   
   // twice $a
   if ($a || $b || $c || $a) {  }
   
   // Hiding in parenthesis is bad
   if (($a) ^ ($a)) {}
   
   // expressions may be large
   if ($a === 1 && 1 === $a) {}
   
   ?>

+-------------+-----------------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Structures/IdenticalConditions                                                                                                                |
+-------------+-----------------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                                                                |
+-------------+-----------------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Critical                                                                                                                                      |
+-------------+-----------------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                                               |
+-------------+-----------------------------------------------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`wordpress-structures-identicalconditions`, :ref:`dolibarr-structures-identicalconditions`, :ref:`mautic-structures-identicalconditions` |
+-------------+-----------------------------------------------------------------------------------------------------------------------------------------------+



.. _identical-consecutive-expression:

Identical Consecutive Expression
################################


Identical consecutive expressions are worth being checked. 

They may be a copy/paste with unmodified content. When the content has to be duplicated, it is recommended to avoid executing the expression again, and just access the cached result.

.. code-block:: php

   <?php
   
   $current  = $array[$i];
   $next     = $array[$i + 1];
   $nextnext = $array[$i + 1]; // OOps, nextnext is wrong.
   
   // Initialization
   $previous = foo($array[1]); // previous is initialized with the first value on purpose
   $next     = foo($array[1]); // the second call to foo() with the same arguments should be avoided
   // the above can be rewritten as : 
   $next     = $previous; // save the processing.
   
   for($i = 1; $i < 200; ++$i) {
       $next = doSomething();
   }
   ?>

+-------------+---------------------------------+
| Short name  | Structures/IdenticalConsecutive |
+-------------+---------------------------------+
| Themes      | :ref:`Analyze`                  |
+-------------+---------------------------------+
| Severity    | Minor                           |
+-------------+---------------------------------+
| Time To Fix | Instant (5 mins)                |
+-------------+---------------------------------+



.. _identical-on-both-sides:

Identical On Both Sides
#######################


Operands should be different when comparing or making a logical combination. Of course, the value each operand holds may be identical. When the same operand appears on both sides of the expression, the result is know before execution. 

.. code-block:: php

   <?php
   
   // Trying to confirm consistency
   if ($login == $login) {
       doSomething();
   }
   
   // Works with every operators
   if ($object->login( ) !== $object->login()) {
       doSomething();
   }
   
   if ($sum >= $sum) {
       doSomething();
   }
   
   //
   if ($mask && $mask) {
       doSomething();
   }
   
   if ($mask || $mask) {
       doSomething();
   }
   
   ?>

+-------------+---------------------------------------------------+
| Short name  | Structures/IdenticalOnBothSides                   |
+-------------+---------------------------------------------------+
| Themes      | :ref:`Analyze`                                    |
+-------------+---------------------------------------------------+
| Severity    | Major                                             |
+-------------+---------------------------------------------------+
| Time To Fix | Quick (30 mins)                                   |
+-------------+---------------------------------------------------+
| Examples    | :ref:`phpmyadmin-structures-identicalonbothsides` |
+-------------+---------------------------------------------------+



.. _if-with-same-conditions:

If With Same Conditions
#######################


Successive If / then structures that have the same condition may be either merged or have one of the condition changed. 

.. code-block:: php

   <?php
   
   if ($a == 1) {
       doSomething();
   }
   
   if ($a == 1) {
       doSomethingElse();
   }
   
   // May be replaced by 
   if ($a == 1) {
       doSomething();
       doSomethingElse();
   }
   
   ?>


Note that if the values used in the condition have been modified in the first if/then structure, the two distinct conditions may be needed. 

.. code-block:: php

   <?php
   
   // May not be merged
   if ($a == 1) {
       // Check that this is really the situation
       $a = checkSomething();
   }
   
   if ($a == 1) {
       doSomethingElse();
   }
   
   ?>




Suggestions
^^^^^^^^^^^

* Merge the two conditions so the condition is used once.
* Change one of the condition, so they are different
* Make it obvious that the first condition is a try, preparing the normal conditions.

+-------------+---------------------------------------------------------------------------------------------------------+
| Short name  | Structures/IfWithSameConditions                                                                         |
+-------------+---------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                          |
+-------------+---------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                   |
+-------------+---------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                         |
+-------------+---------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`phpmyadmin-structures-ifwithsameconditions`, :ref:`phpdocumentor-structures-ifwithsameconditions` |
+-------------+---------------------------------------------------------------------------------------------------------+



.. _iffectations:

Iffectations
############


Affectations that appears in a condition. 

Iffectations are a way to do both a test and an affectations. 
They may also be typos, such as if ($x = 3) { `... <http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list>`_ }, leading to a constant condition. 

.. code-block:: php

   <?php
   
   // an iffectation : assignation in a If condition
   if($connexion = mysql_connect($host, $user, $pass)) {
       $res = mysql_query($connexion, $query);
   }
   
   // Iffectation may happen in while too.
   while($row = mysql_fetch($res)) {
       $store[] = $row;
   }
   
   ?>

+-------------+------------------------+
| Short name  | Structures/Iffectation |
+-------------+------------------------+
| Themes      | :ref:`Analyze`         |
+-------------+------------------------+
| Severity    | Minor                  |
+-------------+------------------------+
| Time To Fix | Quick (30 mins)        |
+-------------+------------------------+



.. _illegal-name-for-method:

Illegal Name For Method
#######################


PHP has reserved usage of methods starting with ``__`` for magic methods. It is recommended to avoid using this prefix, to prevent confusions.

.. code-block:: php

   <?php
   
   class foo{
       // Constructor
       function __construct() {}
   
       // Constructor's typo
       function __constructor() {}
   
       // Illegal function name, even as private
       private function __bar() {}
   }
   
   ?>


See also `Magic Methods <http://php.net/manual/en/language.oop5.magic.php>`_.

+-------------+-------------------------------------+
| Short name  | Classes/WrongName                   |
+-------------+-------------------------------------+
| Themes      | :ref:`Analyze`                      |
+-------------+-------------------------------------+
| Severity    | Major                               |
+-------------+-------------------------------------+
| Time To Fix | Slow (1 hour)                       |
+-------------+-------------------------------------+
| Examples    | :ref:`prestashop-classes-wrongname` |
+-------------+-------------------------------------+



.. _implement-is-for-interface:

Implement Is For Interface
##########################


With class heritage, implements should be used for interfaces, and extends with classes.

PHP defers the implements check until execution : the code in example does lint, but won,t run.

.. code-block:: php

   <?php
   
   class x {}
   
   interface y {}
   
   // This is wrong
   class z implements x {}
   
   ?>

+-------------+---------------------------------+
| Short name  | Classes/ImplementIsForInterface |
+-------------+---------------------------------+
| Themes      | :ref:`Analyze`                  |
+-------------+---------------------------------+
| Severity    | Minor                           |
+-------------+---------------------------------+
| Time To Fix | Quick (30 mins)                 |
+-------------+---------------------------------+



.. _implemented-methods-are-public:

Implemented Methods Are Public
##############################


Class methods that are defined in an interface must be public. They cannot be either private, nor protected.

This error is not reported by lint, but is reported at execution time.

.. code-block:: php

   <?php
   
   interface i {
       function foo();
   }
   
   class X {
       // This method is defined in the interface : it must be public
       protected function foo() {}
       
       // other methods may be private
       private function bar() {}
   }
   
   ?>

+-------------+-------------------------------------+
| Short name  | Classes/ImplementedMethodsArePublic |
+-------------+-------------------------------------+
| Themes      | :ref:`Analyze`                      |
+-------------+-------------------------------------+
| Severity    | Major                               |
+-------------+-------------------------------------+
| Time To Fix | Instant (5 mins)                    |
+-------------+-------------------------------------+



.. _implicit-global:

Implicit Global
###############


Global variables, that are used in local scope with global keyword, but are not declared as global in the global scope. They may be mistaken with distinct values, while, in PHP, variables in the global scope are truly global.

.. code-block:: php

   <?php
   
   // This is implicitely global
   $implicitGlobal = 1;
   
   global $explicitGlobal;
   $explicitGlobal = 2;
   
   foo();
   echo $explicitFunctionGlobal;
   
   function foo() {
       // This global is needed, but not the one in the global space
       global $implicitGlobal, $explicitGlobal, $explicitFunctionGlobal;
       
       // This won't be a global, as it must be 'global' in a function scope
       $notImplicitGlobal = 3;
       $explicitFunctionGlobal = 3;
   }
   
   ?>

+-------------+---------------------------+
| Short name  | Structures/ImplicitGlobal |
+-------------+---------------------------+
| Themes      | :ref:`Analyze`            |
+-------------+---------------------------+
| Severity    | Minor                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _implied-if:

Implied If
##########


It is confusing to emulate if/then with boolean operators.

It is possible to emulate a if/then structure by using the operators 'and' and 'or'. Since optimizations will be applied to them : 
when the left operand of 'and' is false, the right one is not executed, as its result is useless; 
when the left operand of 'or' is true, the right one is not executed, as its result is useless; 

However, such structures are confusing. It is easy to misread them as conditions, and ignore an important logic step. 

.. code-block:: php

   <?php
   
   // Either connect, or die
   mysql_connect('localhost', $user, $pass) or die();
   
   // Defines a constant if not found. 
   defined('SOME_CONSTANT') and define('SOME_CONSTANT', 1);
   
   // Defines a default value if provided is empty-ish 
   // Warning : this is 
   $user = $_GET['user'] || 'anonymous';
   
   ?>


It is recommended to use a real 'if then' structures, to make the condition readable.

+-------------+-------------------------------------------------------------------------------------------+
| Short name  | Structures/ImpliedIf                                                                      |
+-------------+-------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                            |
+-------------+-------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                     |
+-------------+-------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                          |
+-------------+-------------------------------------------------------------------------------------------+
| ClearPHP    | `no-implied-if <https://github.com/dseguy/clearPHP/tree/master/rules/no-implied-if.md>`__ |
+-------------+-------------------------------------------------------------------------------------------+



.. _inclusion-wrong-case:

Inclusion Wrong Case
####################


Inclusion should follow exactly the case of included files and path. This prevents the infamous case-sensitive filesystem bug, where files are correctly included in a case-insensitive system, and failed to be when moved to production.

.. code-block:: php

   <?php
   
   // There must exist a path called path/to and a file library.php with this case
   include path/to/library.php;
   
   // Error on the case, while the file does exist
   include path/to/LIBRARY.php;
   
   // Error on the case, on the PATH
   include path/TO/library.php;
   
   ?>





Suggestions
^^^^^^^^^^^

* Make the inclusion string identical to the file name. 
* Change the name of the file to reflect the actual inclusion. This is the best way when a naming convention has been set up for the project, and the file doesn't adhere to it. Remember to change all other inclusion.

+-------------+--------------------------+
| Short name  | Files/InclusionWrongCase |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Major                    |
+-------------+--------------------------+
| Time To Fix | Slow (1 hour)            |
+-------------+--------------------------+



.. _incompatible-signature-methods:

Incompatible Signature Methods
##############################


Methods should have the same signature when being overwritten.

The same signatures means the children class must have : 
+ the same name
+ the same visibility or less restrictive
+ the same typehint or removed
+ the same default value or removed
+ a reference like its parent

This problem emits a fatal error, for abstract methods, or a warning error, for normal methods. Yet, it is difficult to lint, because classes are often stored in different files. As such, PHP do lint each file independently, as unknown parent classes are not checked if not present. Yet, when executing the code, PHP lint the actual code and may encounter a fatal error.

.. code-block:: php

   <?php
   
   class a {
       public function foo($a = 1) {}
   }
   
   class ab extends a {
       // foo is overloaded and now includes a default value for $a
       public function foo($a) {}
   }
   
   ?>


See also `Object Inheritance <http://www.php.net/manual/en/language.oop5.inheritance.php>`_.

+-------------+-----------------------------------------------+
| Short name  | Classes/IncompatibleSignature                 |
+-------------+-----------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`LintButWontExec`        |
+-------------+-----------------------------------------------+
| Severity    | Critical                                      |
+-------------+-----------------------------------------------+
| Time To Fix | Quick (30 mins)                               |
+-------------+-----------------------------------------------+
| Examples    | :ref:`suitecrm-classes-incompatiblesignature` |
+-------------+-----------------------------------------------+



.. _incompilable-files:

Incompilable Files
##################


Files that cannot be compiled, and, as such, be run by PHP. Scripts are linted against various versions of PHP. 

This is usually undesirable, as all code must compile before being executed. It may be that such files are not compilable because they are not yet ready for an upcoming PHP version.

.. code-block:: php

   <?php
   
   // Can't compile this : Print only accepts one argument
   print $a, $b, $c;
   
   ?>


Code that is incompilable with older PHP versions means that the code is breaking backward compatibility : good or bad is project decision.

When the code is used as a template for PHP code generation, for example at installation time, it is recommended to use a distinct file extension, so as to distinguish them from actual PHP code.



Suggestions
^^^^^^^^^^^

* If this file is a template for PHP code, change the extension to something else than .php
* Fix the syntax so it works with various versions of PHP

+-------------+-----------------------------------------------------------------------------------------------+
| Short name  | Php/Incompilable                                                                              |
+-------------+-----------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                |
+-------------+-----------------------------------------------------------------------------------------------+
| Severity    | Critical                                                                                      |
+-------------+-----------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                 |
+-------------+-----------------------------------------------------------------------------------------------+
| ClearPHP    | `no-incompilable <https://github.com/dseguy/clearPHP/tree/master/rules/no-incompilable.md>`__ |
+-------------+-----------------------------------------------------------------------------------------------+
| Examples    | :ref:`xataface-php-incompilable`                                                              |
+-------------+-----------------------------------------------------------------------------------------------+



.. _inconsistent-elseif:

Inconsistent Elseif
###################


Chaining if/elseif requires a consistent string of conditions. The conditions are executed one after the other, and the conditions shouldn't overlap.

This analysis reports chains of elseif that don't share a common variable (or array, or property, etc.. ). As such, testing different conditions are consistent. 

.. code-block:: php

   <?php
   
   // $a is always common, so situations are mutually exclusive
   if ($a === 1) {
       doSomething();
   } else if ($a > 1) {
       doSomethingElse();
   } else {
       doSomethingDefault();
   }
   
   // $a is always common, so situations are mutually exclusive
   // although, it may be worth checking the consistency here
   if ($a->b === 1) {
       doSomething();
   } else if ($a->c > 1) {
       doSomethingElse();
   } else {
       doSomethingDefault();
   }
   
   // if $a === 1, then $c doesn't matter? 
   // This happens, but then logic doesn't appear in the code.
   if ($a === 1) {
       doSomething();
   } else if ($c > 1) {
       doSomethingElse();
   } else {
       doSomethingDefault();
   }
   
   ?>

+-------------+-------------------------------+
| Short name  | Structures/InconsistentElseif |
+-------------+-------------------------------+
| Themes      | :ref:`Analyze`                |
+-------------+-------------------------------+
| Severity    | Major                         |
+-------------+-------------------------------+
| Time To Fix | Slow (1 hour)                 |
+-------------+-------------------------------+



.. _indices-are-int-or-string:

Indices Are Int Or String
#########################


Indices in an array notation such as ``$array['indice']`` may only be integers or string.

Boolean, Null or float will be converted to their integer or string equivalent.

.. code-block:: php

   <?php
       $a = [true => 1,
             1.0  => 2,
             1.2  => 3,
             1    => 4,
             '1'  => 5,
             0.8  => 6,
             0x1  => 7,
             01   => 8,
             
             null  => 1,
             ''    => 2,
             
             false => 1,
             0     => 2,
   
             '0.8' => 3,
             '01'  => 4,
             '2a'  => 5
             ];
             
       print_r($a);
   
   /*
   The above displays
   Array
   (
       [1] => 8
       [0] => 2
       [] => 2
       [0.8] => 3
       [01] => 4
       [2a] => 5
   )
   */
   ?>


Decimal numbers are rounded to the closest integer; Null is transtyped to '' (empty string); true is 1 and false is 0; Integers in strings are transtyped, while partial numbers or decimals are not analyzed in strings. 

As a general rule of thumb, only use integers or strings that don\'t look like integers. 

This analyzer may find constant definitions, when available.

+-------------+----------------------------------+
| Short name  | Structures/IndicesAreIntOrString |
+-------------+----------------------------------+
| Themes      | :ref:`Analyze`                   |
+-------------+----------------------------------+
| Severity    | Major                            |
+-------------+----------------------------------+
| Time To Fix | Quick (30 mins)                  |
+-------------+----------------------------------+



.. _indirect-injection:

Indirect Injection
##################


Look for injections through indirect usage for GPRC values (`$_GET <http://php.net/manual/en/reserved.variables.get.php>`_, `$_POST <http://php.net/manual/en/reserved.variables.post.php>`_, `$_REQUEST <http://php.net/manual/en/reserved.variables.request.php>`_, $_COOKIE). 

.. code-block:: php

   <?php
   
   $a = $_GET['a'];
   echo $a;
   
   ?>

+-------------+----------------------------+
| Short name  | Security/IndirectInjection |
+-------------+----------------------------+
| Themes      | :ref:`Security`            |
+-------------+----------------------------+
| Severity    | Critical                   |
+-------------+----------------------------+
| Time To Fix | Slow (1 hour)              |
+-------------+----------------------------+



.. _instantiating-abstract-class:

Instantiating Abstract Class
############################


PHP cannot instantiate an abstract class. 

The classes are actually abstract classes, and should be derived into a concrete class to be instantiated.

.. code-block:: php

   <?php
   
   abstract class Foo {
       protected $a;
   }
   
   class Bar extends Foo {
       protected $b;
   }
   
   // instantiating a concrete class.
   new Bar();
   
   // instantiating an abstract class.
   // In real life, this is not possible also because the definition and the instantiation are in the same file
   new Foo();
   
   ?>


See also `Class Abstraction <http://php.net/abstract>`_.

+-------------+------------------------------------+
| Short name  | Classes/InstantiatingAbstractClass |
+-------------+------------------------------------+
| Themes      | :ref:`Analyze`                     |
+-------------+------------------------------------+
| Severity    | Major                              |
+-------------+------------------------------------+
| Time To Fix | Quick (30 mins)                    |
+-------------+------------------------------------+



.. _integer-as-property:

Integer As Property
###################


It is backward incompatible to use integers are property names. This feature was introduced in PHP 7.2.

If the code must be compatible with previous versions, avoid casting arrays to object.

.. code-block:: php

   <?php
   
   // array to object
   $arr = [0 => 1];
   $obj = (object) $arr;
   var_dump(
       $obj,
       $obj->{'0'}, // PHP 7.2+ accessible
       $obj->{0} // PHP 7.2+ accessible
   
       $obj->{'b'}, // always been accessible
   );
   ?>


See also `PHP RFC: Convert numeric keys in object/array casts <https://wiki.php.net/rfc/convert_numeric_keys_in_object_array_casts>`_.

+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Classes/IntegerAsProperty                                                                                                                                        |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.2 and more recent                                                                                                                                     |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                                                                            |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                                                                                    |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _interpolation:

Interpolation
#############


The following strings contain variables that are will be replaced. However, the following characters are ambiguous, and may lead to confusion. 

.. code-block:: php

   <?php
   
   class b { 
       public $b = 'c';
       function __toString() { return __CLASS__; }
   }
   $x = array(1 => new B());
   
   // -> after the $x[1] looks like a 2nd dereferencing, but it is not. 
   print $x[1]->b;
   // displays : b->b
   
   print {$x[1]->b};
   // displays : c
   
   ?>


It is advised to add curly brackets around those structures to make them non-ambiguous.

See also `Double quoted <http://php.net/manual/en/language.types.string.php#language.types.string.syntax.double>`_.

+-------------+------------------------------------------------+
| Short name  | Type/StringInterpolation                       |
+-------------+------------------------------------------------+
| Themes      | :ref:`Coding Conventions <coding-conventions>` |
+-------------+------------------------------------------------+
| Severity    | Minor                                          |
+-------------+------------------------------------------------+
| Time To Fix | Quick (30 mins)                                |
+-------------+------------------------------------------------+



.. _invalid-constant-name:

Invalid Constant Name
#####################


There is a naming convention for PHP constants names. 

According to PHP's manual, constant names, ' A valid constant name starts with a letter or underscore, followed by any number of letters, numbers, or underscores.'.

Constant, must follow this regex : ``/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/``.

In particular when defined using `define() <http://www.php.net/define>`_ function, no error is produced. When using ``const``, on the other hand, the 

.. code-block:: php

   <?php
   
   define('+3', 1); // wrong constant! 
   
   echo constant('+3'); // invalid constant access
   
   ?>


See also `Constants <http://php.net/manual/en/language.constants.php>`_.


Suggestions
^^^^^^^^^^^

* Change constant name

+-------------+--------------------------------------+
| Short name  | Constants/InvalidName                |
+-------------+--------------------------------------+
| Themes      | :ref:`Analyze`                       |
+-------------+--------------------------------------+
| Severity    | Major                                |
+-------------+--------------------------------------+
| Time To Fix | Quick (30 mins)                      |
+-------------+--------------------------------------+
| Examples    | :ref:`openemr-constants-invalidname` |
+-------------+--------------------------------------+



.. _invalid-octal-in-string:

Invalid Octal In String
#######################


Any octal sequence inside a string can't be beyond 7. Those will be a fatal error at parsing time. 

This is true, starting with PHP 7.1. In PHP 7.0 and older, those sequences were silently adapted (divided by 0).

.. code-block:: php

   <?php
   
   // Emit no error in PHP 7.1
   echo 0; // @
   
   // Emit an error in PHP 7.1
   echo 0; // @
   
   ?>


See also `Integers <http://php.net/manual/en/language.types.integer.php>`_.

+-------------+---------------------------+
| Short name  | Type/OctalInString        |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP71` |
+-------------+---------------------------+
| Php Version | With PHP 7.1 and older    |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _invalid-pack-format:

Invalid Pack Format
###################


Some characters are invalid in a `pack() <http://www.php.net/pack>`_ format string.

`pack() <http://www.php.net/pack>`_ and `unpack() <http://www.php.net/unpack>`_ accept the following format specifiers : ``aAhHcCsSnviIlLNVqQJPfgGdeExXZ``. 

`unpack() <http://www.php.net/unpack>`_ also accepts a name after the format specifier and an optional quantifier. 

All other situations is not a valid, and produces a warning : ``pack(): Type t: unknown format code``

.. code-block:: php

   <?php
       $binarydata = pack(nvc*, 0x1234, 0x5678, 65, 66);
       
       // the first unsigned short is stored as 'first'. The next matches are names with numbers.
       $res = unpack('nfirst/vc*', $binarydata);
   ?>


Check `pack() <http://www.php.net/pack>`_ documentation for format specifiers that were introduced in various PHP version, namely 7.0, 7.1 and 7.2.

See also `pack <http://php.net/pack>`_ and 
         `unpack <http://php.net/pack>`_.


Suggestions
^^^^^^^^^^^

* Fix the packing format with correct values

+-------------+------------------------------+
| Short name  | Structures/InvalidPackFormat |
+-------------+------------------------------+
| Themes      | :ref:`Analyze`               |
+-------------+------------------------------+
| Severity    | Major                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _invalid-regex:

Invalid Regex
#############


The PCRE regex doesn't compile. It isn't a valid regex.

Several reasons may lead to this situation : syntax error, Unknown modifier, missing parenthesis or reference.

.. code-block:: php

   <?php
   
   // valid regex
   preg_match('/[abc]/', $string);
   
   // invalid regex (missing terminating ] for character class 
   preg_match('/[abc/', $string);
   
   ?>


Regex are check with the Exakat version of PHP. 

Dynamic regex are only checked for simple values. Dynamic values may eventually generate a compilation error.

+-------------+-------------------------+
| Short name  | Structures/InvalidRegex |
+-------------+-------------------------+
| Themes      | :ref:`Analyze`          |
+-------------+-------------------------+
| Severity    | Major                   |
+-------------+-------------------------+
| Time To Fix | Quick (30 mins)         |
+-------------+-------------------------+



.. _is-actually-zero:

Is Actually Zero
################


This addition actually may be simplified because one term is actually negated by another. 

This kind of error happens when the expression is very large : the more terms are included, the more chances are that some auto-annihilation happens. 

This error may also be a simple typo : for example, calculating the difference between two consecutive terms.

.. code-block:: php

   <?php
   
   // This is quite obvious
   $a = 2 - 2;
   
   // This is obvious too. This may be a typo-ed difference between two consecutive terms. 
   // Could have been $c = $fx[3][4] - $fx[3][3] or $c = $fx[3][5] - $fx[3][4];
   $c = $fx[3][4] - $fx[3][4];
   
   // This is less obvious
   $a = $b[3] - $c + $d->foo(1,2,3) + $c + $b[3];
   
   ?>




Suggestions
^^^^^^^^^^^

* Clean the code and remove the null sum
* Fix one of the variable : this expression needs another variable here
* When adding differences, calculate the difference in a temporary variable first.

+-------------+----------------------------------------------------------------------+
| Short name  | Structures/IsZero                                                    |
+-------------+----------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                       |
+-------------+----------------------------------------------------------------------+
| Severity    | Minor                                                                |
+-------------+----------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                     |
+-------------+----------------------------------------------------------------------+
| Examples    | :ref:`dolibarr-structures-iszero`, :ref:`suitecrm-structures-iszero` |
+-------------+----------------------------------------------------------------------+



.. _isset-multiple-arguments:

Isset Multiple Arguments
########################


`isset() <http://www.php.net/isset>`_ may be used with multiple arguments and acts as a AND.

.. code-block:: php

   <?php
   
   // isset without and 
   if (isset($a, $b, $c)) {
       // doSomething()
   }
   
   // isset with and 
   if (isset($a) && isset($b) && isset($c)) {
       // doSomething()
   }
   
   ?>


See also `isset <http://www.php.net/`isset <http://www.php.net/isset>`_>`_.

+-------------+-------------------------------------------------------------------------------+
| Short name  | Php/IssetMultipleArgs                                                         |
+-------------+-------------------------------------------------------------------------------+
| Themes      | :ref:`Suggestions`                                                            |
+-------------+-------------------------------------------------------------------------------+
| Severity    | Minor                                                                         |
+-------------+-------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                              |
+-------------+-------------------------------------------------------------------------------+
| Examples    | :ref:`thinkphp-php-issetmultipleargs`, :ref:`livezilla-php-issetmultipleargs` |
+-------------+-------------------------------------------------------------------------------+



.. _isset()-on-the-whole-array:

Isset() On The Whole Array
##########################


`Isset() <http://www.php.net/isset>`_ works quietly on a whole array. There is no need to test all previous index before testing for the target index.

.. code-block:: php

   <?php
   
   // Straight to the point
   if (isset($a[1]['source'])) {
       // Do something with $a[1]['source']
   }
   
   // Doing too much work
   if (isset($a) && isset($a[1]) && isset($a[1]['source'])) {
       // Do something with $a[1]['source']
   }
   
   ?>


There is a gain in readability, by avoiding long and hard to read logical expression, and reducing them in one simple `isset <http://www.php.net/isset>`_ call.

There is a gain in performances by using one call to `isset <http://www.php.net/isset>`_, instead of several, but it is a micro-optimization. 

See also `Isset <http://www.php.net/`isset <http://www.php.net/isset>`_>`_.



Suggestions
^^^^^^^^^^^

* Remove all unnecessary calls to isset()

+-------------+-----------------------------------------+
| Short name  | Performances/IssetWholeArray            |
+-------------+-----------------------------------------+
| Themes      | :ref:`Suggestions`, :ref:`Performances` |
+-------------+-----------------------------------------+
| Severity    | Minor                                   |
+-------------+-----------------------------------------+
| Time To Fix | Instant (5 mins)                        |
+-------------+-----------------------------------------+



.. _joining-file():

Joining file()
##############


Use `file() <http://www.php.net/file>`_ to read lines separately. 

Applying join('', ) or implode('', ) to the result of `file() <http://www.php.net/file>`_ provides the same results than using `file_get_contents() <http://www.php.net/file_get_contents>`_, but at a higher cost of memory and processing.

If the delimiter is not '', then `implode() <http://www.php.net/implode>`_ and `file() <http://www.php.net/file>`_ are a better solution than `file_get_contents() <http://www.php.net/file_get_contents>`_ and `str_replace() <http://www.php.net/str_replace>`_ or `nl2br() <http://www.php.net/nl2br>`_.

.. code-block:: php

   <?php
   
   // memory intensive
   $content = file_get_contents('path/to/file.txt');
   
   // memory and CPU intensive
   $content = join('', file('path/to/file.txt'));
   
   // Consider reading the data line by line and processing it along the way, 
   // to save memory 
   $fp = fopen('path/to/file.txt', 'r');
   while($line = fget($fp)) {
       // process a line
   }
   fclose($fp);
   
   ?>


Always use `file_get_contents() <http://www.php.net/file_get_contents>`_ to get the content of a file as a string. Consider using `readfile() <http://www.php.net/readfile>`_ to echo the content directly to the output.

See also `file_get_contents <http://php.net/file_get_contents>`_ and 
         `file <http://php.net/file>`_.


Suggestions
^^^^^^^^^^^

* Use file_get_contents() instead of implode(file()) to read the whole file at once.
* Use readfile() to echo the content to stdout at once.
* Use fopen() to read the lines one by one, generator style.

+-------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Performances/JoinFile                                                                                                                                             |
+-------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Performances`                                                                                                                                               |
+-------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                                                                             |
+-------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                                                                   |
+-------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`wordpress-performances-joinfile`, :ref:`spip-performances-joinfile`, :ref:`expressionengine-performances-joinfile`, :ref:`prestashop-performances-joinfile` |
+-------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _list-short-syntax:

List Short Syntax
#################


Usage of short syntax version of list().

.. code-block:: php

   <?php
   
   // PHP 7.1 short list syntax
   // PHP 7.1 may also use key => value structures with list
   [$a, $b, $c] = ['2', 3, '4'];
   
   // PHP 7.0 list syntax
   list($a, $b, $c) = ['2', 3, '4'];
   
   ?>

+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Php/ListShortSyntax                                                                                                                   |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.1 and more recent                                                                                                          |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                                                 |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                                       |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+



.. _list-with-appends:

List With Appends
#################


List() behavior has changed in PHP 7.0 and it has impact on the indexing when list is used with the [] operator. 

.. code-block:: php

   <?php
   
   $x = array();
   list($x[], $x[], $x[]) = [1, 2, 3];
   
   print_r($x);
   
   ?>


In PHP 7.0, results are :::

   
   Array
   (
       [0] => 1
       [1] => 2
       [2] => 3
   )
   


In PHP 5.6, results are :::

   
   Array
   (
       [0] => 3
       [1] => 2
       [2] => 1
   )

+-------------+---------------------------+
| Short name  | Php/ListWithAppends       |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP70` |
+-------------+---------------------------+
| Severity    | Minor                     |
+-------------+---------------------------+
| Time To Fix | Slow (1 hour)             |
+-------------+---------------------------+



.. _list-with-keys:

List With Keys
##############


Setting keys when using list() is a PHP 7.1 feature.

.. code-block:: php

   <?php
   
   // PHP 7.1 and later only
   list('a' => $a, 'b' => $b) = ['b' => 1, 'c' => 2, 'a' => 3];
   
   ?>

+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Php/ListWithKeys                                                                                                                      |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.1 and more recent                                                                                                          |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                                                 |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                                       |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+



.. _list-with-reference:

List With Reference
###################


Support for references in list calls is not backward compatible with older versions of PHP. The support was introduced in PHP 7.3.

.. code-block:: php

   <?php
   
   $a = [1,2,3];
   
   [$c, $d, $e] = $a;
   
   $d++;
   echo $a[2]; // Displays 4
   
   ?>


See also `list() Reference Assignment <https://wiki.php.net/rfc/list_reference_assignment>`_.

+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Php/ListWithReference                                                                                                                                                                       |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71`, :ref:`CompatibilityPHP72`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.3 and more recent                                                                                                                                                                |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                                                                                                       |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                                                                                                               |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _local-globals:

Local Globals
#############


A global variable is used locally in a method. 

Either the global keyword has been forgotten, or the local variable should be renamed in a less ambiguous manner.

Having both a global and a local variable with the same name is legit. PHP keeps the contexts separated, and it processes them independently.

However, in the mind of the coder, it is easy to mistake the local variable $x and the global variable $x. May they be given different meaning, and this is an error-prone situation. 

It is recommended to keep the global variables's name distinct from the local variables. 

.. code-block:: php

   <?php
   
   // This is actualy a global variable
   $variable = 1;
   $globalVariable = 2;
   
   function foo() {
       global $globalVariable2;
       
       $variable = 4;
       $localVariable = 3;
       
       // This always displays 423, instead of 123
       echo $variable .' ' . $globalVariable . ' ' . $localVariable;
   }
   
   ?>

+-------------+------------------------+
| Short name  | Variables/LocalGlobals |
+-------------+------------------------+
| Themes      | :ref:`Analyze`         |
+-------------+------------------------+
| Severity    | Minor                  |
+-------------+------------------------+
| Time To Fix | Slow (1 hour)          |
+-------------+------------------------+



.. _locally-unused-property:

Locally Unused Property
#######################


Those properties are defined in a class, and this class doesn't have any method that makes use of them. 

While this is syntactically correct, it is unusual that defined resources are used in a child class. It may be worth moving the definition to another class, or to move accessing methods to the class.

.. code-block:: php

   <?php
   
   class foo {
       public $unused, $used;// property $unused is never used in this class
       
       function bar() {
           $this->used++; // property $used is used in this method
       }
   }
   
   class foofoo extends foo {
       function bar() {
           $this->unused++; // property $unused is used in this method, but defined in the parent class
       }
   }
   
   ?>

+-------------+-------------------------------+
| Short name  | Classes/LocallyUnusedProperty |
+-------------+-------------------------------+
| Themes      | :ref:`Dead code <dead-code>`  |
+-------------+-------------------------------+
| Severity    | Minor                         |
+-------------+-------------------------------+
| Time To Fix | Slow (1 hour)                 |
+-------------+-------------------------------+



.. _logical-mistakes:

Logical Mistakes
################


Avoid logical mistakes within long expressions. 

Sometimes, the logic is not what it seems. It is important to check the actual impact of every part of the logical expression. Do not hesitate to make a table with all possible cases. If those cases are too numerous, it may be time to rethink the whole expression. 

.. code-block:: php

   <?php 
   
   // Always false
   if ($a != 1 || $a != 2) { } 
   
   // $a == 1 is useless
   if ($a == 1 || $a != 2) {}
   
   // Always false
   if ($a == 1 && $a == 2) {}
   
   // $a != 2 is useless
   if ($a == 1 && $a != 2) {}
   
   ?>


Based on article from ``Andrey Karpov``  `Logical Expressions in C/C++. Mistakes Made by Professionals <http://www.viva64.com/en/b/0390/>`_

+-------------+----------------------------+
| Short name  | Structures/LogicalMistakes |
+-------------+----------------------------+
| Themes      | :ref:`Analyze`             |
+-------------+----------------------------+
| Severity    | Critical                   |
+-------------+----------------------------+
| Time To Fix | Quick (30 mins)            |
+-------------+----------------------------+



.. _logical-should-use-symbolic-operators:

Logical Should Use Symbolic Operators
#####################################


Logical operators come in two flavors :  and / &&, || / or, ^ / xor. However, they are not exchangeable, as && and and have different precedence. 

.. code-block:: php

   <?php
   
   // Avoid lettered operator, as they have lower priority than expected
   $a = $b and $c;
   // $a === 3 because equivalent to ($a = $b) and $c;
   
   // safe way to write the above : 
   $a = ($b and $c);
   
   $a = $b && $c;
   // $a === 1
   
   ?>


It is recommended to use the symbol operators, rather than the letter ones.

See also `Logical Operators <http://php.net/manual/en/language.operators.logical.php>`_.



Suggestions
^^^^^^^^^^^

* Change the letter operators to the symbol one : and => &&, or => ||, xor => ^. Review the new expressions as processing order may have changed.
* Add parenthesis to make sure that the order is the expted one

+-------------+---------------------------------------------------------------------------------------------------+
| Short name  | Php/LogicalInLetters                                                                              |
+-------------+---------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Suggestions`                                                                |
+-------------+---------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                             |
+-------------+---------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                  |
+-------------+---------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-letter-logical <https://github.com/dseguy/clearPHP/tree/master/rules/no-letter-logical.md>`__ |
+-------------+---------------------------------------------------------------------------------------------------+
| Examples    | :ref:`cleverstyle-php-logicalinletters`, :ref:`openconf-php-logicalinletters`                     |
+-------------+---------------------------------------------------------------------------------------------------+



.. _logical-to-in\_array:

Logical To in_array
###################


Multiples exclusive comparisons may be replaced by `in_array() <http://www.php.net/in_array>`_.

`in_array() <http://www.php.net/in_array>`_ makes the alternatives more readable, especially when the number of alternatives is large. In fact, the list of alternative may even be set in a variable, and centralized for easier management.

Even two 'or' comparisons are slower than using a `in_array() <http://www.php.net/in_array>`_ call. More calls are even slower than just two. This is a micro-optimisation : speed gain is low, and marginal. Code centralisation is a more significant advantage.

.. code-block:: php

   <?php
   
   // Set the list of alternative in a variable, property or constant. 
   $valid_values = array(1, 2, 3, 4);
   if (in_array($a, $valid_values) ) {
       // doSomething()
   }
   
   if ($a == 1 || $a == 2 || $a == 3 || $a == 4) {
       // doSomething()
   }
   
   // in_array also works with strict comparisons
   if (in_array($a, $valid_values, true) ) {
       // doSomething()
   }
   
   if ($a === 1 || $a === 2 || $a === 3 || $a === 4) {
       // doSomething()
   }
   
   ?>


See also `in_array() <http://www.php.net/in_array>`_.

+-------------+----------------------------------------------+
| Short name  | Performances/LogicalToInArray                |
+-------------+----------------------------------------------+
| Themes      | :ref:`Analyze`                               |
+-------------+----------------------------------------------+
| Severity    | Minor                                        |
+-------------+----------------------------------------------+
| Time To Fix | Quick (30 mins)                              |
+-------------+----------------------------------------------+
| Examples    | :ref:`zencart-performances-logicaltoinarray` |
+-------------+----------------------------------------------+



.. _lone-blocks:

Lone Blocks
###########


Any grouped code without a commanding structure is useless. 

Blocks are compulsory when defining a structure, such as a class or a function. They are most often used with flow control instructions, like if then or switch. 

Blocks are also valid syntax that group several instructions together, though they have no effect at all, except confuse the reader. Most often, it is a ruin from a previous flow control instruction, whose condition was removed or commented. They should be removed. 

.. code-block:: php

   <?php
   
       // Lone block
       //foreach($a as $b) 
       {
           $b++;
       }
   ?>

+-------------+----------------------+
| Short name  | Structures/LoneBlock |
+-------------+----------------------+
| Themes      | :ref:`Analyze`       |
+-------------+----------------------+
| Severity    | Minor                |
+-------------+----------------------+
| Time To Fix | Instant (5 mins)     |
+-------------+----------------------+



.. _long-arguments:

Long Arguments
##############


Long arguments should be put in variable, to preserve readability. 

When literal arguments are too long, they `break <http://php.net/manual/en/control-structures.break.php>`_ the hosting structure by moving the next argument too far on the right. Whenever possible, long arguments should be set in a local variable to keep the readability.

.. code-block:: php

   <?php
   
   // Now the call to foo() is easier to read.
   $reallyBigNumber = <<<BIGNUMBER
   123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890
   BIGNUMBER
   foo($reallyBigNumber, 2, '12345678901234567890123456789012345678901234567890');
   
   // where are the next arguments ? 
   foo('123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890', 2, '123456789012345678901234567890123456789012345678901234567890');
   
   // This is still difficult to read
   foo(<<<BIGNUMBER
   123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890
   BIGNUMBER
   , 2, '123456789012345678901234567890123456789012345678901234567890');
   
   ?>


Literal strings and heredoc strings, including variables, that are over 50 chars longs are reported here.

+-------------+--------------------------+
| Short name  | Structures/LongArguments |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Minor                    |
+-------------+--------------------------+
| Time To Fix | Quick (30 mins)          |
+-------------+--------------------------+



.. _lost-references:

Lost References
###############


Either avoid references, or propagate them correctly.

When assigning a referenced variable with another reference, the initial reference is lost, while the intend was to transfer the content. 

.. code-block:: php

   <?php
   
   function foo(&$lostReference, &$keptReference)
   {
       $c = 'c';
   
       // $lostReference was a reference, but now, it is another
       $lostReference =& $c;
       // $keptReference was a reference : now it contains the actual value
       $keptReference = $c;
   }
   
   $bar = 'bar';
   $bar2 = 'bar';
   foo($bar, $bar2); 
   
   //displays bar c, instead of bar bar
   print $bar. ' '.$bar2;
   
   ?>


Do not reassign a reference with another reference. Assign new content to the reference to change its value.

+-------------+--------------------------+
| Short name  | Variables/LostReferences |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Major                    |
+-------------+--------------------------+
| Time To Fix | Quick (30 mins)          |
+-------------+--------------------------+



.. _magic-visibility:

Magic Visibility
################


The class magic methods must have public visibility and cannot be static.

.. code-block:: php

   <?php
   
   class foo{
       // magic method must bt public and non-static
       public static function __clone($name) {    }
   
       // magic method can't be private
       private function __get($name) {    }
   
       // magic method can't be protected
       private function __set($name, $value) {    }
   
       // magic method can't be static
       public static function __isset($name) {    }
   }
   
   ?>


See also `Magic methods <http://php.net/manual/en/language.oop5.magic.php>`_.

+-------------+---------------------------+
| Short name  | Classes/toStringPss       |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP70` |
+-------------+---------------------------+
| Php Version | With PHP 5.4 and older    |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _make-global-a-property:

Make Global A Property
######################


Calling global (or $GLOBALS) in methods is slower and less testable than setting the global to a property, and using this property.

Using properties is slightly faster than calling global or $GLOBALS, though the gain is not important. 

Setting the property in the constructor (or in a factory), makes the class easier to test, as there is now a single point of configuration.

.. code-block:: php

   <?php 
   
   // Wrong way
   class fooBad {
       function x() {
           global $a;
           $a->do();
           // Or $GLOBALS['a']->do();
       }
   }
   
   class fooGood {
       private $bar = null;
       
       function __construct() {
           global $bar; 
           $this->bar = $bar;
           // Even better, do this via arguments
       }
       
       function x() {
           $this->a->do();
       }
   }
   
   ?>

+-------------+-----------------------------+
| Short name  | Classes/MakeGlobalAProperty |
+-------------+-----------------------------+
| Themes      | :ref:`Analyze`              |
+-------------+-----------------------------+
| Severity    | Minor                       |
+-------------+-----------------------------+
| Time To Fix | Slow (1 hour)               |
+-------------+-----------------------------+



.. _make-one-call-with-array:

Make One Call With Array
########################


Avoid calling the same function several times by batching the calls with arrays.

Calling the same function to chain modifications tends to be slower than calling the same function with all the transformations at the same time. Some PHP functions accept scalars or arrays, and using the later is more efficient.

.. code-block:: php

   <?php
   
   $string = 'abcdef'; 
   
   //str_replace() accepts arrays as arguments
   $string = str_replace( ['a', 'b', 'c'],
                          ['A', 'B', 'C'],
                          $string);
   
   // Too many calls to str_replace
   $string = str_replace( 'a', 'A', $string);
   $string = str_replace( 'b', 'B', $string);
   $string = str_replace( 'c', 'C', $string);
   
   // Too many nested calls to str_replace
   $string = str_replace( 'a', 'A', str_replace( 'b', 'B', str_replace( 'c', 'C', $string)));
   
   ?>


Potential replacements : 

+-------------------------------------------------------------------------+------------------------------------------------------------------------------------+
| Function                                                                | Replacement                                                                        |
+-------------------------------------------------------------------------+------------------------------------------------------------------------------------+
| `str_replace() <http://www.php.net/str_replace>`_                       | `str_replace() <http://www.php.net/str_replace>`_                                  |
| `str_ireplace() <http://www.php.net/str_ireplace>`_                     | `str_replace() <http://www.php.net/str_replace>`_                                  |
| `substr_replace() <http://www.php.net/substr_replace>`_                 | `substr_replace() <http://www.php.net/substr_replace>`_                            |
| `preg_replace() <http://www.php.net/preg_replace>`_                     | `preg_replace() <http://www.php.net/preg_replace>`_                                |
| `preg_replace_callback() <http://www.php.net/preg_replace_callback>`_   | `preg_replace_callback_array() <http://www.php.net/preg_replace_callback_array>`_  |
+-------------------------------------------------------------------------+------------------------------------------------------------------------------------+

.. code-block:: php

   <?php
   $subject = 'Aaaaaa Bbb';
   
   
   //preg_replace_callback_array() is better than multiple preg_replace_callback : 
   preg_replace_callback_array(
       [
           '~[a]+~i' => function ($match) {
               echo strlen($match[0]), ' matches for a found', PHP_EOL;
           },
           '~[b]+~i' => function ($match) {
               echo strlen($match[0]), ' matches for b found', PHP_EOL;
           }
       ],
       $subject
   );
   
   $result = preg_replace_callback('~[a]+~i', function ($match) {
               echo strlen($match[0]), ' matches for a found', PHP_EOL;
           }, $subject);
   
   $result = preg_replace_callback('~[b]+~i', function ($match) {
               echo strlen($match[0]), ' matches for b found', PHP_EOL;
           }, $subject);
   
   //str_replace() accepts arrays as arguments
   $string = str_replace( ['a', 'b', 'c'],
                          ['A', 'B', 'C'],
                          $string);
   
   // Too many calls to str_replace
   $string = str_replace( 'a', 'A');
   $string = str_replace( 'b', 'B');
   $string = str_replace( 'c', 'C');
   
   ?>


 

Suggestions
^^^^^^^^^^^

* use str_replace() with arrays as arguments.
* use preg_replace() with arrays as arguments.
* use preg_replace_callback() for merging multiple complex calls.

+-------------+-----------------------------------------------------------------------------------+
| Short name  | Performances/MakeOneCall                                                          |
+-------------+-----------------------------------------------------------------------------------+
| Themes      | :ref:`Performances`                                                               |
+-------------+-----------------------------------------------------------------------------------+
| Severity    | Major                                                                             |
+-------------+-----------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                   |
+-------------+-----------------------------------------------------------------------------------+
| Examples    | :ref:`humo-gen-performances-makeonecall`, :ref:`edusoho-performances-makeonecall` |
+-------------+-----------------------------------------------------------------------------------+



.. _malformed-octal:

Malformed Octal
###############


Those numbers starts with a 0, so they are using the PHP octal convention. Therefore, one can't use 8 or 9 figures in those numbers, as they don't belong to the octal base. The resulting number will be truncated at the first erroneous figure. For example, 090 is actually 0, and 02689 is actually 22. 

.. code-block:: php

   <?php
   
   // A long way to write 0 in PHP 5
   $a = 0890; 
   
   // A fatal error since PHP 7
   
   ?>


Also, note that very large octal, usually with more than 21 figures, will be turned into a real number and undergo a reduction in precision.

See also `Integers <http://php.net/manual/en/language.types.integer.php>`_.

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Type/MalformedOctal                                                                                        |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and older                                                                                     |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                           |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _method-collision-traits:

Method Collision Traits
#######################


Two or more traits are included in the same class, and they have methods collisions. 

Those collisions should be solved with a ``use`` expression. When they are not, PHP stops execution with a fatal error : ``Trait method M has not been applied, because there are collisions with other trait methods on C``.

.. code-block:: php

   <?php
   
   trait A {
       public function A() {}
       public function M() {}
   }
   
   trait B {
       public function B() {}
       public function M() {}
   }
   
   class C {
       use  A, B;
   }
   
   class D {
       use  A, B{
           B::M insteadof A;
       };
   }
   
   ?>


The code above lints, but doesn't execute.

See also `Traits <http://php.net/manual/en/language.oop5.traits.php>`_.

+-------------+----------------------------------------+
| Short name  | Traits/MethodCollisionTraits           |
+-------------+----------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`LintButWontExec` |
+-------------+----------------------------------------+
| Severity    | Critical                               |
+-------------+----------------------------------------+
| Time To Fix | Quick (30 mins)                        |
+-------------+----------------------------------------+



.. _method-could-be-private-method:

Method Could Be Private Method
##############################


The following methods are never used outside their class of definition. Given the analyzed code, they could be set as private. 

.. code-block:: php

   <?php
   
   class foo {
       public function couldBePrivate() {}
       public function cantdBePrivate() {}
       
       function bar() {
           // couldBePrivate is used internally. 
           $this->couldBePrivate();
       }
   }
   
   class foo2 extends foo {
       function bar2() {
           // cantdBePrivate is used in a child class. 
           $this->cantdBePrivate();
       }
   }
   
   //couldBePrivate() is not used outside 
   $foo = new foo();
   
   //cantdBePrivate is used outside the class
   $foo->cantdBePrivate();
   
   ?>


Note that dynamic properties (such as $x->$y) are not taken into account.

+-------------+------------------------------+
| Short name  | Classes/CouldBePrivateMethod |
+-------------+------------------------------+
| Themes      | :ref:`ClassReview`           |
+-------------+------------------------------+
| Severity    | Minor                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _method-could-be-static:

Method Could Be Static
######################


A method that doesn't make any usage of `$this <http://php.net/manual/en/language.oop5.basic.php>`_ could be turned into a static method. 

While static methods are usually harder to handle, recognizing the static status is a first step before turning the method into a standalone function.

.. code-block:: php

   <?php
   
   class foo {
       static $property = 1;
       
       // legit static method
       static function staticMethod() {
           return self::$property;
       }
   
       // This is not using $this, and could be static
       function nonStaticMethod() {
           return self::$property;
       }
   
       // This is not using $this nor self, could be a standalone function
       function nonStaticMethod() {
           return self::$property;
       }
   }
   
   ?>




Suggestions
^^^^^^^^^^^

* Make the method static
* Make the method a standalone function
* Make use of $this in the method : may be it was forgotten.

+-------------+-----------------------+
| Short name  | Classes/CouldBeStatic |
+-------------+-----------------------+
| Themes      | none                  |
+-------------+-----------------------+
| Severity    | Minor                 |
+-------------+-----------------------+
| Time To Fix | Quick (30 mins)       |
+-------------+-----------------------+



.. _method-signature-must-be-compatible:

Method Signature Must Be Compatible
###################################


Make sure methods signature are compatible.

PHP generates the infamous Fatal error at execution : ``Declaration of FooParent\:\:Bar() must be compatible with FooChildren\:\:Bar()``

.. code-block:: php

   <?php
   
   class x {
       function xa() {}
   }
   
   class xxx extends xx {
       function xa($a) {}
   }
   
   ?>


Currently, the analysis doesn't check for ellipsis nor references.



Suggestions
^^^^^^^^^^^

* Fix the child class method() signature.
* Fix the parent class method() signature, after checking that it won't affect the other children.

+-------------+-----------------------------------------+
| Short name  | Classes/MethodSignatureMustBeCompatible |
+-------------+-----------------------------------------+
| Themes      | :ref:`Analyze`                          |
+-------------+-----------------------------------------+
| Severity    | Critical                                |
+-------------+-----------------------------------------+
| Time To Fix | Quick (30 mins)                         |
+-------------+-----------------------------------------+



.. _methodcall-on-new:

Methodcall On New
#################


It is possible to call a method right at object instanciation. 

This syntax was added in PHP 5.4+. Before, this was not possible : the object had to be stored in a variable first.

.. code-block:: php

   <?php
   
   // Data is collected
   $data = data_source();
   
   // Data is saved, but won't be reused from this databaseRow object. It may be ignored.
   $result = (new databaseRow($data))->save();
   
   // The actual result of the save() is collected and tested.
   if ($result !== true) {
       processSaveError($data);
   }
   
   ?>


This syntax is interesting when the object is not reused, and may be discarded

+-------------+------------------------------+
| Short name  | Php/MethodCallOnNew          |
+-------------+------------------------------+
| Themes      | :ref:`CompatibilityPHP53`    |
+-------------+------------------------------+
| Php Version | With PHP 5.4 and more recent |
+-------------+------------------------------+
| Severity    | Major                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _mismatch-type-and-default:

Mismatch Type And Default
#########################


The argument typehint and its default value don't match. 

The code may lint and load, and even work when the argument are provided. Though, PHP won't eventually execute it. 

Most of the mismatch problems are caught by PHP at linting time. You'll get the following error message : 'Argument 1 passed to foo() must be of the type integer, string given'.

The default value may be a constant (normal or class constant) : as such, PHP might find its value only at execution time, from another include. As such, PHP doesn't report anything about the situation at compile time.

The default value may also be a constant scalar expression : since PHP 7, some of the simple operators such as +, -, *, %, `** <http://php.net/manual/en/language.operators.arithmetic.php>`_, etc. are available to build default values. Among them, the ternary operator and Coalesce. Again, those expression may be only evaluated at execution time, when the value of the constants are known. 

.. code-block:: php

   <?php
   
   // bad definition
   const STRING = 3;
   
   function foo(string $s = STRING) {
       echo $s;
   }
   
   // works without problem
   foo('string');
   
   // Fatal error at execution time
   foo();
   
   ?>


See also `Type declarations <http://php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration>`_.

+-------------+----------------------------------+
| Short name  | Functions/MismatchTypeAndDefault |
+-------------+----------------------------------+
| Themes      | :ref:`Analyze`                   |
+-------------+----------------------------------+
| Severity    | Critical                         |
+-------------+----------------------------------+
| Time To Fix | Slow (1 hour)                    |
+-------------+----------------------------------+



.. _mismatched-default-arguments:

Mismatched Default Arguments
############################


Arguments are relayed from one method to the other, and the arguments have different default values. 

Although it is possible to have different default values, it is worth checking why this is actually the case.

.. code-block:: php

   <?php
   
   function foo($a = null, $b = array() ) {
       // foo method calls directly bar. 
       // When argument are provided, it's OK
       // When argument are omited, the default value is not the same as the next method
       bar($a, $b);
   }
   
   function bar($c = 1, $d = array() ) {
   
   }
   
   ?>

+-------------+--------------------------------------+
| Short name  | Functions/MismatchedDefaultArguments |
+-------------+--------------------------------------+
| Themes      | :ref:`Analyze`                       |
+-------------+--------------------------------------+
| Severity    | Minor                                |
+-------------+--------------------------------------+
| Time To Fix | Quick (30 mins)                      |
+-------------+--------------------------------------+



.. _mismatched-ternary-alternatives:

Mismatched Ternary Alternatives
###############################


A ternary operator should yield the same type on both branches.

Ternary operator applies a condition, and yield two different results. Those results will then be processed by code that expects the same types. It is recommended to match the types on both branches of the ternary operator.

.. code-block:: php

   <?php
   
   // $object may end up in a very unstable state
   $object = ($type == 'Type') ? new $type() : null;
   
   //same result are provided by both alternative, though process is very different
   $result = ($type == 'Addition') ? $a + $b : $a * $b;
   
   //Currently, this is omitted
   $a = 1;
   $result = empty($condition) ? $a : 'default value';
   $result = empty($condition) ? $a : getDefaultValue();
   
   ?>

+-------------+------------------------------------+
| Short name  | Structures/MismatchedTernary       |
+-------------+------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Suggestions` |
+-------------+------------------------------------+
| Severity    | Major                              |
+-------------+------------------------------------+
| Time To Fix | Quick (30 mins)                    |
+-------------+------------------------------------+



.. _mismatched-typehint:

Mismatched Typehint
###################


Relayed arguments don't have the same typehint.

Typehint acts as a filter method. When an object is checked with a first class, and then checked again with a second distinct class, the whole process is always false : $a can't be of two different classes at the same time.

.. code-block:: php

   <?php
   
   // Foo() calls bar()
   function foo(A $a, B $b) {
       bar($a, $b);
   }
   
   // $a is of A typehint in both methods, but 
   // $b is of B then BB typehing
   function bar(A $a, BB $b) {
   
   }
   
   ?>


Note : This analysis currently doesn't check generalisation of classes : for example, when B is a child of BB, it is still reported as a mismatch.

+-------------+------------------------------+
| Short name  | Functions/MismatchedTypehint |
+-------------+------------------------------+
| Themes      | :ref:`Analyze`               |
+-------------+------------------------------+
| Severity    | Major                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _missing-cases-in-switch:

Missing Cases In Switch
#######################


It seems that some cases are missing in this switch structure.

When comparing two different `switch() <http://php.net/manual/en/control-structures.switch.php>`_ structures, it appears that some cases are missing in one of them. The set of cases are almost identical, but one of the values are missing. 

`Switch() <http://php.net/manual/en/control-structures.switch.php>`_ structures using strings as literals are compared in this analysis. When the discrepancy between two lists is below 25%, both switches are reported.

.. code-block:: php

   <?php
   
   // This switch operates on a, b, c, d and default 
   switch($a) {
       case 'a': doSomethingA(); break 1;
       case 'b': doSomethingB(); break 1;
       case 'c': doSomethingC(); break 1;
       case 'd': doSomethingD(); break 1;
       default: doNothing();
   }
   
   // This switch operates on a, b, d and default 
   switch($o->p) {
       case 'a': doSomethingA(); break 1;
       case 'b': doSomethingB(); break 1;
   
       case 'd': doSomethingD(); break 1;
       default: doNothing();
   }
   
   ?>


In the example, one may argue that the 'c' case is actually handled by the 'default' case. Otherwise, business logic may request that omission.

+-------------+-------------------------+
| Short name  | Structures/MissingCases |
+-------------+-------------------------+
| Themes      | :ref:`Analyze`          |
+-------------+-------------------------+
| Severity    | Minor                   |
+-------------+-------------------------+
| Time To Fix | Slow (1 hour)           |
+-------------+-------------------------+



.. _missing-include:

Missing Include
###############


The included files doesn't exists in the repository. The inclusions target a files that doesn't exist.

The analysis works with every type of inclusion : include, require, include_once and require_once. It also works with parenthesis when used as parameter delimiter.

The analysis doesn't take into account include_path. This may yield false positives.

.. code-block:: php

   <?php
   
   include 'non_existent.php';
   
   // variables are not resolved. This won't be reported.
   require ($path.'non_existent.php');
   
   ?>


Missing included files may lead to a fatal error, a warning or other error later in the execution.

+-------------+----------------------+
| Short name  | Files/MissingInclude |
+-------------+----------------------+
| Themes      | :ref:`Analyze`       |
+-------------+----------------------+
| Severity    | Critical             |
+-------------+----------------------+
| Time To Fix | Instant (5 mins)     |
+-------------+----------------------+



.. _missing-new-?:

Missing New ?
#############


This functioncall looks like a class instantiation that is missing the new keyword.

Any function definition was found for that function, but a class with that name was. New is probably missing.

.. code-block:: php

   <?php
   
   // Functioncall
   $a = foo2();
   
   // Class definition
   class foo2 {}
   
   ?>

+-------------+-----------------------+
| Short name  | Structures/MissingNew |
+-------------+-----------------------+
| Themes      | :ref:`Analyze`        |
+-------------+-----------------------+
| Severity    | Critical              |
+-------------+-----------------------+
| Time To Fix | Instant (5 mins)      |
+-------------+-----------------------+



.. _missing-parenthesis:

Missing Parenthesis
###################


Add parenthesis to those expression to prevent bugs. 

.. code-block:: php

   <?php
   
   // Missing some parenthesis!!
   if (!$a instanceof Stdclass) {
       print Not\n;
   } else {
       print Is\n;
   }
   
   // Could this addition be actually
   $c = -$a + $b;
   
   // This one ? 
   $c = -($a + $b);
   
   ?>


See also `Operators Precedence <http://php.net/manual/en/language.operators.precedence.php>`_.

+-------------+-------------------------------+
| Short name  | Structures/MissingParenthesis |
+-------------+-------------------------------+
| Themes      | :ref:`Analyze`                |
+-------------+-------------------------------+
| Severity    | Major                         |
+-------------+-------------------------------+
| Time To Fix | Instant (5 mins)              |
+-------------+-------------------------------+



.. _mistaken-concatenation:

Mistaken Concatenation
######################


A unexpected structure is built for initialization. It may be a typo that creates an unwanted expression.

.. code-block:: php

   <?php
   
   // This 'cd' is unexpected. Isn't it 'c', 'd' ? 
   $array = array('a', 'b', 'c'. 'd');
   $array = array('a', 'b', 'c', 'd');
   
   // This 4.5 is unexpected. Isn't it 4, 5 ? 
   $array = array(1, 2, 3, 4.5);
   $array = array(1, 2, 3, 4, 5);
   
   ?>

+-------------+------------------------------+
| Short name  | Arrays/MistakenConcatenation |
+-------------+------------------------------+
| Themes      | :ref:`Analyze`               |
+-------------+------------------------------+
| Severity    | Major                        |
+-------------+------------------------------+
| Time To Fix | Instant (5 mins)             |
+-------------+------------------------------+



.. _mixed-concat-and-interpolation:

Mixed Concat And Interpolation
##############################


Mixed usage of concatenation and string interpolation is error prone. It is harder to read, and leads to overlooking the concatenation or the interpolation.

.. code-block:: php

   <?php
   
   // Concatenation string
   $a = $b . 'c' . $d;
   
   // Interpolation strings
   $a = {$b}c{$d};   // regular form
   $a = {$b}c$d;     // irregular form
   
   // Mixed Concatenation and Interpolation string
   $a = {$b}c . $d;
   $a = $b . c$d;
   $a = $b . c{$d};
   
   // Mixed Concatenation and Interpolation string with constant
   $a = {$b}c . CONSTANT;
   
   ?>


Fixing this issue has no impact on the output. It makes code less error prone.

There are some situations where using concatenation are compulsory : when using a constant, calling a function, running a complex expression or make use of the escape sequence. You may also consider pushing the storing of such expression in a local variable.

+-------------+---------------------------------------------------------------------------------------------------------+
| Short name  | Structures/MixedConcatInterpolation                                                                     |
+-------------+---------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Coding Conventions <coding-conventions>`, :ref:`Analyze`                                          |
+-------------+---------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                   |
+-------------+---------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                         |
+-------------+---------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`suitecrm-structures-mixedconcatinterpolation`, :ref:`edusoho-structures-mixedconcatinterpolation` |
+-------------+---------------------------------------------------------------------------------------------------------+



.. _mixed-keys-arrays:

Mixed Keys Arrays
#################


Avoid mixing constants and literals in array keys.

When defining default values in arrays, it is recommended to avoid mixing constants and literals, as PHP may mistake them and overwrite the previous with the latter.

Either switch to a newer version of PHP (5.5 or newer), or make sure the resulting array hold the expected data. If not, reorder the definitions.

.. code-block:: php

   <?php
   
   const ONE = 1;
   
   $a = [ 1   => 2,
          ONE => 3];
   
   ?>



Suggestions
^^^^^^^^^^^

* Use only literals or constants when building the array

+-------------+------------------------------------------------------+
| Short name  | Arrays/MixedKeys                                     |
+-------------+------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54` |
+-------------+------------------------------------------------------+
| Php Version | With PHP 5.6 and more recent                         |
+-------------+------------------------------------------------------+
| Severity    | Minor                                                |
+-------------+------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                        |
+-------------+------------------------------------------------------+



.. _mkdir-default:

Mkdir Default
#############


`mkdir() <http://www.php.net/mkdir>`_ gives universal access to created folders, by default. It is recommended to gives limited set of rights (0755, 0700), or to explicitly set the rights to 0777. 

.. code-block:: php

   <?php
   
   // By default, this dir is 777
   mkdir('/path/to/dir');
   
   // Explicitely, this is wanted. It may also be audited easily
   mkdir('/path/to/dir', 0777);
   
   // This dir is limited to the current user. 
   mkdir('/path/to/dir', 0700);
   
   ?>


See also `Why 777 Folder Permissions are a Security Risk <https://www.spiralscripts.co.uk/Blog/why-777-folder-permissions-are-a-security-risk.html>`_.

+-------------+-----------------------+
| Short name  | Security/MkdirDefault |
+-------------+-----------------------+
| Themes      | :ref:`Security`       |
+-------------+-----------------------+
| Severity    | Major                 |
+-------------+-----------------------+
| Time To Fix | Quick (30 mins)       |
+-------------+-----------------------+



.. _modernize-empty-with-expression:

Modernize Empty With Expression
###############################


`empty() <http://www.php.net/empty>`_ accept expressions since PHP 5.5. There is no need to store the expression in a variable before testing, unless it is reused later.

.. code-block:: php

   <?php
   
   // PHP 5.5+ empty() usage
   if (empty(strtolower($b . $c))) {
       doSomethingWithoutA();
   }
   
   // Compatible empty() usage
   $a = strtolower($b . $c);
   if (empty($a)) {
       doSomethingWithoutA();
   }
   
   // $a2 is reused, storage is legit
   $a2 = strtolower($b . $c);
   if (empty($a2)) {
       doSomething();
   } else {
       echo $a2;
   }
   
   ?>


See also `empty() <http://www.php.net/empty>`_.

+-------------+------------------------------+
| Short name  | Structures/ModernEmpty       |
+-------------+------------------------------+
| Themes      | :ref:`Analyze`               |
+-------------+------------------------------+
| Php Version | With PHP 5.5 and more recent |
+-------------+------------------------------+
| Severity    | Minor                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _multiple-alias-definitions:

Multiple Alias Definitions
##########################


Some aliases are representing different classes across the repository. This leads to potential confusion. 

Across an application, it is recommended to use the same namespace for one alias. Failing to do this lead to the same keyword to represent different values in different files, with different behavior. Those are hard to find bugs. 

.. code-block:: php

   <?php
   
   namespace A {
       use d\d; // aka D
   }
   
   // Those are usually in different files, rather than just different namespaces.
   
   namespace B {
       use b\c as D; // also D. This could be named something else
   }
   
   ?>



Suggestions
^^^^^^^^^^^

* Give more specific names to classes
* Use an alias 'use A\B ac BC' to give locally another name

+-------------+--------------------------------------------------------------------------------------------------------+
| Short name  | Namespaces/MultipleAliasDefinitions                                                                    |
+-------------+--------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                         |
+-------------+--------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                  |
+-------------+--------------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                       |
+-------------+--------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`churchcrm-namespaces-multiplealiasdefinitions`, :ref:`phinx-namespaces-multiplealiasdefinitions` |
+-------------+--------------------------------------------------------------------------------------------------------+



.. _multiple-alias-definitions-per-file:

Multiple Alias Definitions Per File
###################################


Avoid aliasing the same name with different aliases. This leads to confusion.

.. code-block:: php

   <?php
   
   // first occurrence
   use name\space\ClasseName;
   
   // when this happens, several other uses are mentionned
   
   // name\space\ClasseName has now two names
   use name\space\ClasseName as anotherName;
   
   ?>


See also `Multiple Alias Definitions`_.

+-------------+-------------------------------------------+
| Short name  | Namespaces/MultipleAliasDefinitionPerFile |
+-------------+-------------------------------------------+
| Themes      | :ref:`Analyze`                            |
+-------------+-------------------------------------------+
| Severity    | Minor                                     |
+-------------+-------------------------------------------+
| Time To Fix | Slow (1 hour)                             |
+-------------+-------------------------------------------+



.. _multiple-class-declarations:

Multiple Class Declarations
###########################


It is possible to declare several times the same class in the code. PHP will not mention it until execution time, since declarations may be conditional. 

.. code-block:: php

   <?php
   
   $a = 1;
   
   // Conditional declaration
   if ($a == 1) {
       class foo {
           function method() { echo 'class 1';}
       }
   } else {
       class foo {
           function method() { echo 'class 2';}
       }
   }
   
   (new foo())->method();
   ?>


It is recommended to avoid declaring several times the same class in the code. The best practice is to separate them with namespaces, they are for here for that purpose. In case those two classes are to be used interchangeably, the best is to use an abstract class or an interface.

+-------------+------------------------------+
| Short name  | Classes/MultipleDeclarations |
+-------------+------------------------------+
| Themes      | :ref:`Analyze`               |
+-------------+------------------------------+
| Severity    | Major                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _multiple-classes-in-one-file:

Multiple Classes In One File
############################


It is regarded as a bad practice to store several classes in the same file. This is usually done to make life of __autoload() easier. 

It is often unexpected to find class ``foo`` in the ``bar.php`` file. This is also the case for interfaces and traits.

.. code-block:: php

   <?php
   
   // three classes in the same file
   class foo {}
   class bar {}
   class foobar{}
   
   ?>


One good reason to have multiple classes in one file is to reduce include time by providing everything into one nice include. 

See also `Is it a bad practice to have multiple classes in the same file? <https://stackoverflow.com/questions/360643/is-it-a-bad-practice-to-have-multiple-classes-in-the-same-file>`_.

+-------------+------------------------------------------------+
| Short name  | Classes/MultipleClassesInFile                  |
+-------------+------------------------------------------------+
| Themes      | :ref:`Coding Conventions <coding-conventions>` |
+-------------+------------------------------------------------+
| Severity    | Minor                                          |
+-------------+------------------------------------------------+
| Time To Fix | Quick (30 mins)                                |
+-------------+------------------------------------------------+



.. _multiple-constant-definition:

Multiple Constant Definition
############################


Some constants are defined several times in your code. This will lead to a fatal error, if they are defined during the same execution. 

Multiple definitions may happens at bootstrap, when the application code is collecting information about the current environment. It may also happen at inclusion time, which one set of constant being loaded, while other definition are not, avoiding conflict. Both are false positive. 

.. code-block:: php

   <?php
   
   // OS is defined twice. 
   if (PHP_OS == 'Windows') {
       define('OS', 'Win');
   } else {
       define('OS', 'Other');
   }
   
   ?>




Suggestions
^^^^^^^^^^^

* Move the constants to a class, and include the right class based on control flow.
* Give different names to the constants, and keep the condition close to utilisation.
* Move the constants to an external configuration file : it will be easier to identify that those constants may change.

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Constants/MultipleConstantDefinition                                                                       |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                             |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                            |
+-------------+------------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`dolibarr-constants-multipleconstantdefinition`, :ref:`openconf-constants-multipleconstantdefinition` |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _multiple-definition-of-the-same-argument:

Multiple Definition Of The Same Argument
########################################


A method's signature is holding twice (or more) the same argument. For example, function x ($a, $a) { `... <http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list>`_ }. 

This is accepted as is by PHP 5, and the last parameter's value will be assigned to the variable. PHP 7.0 and more recent has dropped this feature, and reports a fatal error when linting the code.

.. code-block:: php

   <?php
     function x ($a, $a) { print $a; };
     x(1,2); => display 2
   ?>


However, this is not common programming practise : all arguments should be named differently.

See also `Prepare for PHP 7 error messages (part 3) <https://www.exakat.io/prepare-for-php-7-error-messages-part-3/>`_.

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Functions/MultipleSameArguments                                                                            |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and older                                                                                     |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                           |
+-------------+------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `all-unique-arguments <https://github.com/dseguy/clearPHP/tree/master/rules/all-unique-arguments.md>`__    |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _multiple-exceptions-catch():

Multiple Exceptions Catch()
###########################


It is possible to have several distinct exceptions class caught by the same catch, preventing code repetition. 

This is a new feature since PHP 7.1.

.. code-block:: php

   <?php
   
   // PHP 7.1 and more recent
   try {  
       throw new someException(); 
   } catch (Single $s) {
       doSomething();
   } catch (oneType | anotherType $s) {
       processIdentically();
   } finally {
   
   }
   
   // PHP 7.0 and older
   try {  
       throw new someException(); 
   } catch (Single $s) {
       doSomething();
   } catch (oneType $s) {
       processIdentically();
   } catch (anotherType $s) {
       processIdentically();
   } finally {
   
   }
   
   ?>


This is a backward incompatible feature of PHP 7.1.

+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Exceptions/MultipleCatch                                                                                                              |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                                                 |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                                       |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+



.. _multiple-identical-closure:

Multiple Identical Closure
##########################


Several closures are defined with the same code. 

It may be interesting to check if a named function could be defined from them.

.. code-block:: php

   <?php
   
   // the first squares, with closure
   $squares= array_map(function ($a) {return $a * $a; }, range(0, 10) );
   
   // later, in another file...
   // another identical closure 
   $squaring = function ($x) { return $x * $x; };
   foo($x, $squaring);
   
   ?>


This analysis also reports functions and methods that look like the closures : they may be considered for switch.



Suggestions
^^^^^^^^^^^

* Create a function with the body of those closures, and replace the closures by the function's name.

+-------------+------------------------------------+
| Short name  | Functions/MultipleIdenticalClosure |
+-------------+------------------------------------+
| Themes      | :ref:`Suggestions`                 |
+-------------+------------------------------------+
| Severity    | Minor                              |
+-------------+------------------------------------+
| Time To Fix | Slow (1 hour)                      |
+-------------+------------------------------------+



.. _multiple-identical-trait-or-interface:

Multiple Identical Trait Or Interface
#####################################


There is no need to use the same trait, or implements the same interface  more than once.

Up to PHP 7.1 (at least), this doesn't raise any warning. Traits are only imported once, and interfaces may be implemented as many times as wanted.

.. code-block:: php

   <?php
   
   class foo {
       use t3,t3,t3;
   }
   
   class bar implements i,i,i {
   
   }
   
   ?>

+-------------+----------------------------------+
| Short name  | Classes/MultipleTraitOrInterface |
+-------------+----------------------------------+
| Themes      | :ref:`Analyze`                   |
+-------------+----------------------------------+
| Severity    | Minor                            |
+-------------+----------------------------------+
| Time To Fix | Instant (5 mins)                 |
+-------------+----------------------------------+



.. _multiple-index-definition:

Multiple Index Definition
#########################


Indexes that are defined multiple times in the same array. 

.. code-block:: php

   <?php
       // Multiple identical keys
       $x = array(1 => 2, 
                  2 => 3,  
                  1 => 3);
   
       // Multiple identical keys (sneaky version)
       $x = array(1 => 2, 
                  1.1 => 3,  
                  true => 4);
   
       // Multiple identical keys (automated version)
       $x = array(1 => 2, 
                  3,        // This will be index 2
                  2 => 4);  // this index is overwritten
   ?>


They are indeed overwriting each other. This is most probably a typo.


Suggestions
^^^^^^^^^^^

* Review your code and check that arrays only have keys defined once.
* Review carefully your code and check indirect values, like constants, static constants.

+-------------+--------------------------------------------------------------------------------------------+
| Short name  | Arrays/MultipleIdenticalKeys                                                               |
+-------------+--------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                             |
+-------------+--------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                      |
+-------------+--------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                           |
+-------------+--------------------------------------------------------------------------------------------+
| Examples    | :ref:`magento-arrays-multipleidenticalkeys`, :ref:`mediawiki-arrays-multipleidenticalkeys` |
+-------------+--------------------------------------------------------------------------------------------+



.. _multiple-type-variable:

Multiple Type Variable
######################


Avoid using the same variable with different types of data. 

It is recommended to use different names for differently typed data, while processing them. This prevents errors where one believe the variable holds the former type, while it has already been cast to the later.

Incrementing variables, with math operations or concatenation, is OK : the content changes, but not the type. And casting the variable without storing it in itself is OK. 

.. code-block:: php

   <?php
   
   // $x is an array
   $x = range('a', 'z');
   // $x is now a string
   $x = join('', $x);
   $c = count($x); // $x is not an array anymore
   
   
   // $letters is an array
   $letters = range('a', 'z');
   // $alphabet is a string
   $alphabet = join('', $letters);
   
   // Here, $letters is cast by PHP, but the variable is changed.
   if ($letters) { 
       $count = count($letters); // $letters is still an array 
   }
   
   ?>

+-------------+---------------------------------+
| Short name  | Structures/MultipleTypeVariable |
+-------------+---------------------------------+
| Themes      | :ref:`Analyze`                  |
+-------------+---------------------------------+
| Severity    | Minor                           |
+-------------+---------------------------------+
| Time To Fix | Quick (30 mins)                 |
+-------------+---------------------------------+



.. _multiple-usage-of-same-trait:

Multiple Usage Of Same Trait
############################


The same trait is used several times. One trait usage is sufficient.

.. code-block:: php

   <?php
   
   // C is used twice, and could be dropped from B
   trait A { use B, C;}
   trait B { use C;}
   
   ?>


PHP doesn't raise any error when traits are included multiple times.

See also `Traits <http://php.net/manual/en/language.oop5.traits.php>`_.

+-------------+----------------------+
| Short name  | Traits/MultipleUsage |
+-------------+----------------------+
| Themes      | :ref:`Suggestions`   |
+-------------+----------------------+
| Severity    | Minor                |
+-------------+----------------------+
| Time To Fix | Instant (5 mins)     |
+-------------+----------------------+



.. _multiples-identical-case:

Multiples Identical Case
########################


Some cases are defined multiple times, but only one will be processed. Check the list of cases, and remove the extra one.

Exakat tries to find the value of the case as much as possible, and ignore any dynamic cases (using variables).

.. code-block:: php

   <?php
   
   const A = 1;
   
   case ($x) {
       case 1 : 
           break;
       case true:    // This is a duplicate of the previous
           break; 
       case 1 + 0:   // This is a duplicate of the previous
           break; 
       case 1.0 :    // This is a duplicate of the previous
           break; 
       case A :      // The A constant is actually 1
           break; 
       case $y  :    // This is not reported.
           break; 
       default:
           
   }
   ?>



Suggestions
^^^^^^^^^^^

* Remove the double case
* Change the case to another and rightfull value

+-------------+--------------------------------------------------------------------------------------------------------+
| Short name  | Structures/MultipleDefinedCase                                                                         |
+-------------+--------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                         |
+-------------+--------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                  |
+-------------+--------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                        |
+-------------+--------------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-duplicate-case <https://github.com/dseguy/clearPHP/tree/master/rules/no-duplicate-case.md>`__      |
+-------------+--------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`sugarcrm-structures-multipledefinedcase`, :ref:`expressionengine-structures-multipledefinedcase` |
+-------------+--------------------------------------------------------------------------------------------------------+



.. _multiply-by-one:

Multiply By One
###############


Multiplying by 1 is useless. 

If it is used to type cast a value to number, then casting (integer) or (real) is clearer. This behavior may change with PHP 7.1, which has unified the behavior of all hidden casts. 

.. code-block:: php

   <?php
   
   // Still the same value than $m, but now cast to integer or real
   $m = $m * 1; 
   
   // Still the same value than $m, but now cast to integer or real
   $n *= 1; 
   
   // make typecasting clear, and merge it with the producing call.
   $n = (int) $n;
   
   ?>

+-------------+-----------------------------------------------------------------------------------------------+
| Short name  | Structures/MultiplyByOne                                                                      |
+-------------+-----------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                |
+-------------+-----------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                         |
+-------------+-----------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                              |
+-------------+-----------------------------------------------------------------------------------------------+
| ClearPHP    | `no-useless-math <https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-math.md>`__ |
+-------------+-----------------------------------------------------------------------------------------------+



.. _must-call-parent-constructor:

Must Call Parent Constructor
############################


Some PHP native classes require a call to parent\:\:`__construct() <http://php.net/manual/en/language.oop5.decon.php>`_ to be stable. 

As of PHP 7.3, two classes currently need that call : SplTempFileObject and SplFileObject.

The error is only emitted if the class is instantiated, and a parent class is called. 

.. code-block:: php

   <?php
   
   class mySplFileObject extends \SplFileObject {
       public function __construct()    { 
           // Forgottent call to parent::__construct()
       }
   }
   
   (new mySplFileObject())->passthru();
   ?>


See also `Why, php? WHY??? <https://gist.github.com/everzet/4215537>`_.



Suggestions
^^^^^^^^^^^

* Add a call to the parent's constructor
* Remove the extension of the parent class

+-------------+-------------------------------+
| Short name  | Php/MustCallParentConstructor |
+-------------+-------------------------------+
| Themes      | :ref:`Analyze`                |
+-------------+-------------------------------+
| Severity    | Major                         |
+-------------+-------------------------------+
| Time To Fix | Quick (30 mins)               |
+-------------+-------------------------------+



.. _must-return-methods:

Must Return Methods
###################


The following methods are expected to return a value that will be used later. Without return, they are useless.

Methods that must return are : `__get() <http://php.net/manual/en/language.oop5.magic.php>`_, `__isset() <http://php.net/manual/en/language.oop5.magic.php>`_, `__sleep() <http://php.net/manual/en/language.oop5.magic.php>`_, `__toString() <http://php.net/manual/en/language.oop5.magic.php>`_, `__set_state() <http://php.net/manual/en/language.oop5.magic.php>`_, `__invoke() <http://php.net/manual/en/language.oop5.magic.php>`_, `__debugInfo() <http://php.net/manual/en/language.oop5.magic.php>`_.
Methods that may not return, but are often expected to : `__call() <http://php.net/manual/en/language.oop5.magic.php>`_, `__callStatic() <http://php.net/manual/en/language.oop5.magic.php>`_.


.. code-block:: php

   <?php
   
   class foo {
       public function __isset($a) {
           // returning something useful
           return isset($this->$var[$a]);
       }
   
       public function __get($a) {
           $this->$a++;
           // not returning... 
       }
   
       public function __call($name, $args) {
           $this->$name(...$args);
           // not returning anything, but that's OK
       }
   
   }
   ?>

+-------------+----------------------+
| Short name  | Functions/MustReturn |
+-------------+----------------------+
| Themes      | :ref:`Analyze`       |
+-------------+----------------------+
| Severity    | Major                |
+-------------+----------------------+
| Time To Fix | Quick (30 mins)      |
+-------------+----------------------+



.. _named-regex:

Named Regex
###########


Captured subpatterns may be named, for easier reference. 

From the manual : It is possible to name a subpattern using the syntax ``(?P<name>pattern)``. This subpattern will then be indexed in the matches array by its normal numeric position and also by name. PHP 5.2.2 introduced two alternative syntaxes ``(?<name>pattern)`` and ``(?'name'pattern)``.

Naming subpatterns makes it easier to know what is read from the results of the subpattern : for example, ``$r['name']`` has more meaning than ``$r[1]``. 

Named subpatterns may also be shifted in the regex without impact on the resulting array. 

.. code-block:: php

   <?php
   
   $x = 'abc';
   preg_match_all('/(?<name>a)/', $x, $r);
   print_r($r[1]);
   print_r($r['name']);
   
   preg_match("/(?<name>a)(?'sub'b)/", $x, $s);
   print $s[2];
   print $s['sub'];
   
   ?>


See also `Subpatterns <http://php.net/manual/en/regexp.reference.subpatterns.php>`_.

 

Suggestions
^^^^^^^^^^^

* Use named regex, and stop using integer-named subpatterns

+------------+---------------------------------------------------------------------------+
| Short name | Structures/NamedRegex                                                     |
+------------+---------------------------------------------------------------------------+
| Themes     | :ref:`Suggestions`                                                        |
+------------+---------------------------------------------------------------------------+
| Examples   | :ref:`phinx-structures-namedregex`, :ref:`shopware-structures-namedregex` |
+------------+---------------------------------------------------------------------------+



.. _negative-power:

Negative Power
##############


The power operator `** <http://php.net/manual/en/language.operators.arithmetic.php>`_ has higher precedence than the sign operators + and -.

This means that -2 `** <http://php.net/manual/en/language.operators.arithmetic.php>`_ 2 == -4. It is in fact, -(2 `** <http://php.net/manual/en/language.operators.arithmetic.php>`_ 2). 

When using negative power, it is clearer to add parenthesis or to use the `pow() <http://www.php.net/pow>`_ function, which has no such ambiguity : 

.. code-block:: php

   <?php
   
   // -2 to the power of 2 (a square)
   pow(-2, 2) == 4;
   
   // minus 2 to the power of 2 (a negative square)
   -2 ** 2 == -(2 ** 2) == 4;
   
   ?>

+-------------+------------------------+
| Short name  | Structures/NegativePow |
+-------------+------------------------+
| Themes      | :ref:`Analyze`         |
+-------------+------------------------+
| Severity    | Major                  |
+-------------+------------------------+
| Time To Fix | Instant (5 mins)       |
+-------------+------------------------+



.. _nested-ifthen:

Nested Ifthen
#############


Three levels of ifthen is too much. The method should be split into smaller functions.

.. code-block:: php

   <?php
   
   function foo($a, $b) {
       if ($a == 1) {
           // Second level, possibly too much already
           if ($b == 2) {
               
           }
       }
   }
   
   function bar($a, $b, $c) {
       if ($a == 1) {
           // Second level. 
           if ($b == 2) {
               // Third level level. 
               if ($c == 3) {
                   // Too much
               }
           }
       }
   }
   
   ?>

+-------------+-------------------------+
| Short name  | Structures/NestedIfthen |
+-------------+-------------------------+
| Themes      | :ref:`Analyze`          |
+-------------+-------------------------+
| Severity    | Major                   |
+-------------+-------------------------+
| Time To Fix | Quick (30 mins)         |
+-------------+-------------------------+



.. _nested-ternary:

Nested Ternary
##############


Ternary operators should not be nested too deep.

They are a convenient instruction to apply some condition, and avoid a if() structure. It works best when it is simple, like in a one liner. 

However, ternary operators tends to make the syntax very difficult to read when they are nested. It is then recommended to use an if() structure, and make the whole code readable.

.. code-block:: php

   <?php
   
   // Simple ternary expression
   echo ($a == 1 ? $b : $c) ;
   
   // Nested ternary expressions
   echo ($a === 1 ? $d === 2 ? $b : $d : $d === 3 ? $e : $c) ;
   echo ($a === 1 ? $d === 2 ? $f ===4 ? $g : $h : $d : $d === 3 ? $e : $i === 5 ? $j : $k) ;
   
   //Previous expressions, written as a if / Then expression
   if ($a === 1) {
       if ($d === 2) {
           echo $b;
       } else {
           echo $d;
       }
   } else {
       if ($d === 3) {
           echo $e;
       } else {
           echo $c;
       }
   }
   
   if ($a === 1) {
       if ($d === 2) {
           if ($f === 4) {
               echo $g;
           } else {
               echo $h;
           }
       } else {
           echo $d;
       }
   } else {
       if ($d === 3) {
           echo $e;
       } else {
           if ($i === 5) {
               echo $j;
           } else {
               echo $k;
           }
       }
   }
   
   ?>


See also `Nested Ternaries are Great <https://medium.com/javascript-scene/nested-ternaries-are-great-361bddd0f340>`_.



Suggestions
^^^^^^^^^^^

* Replace ternaries by if/then structures.
* Replace ternaries by a functioncall : this provides more readability, offset the actual code, and gives room for making it different.

+-------------+---------------------------------------------------------------------------------------------------+
| Short name  | Structures/NestedTernary                                                                          |
+-------------+---------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                    |
+-------------+---------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                             |
+-------------+---------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                   |
+-------------+---------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-nested-ternary <https://github.com/dseguy/clearPHP/tree/master/rules/no-nested-ternary.md>`__ |
+-------------+---------------------------------------------------------------------------------------------------+
| Examples    | :ref:`spip-structures-nestedternary`, :ref:`zencart-structures-nestedternary`                     |
+-------------+---------------------------------------------------------------------------------------------------+



.. _never-used-parameter:

Never Used Parameter
####################


When a parameter is never used at calltime, it may be turned into a local variable.

It seems that the parameter was set up initially, but never found its practical usage. It is never mentioned, and always fall back on its default value.  

Parameter without a default value are reported by PHP, and are usually always filled. 

.. code-block:: php

   <?php
   
   // $b may be turned into a local var, it is unused
   function foo($a, $b = 1) {
       return $a + $b;
   }
   
   // whenever foo is called, the 2nd arg is not mentionned
   foo($a);
   foo(3);
   foo('a');
   foo($c);
   
   ?>

+-------------+------------------------------------+
| Short name  | Functions/NeverUsedParameter       |
+-------------+------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Suggestions` |
+-------------+------------------------------------+
| Severity    | Minor                              |
+-------------+------------------------------------+
| Time To Fix | Slow (1 hour)                      |
+-------------+------------------------------------+



.. _never-used-properties:

Never Used Properties
#####################


Properties that are never used. They are defined, but never actually used.

.. code-block:: php

   <?php
   
   class foo {
       public $usedProperty = 1;
   
       // Never used anywhere
       public $unusedProperty = 2;
       
       function bar() {
           // Used internally
           ++$this->usedProperty;
       }
   }
   
   class foo2  extends foo {
       function bar2() {
           // Used in child class
           ++$this->usedProperty;
       }
   }
   
   // Used externally
   ++$this->usedProperty;
   
   ?>

+-------------+---------------------------+
| Short name  | Classes/PropertyNeverUsed |
+-------------+---------------------------+
| Themes      | :ref:`Analyze`            |
+-------------+---------------------------+
| Severity    | Minor                     |
+-------------+---------------------------+
| Time To Fix | Slow (1 hour)             |
+-------------+---------------------------+



.. _new-constants-in-php-7.2:

New Constants In PHP 7.2
########################


The following constants are now native in PHP 7.2. It is advised to avoid using such names for constant before moving to this new version.

* ``PHP_OS_FAMILY``
* ``PHP_FLOAT_DIG``
* ``PHP_FLOAT_EPSILON``
* ``PHP_FLOAT_MAX``
* ``PHP_FLOAT_MIN``
* ``SQLITE3_DETERMINISTIC``
* ``CURLSSLOPT_NO_REVOKE``
* ``CURLOPT_DEFAULT_PROTOCOL``
* ``CURLOPT_STREAM_WEIGHT``
* ``CURLMOPT_PUSHFUNCTION``
* ``CURL_PUSH_OK``
* ``CURL_PUSH_DENY``
* ``CURL_HTTP_VERSION_2TLS``
* ``CURLOPT_TFTP_NO_OPTIONS``
* ``CURL_HTTP_VERSION_2_PRIOR_KNOWLEDGE``
* ``CURLOPT_CONNECT_TO``
* ``CURLOPT_TCP_FASTOPEN``
* ``DNS_CAA``

See also `New global constants in 7.2 <http://php.net/manual/en/migration72.constants.php>`_.

+-------------+---------------------------+
| Short name  | Php/Php72NewConstants     |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP72` |
+-------------+---------------------------+
| Php Version | With PHP 7.2 and older    |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Slow (1 hour)             |
+-------------+---------------------------+



.. _new-functions-in-php-5.4:

New Functions In PHP 5.4
########################


PHP introduced new functions in PHP 5.4. If there are defined functions with such names, there will be a conflict when upgrading. It is advised to change those functions' name.

+-------------+---------------------------+
| Short name  | Php/Php54NewFunctions     |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP53` |
+-------------+---------------------------+
| Php Version | With PHP 5.3 and older    |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Slow (1 hour)             |
+-------------+---------------------------+



.. _new-functions-in-php-5.5:

New Functions In PHP 5.5
########################


PHP introduced new functions in PHP 5.5. If you have already defined functions with such names, you will get a conflict when trying to upgrade. It is advised to change those functions' name.

+-------------+------------------------------------------------------+
| Short name  | Php/Php55NewFunctions                                |
+-------------+------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54` |
+-------------+------------------------------------------------------+
| Php Version | With PHP 5.5 and older                               |
+-------------+------------------------------------------------------+
| Severity    | Major                                                |
+-------------+------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                        |
+-------------+------------------------------------------------------+



.. _new-functions-in-php-5.6:

New Functions In PHP 5.6
########################


PHP introduced new functions in PHP 5.6. If you have already defined functions with such names, you will get a conflict when trying to upgrade. It is advised to change those functions' name.

+-------------+---------------------------------------------------------------------------------+
| Short name  | Php/Php56NewFunctions                                                           |
+-------------+---------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55` |
+-------------+---------------------------------------------------------------------------------+
| Php Version | With PHP 5.6 and older                                                          |
+-------------+---------------------------------------------------------------------------------+
| Severity    | Major                                                                           |
+-------------+---------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                   |
+-------------+---------------------------------------------------------------------------------+



.. _new-functions-in-php-7.0:

New Functions In PHP 7.0
########################


The following functions are now native functions in PHP 7.0. It is advised to change them before moving to this new version.

* get_resources()
* gc_mem_caches()
* `preg_replace_callback_array() <http://www.php.net/preg_replace_callback_array>`_
* posix_setrlimit()
* random_bytes()
* random_int()
* intdiv()
* error_clear_last()

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Php/Php70NewFunctions                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and older                                                                                     |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                              |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _new-functions-in-php-7.1:

New Functions In PHP 7.1
########################


The following functions are now native functions in PHP 7.1. It is advised to change them before moving to this new version.

* curl_share_strerror()
* curl_multi_errno()
* curl_share_errno()
* mb_ord()
* mb_chr()
* mb_scrub()
* is_iterable()

+-------------+---------------------------+
| Short name  | Php/Php71NewFunctions     |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP71` |
+-------------+---------------------------+
| Php Version | With PHP 7.1 and older    |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Slow (1 hour)             |
+-------------+---------------------------+



.. _new-functions-in-php-7.2:

New Functions In PHP 7.2
########################


The following functions are now native functions in PHP 7.2. It is advised to change them before moving to this new version.

* mb_ord()
* mb_chr()
* mb_scrub()
* stream_isatty()
* `proc_nice() <http://www.php.net/proc_nice>`_ (Windows only)

+-------------+---------------------------+
| Short name  | Php/Php72NewFunctions     |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP72` |
+-------------+---------------------------+
| Php Version | With PHP 7.2 and older    |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Slow (1 hour)             |
+-------------+---------------------------+



.. _new-functions-in-php-7.3:

New Functions In PHP 7.3
########################


New functions are added to new PHP version.

The following functions are now native functions in PHP 7.3. It is compulsory to rename any custom function that was created in older versions. One alternative is to move the function to a custom namespace, and update the ``use`` list at the beginning of the script. 

* `net_get_interfaces <http://php.net/net_get_interfaces>`_
* `gmp_binomial <http://php.net/gmp_binomial>`_
* `gmp_lcm <http://php.net/gmp_lcm>`_
* `gmp_perfect_power <http://php.net/gmp_perfect_power>`_
* `gmp_kronecker <http://php.net/gmp_kronecker>`_
* `openssl_pkey_derive <http://php.net/openssl_pkey_derive>`_
* `is_countable <http://php.net/is_countable>`_
* `ldap_exop_refresh <http://php.net/ldap_exop_refresh>`_

Note : At the moment of writing, all links to the manual are not working.

+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Php/Php73NewFunctions                                                                                                                                                                                                  |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71`, :ref:`CompatibilityPHP72`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP73` |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.3 and older                                                                                                                                                                                                 |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                                                                                                                                  |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                                                                                                                        |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _next-month-trap:

Next Month Trap
###############


Avoid using +1 month with strtotime(). 

strtotime() calculates the next month by incrementing the month number. For day number that do not exist from one month to the next, strtotime() fixes them by setting them in the next-next month. 

This happens to January, March, May, July, August and October. January is also vulnerable for 29 (not every year), 30 and 31. 

Avoid using '+1 month', and rely on 'first day of next month' or 'last day of next month' to extract the next month's name.

.. code-block:: php

   <?php
   
   // Base date is October 31 => 10/31
   // +1 month adds +1 to 10 => 11/31 
   // Since November 31rst doesn't exists, it is corrected to 12/01. 
   echo date('F', strtotime('+1 month',mktime(0,0,0,$i,31,2017))).PHP_EOL;
   
   // Base date is October 31 => 10/31
   echo date('F', strtotime('first day of next month',mktime(0,0,0,$i,31,2017))).PHP_EOL;
   
   ?>


See also `It is the 31st again <https://twitter.com/rasmus/status/925431734128197632>`_.

+-------------+---------------------------------------------------------------------------------+
| Short name  | Structures/NextMonthTrap                                                        |
+-------------+---------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                  |
+-------------+---------------------------------------------------------------------------------+
| Severity    | Major                                                                           |
+-------------+---------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                |
+-------------+---------------------------------------------------------------------------------+
| Examples    | :ref:`contao-structures-nextmonthtrap`, :ref:`edusoho-structures-nextmonthtrap` |
+-------------+---------------------------------------------------------------------------------+



.. _no-boolean-as-default:

No Boolean As Default
#####################


Default values should always be set up with a constant name.

Class constants and constants improve readability when calling the methods or comparing values and statuses.

.. code-block:: php

   <?php
   
   const CASE_INSENSITIVE = true;
   const CASE_SENSITIVE = false;
   
   function foo($case_insensitive = true) {
       // doSomething()
   }
   
   // Readable code 
   foo(CASE_INSENSITIVE);
   foo(CASE_SENSITIVE);
   
   
   // unreadable code  : is true case insensitive or case sensitive ? 
   foo(true);
   foo(false);
   
   ?>

+-------------+------------------------------+
| Short name  | Functions/NoBooleanAsDefault |
+-------------+------------------------------+
| Themes      | :ref:`Analyze`               |
+-------------+------------------------------+
| Severity    | Minor                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _no-choice:

No Choice
#########


A conditional structure is being used, but both alternatives are the same, leading to the illusion of choice. 

Either the condition is useless, and may be removed, or the alternatives need to be distinguished.

.. code-block:: php

   <?php
   
   if ($condition == 2) {
       doSomething();
   } else {
       doSomething();
   }
   
   $condition == 2 ?     doSomething() :     doSomething();
   
   ?>





Suggestions
^^^^^^^^^^^

* Remove the conditional, and call the expression directly
* Replace one of the alternative with a distinct call
* Remove the whole conditional : it may end up being useless

+-------------+--------------------------------------------------------------------------+
| Short name  | Structures/NoChoice                                                      |
+-------------+--------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                           |
+-------------+--------------------------------------------------------------------------+
| Severity    | Major                                                                    |
+-------------+--------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                         |
+-------------+--------------------------------------------------------------------------+
| Examples    | :ref:`nextcloud-structures-nochoice`, :ref:`zencart-structures-nochoice` |
+-------------+--------------------------------------------------------------------------+



.. _no-class-as-typehint:

No Class As Typehint
####################


Avoid using classes as typehint : always use interfaces. This way, different classes, or versions of classes may be passed as argument. 

.. code-block:: php

   <?php
   
   class X {
       function foo() {}
   }
   
   interface i {
       function foo();
   }
   
   // X is a class : any update in the code requires changing / subclassing X or the rest of the code.
   function bar(X $x) {
       $x->foo();
   }
   
   // I is an interface : X may implements this interface without refactoring and pass
   // later, newer versions of X may get another name, but still implement I, and still pass
   function bar2(I $x) {
       $x->foo();
   }
   
   ?>


See also `Type hinting for interfaces <http://phpenthusiast.com/object-oriented-php-tutorials/type-hinting-for-interfaces>`_.

+-------------+-----------------------------+
| Short name  | Functions/NoClassAsTypehint |
+-------------+-----------------------------+
| Themes      | :ref:`Analyze`              |
+-------------+-----------------------------+
| Severity    | Major                       |
+-------------+-----------------------------+
| Time To Fix | Quick (30 mins)             |
+-------------+-----------------------------+



.. _no-class-in-global:

No Class In Global
##################


Avoid defining structures in Global namespace. Always prefer using a namespace. This will come handy later, either when publishing the code, or when importing a library, or even if PHP reclaims that name. 

.. code-block:: php

   <?php
   
   // Code prepared for later
   namespace Foo {
       class Bar {}
   }
   
   // Code that may conflict with other names.
   namespace {
       class Bar {}
   }
   
   ?>

+-------------+---------------------+
| Short name  | Php/NoClassInGlobal |
+-------------+---------------------+
| Themes      | :ref:`Analyze`      |
+-------------+---------------------+
| Severity    | Minor               |
+-------------+---------------------+
| Time To Fix | Slow (1 hour)       |
+-------------+---------------------+



.. _no-count-with-0:

No Count With 0
###############


Comparing `count() <http://www.php.net/count>`_, strlen() or mb_strlen() to 0 is a waste of resources. There are three distinct situations.

When comparing `count() <http://www.php.net/count>`_ with 0, with ===, ==, !==, !=, it is more efficient to use `empty() <http://www.php.net/empty>`_. `empty() <http://www.php.net/empty>`_ is a language construct that checks if a value is present, while `count() <http://www.php.net/count>`_ actually load the number of element.

.. code-block:: php

   <?php
   
   // Checking if an array is empty
   if (count($array) == 0) {
       // doSomething();
   }
   // This may be replaced with 
   if (empty($array)) {
       // doSomething();
   }
   
   ?>


When comparing `count() <http://www.php.net/count>`_ strictly with 0 and ``>``, it is more efficient to use ``!(empty(  ))``

.. code-block:: php

   <?php
   
   // Checking if an array is empty
   if (count($array) > 0) {
       // doSomething();
   }
   // This may be replaced with 
   if (!empty($array)) {
       // doSomething();
   }
   
   Of course comparing count() with negative values, or with >= is useless.
   
   <?php
   
   // Checking if an array is empty
   if (count($array) < 0) {
       // This never happens
       // doSomething();
   }
   
   ?>


Comparing `count() <http://www.php.net/count>`_, strlen() or mb_strlen() with other values than 0 cannot be replaced with a comparison with `empty() <http://www.php.net/empty>`_.

Note that this is a micro-optimisation : since PHP keeps track of the number of elements in arrays (or number of chars in strings), the total computing time of both operations is often lower than a ms. However, both functions tends to be heavily used, and may even be used inside loops. 

See also `count <http://php.net/count>`_,
         `strlen <http://php.net/strlen>`_ and 
         `mb_strlen <http://php.net/mb_strlen>`_.


Suggestions
^^^^^^^^^^^

* Use empty() on the data
* Compare the variable with a default value, such as an empty array

+-------------+-------------------------------------------------------------------------------------+
| Short name  | Performances/NotCountNull                                                           |
+-------------+-------------------------------------------------------------------------------------+
| Themes      | :ref:`Performances`                                                                 |
+-------------+-------------------------------------------------------------------------------------+
| Severity    | Minor                                                                               |
+-------------+-------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                    |
+-------------+-------------------------------------------------------------------------------------+
| Examples    | :ref:`contao-performances-notcountnull`, :ref:`wordpress-performances-notcountnull` |
+-------------+-------------------------------------------------------------------------------------+



.. _no-direct-call-to-magic-method:

No Direct Call To Magic Method
##############################


PHP features magic methods, which are methods related to operators.

Magic methods, such as `__get() <http://php.net/manual/en/language.oop5.magic.php>`_, related to =, or `__clone() <http://php.net/manual/en/language.oop5.magic.php>`_, related to ``clone``, are supposed to be used in an object environment, and not with direct call. 

It is recommended to use the magic method with its intended usage, and not to call it directly. For example, typecast to ``string`` instead of calling the `__toString() <http://php.net/manual/en/language.oop5.magic.php>`_ method.

.. code-block:: php

   <?php
   // Write
     print $x->a;
   // instead of 
     print $x->__get('a'); 
   
   class Foo {
       private $b = secret;
   
       public function __toString() {
           return strtoupper($this->b);
       }
   }
   
   $bar = new Foo();
   echo (string) $bar;
   
   ?>


Accessing those methods in a static way is also discouraged.

See also `Magic Methods <http://php.net/manual/en/language.oop5.magic.php>`_ and 
         `Magical PHP: `__call() <http://php.net/manual/en/language.oop5.magic.php>`_ <https://www.garfieldtech.com/blog/magical-php-call>`_.

+-------------+---------------------------------+
| Short name  | Classes/DirectCallToMagicMethod |
+-------------+---------------------------------+
| Themes      | :ref:`Analyze`                  |
+-------------+---------------------------------+
| Severity    | Major                           |
+-------------+---------------------------------+
| Time To Fix | Quick (30 mins)                 |
+-------------+---------------------------------+



.. _no-direct-usage:

No Direct Usage
###############


The results of the following functions shouldn't be used directly, but checked first. 

For example, `glob() <http://www.php.net/glob>`_ returns an array, unless some error happens, in which case it returns a boolean (false). In such case, however rare it is, plugging `glob() <http://www.php.net/glob>`_ directly in a `foreach() <http://php.net/manual/en/control-structures.foreach.php>`_ loops will yield errors.

.. code-block:: php

   <?php
       // Used without check : 
       foreach(glob('.') as $file) { /* do Something */ }.
       
       // Used without check : 
       $files = glob('.');
       if (!is_array($files)) {
           foreach($files as $file) { /* do Something */ }.
       }
   ?>

+-------------+--------------------------+
| Short name  | Structures/NoDirectUsage |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Major                    |
+-------------+--------------------------+
| Time To Fix | Slow (1 hour)            |
+-------------+--------------------------+



.. _no-empty-regex:

No Empty Regex
##############


PHP regex don't accept empty regex, nor regex with alphanumeric delimiter.

Most of those errors happen at execution time, when the regex is build dynamically, but still may end empty. At compile time, such error are made when the code is not tested before commit.

.. code-block:: php

   <?php
   
   // No empty regex
   preg_match('', $string, $r); 
   
   // Delimiter must be non-alphanumerical
   preg_replace('1abc1', $string, $r); 
   
   // Delimiter must be non-alphanumerical
   preg_replace('1'.$regex.'1', $string, $r); 
   
   ?>


See also `PCRE <http://php.net/pcre>`_ and `Delimiters <http://php.net/manual/en/regexp.reference.delimiters.php>`_.


Suggestions
^^^^^^^^^^^

* Fix the regex by adding regex delimiters

+-------------+-----------------------------------------+
| Short name  | Structures/NoEmptyRegex                 |
+-------------+-----------------------------------------+
| Themes      | :ref:`Analyze`                          |
+-------------+-----------------------------------------+
| Severity    | Critical                                |
+-------------+-----------------------------------------+
| Time To Fix | Quick (30 mins)                         |
+-------------+-----------------------------------------+
| Examples    | :ref:`tikiwiki-structures-noemptyregex` |
+-------------+-----------------------------------------+



.. _no-hardcoded-hash:

No Hardcoded Hash
#################


Hash should never be hardcoded. 

Hashes may be MD5, SHA1, SHA512, Bcrypt or any other. Such values must be easily changed, for security reasons, and the source code is not the safest place to hide it. 

.. code-block:: php

   <?php
   
       // Those strings may be sha512 hashes. 
       // it is recomemdned to check if they are static or should be put into configuration
       $init512 = array( // initial values for SHA512
           '6a09e667f3bcc908', 'bb67ae8584caa73b', '3c6ef372fe94f82b', 'a54ff53a5f1d36f1', 
       );
   
       // strings which are obvious conversion are ignored 
       $decimal = intval('87878877', 12);
   ?>


See also `Salted Password Hashing - Doing it Right <https://crackstation.net/hashing-security.htm>`_ and 
         `Hash-Buster <https://github.com/s0md3v/Hash-Buster>`_.


Suggestions
^^^^^^^^^^^

* Put any hardcoded hash in a configuration file, a database or a environnement variable. An external source.

+-------------+----------------------------------------------------------------------------------------+
| Short name  | Structures/NoHardcodedHash                                                             |
+-------------+----------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Security`                                                        |
+-------------+----------------------------------------------------------------------------------------+
| Severity    | Critical                                                                               |
+-------------+----------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                          |
+-------------+----------------------------------------------------------------------------------------+
| Examples    | :ref:`shopware-structures-nohardcodedhash`, :ref:`sugarcrm-structures-nohardcodedhash` |
+-------------+----------------------------------------------------------------------------------------+



.. _no-hardcoded-ip:

No Hardcoded Ip
###############


Do not leave hard coded IP in your code.

It is recommended to move such configuration in external files or databases, for each update. 
This may also come handy when testing. 

.. code-block:: php

   <?php
   
   // This IPv4 is hardcoded. 
   $ip = '183.207.224.50';
   // This IPv6 is hardcoded. 
   $ip = '2001:0db8:85a3:0000:0000:8a2e:0370:7334';
   
   // This looks like an IP
   $thisIsNotAnIP = '213.187.99.50';
   $thisIsNotAnIP = '2133:1387:9393:5330';
   
   ?>


127.0.0.1, \:\:1 and \:\:0 are omitted, and not considered as a violation.

+-------------+---------------------------------+
| Short name  | Structures/NoHardcodedIp        |
+-------------+---------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Security` |
+-------------+---------------------------------+
| Severity    | Minor                           |
+-------------+---------------------------------+
| Time To Fix | Slow (1 hour)                   |
+-------------+---------------------------------+



.. _no-hardcoded-path:

No Hardcoded Path
#################


It is not recommended to have literals when accessing files. 

Either use `__FILE__ <http://php.net/manual/en/language.constants.predefined.php>`_ and `__DIR__ <http://php.net/manual/en/language.constants.predefined.php>`_ to make the path relative to the current file; use a ``DOC_ROOT`` as a configuration constant that will allow the moving of the script to another folder; finally functions like `sys_get_temp_dir() <http://www.php.net/sys_get_temp_dir>`_ produce a viable temporary folder.

.. code-block:: php

   <?php
   
       // This depends on the current executed script
       file_get_contents('token.txt');
   
       // Exotic protocols are ignored
       file_get_contents('jackalope://file.txt');
   
       // Some protocols are ignored : http, https, ftp, ssh2, php (with memory)
       file_get_contents('http://www.php.net/');
       file_get_contents('php://memory/');
       
       // glob() with special chars * and ? are not reported
       glob('./*/foo/bar?.txt');
       // glob() without special chars * and ? are reported
       glob('/foo/bar/');
       
   ?>

+-------------+---------------------------------------------------------------------------------------------------+
| Short name  | Structures/NoHardcodedPath                                                                        |
+-------------+---------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                    |
+-------------+---------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                             |
+-------------+---------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                     |
+-------------+---------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-hardcoded-path <https://github.com/dseguy/clearPHP/tree/master/rules/no-hardcoded-path.md>`__ |
+-------------+---------------------------------------------------------------------------------------------------+



.. _no-hardcoded-port:

No Hardcoded Port
#################


When connecting to a remove server, port is an important information. It is recommended to make this configurable (with constant or configuration), to as to be able to change this value without changing the code.

.. code-block:: php

   <?php
   
       // Both configurable IP and hostname
       $connection = ssh2_connect($_ENV['SSH_HOST'], $_ENV['SSH_PORT'], $methods, $callbacks);
       
       // Both hardcoded IP and hostname
       $connection = ssh2_connect('shell.example.com', 22, $methods, $callbacks);
   
       if (!$connection) die('Connection failed');
   ?>

+-------------+---------------------------------+
| Short name  | Structures/NoHardcodedPort      |
+-------------+---------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Security` |
+-------------+---------------------------------+
| Severity    | Minor                           |
+-------------+---------------------------------+
| Time To Fix | Quick (30 mins)                 |
+-------------+---------------------------------+



.. _no-list-with-string:

No List With String
###################


list() can't be used anymore to access particular offset in a string. This should be done with substr() or $string[$offset] syntax.

.. code-block:: php

   <?php
   
   $x = 'abc';
   list($a, $b, $c) = $x;
   
   //list($a, $b, $c) = 'abc'; Never works
   
   print $c;
   // PHP 5.6- displays 'c'
   // PHP 7.0+ displays nothing
   
   ?>


See also `PHP 7.0 Backward incompatible changes <http://php.net/manual/en/migration70.incompatible.php>`_ : list() can no longer unpack string variables.

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Php/NoListWithString                                                                                       |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and older                                                                                     |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                           |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _no-magic-with-array:

No Magic With Array
###################


Magic method ``__get()`` doesn't work for array syntax. 

When overloading properties, they can only be used for scalar values, excluding arrays. Under the hood, PHP uses ``__get()`` to reach for the name of the property, and doesn't recognize the following index as an array. It yields an error : Indirect modification of overloaded property.

.. code-block:: php

   <?php
   
   class c {
       private $a;
       private $o = array();
   
       function __get($name) {
           return $this->o[$name];
       }
       
       function foo() {
           // property b doesn't exists
           $this->b['a'] = 3;
           
           print_r($this);
       }
   
       // This method has no impact on the issue
       function __set($name, $value) {
           $this->o[$name] = $value;
       }
   }
   
   $c = new c();
   $c->foo();
   
   ?>


This is not reported by linting.

In this analysis, only properties that are found to be magic are reported. For example, using the b property outside the class scope is not reported, as it would yield too many false-positives.

See also `Overload <http://php.net/manual/en/language.oop5.overloading.php#object.get>`_.

+-------------+--------------------------+
| Short name  | Classes/NoMagicWithArray |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Major                    |
+-------------+--------------------------+
| Time To Fix | Slow (1 hour)            |
+-------------+--------------------------+



.. _no-need-for-else:

No Need For Else
################


Else is not needed when the Then ends with a `break <http://php.net/manual/en/control-structures.break.php>`_. A `break <http://php.net/manual/en/control-structures.break.php>`_ may be the following keywords : `break <http://php.net/manual/en/control-structures.break.php>`_, `continue <http://php.net/manual/en/control-structures.continue.php>`_, return, goto. Any of these send the execution somewhere in the code. The else block is then executed as the main sequence, only if the condition fails.

.. code-block:: php

   <?php
   
   function foo() {
       // Else may be in the main sequence.
       if ($a1) {
           return $a1;
       } else {
           $a++;
       }
   
       // Same as above, but negate the condition : if (!$a2) { return $a2; }
       if ($a2) {
           $a++;
       } else {
           return $a2;
       }
   
       // This is OK
       if ($a3) {
           return;
       }
   
       // This has no break
       if ($a4) {
           $a++;
       } else {
           $b++;
       }
   
       // This has no else
       if ($a5) {
           $a++;
       }
   }
   ?>


See also `Object Calisthenics, rule # 2 <http://williamdurand.fr/2013/06/03/object-calisthenics/>`_.

+-------------+--------------------------+
| Short name  | Structures/NoNeedForElse |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Minor                    |
+-------------+--------------------------+
| Time To Fix | Quick (30 mins)          |
+-------------+--------------------------+



.. _no-net-for-xml-load:

No Net For Xml Load
###################


Simplexml and ext/DOM load all external entities from the web, by default. This is dangerous, in particular when loading unknown XML code.::

   
   
   <!DOCTYPE replace [<!ENTITY xxe SYSTEM "php://filter/convert.base64-encode/resource=index.php"> ]>
   <replace>&xxe;</replace>
   
   


Here, PHP tries to load the XML file, finds the entity, then solves the entity by encoding a file called ``index.php``. The source code of the file is not used as data in the XML file. 

See also `XML External Entity <https://github.com/swisskyrepo/PayloadsAllTheThings/tree/master/XXE%20injections>`_, 
         `XML External Entity (XXE) Processing <https://www.owasp.org/index.php/XML_External_Entity_(XXE)_Processing>`_ and 
         `Detecting and exploiting XXE in SAML Interfaces <https://web-in-security.blogspot.nl/2014/11/detecting-and-exploiting-xxe-in-saml.html>`_.

+-------------+--------------------------+
| Short name  | Security/NoNetForXmlLoad |
+-------------+--------------------------+
| Themes      | :ref:`Security`          |
+-------------+--------------------------+
| Severity    | Major                    |
+-------------+--------------------------+
| Time To Fix | Slow (1 hour)            |
+-------------+--------------------------+



.. _no-parenthesis-for-language-construct:

No Parenthesis For Language Construct
#####################################


Some PHP language constructs, such are ``include``, ``print``, ``echo`` don't need parenthesis. They accept parenthesis, but it is may lead to strange situations. 

.. code-block:: php

   <?php
   
   // This is an attempt to load 'foo.inc', or kill the script
   include('foo.inc') or die();
   // in fact, this is read by PHP as : include 1 
   // include  'foo.inc' or die();
   
   ?>


It it better to avoid using parenthesis with ``echo``, ``print``, ``return``, ``throw``, ``yield``, ``yield from``, ``include``, ``require``, ``include_once``, ``require_once``.

See also `include <http://php.net/manual/en/function.include.php>`_.


Suggestions
^^^^^^^^^^^

* Remove parenthesis

+-------------+-------------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Structures/NoParenthesisForLanguageConstruct                                                                                              |
+-------------+-------------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Suggestions`                                                                                                        |
+-------------+-------------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                                                     |
+-------------+-------------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                                           |
+-------------+-------------------------------------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-parenthesis-for-language-construct <https://github.com/dseguy/clearPHP/tree/master/rules/no-parenthesis-for-language-construct.md>`__ |
+-------------+-------------------------------------------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`phpdocumentor-structures-noparenthesisforlanguageconstruct`, :ref:`phpmyadmin-structures-noparenthesisforlanguageconstruct`         |
+-------------+-------------------------------------------------------------------------------------------------------------------------------------------+



.. _no-plus-one:

No Plus One
###########


Incrementing a variable should be done with the ++ or -- operators. Any other way, may be avoided.

.. code-block:: php

   <?php
   
   // Best way to increment
   ++$x; --$y;
   
   // Second best way to increment, if the current value is needed :
   echo $x++, $y--;
   
   // Good but slow 
   $x += 1; 
   $x -= -1; 
   
   $y += -1;
   $y -= 1;
   
   // even slower
   $x = $x + 1; 
   $y = $y - 1; 
   
   ?>

+-------------+------------------------------------------------+
| Short name  | Structures/PlusEgalOne                         |
+-------------+------------------------------------------------+
| Themes      | :ref:`Coding Conventions <coding-conventions>` |
+-------------+------------------------------------------------+
| Severity    | Minor                                          |
+-------------+------------------------------------------------+
| Time To Fix | Quick (30 mins)                                |
+-------------+------------------------------------------------+



.. _no-public-access:

No Public Access
################


The properties below are declared with public access, but are never used publicly. They can be made protected or private.

.. code-block:: php

   <?php
   
   class foo {
       public $bar = 1;            // Public, and used in public space
       public $neverInPublic = 3;  // Public, but never used in outside the class
       
       function bar() {
           $neverInPublic++;
       }
   }
   
   $x = new foo();
   $x->bar = 3;
   $x->bar();
   
   ?>

+-------------+------------------------+
| Short name  | Classes/NoPublicAccess |
+-------------+------------------------+
| Themes      | :ref:`Analyze`         |
+-------------+------------------------+
| Severity    | Minor                  |
+-------------+------------------------+
| Time To Fix | Quick (30 mins)        |
+-------------+------------------------+



.. _no-real-comparison:

No Real Comparison
##################


Avoid comparing decimal numbers with ==, ===, !==, !=. Real numbers have an error margin which is random, and makes it very difficult to match even if the compared value is a literal. 

PHP uses an internal representation in base 2 : any number difficult to represent with this base (like 0.1 or 0.7) will have a margin of error.

.. code-block:: php

   <?php
   
   $a = 1/7;
   $b = 2.0;
   
   // 7 * $a is a real, not an integer
   var_dump( 7 * $a === 1);
   
   // rounding error leads to wrong comparison
   var_dump( (0.1 + 0.7) * 10 == 8);
   // although
   var_dump( (0.1 + 0.7) * 10);
   // displays 8
   
   // precision formula to use with reals. Adapt 0.0001 to your precision needs
   var_dump( abs(((0.1 + 0.7) * 10) - 8) < 0.0001); 
   
   ?>


Use precision formulas with `abs() <http://www.php.net/abs>`_ to approximate values with a given precision, or avoid reals altogether. 

See also `Floating point numbers <http://php.net/manual/en/language.types.float.php#language.types.float>`_.

+-------------+-----------------------------------------------------------------------------------------------------+
| Short name  | Type/NoRealComparison                                                                               |
+-------------+-----------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                      |
+-------------+-----------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                               |
+-------------+-----------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                     |
+-------------+-----------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-real-comparison <https://github.com/dseguy/clearPHP/tree/master/rules/no-real-comparison.md>`__ |
+-------------+-----------------------------------------------------------------------------------------------------+



.. _no-reference-for-static-property:

No Reference For Static Property
################################


Static properties used to behave independantly when set to a reference value. This was fixed in PHP 7.3. 

According to the PHP 7.3 changelog : ``In PHP, static properties are shared between inheriting classes, unless the static property is explicitly overridden in a child class. However, due to an implementation artifact it was possible to separate the static properties by assigning a reference. This loophole has been fixed.``.

.. code-block:: php

   <?php
   
           class Test {
               public static $x = 0;
           }
           class Test2 extends Test { }
   
           Test2::$x = &$x;
           $x = 1;
   
           var_dump(Test::$x, Test2::$x);
           // Previously: int(0), int(1)
           // Now: int(1), int(1)
   
   ?>


See also `PHP 7.3 UPGRADE NOTES <https://github.com/php/php-src/blob/3b6e1ee4ee05678b5d717cd926a35ffdc1335929/UPGRADING#L66-L81>`_.

+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Php/NoReferenceForStaticProperty                                                                                                                                                            |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71`, :ref:`CompatibilityPHP72`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.3 and older                                                                                                                                                                      |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                                                                                                       |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                                                                                                               |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _no-reference-for-ternary:

No Reference For Ternary
########################


The ternary operator and the null coalescing operator are both expressions that only return values, and not a variable. 

This means that any provided reference will be turned into its value. While this is usually invisible, it will raise a warning when a reference is expected. This is the case with methods returning a reference. 

A PHP notice is generated when using a ternary operator or the null coalesce operator : ``Only variable references should be returned by reference``. The notice is also emitted when returning objects. 

This applies to methods, functions and closures. 

.. code-block:: php

   <?php
   
   // This works
   function &foo($a, $b) { 
       if ($a === 1) {
           return $b; 
       } else {
           return $a; 
       }
   }
   
   // This raises a warning, as the operator returns a value
   function &foo($a, $b) { return $a === 1 ? $b : $a; }
   
   ?>


See also `Null Coalescing Operator <http://php.net/manual/en/language.operators.comparison.php#language.operators.comparison.coalesce>`_, 
         `Ternary Operator <http://php.net/manual/en/language.operators.comparison.php#language.operators.comparison.ternary>`_.

+-------------+--------------------------------------------+
| Short name  | Php/NoReferenceForTernary                  |
+-------------+--------------------------------------------+
| Themes      | :ref:`Analyze`                             |
+-------------+--------------------------------------------+
| Severity    | Major                                      |
+-------------+--------------------------------------------+
| Time To Fix | Slow (1 hour)                              |
+-------------+--------------------------------------------+
| Examples    | :ref:`phpadsnew-php-noreferenceforternary` |
+-------------+--------------------------------------------+



.. _no-reference-on-left-side:

No Reference On Left Side
#########################


Do not use references as the right element in an assignation. 

.. code-block:: php

   <?php
   
   $b = 2;
   $c = 3;
   
   $a = &$b + $c;
   // $a === 2 === $b;
   
   $a = $b + $c;
   // $a === 5
   
   ?>


This is the case for most situations : addition, multiplication, bitshift, logical, power, concatenation.
Note that PHP won't compile the code if the operator is a short operator (+=, .=, etc.), nor if the & is on the right side of the operator.

+-------------+------------------------------+
| Short name  | Structures/NoReferenceOnLeft |
+-------------+------------------------------+
| Themes      | :ref:`Analyze`               |
+-------------+------------------------------+
| Severity    | Critical                     |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _no-return-for-generator:

No Return For Generator
#######################


Return is not allowed in pre PHP 7 generators. They yield a fatal Error.

.. code-block:: php

   <?php
   
   function generatorWithReturn() {
       yield 1;
       return 2;
   }
   
   ?>


See also `Generators overview <http://php.net/manual/en/language.generators.overview.php>`_.

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Php/NoReturnForGenerator                                                                                   |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and more recent                                                                               |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Critical                                                                                                   |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                            |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _no-return-or-throw-in-finally:

No Return Or Throw In Finally
#############################


Avoid using return and throw in a finally block. Both command will interrupt the processing of the try catch block, and any exception that was emitted will not be processed. This leads to unprocessed exceptions, leaving the application in an unstable state.

Note that PHP prevents the usage of goto, `break <http://php.net/manual/en/control-structures.break.php>`_ and `continue <http://php.net/manual/en/control-structures.continue.php>`_ within the finally block at linting phase. This is categorized as a Security problem.

.. code-block:: php

   <?php
   function foo() {
           try {
               // Exception is thrown here 
               throw new \Exception();
           } catch (Exception $e) {
               // This is executed AFTER finally
               return 'Exception';
           } finally {
               // This is executed BEFORE catch
               return 'Finally';
           }
       }
   }
   
   // Displays 'Finally'. No exception
   echo foo();
   
   function bar() {
           try {
               // Exception is thrown here 
               throw new \Exception();
           } catch (Exception $e) {
               // Process the exception. 
               return 'Exception';
           } finally {
               // clean the current situation
               // Keep running the current function
           }
           return 'Finally';
       }
   }
   
   // Displays 'Exception', with processed Exception
   echo bar();
   
   ?>


See also `Return Inside Finally Block <https://www.owasp.org/index.php/Return_Inside_Finally_Block>`_.

+-------------+------------------------------+
| Short name  | Structures/NoReturnInFinally |
+-------------+------------------------------+
| Themes      | :ref:`Security`              |
+-------------+------------------------------+
| Severity    | Major                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _no-return-used:

No Return Used
##############


The return value of the following functions are never used. The return argument may be dropped from the code, as it is dead code.

This analysis supports functions and static methods, when a definition may be found. It doesn't support method calls.

.. code-block:: php

   <?php
   
   function foo($a = 1;) { return 1; }
   foo();
   foo();
   foo();
   foo();
   foo();
   foo();
   
   // This function doesn't return anything. 
   function foo2() { }
   
   // The following function are used in an expression, thus the return is important
   function foo3() {  return 1;}
   function foo4() {  return 1;}
   function foo5() {  return 1;}
   
   foo3() + 1; 
   $a = foo4();
   foo(foo5());
   
   ?>

+-------------+------------------------------------+
| Short name  | Functions/NoReturnUsed             |
+-------------+------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Suggestions` |
+-------------+------------------------------------+
| Severity    | Minor                              |
+-------------+------------------------------------+
| Time To Fix | Slow (1 hour)                      |
+-------------+------------------------------------+



.. _no-self-referencing-constant:

No Self Referencing Constant
############################


It is not possible to use a constant to define itself in a class. It yields a fatal error at runtime. 

The PHP error reads : ``Cannot declare self-referencing constant 'self\:\:C2'``. Unlike PHP which is self-referencing, self referencing variables can't have a value : just don't use that.

.. code-block:: php

   <?php
       class a { 
           const C1 = 1;         // fully defined constant
           const C2 = self::C2;  // self referencing constant
           const C3 = a::C3 + 2; // self referencing constant
       }
   ?>


The code may access an already declared constant with self or with its class name.

.. code-block:: php

   <?php
       class a { 
           const C1 = 1; 
           const C2 = a::C1; 
       }
   ?>


This error is not detected by linting. It is only detected at instantiation time : if the class is not used, it won't appear.



Suggestions
^^^^^^^^^^^

* Give a literal value to this constant
* Give a constant value to this constant : other class constants or constant are allowed here.

+-------------+----------------------------------------+
| Short name  | Classes/NoSelfReferencingConstant      |
+-------------+----------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`LintButWontExec` |
+-------------+----------------------------------------+
| Severity    | Minor                                  |
+-------------+----------------------------------------+
| Time To Fix | Quick (30 mins)                        |
+-------------+----------------------------------------+



.. _no-string-with-append:

No String With Append
#####################


PHP 7 doesn't allow the usage of [] with strings. [] is an array-only operator.

.. code-block:: php

   <?php
   
   $string = 'abc';
   
   // Not possible in PHP 7
   $string[] = 'd';
   
   ?>


This was possible in PHP 5, but is now forbidden in PHP 7.

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Php/NoStringWithAppend                                                                                     |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and more recent                                                                               |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                            |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _no-substr-minus-one:

No Substr Minus One
###################


Negative index were introduced in PHP 7.1. This syntax is not compatible with PHP 7.0 and older.

.. code-block:: php

   <?php
   $string = 'abc';
   
   echo $string[-1]; // c
   
   echo $string[1]; // a
   
   ?>


Seel also `Generalize support of negative string offsets <https://wiki.php.net/rfc/negative-string-offsets>`_.

+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Php/NoSubstrMinusOne                                                                                                                  |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.1 and more recent                                                                                                          |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                                                 |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                                       |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+



.. _no-substr()-one:

No Substr() One
###############


Use array notation ``$string[$position]`` to reach a single byte in a string.

There are two ways to access a byte in a string : substr() and ``$v[$pos]``.

The second style is more readable. It may be up to four times faster, though it is a micro-optimization. It is recommended to use it. 

PHP 7.1 also introduces the support of negative offsets as string index : negative offset are also reported.

.. code-block:: php

   <?php
   
   $string = ab人cde;
   
   echo substr($string, $pos, 1);
   echo $string[$pos];
   echo mb_substr($string, $pos, 1);
   
   // $pos = 1
   // bbb
   // $pos = 2
   // ??人
   
   ?>


Beware that substr() and ``$v[$pos]`` are similar, while `mb_substr() <http://www.php.net/mb_substr>`_ is not. The first function works on bytes, while the latter works on characters.

+-------------+------------------------------------------------------------------------------------+
| Short name  | Structures/NoSubstrOne                                                             |
+-------------+------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Performances`, :ref:`CompatibilityPHP71`, :ref:`Suggestions` |
+-------------+------------------------------------------------------------------------------------+
| Severity    | Minor                                                                              |
+-------------+------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                   |
+-------------+------------------------------------------------------------------------------------+



.. _no-array\_merge()-in-loops:

No array_merge() In Loops
#########################


`array_merge() <http://www.php.net/array_merge>`_ is memory intensive : every call will duplicate the arguments in memory, before merging them. 

To handle arrays that may be quite big, it is recommended to avoid using `array_merge() <http://www.php.net/array_merge>`_ in a loop. Instead, one should use `array_merge() <http://www.php.net/array_merge>`_ with as many arguments as possible, making the merge a on time call.

.. code-block:: php

   <?php
   
   // A large multidimensional array
   $source = ['a' => ['a', 'b', /*...*/],
              'b' => ['b', 'c', 'd', /*...*/],
              /*...*/
              ];
   
   // Faster way
   $b = array();
   foreach($source as $key => $values) {
       //Collect in an array
       $b[] = $values;
   }
   
   // One call to array_merge
   $b = call_user_func_array('array_merge', $b);
   // or with variadic
   $b = call_user_func('array_merge', ..$b);
   
   // Fastest way (with above example, without checking nor data pulling)
   $b = call_user_func_array('array_merge', array_values($source))
   // or
   $b = call_user_func('array_merge', ...array_values($source))
   
   // Slow way to merge it all
   $b = array();
   foreach($source as $key => $values) {
       $b = array_merge($b, $values);
   }
   
   ?>


Note that `array_merge_recursive() <http://www.php.net/array_merge_recursive>`_ and `file_put_contents() <http://www.php.net/file_put_contents>`_ are affected and reported the same way.

+-------------+-------------------------------------------------------------------------------------------------------------+
| Short name  | Performances/ArrayMergeInLoops                                                                              |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Performances`                                                                         |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                       |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                             |
+-------------+-------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-array_merge-in-loop <https://github.com/dseguy/clearPHP/tree/master/rules/no-array_merge-in-loop.md>`__ |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`tine20-performances-arraymergeinloops`                                                                |
+-------------+-------------------------------------------------------------------------------------------------------------+



.. _no-get\_class()-with-null:

No get_class() With Null
########################


It is not possible to pass explicitly null to get_class() to get the current's class name. Since PHP 7.2, one must call get_class() without arguments to achieve that result.

.. code-block:: php

   <?php
   
   class A {
     public function f() {
       // Gets the classname
       $classname = get_class();
   
       // Gets the classname and a warning
       $classname = get_class(null);
     }
   }
   
   $a = new A();
   $a->f('get_class');
   
   ?>

+-------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Structures/NoGetClassNull                                                                                                                                                                                   |
+-------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP72` |
+-------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                                                                                                                       |
+-------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                                                                                                                            |
+-------------+-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _no-isset()-with-empty():

No isset() With empty()
#######################


`empty() <http://www.php.net/empty>`_ actually does the job of `isset() <http://www.php.net/isset>`_ too. 

From the manual : ``No warning is generated if the variable does not exist. That means `empty() <http://www.php.net/empty>`_ is essentially the concise equivalent to !`isset( <http://www.php.net/isset>`_$var) || $var == false.`` The main difference is that `isset() <http://www.php.net/isset>`_ only works with variables, while `empty() <http://www.php.net/empty>`_ works with other structures, such as constants.

.. code-block:: php

   <?php
   
   
   // Enough validation
   if (!empty($a)) {
       doSomething();
   }
   
   // Too many tests
   if (isset($a) && !empty($a)) {
       doSomething();
   }
   
   ?>


See also `isset <http://www.php.net/`isset <http://www.php.net/isset>`_>`_ and 
         `empty <http://www.php.net/empty>`_.


Suggestions
^^^^^^^^^^^

* Only use isset(), just drop the empty()
* Only use empty(), just drop the empty()
* Use a null value, so the variable is always set

+-------------+------------------------------------------+
| Short name  | Structures/NoIssetWithEmpty              |
+-------------+------------------------------------------+
| Themes      | :ref:`Analyze`                           |
+-------------+------------------------------------------+
| Severity    | Minor                                    |
+-------------+------------------------------------------+
| Time To Fix | Instant (5 mins)                         |
+-------------+------------------------------------------+
| Examples    | :ref:`xoops-structures-noissetwithempty` |
+-------------+------------------------------------------+



.. _non-ascii-variables:

Non Ascii Variables
###################


PHP allows certain characters in variable names. The variable name must only include letters, figures, underscores and ASCII characters from 128 to 255. 

In practice, letters outside the scope of ``a-zA-Z0-9`` are rare, and require more care when editing the code or passing it from OS to OS. 

.. code-block:: php

   <?php
   
   class 人 {
       // An actual working class in PHP.
       public function __construct() {
           echo __CLASS__;
       }
   }
   
   $people = new 人();
   
   ?>


See also `Variables <http://php.net/manual/en/language.variables.basics.php>`_.

+-------------+-------------------------------------------+
| Short name  | Variables/VariableNonascii                |
+-------------+-------------------------------------------+
| Themes      | :ref:`Analyze`                            |
+-------------+-------------------------------------------+
| Severity    | Minor                                     |
+-------------+-------------------------------------------+
| Time To Fix | Slow (1 hour)                             |
+-------------+-------------------------------------------+
| Examples    | :ref:`magento-variables-variablenonascii` |
+-------------+-------------------------------------------+



.. _non-static-methods-called-in-a-static:

Non Static Methods Called In A Static
#####################################


Static methods have to be declared as such (using the static keyword). Then, 
one may call them without instantiating the object.

PHP 7.0, and more recent versions, yield a deprecated error : ``Non-static method A\:\:B() should not be called statically``.

PHP 5 and older doesn't check that a method is static or not : at any point, the code may call one method statically.

.. code-block:: php

   <?php
       class x {
           static public function sm( ) { echo __METHOD__.\n; }
           public public sm( ) { echo __METHOD__.\n; }
       } 
       
       x::sm( ); // echo x::sm 
   ?>


It is a bad idea to call non-static method statically. Such method may make use of special
variable `$this <http://php.net/manual/en/language.oop5.basic.php>`_, which will be undefined. PHP will not check those calls at compile time,
nor at running time. 

It is recommended to update this situation : make the method actually static, or use it only 
in object context.

Note that this analysis reports all static method call made on a non-static method,
even within the same class or class hierarchy. PHP silently accepts static call to any
in-family method.

.. code-block:: php

   <?php
       class x {
           public function foo( ) { self::bar() }
           public function bar( ) { echo __METHOD__.\n; }
       } 
   ?>


See also `static keyword <http://php.net/manual/en/language.oop5.static.php>`_.



Suggestions
^^^^^^^^^^^

* Call the method the correct way
* Define the method as static

+-------------+----------------------------------------------------------------------------------------------------------------------------+
| Short name  | Classes/NonStaticMethodsCalledStatic                                                                                       |
+-------------+----------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55` |
+-------------+----------------------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                                      |
+-------------+----------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                            |
+-------------+----------------------------------------------------------------------------------------------------------------------------+



.. _non-constant-index-in-array:

Non-constant Index In Array
###########################


Undefined constants revert as strings in Arrays. They are also called ``barewords``.

In ``$array[index]``, PHP cannot find index as a constant, but, as a default behavior, turns it into the string ``index``. 

This default behavior raise concerns when a corresponding constant is defined, either using `define() <http://www.php.net/define>`_ or the const keyword (outside a class). The definition of the index constant will modify the behavior of the index, as it will now use the constant definition, and not the 'index' string. 

.. code-block:: php

   <?php
   
   // assign 1 to the element index in $array
   // index will fallback to string
   $array[index] = 1; 
   //PHP Notice:  Use of undefined constant index - assumed 'index'
   
   echo $array[index];      // display 1 and the above error
   echo "$array[index]";    // display 1
   echo "$array['index']";  // Syntax error
   
   
   define('index', 2);
    
    // now 1 to the element 2 in $array
    $array[index] = 1;
   
   ?>


It is recommended to make index a real string (with ' or "), or to define the corresponding constant to avoid any future surprise.

Note that PHP 7.2 removes the support for this feature.

See also `PHP RFC: Deprecate and Remove Bareword (Unquoted) Strings <https://wiki.php.net/rfc/deprecate-bareword-strings>`_ and 
         `Syntax <http://php.net/manual/en/language.constants.syntax.php>`_.

+-------------+-------------------------+
| Short name  | Arrays/NonConstantArray |
+-------------+-------------------------+
| Themes      | :ref:`Analyze`          |
+-------------+-------------------------+
| Severity    | Minor                   |
+-------------+-------------------------+
| Time To Fix | Instant (5 mins)        |
+-------------+-------------------------+



.. _non-lowercase-keywords:

Non-lowercase Keywords
######################


Usual convention is to write PHP keywords (like as, foreach, switch, case, `break <http://php.net/manual/en/control-structures.break.php>`_, etc.) all in lowercase. 

.. code-block:: php

   <?php
   
   // usual PHP convention
   foreach($array as $element) {
       echo $element;
   }
   
   // unusual PHP conventions
   Foreach($array AS $element) {
       eCHo $element;
   }
   
   ?>


PHP do understand them in lowercase, UPPERCASE or WilDCase, so there is nothing compulsory here. Although, it will look strange to many. 

Some keywords are missing from this analysis : ``extends``, ``implements``, ``as``. This is due to the internal engine, which doesn't keep track of them in its AST representation.


Suggestions
^^^^^^^^^^^

* Use lowercase only PHP keywords, except for constants such as __CLASS__.

+-------------+------------------------------------------------+
| Short name  | Php/UpperCaseKeyword                           |
+-------------+------------------------------------------------+
| Themes      | :ref:`Coding Conventions <coding-conventions>` |
+-------------+------------------------------------------------+
| Severity    | Minor                                          |
+-------------+------------------------------------------------+
| Time To Fix | Instant (5 mins)                               |
+-------------+------------------------------------------------+



.. _not-a-scalar-type:

Not A Scalar Type
#################


``int`` is the actual PHP scalar type, not ``integer``. 

PHP 7 introduced several scalar types, in particular ``int``, ``bool`` and ``float``. Those three types are easily mistaken with ``integer``, ``boolean``, ``real`` and ``double``. 

Unless those classes actually exists, PHP emits some strange error messages.

.. code-block:: php

   <?php
   
   // This expects a scalar of type 'integer'
   function foo(int $i) {}
   
   // This expects a object of class 'integer'
   function abr(integer $i) {}
   
   ?>


Thanks to ``Benoit Viguier`` for the `original idea <https://twitter.com/b_viguier/status/940173951908700161>`__ for this analysis.

See also `Type declarations <http://php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration>`_.

+-------------+-------------------+
| Short name  | Php/NotScalarType |
+-------------+-------------------+
| Themes      | :ref:`Analyze`    |
+-------------+-------------------+
| Severity    | Major             |
+-------------+-------------------+
| Time To Fix | Instant (5 mins)  |
+-------------+-------------------+



.. _not-not:

Not Not
#######


Double not makes a boolean, not a true.

This is a wrongly done casting to boolean. PHP supports ``(boolean)`` to do the same, faster and cleaner.

.. code-block:: php

   <?php
       // Explicit code
       $b = (boolean) $x; 
       $b = (bool) $x; 
   
       // Wrong type casting
       $b = !!$x; 
   
   ?>


See also `Logical Operators <http://php.net/manual/en/language.operators.logical.php>`_ and 
         `Type Juggling <http://php.net/manual/en/language.types.type-juggling.php>`_.


Suggestions
^^^^^^^^^^^

* Use (bool) casting operator for that
* Don't typecast, and let PHP handle it. This works in situations where the boolean is immediately used.

+-------------+-----------------------------------------------------------------------------------------------+
| Short name  | Structures/NotNot                                                                             |
+-------------+-----------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                |
+-------------+-----------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                         |
+-------------+-----------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                              |
+-------------+-----------------------------------------------------------------------------------------------+
| ClearPHP    | `no-implied-cast <https://github.com/dseguy/clearPHP/tree/master/rules/no-implied-cast.md>`__ |
+-------------+-----------------------------------------------------------------------------------------------+
| Examples    | :ref:`cleverstyle-structures-notnot`, :ref:`tine20-structures-notnot`                         |
+-------------+-----------------------------------------------------------------------------------------------+



.. _null-on-new:

Null On New
###########


Until PHP 7, some classes instantiation could yield null, instead of throwing an exception. 

After issuing a 'new' with those classes, it was important to check if the returned object were null or not. No exception were thrown.

.. code-block:: php

   <?php
   
   // Example extracted from the wiki below
   $mf = new MessageFormatter('en_US', '{this was made intentionally incorrect}');
   if ($mf === null) {
       echo 'Surprise!';
   }
   
   ?>


This inconsistency has been cleaned in PHP 7 : see See `Internal Constructor Behavior <https://wiki.php.net/rfc/internal_constructor_behaviour>`_

See also `PHP RFC: Constructor behaviour of internal classes <https://wiki.php.net/rfc/internal_constructor_behaviour>`_.

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Classes/NullOnNew                                                                                          |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and older                                                                                     |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                           |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _objects-don't-need-references:

Objects Don't Need References
#############################


There is no need to create references for objects, as those are always passed by reference when used as arguments.

.. code-block:: php

   <?php
       
       $object = new stdClass();
       $object->name = 'a';
       
       foo($object);
       print $object->name; // Name is 'b'
       
       // No need to make $o a reference
       function foo(&$o) {
           $o->name = 'b';
       }
       
       $array = array($object);
       foreach($array as &$o) { // No need to make this a reference
           $o->name = 'c';
       }
   
   ?>


See also `Passing by reference <http://php.net/manual/en/language.references.pass.php>`_.

+-------------+-----------------------------------------------------------------------------------------------------------------+
| Short name  | Structures/ObjectReferences                                                                                     |
+-------------+-----------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                                  |
+-------------+-----------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                           |
+-------------+-----------------------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                                |
+-------------+-----------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-references-on-objects <https://github.com/dseguy/clearPHP/tree/master/rules/no-references-on-objects.md>`__ |
+-------------+-----------------------------------------------------------------------------------------------------------------+



.. _old-style-constructor:

Old Style Constructor
#####################


PHP classes used to have the method bearing the same name as the class acts as the constructor. That was PHP 4, and early PHP 5. 

The manual issues a warning about this syntax : ``Old style constructors are DEPRECATED in PHP 7.0, and will be removed in a future version. You should always use `__construct() <http://php.net/manual/en/language.oop5.decon.php>`_ in new code.``

.. code-block:: php

   <?php
   
   namespace {
       // Global namespace is important
       class foo {
           function foo() {
               // This acts as the old-style constructor, and is reported by PHP
           }
       }
   
       class bar {
           function __construct() { }
           function bar() {
               // This doesn't act as constructor, as bar has a __construct() method
           }
       }
   }
   
   namespace Foo\Bar{
       class foo {
           function foo() {
               // This doesn't act as constructor, as bar is not in the global namespace
           }
       }
   }
   
   ?>


This is no more the case in PHP 5, which relies on `__construct() <http://php.net/manual/en/language.oop5.decon.php>`_ to do so. Having this old style constructor may bring in confusion, unless you are also supporting old time PHP 4.

Note that classes with methods bearing the class name, but inside a namespace are not following this convention, as this is not breaking backward compatibility. Those are excluded from the analyze.

See also `Constructors and Destructors <http://php.net/manual/en/language.oop5.decon.php>`_.


Suggestions
^^^^^^^^^^^

* Remove old style constructor and make it __construct
* Remove old libraries and use a modern component

+-------------+---------------------------------------------------------------------------------------------------------+
| Short name  | Classes/OldStyleConstructor                                                                             |
+-------------+---------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                          |
+-------------+---------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                   |
+-------------+---------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                         |
+-------------+---------------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-php4-class-syntax <https://github.com/dseguy/clearPHP/tree/master/rules/no-php4-class-syntax.md>`__ |
+-------------+---------------------------------------------------------------------------------------------------------+



.. _old-style-\_\_autoload():

Old Style __autoload()
######################


Avoid __autoload(), only use spl_register_autoload().

__autoload() will be deprecated in PHP 7.2 and possibly removed in later versions.

__autoload() may only be declared once, and cannot be modified later. This creates potential conflicts between libraries that try to set up their own autoloading schema. 

On the other hand, spl_register_autoload() allows registering and de-registering multiple autoloading functions or methods. 

.. code-block:: php

   <?php
   
   // Modern autoloading.
   function myAutoload($class){}
   spl_register_autoload('myAutoload');
   
   // Old style autoloading.
   function __autoload($class){}
   
   ?>


Do not use the old __autoload() function, but rather the new spl_register_autoload() function. 

See also `Autoloading Classe <http://php.net/manual/en/language.oop5.autoload.php>`_.

+-------------+-----------------------------------------------------------------------------------------------------+
| Short name  | Php/oldAutoloadUsage                                                                                |
+-------------+-----------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                      |
+-------------+-----------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                               |
+-------------+-----------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                    |
+-------------+-----------------------------------------------------------------------------------------------------+
| ClearPHP    | `use-smart-autoload <https://github.com/dseguy/clearPHP/tree/master/rules/use-smart-autoload.md>`__ |
+-------------+-----------------------------------------------------------------------------------------------------+



.. _one-if-is-sufficient:

One If Is Sufficient
####################


Nested conditions may be written another way, and reduce the amount of code.

Nested conditions are equivalent to a ``&&`` condition. As such, they may be switched. When one of the condition has no explicit else, then it is lighter to write it as the first condition. This way, it is written once, and not repeated.

.. code-block:: php

   <?php
   
   // Less conditions are written here.
     	if($b == 2) {
           if($a == 1) {
       		++$c;
       	}
           else {
       		++$d;
       	}
       }
   
   // ($b == 2) is double here
       if($a == 1) {
       	if($b == 2) {
       		++$c;
       	}
       }
       else {
       	if($b == 2) {
       		++$d;
       	}
       }
   ?>




Suggestions
^^^^^^^^^^^

* Switch the if...then conditions, to reduce the amount of conditions to read.

+-------------+----------------------------------------------+
| Short name  | Structures/OneIfIsSufficient                 |
+-------------+----------------------------------------------+
| Themes      | :ref:`Suggestions`                           |
+-------------+----------------------------------------------+
| Severity    | Minor                                        |
+-------------+----------------------------------------------+
| Time To Fix | Quick (30 mins)                              |
+-------------+----------------------------------------------+
| Examples    | :ref:`tikiwiki-structures-oneifissufficient` |
+-------------+----------------------------------------------+



.. _one-letter-functions:

One Letter Functions
####################


One letter functions seems to be really short for a meaningful name. This may happens for very high usage functions, so as to keep code short, but such functions should be rare.

.. code-block:: php

   <?php
   
   // Always use a meaningful name 
   function addition($a, $b) {
       return $a + $b;
   }
   
   // One letter functions are rarely meaningful
   function f($a, $b) {
       return $a + $b;
   }
   
   ?>

+-------------+------------------------------+
| Short name  | Functions/OneLetterFunctions |
+-------------+------------------------------+
| Themes      | :ref:`Analyze`               |
+-------------+------------------------------+
| Severity    | Minor                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _one-variable-string:

One Variable String
###################


These strings only contains one variable or property or array. 

.. code-block:: php

   <?php
   
   $a = 0;
   $b = "$a"; // This is a one-variable string
   
   // Better way to write the above
   $b = (string) $a;
   
   // Alternatives : 
   $b2 = "$a[1]"; // This is a one-variable string
   $b3 = "$a->b"; // This is a one-variable string
   $c = "d";
   $d = "D";
   $b4 = "{$$c}";
   $b5 = "{$a->foo()}";
   
   ?>


When the goal is to convert a variable to a string, it is recommended to use the type casting (string) operator : it is then clearer to understand the conversion. It is also marginally faster, though very little. 

See also `Strings <http://php.net/manual/en/language.types.string.php>`_ and
         `Type Juggling <http://php.net/manual/en/language.types.type-juggling.php>`_.

+-------------+-----------------------------------------------------------------------------------+
| Short name  | Type/OneVariableStrings                                                           |
+-------------+-----------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                    |
+-------------+-----------------------------------------------------------------------------------+
| Severity    | Minor                                                                             |
+-------------+-----------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                  |
+-------------+-----------------------------------------------------------------------------------+
| Examples    | :ref:`tikiwiki-type-onevariablestrings`, :ref:`nextcloud-type-onevariablestrings` |
+-------------+-----------------------------------------------------------------------------------+



.. _only-variable-for-reference:

Only Variable For Reference
###########################


When a method is requesting an argument to be a reference, it cannot be called with a literal value.

The call must be made with a variable, or any assimilated data container : array, property or static property. 

.. code-block:: php

   <?php
   
   // This is not possible
   foo(1,2);
   
   // This is working
   foo($a, $b);
   
   function foo($a, &$b) {}
   
   ?>


Note that PHP may detect this error at linting time, if the method is defined after being called : at that point, PHP will only check the problem during execution. This is definitely the case for methods, compared to functions or static methods.

See also `Passing arguments by reference <http://php.net/manual/en/functions.arguments.php#functions.arguments.by-reference>`_.


Suggestions
^^^^^^^^^^^

* Put the literal value in a variable before calling the method.
* Put the literal value in the default value of the reference argument.

+-------------+----------------------------------------+
| Short name  | Functions/OnlyVariableForReference     |
+-------------+----------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`LintButWontExec` |
+-------------+----------------------------------------+
| Severity    | Critical                               |
+-------------+----------------------------------------+
| Time To Fix | Quick (30 mins)                        |
+-------------+----------------------------------------+



.. _only-variable-passed-by-reference:

Only Variable Passed By Reference
#################################


When an argument is expected by reference, it is compulsory to provide a container. A container may be a variable, an array, a property or a static property. 

This may be linted by PHP, when the function definition is in the same file as the function usage. This is silently linted if definition and usage are separated, if the call is dynamical or made as a method.

.. code-block:: php

   <?php
   
   function foo(&$bar) { /**/ }
   
   function &bar() { /**/ }
   
   // This is not possible : strtolower() returns a value
   foo(strtolower($string));
   
   // This is valid : bar() returns a reference
   foo(bar($string));
   
   ?>


This analysis currently covers functioncalls and static methodcalls, but omits methodcalls.

+-------------+----------------------------------------------------------------------------------------------------------------+
| Short name  | Functions/OnlyVariablePassedByReference                                                                        |
+-------------+----------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                                 |
+-------------+----------------------------------------------------------------------------------------------------------------+
| Severity    | Critical                                                                                                       |
+-------------+----------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                                  |
+-------------+----------------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`dolphin-functions-onlyvariablepassedbyreference`, :ref:`phpipam-functions-onlyvariablepassedbyreference` |
+-------------+----------------------------------------------------------------------------------------------------------------+



.. _only-variable-returned-by-reference:

Only Variable Returned By Reference
###################################


Function can't return literals by reference.

When a function returns a reference, it is only possible to return variables, properties or static properties. 

Anything else, like literals or static expressions, yield a warning at execution time.

.. code-block:: php

   <?php
   
   // Can't return a literal number
   function &foo() {
       return 3 + rand();
   }
   
   // bar must return values that are stored in a 
   function &bar() {
       $a = 3 + rand();
       return $a;
   }
   
   ?>

+-------------+--------------------------------------------+
| Short name  | Structures/OnlyVariableReturnedByReference |
+-------------+--------------------------------------------+
| Themes      | :ref:`Analyze`                             |
+-------------+--------------------------------------------+
| Severity    | Major                                      |
+-------------+--------------------------------------------+
| Time To Fix | Quick (30 mins)                            |
+-------------+--------------------------------------------+



.. _or-die:

Or Die
######


Classic old style failed error management. 

.. code-block:: php

   <?php
   
   // In case the connexion fails, this kills the current script
   mysql_connect('localhost', $user, $pass) or die();
   
   ?>


Interrupting a script will leave the application with a blank page, will make your life miserable for testing. Just don't do that.

+-------------+-------------------------------------------------------------------------------------------+
| Short name  | Structures/OrDie                                                                          |
+-------------+-------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                            |
+-------------+-------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                     |
+-------------+-------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                           |
+-------------+-------------------------------------------------------------------------------------------+
| ClearPHP    | `no-implied-if <https://github.com/dseguy/clearPHP/tree/master/rules/no-implied-if.md>`__ |
+-------------+-------------------------------------------------------------------------------------------+



.. _order-of-declaration:

Order Of Declaration
####################


The order used to declare members and methods has a great impact on readability and maintenance. However, practices varies greatly. As usual, being consistent is the most important and useful.

The suggested order is the following : traits, constants, properties, methods. 
Optional characteristics, like final, static... are not specified. Special methods names are not specified. 

.. code-block:: php

   <?php
   
   class x {
       use traits;
       
       const CONSTANTS = 1;
       const CONSTANTS2 = 1;
       const CONSTANTS3 = 1;
       
       private $property = 2;
       private $property2 = 2;
       private $property3 = 2;
       
       public function foo() {}
       public function foo2() {}
       public function foo3() {}
       public function foo4() {}
   }
   
   ?>

+------------+------------------------------------------------+
| Short name | Classes/OrderOfDeclaration                     |
+------------+------------------------------------------------+
| Themes     | :ref:`Coding Conventions <coding-conventions>` |
+------------+------------------------------------------------+



.. _overwriting-variable:

Overwriting Variable
####################


Replacing the content of a variable by something different is prone to errors. For example, it is not obvious if the $text variable is plain text or HTML text. 

.. code-block:: php

   <?php
   
   // Confusing
   $text = htmlentities($text);
   
   // Better
   $textHTML = htmlentities($text);
   
   ?>


Besides, it is possible that the source is needed later, for extra processing. 

Note that accumulators, like += .=  or [] etc., that are meant to collect lots of values with consistent type are OK.

+-------------+-----------------------+
| Short name  | Variables/Overwriting |
+-------------+-----------------------+
| Themes      | :ref:`Analyze`        |
+-------------+-----------------------+
| Severity    | Major                 |
+-------------+-----------------------+
| Time To Fix | Quick (30 mins)       |
+-------------+-----------------------+



.. _overwritten-exceptions:

Overwritten Exceptions
######################


In catch blocks, it is good practice not to overwrite the incoming exception, as information about the exception will be lost.

.. code-block:: php

   <?php
   
   try {
       doSomething();
   } catch (SomeException $e) { 
       // $e is overwritten 
       $e = new anotherException($e->getMessage()); 
       throw $e;
   } catch (SomeOtherException $e) { 
       // $e is chained with the next exception 
       $e = new Exception($e->getMessage(), 0, $e); 
       throw $e;
   }
   
   ?>

+-------------+------------------------------------+
| Short name  | Exceptions/OverwriteException      |
+-------------+------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Suggestions` |
+-------------+------------------------------------+
| Severity    | Minor                              |
+-------------+------------------------------------+
| Time To Fix | Quick (30 mins)                    |
+-------------+------------------------------------+



.. _overwritten-literals:

Overwritten Literals
####################


The same variable is assigned a literal twice. It is possible that one of the assignation is too much.

This analysis doesn't take into account the distance between two assignations : it may report false positives when the variable is actually used for several purposes, and, as such, assigned twice with different values.

.. code-block:: php

   <?php
   
   function foo() {
       // Two assignations in a short sequence : one is too many.
       $a = 1;
       $a = 2;
       
       for($i = 0; $i < 10; $i++) {
           $a += $i;
       }
       $b = $a;
       
       // New assignation. $a is now used as an array. 
       $a = array(0);
   }
   
   ?>

+-------------+-------------------------------+
| Short name  | Variables/OverwrittenLiterals |
+-------------+-------------------------------+
| Themes      | :ref:`Analyze`                |
+-------------+-------------------------------+
| Severity    | Major                         |
+-------------+-------------------------------+
| Time To Fix | Instant (5 mins)              |
+-------------+-------------------------------+



.. _php-7.0-new-classes:

PHP 7.0 New Classes
###################


Those classes are now declared natively in PHP 7.0 and should not be declared in custom code. 

There are 8 new classes : 

* ``Error``
* ``ParseError``
* ``TypeError``
* ``ArithmeticError``
* ``DivisionByZeroError``
* ``ClosedGeneratorException``
* ``ReflectionGenerator``
* ``ReflectionType``
* ``AssertionError``

.. code-block:: php

   <?php
   
   namespace {
       // Global namespace
       class Error {
           // Move to a namespace
           // or, remove this class
       }
   }
   
   namespace B {
       class Error {
           // This is OK : in a namespace
       }
   }
   
   ?>


See also `New Classes and Interfaces <http://php.net/manual/en/migration70.classes.php>`_.

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Php/Php70NewClasses                                                                                        |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and older                                                                                     |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                              |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _php-7.0-new-interfaces:

PHP 7.0 New Interfaces
######################


The following interfaces are introduced in PHP 7.0. They shouldn't be defined in custom code.

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Php/Php70NewInterfaces                                                                                     |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and older                                                                                     |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                              |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _php-7.0-removed-directives:

PHP 7.0 Removed Directives
##########################


List of directives that are removed in PHP 7.0.

+-------------+------------------------------------------------------+
| Short name  | Php/Php70RemovedDirective                            |
+-------------+------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71` |
+-------------+------------------------------------------------------+
| Php Version | With PHP 7.0 and more recent                         |
+-------------+------------------------------------------------------+
| Severity    | Major                                                |
+-------------+------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                        |
+-------------+------------------------------------------------------+



.. _php-7.0-scalar-typehints:

PHP 7.0 Scalar Typehints
########################


New scalar typehints were introduced : bool, int, float, string. 

They cannot be used before PHP 7.0, and will be confused with classes or interfaces.

.. code-block:: php

   <?php
   
   function foo(string $name) {
       print Hello $name;
   }
   
   foo(Damien); 
   // display 'Hello Damien'
   
   foo(33); 
   // displays an error
   
   ?>


See also `Scalar type declarations <http://php.net/manual/en/migration70.new-features.php#migration70.new-features.scalar-type-declarations>`_, and 
         `PHP 7 SCALAR TYPE DECLARATIONS <https://tutorials.kode-blog.com/php-7-scalar-type-declarations>`_.

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Php/PHP70scalartypehints                                                                                   |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and more recent                                                                               |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Critical                                                                                                   |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                            |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _php-7.1-microseconds:

PHP 7.1 Microseconds
####################


PHP supports microseconds in ``DateTime`` class and date_create() function. This was introduced in PHP 7.1.

In previous PHP versions, those dates only used seconds, leading to lazy comparisons : 

.. code-block:: php

   <?php
   
   $now = date_create();
   usleep(10);              // wait for 0.001 ms
   var_dump($now == date_create());
   
   ?>


This code displays true in PHP 7.0 and older, (unless the code was run too close from the next second). In PHP 7.1, this is always false.

This is also true with Datetime : 

.. code-block:: php

   <?php
   
   $now = new DateTime();
   usleep(10);              // wait for 0.001 ms
   var_dump((new DateTime())->format('u') == $now->format('u'));
   
   ?>


This evolution impacts mostly exact comparisons (== and ===). Non-equality (!= and !==) will probably be always true, and should be reviewed.

See also `Backward incompatible changes <http://php.net/manual/en/migration71.incompatible.php>`_.

+-------------+---------------------------+
| Short name  | Php/Php71microseconds     |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP71` |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _php-7.1-removed-directives:

PHP 7.1 Removed Directives
##########################


List of directives that are removed in PHP 7.1.

+-------------+------------------------------+
| Short name  | Php/Php71RemovedDirective    |
+-------------+------------------------------+
| Themes      | :ref:`CompatibilityPHP71`    |
+-------------+------------------------------+
| Php Version | With PHP 7.1 and more recent |
+-------------+------------------------------+
| Severity    | Major                        |
+-------------+------------------------------+
| Time To Fix | Slow (1 hour)                |
+-------------+------------------------------+



.. _php-7.1-scalar-typehints:

PHP 7.1 Scalar Typehints
########################


A new scalar typehint was introduced : iterable. 

It can't be used before PHP 7.1, and will be confused with classes or interfaces.

.. code-block:: php

   <?php
   
   function foo(iterable $iterable) {
       foreach ($iterable as $value) {
           echo $value.PHP_EOL;
       }
   }
   
   foo(range(1,20)); 
   // works with array
   
   foo(new ArrayIterator([1, 2, 3])); 
   // works with an iterator
   
   foo((function () { yield 1; })() ); 
   // works with a generator 
   
   ?>


See also `iterable pseudo-type <http://php.net/manual/en/migration71.new-features.php#migration71.new-features.iterable-pseudo-type>`_, and 
         `The iterable Pseudo-Type <https://knpuniversity.com/screencast/php7/iterable-type>`_.

+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Php/PHP71scalartypehints                                                                                                              |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.1 and more recent                                                                                                          |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Critical                                                                                                                              |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                                       |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+



.. _php-7.2-deprecations:

PHP 7.2 Deprecations
####################


Several functions are deprecated in PHP 7.2. 

* `parse_str() <http://www.php.net/parse_str>`_ with no second argument
* `assert() <http://www.php.net/assert>`_ on strings
* Usage of gmp_random(), `create_function() <http://www.php.net/create_function>`_, `each() <http://www.php.net/each>`_
* Usage of (unset)
* Usage of $php_errormsg
* directive mbstring.func_overload (not supported yet)

Deprecated functions and extensions are reported in a separate analysis.

+-------------+---------------------------+
| Short name  | Php/Php72Deprecation      |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP72` |
+-------------+---------------------------+
| Php Version | With PHP 7.2 and older    |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Slow (1 hour)             |
+-------------+---------------------------+



.. _php-7.2-object-keyword:

PHP 7.2 Object Keyword
######################


'object' is a PHP keyword. It can't be used for class, interface or trait name. 

This is the case since PHP 7.2. 

.. code-block:: php

   <?php
   
   // Valid until PHP 7.2
   class object {}
   
   // Altough it is really weird anyway...
   
   ?>


See also `List of Keywords <http://php.net/manual/en/reserved.keywords.php>`_.

+-------------+---------------------------+
| Short name  | Php/Php72ObjectKeyword    |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP72` |
+-------------+---------------------------+
| Php Version | With PHP 7.2 and older    |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _php-7.2-removed-functions:

PHP 7.2 Removed Functions
#########################


The following PHP native functions were removed in PHP 7.2.

* png2wbmp()
* jpeg2wbmp()

See also `Deprecated features in PHP 7.2.x <http://php.net/manual/en/migration72.deprecated.php>`_.

+-------------+---------------------------+
| Short name  | Php/Php72RemovedFunctions |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP72` |
+-------------+---------------------------+
| Php Version | With PHP 7.2 and older    |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Slow (1 hour)             |
+-------------+---------------------------+



.. _php-7.2-scalar-typehints:

PHP 7.2 Scalar Typehints
########################


A new scalar typehint was introduced : object. 

It can't be used before PHP 7.2, and will be confused with classes or interfaces.

.. code-block:: php

   <?php
   
   function test(object $obj) : object
   {
       return new SplQueue();
   }
   
   test(new StdClass());
   
   ?>


See also `New object type <http://php.net/manual/en/migration72.new-features.php#migration72.new-features.iterable-pseudo-type>`_, and 
         `PHP 7.2 and Object Typehint <http://blog.tekmi.nl/php-7-2-and-object-typehint/>`_.

+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Php/PHP72scalartypehints                                                                                                                                         |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.2 and more recent                                                                                                                                     |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Critical                                                                                                                                                         |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                                                                  |
+-------------+------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _php-7.3-last-empty-argument:

PHP 7.3 Last Empty Argument
###########################


PHP allows the last element of any functioncall to be empty. The argument is then not send.

This was introduced in PHP 7.3, and is not backward compatible.

The last empty line is easier on the VCS, allowing clearer text diffs. 

.. code-block:: php

   <?php
   
   function foo($a, $b) {
       print_r(func_get_args());
   }
   
   
   foo(1, 
       2, 
       );
   
   foo(1);
   
   
   ?>


See also `Allow a trailing comma in function calls <https://wiki.php.net/rfc/trailing-comma-function-calls>`_ and 
         `Trailing commas <https://www.puppetcookbook.com/posts/trailing-commas.html>`_.

+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Php/PHP73LastEmptyArgument                                                                                                                                                                  |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71`, :ref:`CompatibilityPHP72`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.3 and more recent                                                                                                                                                                |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Critical                                                                                                                                                                                    |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                                                                                             |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _php-7.3-removed-functions:

PHP 7.3 Removed Functions
#########################


The following PHP native functions were removed in PHP 7.3.

* image2wbmp

+-------------+---------------------------+
| Short name  | Php/Php73RemovedFunctions |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP73` |
+-------------+---------------------------+
| Php Version | With PHP 7.3 and older    |
+-------------+---------------------------+
| Severity    | Critical                  |
+-------------+---------------------------+
| Time To Fix | Slow (1 hour)             |
+-------------+---------------------------+



.. _php-70-removed-functions:

PHP 70 Removed Functions
########################


The following PHP native functions were removed in PHP 7.0.

* ereg()
* ereg_replace()
* eregi()
* eregi_replace()
* split()
* spliti()
* sql_regcase()
* `magic_quotes_runtime() <http://www.php.net/magic_quotes_runtime>`_
* `set_magic_quotes_runtime() <http://www.php.net/set_magic_quotes_runtime>`_
* `call_user_method() <http://www.php.net/call_user_method>`_
* `call_user_method_array() <http://www.php.net/call_user_method_array>`_
* `set_socket_blocking() <http://www.php.net/set_socket_blocking>`_
* mcrypt_ecb()
* mcrypt_cbc()
* mcrypt_cfb()
* mcrypt_ofb()
* datefmt_set_timezone_id()
* imagepsbbox()
* imagepsencodefont()
* imagepsextendfont()
* imagepsfreefont()
* imagepsloadfont()
* imagepsslantfont()
* imagepstext()

+-------------+------------------------------------------------------+
| Short name  | Php/Php70RemovedFunctions                            |
+-------------+------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71` |
+-------------+------------------------------------------------------+
| Php Version | With PHP 7.0 and older                               |
+-------------+------------------------------------------------------+
| Severity    | Major                                                |
+-------------+------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                        |
+-------------+------------------------------------------------------+



.. _php-keywords-as-names:

PHP Keywords As Names
#####################


PHP has a set of reserved keywords. It is recommended not to use those keywords for names structures. 

PHP does check that a number of structures, such as classes, methods, interfaces... can't be named or called using one of the keywords. However, in a few other situations, no check are enforced. Using keywords in such situation is confusing. 

.. code-block:: php

   <?php
   
   // This keyword is reserved since PHP 7.2
   class object {
       // _POST is used by PHP for the $_POST variable
       // This methods name is probably confusing, 
       // and may attract more than its share of attention
       function _POST() {
       
       }
   }
   
   ?>


See also `List of Keywords <http://php.net/manual/en/reserved.keywords.php>`_,
         `Predefined Classes <http://php.net/manual/en/reserved.classes.php>`_,
         `Predefined Constants <http://php.net/manual/en/reserved.constants.php>`_,
         `List of other reserved words <http://php.net/manual/en/reserved.other-reserved-words.php>`_ and 
         `Predefined Variables <http://php.net/manual/en/reserved.variables.php>`_.

+---------------+---------+--------+------------------------------------------------------------------------------------+
| Name          | Default | Type   | Description                                                                        |
+---------------+---------+--------+------------------------------------------------------------------------------------+
| reservedNames |         | string | Other reserved names : all in a string, comma separated.                           |
+---------------+---------+--------+------------------------------------------------------------------------------------+
| allowedNames  |         | string | PHP reserved names that can be used in the code. All in a string, comma separated. |
+---------------+---------+--------+------------------------------------------------------------------------------------+



+-------------+-------------------+
| Short name  | Php/ReservedNames |
+-------------+-------------------+
| Themes      | :ref:`Analyze`    |
+-------------+-------------------+
| Severity    | Major             |
+-------------+-------------------+
| Time To Fix | Quick (30 mins)   |
+-------------+-------------------+



.. _php5-indirect-variable-expression:

PHP5 Indirect Variable Expression
#################################


Indirect variable expressions changes between PHP 5 an 7.

The following structures are evaluated differently in PHP 5 and 7. It is recommended to review them or switch to a less ambiguous syntax.

.. code-block:: php

   <?php
   
   // PHP 7 
   $foo = 'bar';
   $bar['bar']['baz'] = 'foobarbarbaz';
   echo $$foo['bar']['baz'];
   echo ($$foo)['bar']['baz'];
   
   // PHP 5
   $foo['bar']['baz'] = 'bar';
   $bar = 'foobarbazbar';
   echo $$foo['bar']['baz'];
   echo ${$foo['bar']['baz']};
   
   ?>


See `Backward incompatible changes PHP 7.0 <http://php.net/manual/en/migration70.incompatible.php>`_

+-----------------------+-------------------------+-------------------------+
| Expression            | PHP 5 interpretation    | PHP 7 interpretation    |
+-----------------------+-------------------------+-------------------------+
|$$foo['bar']['baz']    |${$foo['bar']['baz']}    |($$foo)['bar']['baz']    |
|$foo->$bar['baz']      |$foo->{$bar['baz']}      |($foo->$bar)['baz']      |
|$foo->$bar['baz']()    |$foo->{$bar['baz']}()    |($foo->$bar)['baz']()    |
|Foo\:\:$bar['baz']()   |Foo\:\:{$bar['baz']}()   |(Foo\:\:$bar)['baz']()   |
+-----------------------+-------------------------+-------------------------+

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Variables/Php5IndirectExpression                                                                           |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and older                                                                                     |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                              |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _php7-dirname:

PHP7 Dirname
############


With PHP 7, `dirname() <http://www.php.net/dirname>`_ has a second argument that represents the number of parent folder to follow. This prevent us from using nested `dirname() <http://www.php.net/dirname>`_ calls to reach an grand-parent direct.

.. code-block:: php

   <?php
   $path = '/a/b/c/d/e/f';
   
   // PHP 7 syntax
   $threeFoldersUp = dirname($path, 3);
   
   // PHP 5 syntax
   $threeFoldersUp = dirname(dirname(dirname($path)));
   
   ?>


See also `dirname <http://php.net/dirname>`_.

+-------------+--------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Structures/PHP7Dirname                                                                                                         |
+-------------+--------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56`, :ref:`Suggestions` |
+-------------+--------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                                          |
+-------------+--------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                                |
+-------------+--------------------------------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`openconf-structures-php7dirname`, :ref:`mediawiki-structures-php7dirname`                                                |
+-------------+--------------------------------------------------------------------------------------------------------------------------------+



.. _parent-first:

Parent First
############


When calling parent constructor, always put it first in the ``__construct`` method. It ensures the parent is correctly build before the child start using values. 

.. code-block:: php

   <?php
   
   class father {
       protected $name = null;
       
       function __construct() {
           $this->name = init();
       }
   }
   
   class goodSon {
       function __construct() {
           // parent is build immediately, 
           parent::__construct();
           echo my name is.$this->name;
       }
   }
   
   class badSon {
       function __construct() {
           // This will fail.
           echo my name is.$this->name;
   
           // parent is build later, 
           parent::__construct();
       }
   }
   
   ?>


This analysis doesn't apply to Exceptions. 


Suggestions
^^^^^^^^^^^

* Use ``parent\:\:__construct`` as the first call in the constructor.

+-------------+---------------------------------------+
| Short name  | Classes/ParentFirst                   |
+-------------+---------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Suggestions`    |
+-------------+---------------------------------------+
| Severity    | Minor                                 |
+-------------+---------------------------------------+
| Time To Fix | Quick (30 mins)                       |
+-------------+---------------------------------------+
| Examples    | :ref:`prestashop-classes-parentfirst` |
+-------------+---------------------------------------+



.. _parent,-static-or-self-outside-class:

Parent, Static Or Self Outside Class
####################################


Parent, static and self keywords must be used within a class or a trait. They make no sens outside a class or trait scope, as self and static refers to the current class and parent refers to one of parent above.

PHP 7.0 and later detect their usage at compile time, and emits a fatal error.

.. code-block:: php

   <?php
   
   class x {
       const Y = 1;
       
       function foo() {
           // self is \x
           echo self::Y;
       }
   }
   
   const Z = 1;
   // This doesn't compile anymore
   echo self::Z;
   
   ?>


Static may be used in a function or a closure, but not globally.

+-------------+-------------------------+
| Short name  | Classes/PssWithoutClass |
+-------------+-------------------------+
| Themes      | :ref:`Analyze`          |
+-------------+-------------------------+
| Severity    | Major                   |
+-------------+-------------------------+
| Time To Fix | Quick (30 mins)         |
+-------------+-------------------------+



.. _parenthesis-as-parameter:

Parenthesis As Parameter
########################


Using parenthesis around parameters used to silent some internal check. This is not the case anymore in PHP 7, and should be fixed by removing the parenthesis and making the value a real reference.

.. code-block:: php

   <?php
   
   // PHP 7 sees through parenthesis
   $d = foo(1, 2, $c);
   
   // Avoid parenthesis in arguments
   $d = foo(1, 2, ($c));
   
   ?>

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Php/ParenthesisAsParameter                                                                                 |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                            |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _pathinfo()-returns-may-vary:

Pathinfo() Returns May Vary
###########################


`pathinfo() <http://www.php.net/pathinfo>`_ function returns an array whose content may vary. It is recommended to collect the values after check, rather than directly.

.. code-block:: php

   <?php
   
   $file = '/a/b/.c';
   //$extension may be missing, leading to empty $filename and filename in $extension
   list( $dirname, $basename, $extension, $filename ) = array_values( pathinfo($file) );
   
   //Use PHP 7.1 list() syntax to assign correctly the values, and skip array_values()
   //This emits a warning in case of missing index
   ['dirname'   => $dirname, 
    'basename'  => $basename, 
    'extension' => $extension, 
    'filename'  => $filename ] = pathinfo($file);
    
   //This works without warning
   $details = pathinfo($file);
   $dirname   = $details['dirname'] ?? getpwd();
   $basename  = $details['basename'] ?? '';
   $extension = $details['extension'] ?? '';
   $filename  = $details['filename'] ?? '';
   
   ?>


The same applies to `parse_url() <http://www.php.net/parse_url>`_, which returns an array with various index.

+-------------+---------------------+
| Short name  | Php/PathinfoReturns |
+-------------+---------------------+
| Themes      | :ref:`Analyze`      |
+-------------+---------------------+
| Severity    | Minor               |
+-------------+---------------------+
| Time To Fix | Quick (30 mins)     |
+-------------+---------------------+



.. _php-7-indirect-expression:

Php 7 Indirect Expression
#########################


Those are variable indirect expressions that are interpreted differently in PHP 5 and PHP 7. 

You should check them so they don't behave strangely.

.. code-block:: php

   <?php
   
   // Ambiguous expression : 
   $b = $$foo['bar']['baz'];
   echo $b;
   
   $foo = array('bar' => array('baz' => 'bat'));
   $bat = 'PHP 5.6';
   
   // In PHP 5, the expression above means : 
   $b = $\{$foo['bar']['baz']};
   $b = 'PHP 5.6';
   
   $foo = 'a';
   $a = array('bar' => array('baz' => 'bat'));
   
   // In PHP 7, the expression above means : 
   $b = ($$foo)['bar']['baz'];
   $b = 'bat';
   
   ?>


See also `Changes to variable handling <http://php.net/manual/en/migration70.incompatible.php>`_.

+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Variables/Php7IndirectExpression                                                                                                      |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP70` |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and more recent                                                                                                          |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                                                 |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                                                         |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+



.. _php-7.1-new-class:

Php 7.1 New Class
#################


New classes, introduced in PHP 7.1. If classes where created with the same name, in current code, they have to be moved in a namespace, or removed from code to migrate safely to PHP 7.1.

The new class is : ReflectionClassConstant. The other class is 'Void' : this is forbidden as a class name, as Void is used for return type hint.

.. code-block:: php

   <?php
   
   class ReflectionClassConstant {
       // Move to a namespace, do not leave in global
       // or, remove this class
   }
   
   ?>

+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Php/Php71NewClasses                                                                                                                   |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.1 and older                                                                                                                |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                                                 |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                                                         |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+



.. _php-7.2-new-class:

Php 7.2 New Class
#################


New classes, introduced in PHP 7.2. If classes where created with the same name, in current code, they have to be moved in a namespace, or removed from code to migrate safely to PHP 7.2.

The new class is : HashContext.

.. code-block:: php

   <?php
   
   namespace {
       // Global namespace
       class HashContext {
           // Move to a namespace
           // or, remove this class
       }
   }
   
   namespace B {
       class HashContext {
           // This is OK : in a namespace
       }
   }
   
   ?>

+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Php/Php72NewClasses                                                                                                                                                                         |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56`, :ref:`CompatibilityPHP72` |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.2 and older                                                                                                                                                                      |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                                                                                                       |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                                                                                                               |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _php7-relaxed-keyword:

Php7 Relaxed Keyword
####################


Most of the traditional PHP keywords may be used inside classes, trait or interfaces.

.. code-block:: php

   <?php
   
   // Compatible with PHP 7.0 + 
   class foo {
       // as is a PHP 5 keyword
       public function as() {
       
       }
   }
   
   ?>


This was not the case in PHP 5, and will yield parse errors.

See also `Loosening Reserved Word Restrictions <http://php.net/manual/en/migration70.other-changes.php#migration70.other-changes.loosening-reserved-words>`_.

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Php/Php7RelaxedKeyword                                                                                     |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and more recent                                                                               |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                              |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _phpinfo:

Phpinfo
#######


`phpinfo() <http://www.php.net/phpinfo>`_ is a great function to learn about the current configuration of the server.

.. code-block:: php

   <?php
   
   if (DEBUG) {
       phpinfo();
   }
   
   ?>


If left in the production code, it may lead to a critical leak, as any attacker gaining access to this data will know a lot about the server configuration.
It is advised to never leave that kind of instruction in a production code.

+-------------+---------------------------------+
| Short name  | Structures/PhpinfoUsage         |
+-------------+---------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Security` |
+-------------+---------------------------------+
| Severity    | Major                           |
+-------------+---------------------------------+
| Time To Fix | Quick (30 mins)                 |
+-------------+---------------------------------+



.. _possible-increment:

Possible Increment
##################


This expression looks like a typo : a missing + would change the behavior.

The same pattern is not reported with -, as it is legit expression. + sign is usually understated, rather than explicit.

.. code-block:: php

   <?php
   
   // could it be a ++$b ? 
   $a = +$b;
   
   ?>


See also `Incrementing/Decrementing Operators <http://php.net/manual/en/language.operators.increment.php>`_ and 
         `Arithmetic Operators <http://php.net/manual/en/language.operators.arithmetic.php>`_.


Suggestions
^^^^^^^^^^^

* Drop the whole assignation
* Complete the addition with another value : $a = 1 + $b
* Make this a ++ operator : ++$b
* Make this a negative operator : -$b
* Make the casting explicit : (int) $b

+-------------+------------------------------------------------------------------------------------------+
| Short name  | Structures/PossibleIncrement                                                             |
+-------------+------------------------------------------------------------------------------------------+
| Themes      | :ref:`Suggestions`                                                                       |
+-------------+------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                    |
+-------------+------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                         |
+-------------+------------------------------------------------------------------------------------------+
| Examples    | :ref:`zurmo-structures-possibleincrement`, :ref:`mediawiki-structures-possibleincrement` |
+-------------+------------------------------------------------------------------------------------------+



.. _possible-infinite-loop:

Possible Infinite Loop
######################


Loops on files that can't be open results in infinite loop.

`fgets() <http://www.php.net/fgets>`_, and functions like `fgetss() <http://www.php.net/fgetss>`_, `fgetcsv() <http://www.php.net/fgetcsv>`_, `fread() <http://www.php.net/fread>`_, return false when they finish reading, or can't access the file. 

In case the file is not accessible, comparing the result of the reading to something that is falsy, leads to a permanent valid condition. The execution will only finish when the ``max_execution_time`` is reached. 

.. code-block:: php

   <?php
   
   $file = fopen('/path/to/file.txt', 'r');
   // when fopen() fails, the next loops is infinite
   // fgets() will always return false, and while will always be true. 
   while($line = fgets($file) != 'a') {
       doSomething();
   }
   
   ?>


It is recommended to check the file resources when they are opened, and always use === or !== to compare readings. `feof() <http://www.php.net/feof>`_ is also a reliable function here.

+-------------+---------------------------------+
| Short name  | Structures/PossibleInfiniteLoop |
+-------------+---------------------------------+
| Themes      | :ref:`Analyze`                  |
+-------------+---------------------------------+
| Severity    | Critical                        |
+-------------+---------------------------------+
| Time To Fix | Quick (30 mins)                 |
+-------------+---------------------------------+



.. _pre-increment:

Pre-increment
#############


When possible, use the pre-increment operator (++$i or --$i) instead of the post-increment operator ($i++ or $i--).

The latter needs an extra memory allocation that costs about 10% of performances. 

.. code-block:: php

   <?php
   
   // ++$i should be preferred over $i++, as current value is not important
   for($i = 0; $i <10; ++$i) {
       // do Something
   }
   
   // ++$b and $b++ have different impact here, since $a will collect $b + 1 or $b, respectively.
   $a = $b++;
   
   ?>


This is a micro-optimisation. However, its usage is so widespread, including within loops, that it may eventually be visible. As such, it is recommended to adopt this rule, and only consider changing legacy code as they are refactored for other reasons.

+-------------+-------------------------------------+
| Short name  | Performances/PrePostIncrement       |
+-------------+-------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Performances` |
+-------------+-------------------------------------+
| Severity    | Minor                               |
+-------------+-------------------------------------+
| Time To Fix | Quick (30 mins)                     |
+-------------+-------------------------------------+



.. _preprocess-arrays:

Preprocess Arrays
#################


Using long list of assignations for initializing arrays is significantly slower than the declaring them as an array. 

.. code-block:: php

   <?php
   
   // Slow way
   $a = []; // also with $a = array();
   $a[1] = 2;
   $a[2] = 3;
   $a[3] = 5;
   $a[4] = 7;
   $a[5] = 11;
   
   // Faster way
   $a = [1 => 2, 
         2 => 3,
         3 => 5,
         4 => 7,
         5 => 11];
   
   // Even faster way if indexing is implicit
   $a = [2, 3, 5, 7, 11];
   
   ?>


If the array has to be completed rather than created, it is also faster to use += when there are more than ten elements to add.

.. code-block:: php

   <?php
   
   // Slow way
   $a = []; // also with $a = array();
   $a[1] = 2;
   $a[2] = 3;
   $a[3] = 5;
   // some expressions to get $seven and $eleven
   $a[4] = $seven;
   $a[5] = $eleven;
   
   // Faster way
   $a = [1 => 2, 
         2 => 3,
         3 => 5];
   // some expressions to get $seven and $eleven
   $a += [4 => $seven, 
          5 => $eleven];
   
   // Even faster way if indexing is implicit
   $a = [2, 3, 5];
   // some expressions to get $seven and $eleven
   $a += [$seven, $eleven];
   
   ?>

+-------------+-------------------------+
| Short name  | Arrays/ShouldPreprocess |
+-------------+-------------------------+
| Themes      | none                    |
+-------------+-------------------------+
| Severity    | Minor                   |
+-------------+-------------------------+
| Time To Fix | Quick (30 mins)         |
+-------------+-------------------------+



.. _preprocessable:

Preprocessable
##############


The following expression are made of literals or already known values : they may be fully calculated before running PHP.

.. code-block:: php

   <?php
   
   // Building an array from a string
   $name = 'PHP'.' '.'7.2';
   
   // Building an array from a string
   $list = explode(',', 'a,b,c,d,e,f');
   
   // Calculating a power
   $kbytes = $bytes / pow(2, 10);
   
   // This will never change
   $name = ucfirst(strtolower('PARIS'));
   
   ?>


By doing so, this will reduce the amount of work of PHP.

+-------------+--------------------------------------------------------+
| Short name  | Structures/ShouldPreprocess                            |
+-------------+--------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Suggestions`, :ref:`Suggestions` |
+-------------+--------------------------------------------------------+
| Severity    | Minor                                                  |
+-------------+--------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                       |
+-------------+--------------------------------------------------------+



.. _print-and-die:

Print And Die
#############


`Die() <http://www.php.net/die>`_ also prints. 

When stopping a script with `die() <http://www.php.net/die>`_, it is possible to provide a message as first argument, that will be displayed at execution. There is no need to make a specific call to print or echo.

.. code-block:: php

   <?php
   
   //  die may do both print and die.
   echo 'Error message';
   die();
   
   //  exit may do both print and die.
   print 'Error message';
   exit;
   
   //  exit cannot print integers only : they will be used as status report to the system.
   print 'Error message';
   exit 1;
   
   ?>

+-------------+------------------------+
| Short name  | Structures/PrintAndDie |
+-------------+------------------------+
| Themes      | :ref:`Analyze`         |
+-------------+------------------------+
| Severity    | Minor                  |
+-------------+------------------------+
| Time To Fix | Instant (5 mins)       |
+-------------+------------------------+



.. _printf-number-of-arguments:

Printf Number Of Arguments
##########################


The number of arguments provided to `printf() <http://www.php.net/printf>`_ or `vprintf() <http://www.php.net/vprintf>`_ doesn't match the format string.

Extra arguments are ignored, and are dead code as such. Missing arguments are reported with a warning, and nothing is displayed.

.. code-block:: php

   <?php
   
   // not enough
   printf(' a %s ', $a1); 
   // OK
   printf(' a %s ', $a1, $a2); 
   // too many
   printf(' a %s ', $a1, $a2, $a3); 
   
   // not enough
   sprintf(' a %s ', $a1); 
   // OK
   \sprintf(' a %s ', $a1, $a2); 
   // too many
   sprintf(' a %s ', $a1, $a2, $a3); 
   
   ?>


See also `printf <http://php.net/printf>`_ and `sprintf <http://php.net/sprintf>`_.

+-------------+----------------------------+
| Short name  | Structures/PrintfArguments |
+-------------+----------------------------+
| Themes      | :ref:`Analyze`             |
+-------------+----------------------------+
| Severity    | Minor                      |
+-------------+----------------------------+
| Time To Fix | Instant (5 mins)           |
+-------------+----------------------------+



.. _processing-collector:

Processing Collector
####################


When accumulating data in a variable, within a loop, it is slow to apply repeatedly a function to the variable.

The example below illustrate the problem : ``$collector`` is build with element from ``$array``. ``$collector`` actually gets larger and larger, slowing the `in_array() <http://www.php.net/in_array>`_ call each time. 

It is better to apply the `preg_replace() <http://www.php.net/preg_replace>`_ to ``$a``, a short variable, and then, add ``$a`` to the collector.

.. code-block:: php

   <?php
   
   // Fast way
   $collector = '';
   foreach($array as $a){
       $a = preg_replace('/__(.*?)__/', '<b>$1</b>', $a);
       $collector .= $a;
   }
   
   // Slow way
   $collector = '';
   foreach($array as $a){
       $collector .= $a;
       $collector = preg_replace('/__(.*?)__/', '<b>$1</b>', $collector);
   }
   
   ?>

+-------------+-------------------------------+
| Short name  | Performances/RegexOnCollector |
+-------------+-------------------------------+
| Themes      | :ref:`Performances`           |
+-------------+-------------------------------+
| Severity    | Minor                         |
+-------------+-------------------------------+
| Time To Fix | Slow (1 hour)                 |
+-------------+-------------------------------+



.. _property-could-be-local:

Property Could Be Local
#######################


A property only used in one method may be turned into a local variable.

Public properties are omitted here : they may be modified anywhere in the code.

.. code-block:: php

   <?php
   
   class x {
       private $foo = 1;
       
       function bar() {
           $this->foo++;
           
           return $this->foo;
       }
   }
   
   ?>

+-------------+------------------------------------+
| Short name  | Classes/PropertyCouldBeLocal       |
+-------------+------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`ClassReview` |
+-------------+------------------------------------+
| Severity    | Minor                              |
+-------------+------------------------------------+
| Time To Fix | Slow (1 hour)                      |
+-------------+------------------------------------+



.. _property-could-be-private-property:

Property Could Be Private Property
##################################


The following properties are never used outside their class of definition  Given the analyzed code, they could be set as private. 

.. code-block:: php

   <?php
   
   class foo {
       public $couldBePrivate = 1;
       public $cantdBePrivate = 1;
       
       function bar() {
           // couldBePrivate is used internally. 
           $this->couldBePrivate = 3;
       }
   }
   
   class foo2 extends foo {
       function bar2() {
           // cantdBePrivate is used in a child class. 
           $this->cantdBePrivate = 3;
       }
   }
   
   //$couldBePrivate is not used outside 
   $foo = new foo();
   
   //$cantdBePrivate is used outside the class
   $foo->cantdBePrivate = 2;
   
   ?>


Note that dynamic properties (such as $x->$y) are not taken into account.

+-------------+------------------------+
| Short name  | Classes/CouldBePrivate |
+-------------+------------------------+
| Themes      | :ref:`ClassReview`     |
+-------------+------------------------+
| Severity    | Minor                  |
+-------------+------------------------+
| Time To Fix | Slow (1 hour)          |
+-------------+------------------------+



.. _property-used-in-one-method-only:

Property Used In One Method Only
################################


Properties should be used in several methods. When a property is used in only one method, this should have be of another shape. 

Properties used in one method only may be used several times, and read only. This may be a class constant. Such properties are meant to be overwritten by an extending class, and that's possible with class constants.

Properties that read and written may be converted into a variable, static to the method. This way, they are kept close to the method, and do not pollute the object's properties.

.. code-block:: php

   <?php
   
   class foo {
       private $once = 1;
       const ONCE = 1;
       private $counter = 0;
       
       function bar() {
           // $this->once is never used anywhere else. 
           someFunction($this->once);
           someFunction(self::ONCE);   // Make clear that it is a 
       }
   
       function bar2() {
           static $localCounter = 0;
           $this->counter++;
           
           // $this->once is only used here, for distinguising calls to someFunction2
           if ($this->counter > 10) { // $this->counter is used only in bar2, but it may be used several times
               return false;
           }
           someFunction2($this->counter);
   
           // $localCounter keeps track for all the calls
           if ($localCounter > 10) { 
               return false;
           }
           someFunction2($localCounter);
       }
   }
   
   ?>


Note : properties used only once are not returned by this analysis. They are omitted, and are available in the analysis `Used Once Property`_.

+-------------+-------------------------------------+
| Short name  | Classes/PropertyUsedInOneMethodOnly |
+-------------+-------------------------------------+
| Themes      | :ref:`Analyze`                      |
+-------------+-------------------------------------+
| Severity    | Minor                               |
+-------------+-------------------------------------+
| Time To Fix | Slow (1 hour)                       |
+-------------+-------------------------------------+



.. _property-variable-confusion:

Property Variable Confusion
###########################


Within a class, there is both a property and variables bearing the same name. 

.. code-block:: php

   <?php
   class Object {
       private $x;
       
       function SetData( ) {
           $this->x = $x + 2;
       }
   }
   ?>


The property and the variable may easily be confused one for another and lead to a bug. 

Sometimes, when the property is going to be replaced by the incoming argument, or data based on that argument, this naming schema is made on purpose, indicating that the current argument will eventually end up in the property. When the argument has the same name as the property, no warning is reported.

+-------------+--------------------------------------+
| Short name  | Structures/PropertyVariableConfusion |
+-------------+--------------------------------------+
| Themes      | :ref:`Analyze`                       |
+-------------+--------------------------------------+
| Severity    | Minor                                |
+-------------+--------------------------------------+
| Time To Fix | Slow (1 hour)                        |
+-------------+--------------------------------------+



.. _queries-in-loops:

Queries In Loops
################


Avoid querying databases in a loop. 

Querying an external database in a loop usually leads to performances problems. This is also called the 'n + 1 problem'. 

This problem applies also to prepared statement : when such statement are called in a loop, they are slower than one-time large queries.

It is recommended to reduce the number of queries by making one query, and dispatching the results afterwards. This is true with SQL databases, graph queries, LDAP queries, etc. 

.. code-block:: php

   <?php
   
   // Typical N = 1 problem : there will be as many queries as there are elements in $array
   $ids = array(1,2,3,5,6,10);
   
   $db = new SQLite3('mysqlitedb.db');
   
   // all the IDS are merged into the query at once
   $results = $db->query('SELECT bar FROM foo WHERE id  in ('.implode(',', $id).')');
   while ($row = $results->fetchArray()) {
       var_dump($row);
   }
   
   
   // Typical N = 1 problem : there will be as many queries as there are elements in $array
   $ids = array(1,2,3,5,6,10);
   
   $db = new SQLite3('mysqlitedb.db');
   
   foreach($ids as $id) {
       $results = $db->query('SELECT bar FROM foo WHERE id = '.$id);
       while ($row = $results->fetchArray()) {
           var_dump($row);
       }
   }
   
   ?>


This optimisation is not always possible : for example, some SQL queries may not be prepared, like ``DROP TABLE`` or ``DESC``. ``UPDATE`` commands often update one row at a time, and grouping such queries may be counter-productive or unsafe.

+-------------+--------------------------+
| Short name  | Structures/QueriesInLoop |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Major                    |
+-------------+--------------------------+
| Time To Fix | Slow (1 hour)            |
+-------------+--------------------------+



.. _raised-access-level:

Raised Access Level
###################


A property's visibility may be lowered, but not raised.

This error may be detected when the classes are all in the same file : then, PHP reports the problem. However, when the classes are separated in different files, as it is customary, PHP won't check this at linting time, yielding a fatal error at execution time. 

First file.

.. code-block:: php

   <?php
   
   class Foo {
       public $publicProperty;
       protected $protectedProperty;
       private $privateProperty;
   }
   ?>


Second file.

.. code-block:: php

   <?php
   
   class Bar extends Foo {
       private $publicProperty;
       private $protectedProperty;
       private $privateProperty;   // This one is OK
   }
   ?>


See also `Visibility <http://php.net/manual/en/language.oop5.visibility.php>`_.

+-------------+---------------------------+
| Short name  | Classes/RaisedAccessLevel |
+-------------+---------------------------+
| Themes      | :ref:`ClassReview`        |
+-------------+---------------------------+
| Severity    | Critical                  |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _random-without-try:

Random Without Try
##################


random_int() and random_bytes() require a try/catch structure around them.

random_int() and random_bytes() emit Exceptions if they meet a problem. This way, failure can't be mistaken with returning an empty value, which leads to lower security. 

.. code-block:: php

   <?php
   
   try {
       $salt = random_bytes($length);
   } catch (TypeError $e) {
       // Error while reading the provided parameter
   } catch (Exception $e) {
       // Insufficient random data generated
   } catch (Error $e) {
       // Error with the provided parameter : <= 0
   }
   
   ?>

+-------------+------------------------------+
| Short name  | Structures/RandomWithoutTry  |
+-------------+------------------------------+
| Themes      | :ref:`Security`              |
+-------------+------------------------------+
| Php Version | With PHP 7.0 and more recent |
+-------------+------------------------------+
| Severity    | Critical                     |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _randomly-sorted-arrays:

Randomly Sorted Arrays
######################


Those literal arrays are written in several places, but their items are in various orders. 

This may reduce the reading and proofing of the arrays, and induce confusion. The random order may also be a residue of development : both arrays started with different values, but they grew overtime to handle the same items. The way they were written lead to the current order.

Unless order is important, it is recommended to always use the same order when defining literal arrays. This makes it easier to match different part of the code by recognizing one of its literal.

.. code-block:: php

   <?php
   
   // an array
   $set = [1,3,5,9,10];
   
   function foo() {
       // an array, with the same values but different order, in a different context
       $list = [1,3,5,10,9,];
   }
   
   // an array, with the same order than the initial one
   $inits = [1,3,5,9,10];
   
   ?>




Suggestions
^^^^^^^^^^^

* Match the sorting order of the arrays. Choose any of them.
* Configure a constant and use it as a replacement for those arrays.
* Leave the arrays intact : the order may be important.
* For hash arrays, consider turning the array in a class.

+-------------+-------------------------------------------------------------------------------------------+
| Short name  | Arrays/RandomlySortedLiterals                                                             |
+-------------+-------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Suggestions`                                                        |
+-------------+-------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                     |
+-------------+-------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                             |
+-------------+-------------------------------------------------------------------------------------------+
| Examples    | :ref:`contao-arrays-randomlysortedliterals`, :ref:`vanilla-arrays-randomlysortedliterals` |
+-------------+-------------------------------------------------------------------------------------------+



.. _redeclared-php-functions:

Redeclared PHP Functions
########################


Function that bear the same name as a PHP function, and that are declared. 

This is possible when managing some backward compatibility, like emulating an old function, or preparing for newer PHP version, like emulating new upcoming function.

.. code-block:: php

   <?php
   
   if (version_compare(PHP_VERSION, 7.0) > 0) {
       function split($separator, $string) {
           return explode($separator, $string);
       }
   }
   
   print_r( split(' ', '2 3'));
   
   ?>

+-------------+---------------------------------+
| Short name  | Functions/RedeclaredPhpFunction |
+-------------+---------------------------------+
| Themes      | :ref:`Analyze`                  |
+-------------+---------------------------------+
| Severity    | Minor                           |
+-------------+---------------------------------+
| Time To Fix | Slow (1 hour)                   |
+-------------+---------------------------------+



.. _redefined-class-constants:

Redefined Class Constants
#########################


Redefined class constants.

Class constants may be redefined, though it is prone to errors when using them, as it is now crucial to use the right class name to access the right value.

.. code-block:: php

   <?php
   
   class a {
       const A = 1;
   }
   
   class b extends a {
       const A = 2;
   }
   
   class c extends c { }
   
   echo a::A, ' ', b::A, ' ', c::A;
   // 1 2 2
   
   ?>


It is recommended to use distinct names.

+-------------+----------------------------+
| Short name  | Classes/RedefinedConstants |
+-------------+----------------------------+
| Themes      | :ref:`Analyze`             |
+-------------+----------------------------+
| Severity    | Minor                      |
+-------------+----------------------------+
| Time To Fix | Slow (1 hour)              |
+-------------+----------------------------+



.. _redefined-default:

Redefined Default
#################


Classes allows properties to be set with a default value. When those properties get, unconditionally, another value at constructor time, then one of the default value are useless. One of those definition should go : it is better to define properties outside the constructor.

.. code-block:: php

   <?php
   
   class foo {
       public $redefined = 1;
   
       public function __construct( ) {
           $this->redefined = 2;
       }
   }
   
   ?>

+-------------+--------------------------+
| Short name  | Classes/RedefinedDefault |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Major                    |
+-------------+--------------------------+
| Time To Fix | Slow (1 hour)            |
+-------------+--------------------------+



.. _redefined-private-property:

Redefined Private Property
##########################


Private properties are local to their defined class. PHP doesn't forbid the re-declaration of a private property in a child class.

However, having two or more properties with the same name, in the class hierarchy tends to be error prone. 

.. code-block:: php

   <?php
   
   class A {
       private $isReady = true;
   }
   
   class B {
       private $isReady = false;
   }
   
   ?>

+-------------+-----------------------------------------------+
| Short name  | Classes/RedefinedPrivateProperty              |
+-------------+-----------------------------------------------+
| Themes      | :ref:`Analyze`                                |
+-------------+-----------------------------------------------+
| Severity    | Major                                         |
+-------------+-----------------------------------------------+
| Time To Fix | Slow (1 hour)                                 |
+-------------+-----------------------------------------------+
| Examples    | :ref:`zurmo-classes-redefinedprivateproperty` |
+-------------+-----------------------------------------------+



.. _redefined-property:

Redefined Property
##################


Property redefined in a parent class. 

Using heritage, it is possible to define several times the same property, at different levels of the hierarchy.

.. code-block:: php

   <?php
   
   class foo {
       protected $aProperty = 1;
   }
   
   class bar extends foo {
       // This property is redefined in the parent class, leading to potential confusion
       protected $aProperty = 1;
   }
   
   ?>


When this is the case, it is difficult to understand which class will actually handle the property. 

In the case of a private property, the different instances will stay distinct. In the case of protected or public properties, they will all share the same value. 

It is recommended to avoid redefining the same property in a hierarchy.

+-------------+---------------------------+
| Short name  | Classes/RedefinedProperty |
+-------------+---------------------------+
| Themes      | :ref:`ClassReview`        |
+-------------+---------------------------+
| Severity    | Minor                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _register-globals:

Register Globals
################


``register_globals`` was a PHP directive that dumped all incoming variables from GET, POST, COOKIE and FILES as global variables in the called scripts.
This lead to security failures, as the variables were often used but not filtered. 

Though it is less often found in more recent code, ``register_globals`` is sometimes needed in legacy code, that haven't made the move to eradicate this style of coding.
Backward compatible pieces of code that mimic the ``register_globals`` features usually create even greater security risks by being run after scripts startup. At that point, some important variables are already set, and may be overwritten by the incoming call, creating confusion in the script.

Mimicking ``register_globals`` is achieved with variables variables, `extract() <http://www.php.net/extract>`_, `parse_str() <http://www.php.net/parse_str>`_ and `import_request_variables() <http://www.php.net/import_request_variables>`_ (Up to PHP 5.4). 

.. code-block:: php

   <?php
   
   // Security warning ! This overwrites existing variables. 
   extract($_POST);
   
   // Security warning ! This overwrites existing variables. 
   foreach($_REQUEST as $var => $value) {
       $$var = $value;
   }
   
   ?>

+-------------+---------------------------------------------------------------------------------+
| Short name  | Security/RegisterGlobals                                                        |
+-------------+---------------------------------------------------------------------------------+
| Themes      | :ref:`Security`                                                                 |
+-------------+---------------------------------------------------------------------------------+
| Severity    | Critical                                                                        |
+-------------+---------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                   |
+-------------+---------------------------------------------------------------------------------+
| Examples    | :ref:`teampass-security-registerglobals`, :ref:`xoops-security-registerglobals` |
+-------------+---------------------------------------------------------------------------------+



.. _relay-function:

Relay Function
##############


Relay function only delegate workload to another one. 

Relay functions and methods are delegating the actual work to another function or method. They do not have any impact on the results, besides exposing another name for the same feature.

.. code-block:: php

   <?php
   
   function myStrtolower($string) {
       return \strtolower($string);
   }
   
   ?>


Relay functions are typical of transition API, where an old API have to be preserved until it is fully migrated. Then, they may be removed, so as to reduce confusion, and declutter the API. 


Suggestions
^^^^^^^^^^^

* Remove relay function, call directly the final function
* Remove the target function, and move the code here
* Add more logic to that function, like conditions or cache

+-------------+------------------------------------------------------------------------------+
| Short name  | Functions/RelayFunction                                                      |
+-------------+------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                               |
+-------------+------------------------------------------------------------------------------+
| Severity    | Major                                                                        |
+-------------+------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                              |
+-------------+------------------------------------------------------------------------------+
| Examples    | :ref:`teampass-functions-relayfunction`, :ref:`spip-functions-relayfunction` |
+-------------+------------------------------------------------------------------------------+



.. _repeated-interface:

Repeated Interface
##################


A class should implements only once an interface.

PHP accepts multiple times the same interface in the ``implements`` clause. In fact, it doesn't do anything beyond the first implement. 

.. code-block:: php

   <?php
   
   use i as j;
   
   interface i {}
   
   // Multiple ways to reference an interface
   class foo implements i, \i, j {}
   
   // This applies to interfaces too
   interface bar extends i, \i, j {}
   
   ?>


See also `Object Interfaces <http://php.net/manual/en/language.oop5.interfaces.php>`_ and 
         `The Basics <http://php.net/manual/en/language.oop5.basic.php>`_.

+-------------+------------------------------+
| Short name  | Interfaces/RepeatedInterface |
+-------------+------------------------------+
| Themes      | :ref:`Analyze`               |
+-------------+------------------------------+
| Severity    | Minor                        |
+-------------+------------------------------+
| Time To Fix | Instant (5 mins)             |
+-------------+------------------------------+



.. _repeated-regex:

Repeated Regex
##############


Repeated regex should be centralized. 

When a regex is repeatedly used in the code, it is getting harder to update. 

.. code-block:: php

   <?php
   
   // Regex used several times, at least twice.
   preg_match('/^abc_|^square$/i', $_GET['x']);
   
   //.......
   
   preg_match('/^abc_|^square$/i', $row['name']);
   
   // This regex is dynamically built, so it is not reported.
   preg_match('/^circle|^'.$x.'$/i', $string);
   
   // This regex is used once, so it is not reported.
   preg_match('/^circle|^square$/i', $string);
   
   ?>


Regex that are repeated at least once (aka, used twice or more) are reported. Regex that are dynamically build are not reported.

+-------------+--------------------------+
| Short name  | Structures/RepeatedRegex |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Minor                    |
+-------------+--------------------------+
| Time To Fix | Quick (30 mins)          |
+-------------+--------------------------+



.. _repeated-print():

Repeated print()
################


Always merge several print or echo in one call.

It is recommended to use echo with multiple arguments, or a concatenation with print, instead of multiple calls to print echo, when outputting several blob of text.

.. code-block:: php

   <?php
   
   //Write : 
     echo 'a', $b, 'c';
     print 'a' . $b . 'c';
   
   //Don't write :  
     print 'a';
     print $b;
     print 'c';
   ?>
  



Suggestions
^^^^^^^^^^^

* Merge all prints into one echo call, separating arguments by commas.
* Collect all values in one variable, and do only one call to print or echo.

+-------------+---------------------------------------------------------------------------------------------------+
| Short name  | Structures/RepeatedPrint                                                                          |
+-------------+---------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Suggestions`                                                                |
+-------------+---------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                             |
+-------------+---------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                   |
+-------------+---------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-repeated-print <https://github.com/dseguy/clearPHP/tree/master/rules/no-repeated-print.md>`__ |
+-------------+---------------------------------------------------------------------------------------------------+
| Examples    | :ref:`edusoho-structures-repeatedprint`, :ref:`humo-gen-structures-repeatedprint`                 |
+-------------+---------------------------------------------------------------------------------------------------+



.. _reserved-keywords-in-php-7:

Reserved Keywords In PHP 7
##########################


PHP reserved names for class/trait/interface. They won't be available anymore in user space starting with PHP 7.

For example, string, float, false, true, null, resource,`... <http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list>`_ are not acceptable as class name. 

.. code-block:: php

   <?php
   
   // This doesn't compile in PHP 7.0 and more recent
   class null { }
   
   ?>


See also `List of other reserved words <http://php.net/manual/en/reserved.other-reserved-words.php>`_.

+-------------+---------------------------+
| Short name  | Php/ReservedKeywords7     |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP70` |
+-------------+---------------------------+
| Php Version | With PHP 7.0 and older    |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Slow (1 hour)             |
+-------------+---------------------------+



.. _results-may-be-missing:

Results May Be Missing
######################


preg_match() may return empty values, if the search fails. It is important to check for the existence of results before assigning them to another variable, or using it.

.. code-block:: php

   <?php
       preg_match('/PHP ([0-9\.]+) /', $res, $r);
       $s = $r[1];
       // $s may end up null if preg_match fails.
   ?>

+-------------+-------------------------------+
| Short name  | Structures/ResultMayBeMissing |
+-------------+-------------------------------+
| Themes      | :ref:`Analyze`                |
+-------------+-------------------------------+
| Severity    | Major                         |
+-------------+-------------------------------+
| Time To Fix | Quick (30 mins)               |
+-------------+-------------------------------+



.. _rethrown-exceptions:

Rethrown Exceptions
###################


Throwing a caught exception is usually useless and dead code.

When exceptions are caught, they should be processed or transformed, but not rethrown as is.

Those issues often happen when a catch structure was positioned for debug purposes, but lost its usage later. 

.. code-block:: php

   <?php
   
   try {
       doSomething();
   } catch (Exception $e) {
       throw $e;
   }
   
   ?>


See also `What are the best practices for catching and re-throwing exceptions? <https://stackoverflow.com/questions/5551668/what-are-the-best-practices-for-catching-and-re-throwing-exceptions>`_ and 
         `Exception chaining <http://php.net/manual/en/exception.construct.php>`_.



Suggestions
^^^^^^^^^^^

* Log the message of the exception for later usage.
* Remove the try/catch and let the rest of the application handle this exception.
* Chain the exception, by throwing a new exception, including the caught exception.

+-------------+---------------------------------------+
| Short name  | Exceptions/Rethrown                   |
+-------------+---------------------------------------+
| Themes      | :ref:`Dead code <dead-code>`          |
+-------------+---------------------------------------+
| Severity    | Minor                                 |
+-------------+---------------------------------------+
| Time To Fix | Quick (30 mins)                       |
+-------------+---------------------------------------+
| Examples    | :ref:`prestashop-exceptions-rethrown` |
+-------------+---------------------------------------+



.. _return-true-false:

Return True False
#################


These conditional expressions return true/false, depending on the condition. This may be simplified by dropping the control structure altogether.

.. code-block:: php

   <?php
   
   if (version_compare($a, $b) >= 0) {
       return true;
   } else {
       return false;
   }
   
   ?>


This may be simplified with : 

.. code-block:: php

   <?php
   
   return version_compare($a, $b) >= 0;
   
   ?>


This may be applied to assignations and ternary operators too.

.. code-block:: php

   <?php
   
   if (version_compare($a, $b) >= 0) {
       $a = true;
   } else {
       $a = false;
   }
   
   $a = version_compare($a, $b) >= 0 ? false : true;
   
   ?>

+-------------+----------------------------+
| Short name  | Structures/ReturnTrueFalse |
+-------------+----------------------------+
| Themes      | :ref:`Analyze`             |
+-------------+----------------------------+
| Severity    | Major                      |
+-------------+----------------------------+
| Time To Fix | Quick (30 mins)            |
+-------------+----------------------------+



.. _return-with-parenthesis:

Return With Parenthesis
#######################


return statement doesn't need parenthesis. PHP tolerates them with return statement, but it is recommended not to use them. 

.. code-block:: php

   <?php
   
   function foo() {
       $a = rand(0, 10);
   
       // No need for parenthesis
       return $a;
   
       // Parenthesis are useless here
       return ($a);
   
       // Parenthesis are useful here: they are needed by the multplication.
       return ($a + 1) * 3;
   }
   
   ?>

+-------------+------------------------------------------------+
| Short name  | Php/ReturnWithParenthesis                      |
+-------------+------------------------------------------------+
| Themes      | :ref:`Coding Conventions <coding-conventions>` |
+-------------+------------------------------------------------+
| Severity    | Minor                                          |
+-------------+------------------------------------------------+
| Time To Fix | Instant (5 mins)                               |
+-------------+------------------------------------------------+



.. _reuse-variable:

Reuse Variable
##############


A variable is already holding the content that is re-calculated later. Use the cached value.

.. code-block:: php

   <?php
   
   function foo($a) {
       $b = strtolower($a);
       
       // strtolower($a) is already calculated in $b. Just reuse the value.
       if (strtolower($a) === 'c') {
           doSomething();
       }
   }
   
   ?>

+-------------+--------------------------+
| Short name  | Structures/ReuseVariable |
+-------------+--------------------------+
| Themes      | :ref:`Suggestions`       |
+-------------+--------------------------+
| Severity    | Minor                    |
+-------------+--------------------------+
| Time To Fix | Slow (1 hour)            |
+-------------+--------------------------+



.. _safe-curl-options:

Safe Curl Options
#################


It is advised to always use ``CURLOPT_SSL_VERIFYPEER`` and ``CURLOPT_SSL_VERIFYHOST`` when requesting a SSL connection. 

With those tests, the certificate is verified, and if it isn't valid, the connection fails : this is a safe behavior.

.. code-block:: php

   <?php
   $ch = curl_init();
   
   curl_setopt($ch, CURLOPT_URL, https://www.php.net/);
   
   // To be safe, always set this to true
   curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
   
   curl_exec($ch);
   curl_close($ch);
   ?>


See also `Don’t turn off CURLOPT_SSL_VERIFYPEER, fix your PHP configuration <https://www.saotn.org/dont-turn-off-curlopt_ssl_verifypeer-fix-php-configuration/>`_.

+-------------+----------------------+
| Short name  | Security/CurlOptions |
+-------------+----------------------+
| Themes      | :ref:`Security`      |
+-------------+----------------------+
| Severity    | Major                |
+-------------+----------------------+
| Time To Fix | Quick (30 mins)      |
+-------------+----------------------+



.. _safe-http-headers:

Safe HTTP Headers
#################


Avoid configuring HTTP headers with lax restriction from within PHP. 

There are a lot of HTTP headers those days, targeting various vulnerabilities. To ensure backward compatibility, those headers have a default mode that is lax and permissive. It is recommended to avoid using those from within the code.

.. code-block:: php

   <?php
   
   //Good configuration, limiting access to origin
   header('Access-Control-Allow-Origin: https://www.exakat.io');
   
   //Configuration is present, but doesn't restrict anything : any external site is a potential source
   header('Access-Control-Allow-Origin: *');
   
   ?>


See also `Hardening Your HTTP Security Headers <https://www.keycdn.com/blog/http-security-headers>`_,
        `How To Secure Your Web App With HTTP Headers <https://www.smashingmagazine.com/2017/04/secure-web-app-http-headers/>`_ and 
         `SecurityHeaders <https://securityheaders.com/>`_.

+-------------+--------------------------+
| Short name  | Security/SafeHttpHeaders |
+-------------+--------------------------+
| Themes      | :ref:`Security`          |
+-------------+--------------------------+
| Severity    | Major                    |
+-------------+--------------------------+
| Time To Fix | Quick (30 mins)          |
+-------------+--------------------------+



.. _same-conditions-in-condition:

Same Conditions In Condition
############################


At least two consecutive if/then structures use identical conditions. The latter will probably be ignored.

This analysis returns false positive when there are attempt to fix the situation, or to call an alternative solution. 

.. code-block:: php

   <?php
   
   if ($a == 1) { doSomething(); }
   elseif ($b == 1) { doSomething(); }
   elseif ($c == 1) { doSomething(); }
   elseif ($a == 1) { doSomething(); }
   else {}
   
   // Also works on if then else if chains
   if ($a == 1) { doSomething(); }
   else if ($b == 1) { doSomething(); }
   else if ($c == 1) { doSomething(); }
   else if ($a == 1) { doSomething(); }
   else {}
   
   // This sort of situation generate false postive. 
   $config = load_config_from_commandline();
   if (empty($config)) {
       $config = load_config_from_file();
       if (empty($config)) {
           $config = load_default_config();
       }
   }
   
   ?>

+-------------+---------------------------+
| Short name  | Structures/SameConditions |
+-------------+---------------------------+
| Themes      | :ref:`Analyze`            |
+-------------+---------------------------+
| Severity    | Critical                  |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _same-variables-foreach:

Same Variables Foreach
######################


A foreach which uses its own source as a blind variable is actually broken.

Actually, PHP makes a copy of the source before it starts the loop. As such, the same variable may be used for both source and blind value. 

Of course, this is very confusing, to see the same variables used in very different ways. 

The source will also be destroyed immediately after the blind variable has been turned into a reference.

.. code-block:: php

   <?php
   
   $array = range(0, 10);
   foreach($array as $array) {
       print $array.PHP_EOL;
   }
   
   print_r($array); // display number from 0 to 10.
   
   $array = range(0, 10);
   foreach($array as &$array) {
       print $array.PHP_EOL;
   }
   
   print_r($array); // display 10
   
   ?>

+-------------+-----------------------------+
| Short name  | Structures/AutoUnsetForeach |
+-------------+-----------------------------+
| Themes      | :ref:`Analyze`              |
+-------------+-----------------------------+
| Severity    | Minor                       |
+-------------+-----------------------------+
| Time To Fix | Quick (30 mins)             |
+-------------+-----------------------------+



.. _scalar-or-object-property:

Scalar Or Object Property
#########################


Property shouldn't use both object and scalar syntaxes. When a property may be an object, it is recommended to implement the Null Object pattern : instead of checking if the property is scalar, make it always object. 

.. code-block:: php

   <?php
   
   class x {
       public $display = 'echo';
       
       function foo($string) {
           if (is_string($this->display)) {
               echo $this->string;
           } elseif ($this->display instanceof myDisplayInterface) {
               $display->display();
           } else {
               print Error when displaying\n;
           }
       }
   }
   
   interface myDisplayInterface {
       public function display($string); // does the display in its own way
   }
   
   class nullDisplay implements myDisplayInterface {
       // implements myDisplayInterface but does nothing
       public function display($string) {}
   }
   
   class x2 {
       public $display = null;
       
       public function __construct() {
           $this->display = new nullDisplay();
       }
       
       function foo($string) {
           // Keep the check, as $display is public, and may get wrong values
           if ($this->display instanceof myDisplayInterface) {
               $display->display();
           } else {
               print Error when displaying\n;
           }
       }
   }
   
   // Simple class for echo
   class echoDisplay implements myDisplayInterface {
       // implements myDisplayInterface but does nothing
       public function display($string) {
           echo $string;
       }
   }
   
   ?>


See also `Null Object Pattern <https://en.wikipedia.org/wiki/Null_Object_pattern#PHP>`_. and `The Null Object Pattern <https://www.sitepoint.com/the-null-object-pattern-polymorphism-in-domain-models/>`_.

+-------------+--------------------------------+
| Short name  | Classes/ScalarOrObjectProperty |
+-------------+--------------------------------+
| Themes      | :ref:`Analyze`                 |
+-------------+--------------------------------+
| Severity    | Minor                          |
+-------------+--------------------------------+
| Time To Fix | Slow (1 hour)                  |
+-------------+--------------------------------+



.. _self-using-trait:

Self Using Trait
################


Trait uses itsel : this is uncessary. Traits may use themselves, or be used by other traits, that are using the initial trait itself. 

PHP handles the situation quietly, by ignoring all extra use of the same trait, keeping only one valid version.

.. code-block:: php

   <?php
   
   // empty, but valid
   trait a {} 
   
   // obvious self usage
   trait b { use b; }
   
   // less obvious self usage
   trait c { use d, e, f, g, h, c; }
   
   // level 2 self usage
   trait i { use j; }
   trait j { use i; }
   
   ?>


See also `Traits <http://php.net/manual/en/language.oop5.traits.php>`_.


Suggestions
^^^^^^^^^^^

* Remove the extra usage of the trait.

+-------------+------------------------------+
| Short name  | Traits/SelfUsingTrait        |
+-------------+------------------------------+
| Themes      | :ref:`Dead code <dead-code>` |
+-------------+------------------------------+
| Severity    | Minor                        |
+-------------+------------------------------+
| Time To Fix | Slow (1 hour)                |
+-------------+------------------------------+



.. _sequences-in-for:

Sequences In For
################


`For() <http://php.net/manual/en/control-structures.for.php>`_ instructions allows several instructions in each of its parameters. Then, the instruction separator is comma ',', not semi-colon, which is used for separating the 3 arguments.

.. code-block:: php

   <?php
      for ($a = 0, $b = 0; $a < 10, $b < 20; $a++, $b += 3) {
       // For loop
      }
   ?>


This loop will simultaneously increment $a and $b. It will stop only when the last of the central sequence reach a value of false : here, when $b reach 20 and $a will be 6. 

This structure is often unknown, and makes the for instruction quite difficult to read. It is also easy to oversee the multiples instructions, and omit one of them.
It is recommended not to use it.

+-------------+--------------------------+
| Short name  | Structures/SequenceInFor |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Minor                    |
+-------------+--------------------------+
| Time To Fix | Quick (30 mins)          |
+-------------+--------------------------+



.. _session-lazy-write:

Session Lazy Write
##################


Classes that implements SessionHandlerInterface must also implements SessionUpdateTimestampHandlerInterface. 

The two extra methods are used to help lazy loading : the first actually checks if a sessionId is available, and the seconds updates the time of last usage of the session data in the session storage. 

This was spotted by Nicolas Grekas, and fixed in Symfony `[HttpFoundation] Make sessions secure and lazy #24523 <https://github.com/symfony/symfony/pull/24523>`_. 

.. code-block:: php

   <?php
   
   interface SessionUpdateTimestampHandlerInterface {
       // returns a boolean to indicate that valid data is available for this sessionId, or not.
       function validateId($sessionId);
       
       //called to change the last time of usage for the session data.
       //It may be a file's touch or full write, or a simple update on the database
       function updateTimestamp($sessionId, $sessionData);
   }
   
   ?>


See also ` <https://wiki.php.net/rfc/session-read_only-lazy_write>`_ and the `Sessions <http://php.net/manual/en/book.session.php>`_.

+-------------+---------------------------+
| Short name  | Security/SessionLazyWrite |
+-------------+---------------------------+
| Themes      | :ref:`Security`           |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Slow (1 hour)             |
+-------------+---------------------------+



.. _set-cookie-safe-arguments:

Set Cookie Safe Arguments
#########################


The last five arguments of `setcookie() <http://www.php.net/setcookie>`_ and `setrawcookie() <http://www.php.net/setrawcookie>`_ are for security. Use them anytime you can.

setcookie ( string $name [, string $value =  [, int $expire = 0 [, string $path =  [, string $domain =  [, bool $secure = false [, bool $httponly = false ]]]]]] )

The ``$expire`` argument sets the date of expiration of the cookie. It is recommended to make it as low as possible, to reduce its chances to be captured. Sometimes, low expiration date may be several days (for preferences), and other times, low expiration date means a few minutes. 

The ``$path`` argument limits the transmission of the cookie to URL whose path matches the one mentioned here. By default, it is ``'/'``, which means the whole server. If a cookie usage is limited to a part of the application, use it here.

The ``$domain`` argument limits the transmission of the cookie to URL whose domain matches the one mentioned here. By default, it is ``''``, which means any server on the internet. At worse, you may use ``mydomain.com`` to cover your whole domain, or better, refine it with the actual subdomain of usage.

The ``$secure`` argument limits the transmission of the cookie over HTTP (by default) or HTTPS. The second is better, as the transmission of the cookie is crypted. In case HTTPS is still at the planned stage, use '$_SERVER[HTTPS]'. This environnement variable is false on HTTP, and true on HTTPS.

The ``$httponly`` argument limits the access of the cookie to JavaScript. It is only transmitted to the browser, and retransmitted. This helps reducing XSS and CSRF attacks, though it is disputed. 

The ``$samesite`` argument limits the sending of the cookie to the domain that initiated the request. It is by default ``Lax`` but should be upgraded to ``Strict`` whenever possible. This feature is available as PHP 7.3. 

.. code-block:: php

   <?php
   
   //admin cookie, available only on https://admin.my-domain.com/system/, for the next minute, and not readable by javascript
   setcookie(admin, $login, time()+60, /system/, admin.my-domain.com, $_SERVER['HTTPS'], 1);
   
   //login cookie, available until the browser is closed, over http or https
   setcookie(login, $login);
   
   //removing the login cookie : Those situations are omitted by the analysis
   setcookie(login, '');
   
   ?>


See also `setcookie <http://www.php.net/setcookie>`_ and 
         `'SameSite' cookie attribute <https://www.chromestatus.com/feature/4672634709082112>`_.

+-------------+------------------------+
| Short name  | Security/SetCookieArgs |
+-------------+------------------------+
| Themes      | :ref:`Security`        |
+-------------+------------------------+
| Severity    | Major                  |
+-------------+------------------------+
| Time To Fix | Instant (5 mins)       |
+-------------+------------------------+



.. _setlocale()-uses-constants:

Setlocale() Uses Constants
##########################


setlocal() don't use strings but constants. 

The first argument of `setlocale() <http://www.php.net/setlocale>`_ must be one of the valid constants, ``LC_ALL``, ``LC_COLLATE``, ``LC_CTYPE``, ``LC_MONETARY``, ``LC_NUMERIC``, ``LC_TIME, LC_MESSAGES``.

.. code-block:: php

   <?php
   
   // Use constantes for setlocale first argument
   setlocale(LC_ALL, 'nl_NL');
   setlocale(\LC_ALL, 'nl_NL');
   
   // Don't use string for setlocale first argument
   setlocale('LC_ALL', 'nl_NL');
   setlocale('LC_'.'ALL', 'nl_NL');
   
   ?>


The PHP 5 usage of strings (same name as above, enclosed in ' or ") is not legit anymore in PHP 7 and later.

See also `setlocale <http://php.net/setlocale>`_.

+-------------+------------------------------------+
| Short name  | Structures/SetlocaleNeedsConstants |
+-------------+------------------------------------+
| Themes      | :ref:`CompatibilityPHP70`          |
+-------------+------------------------------------+
| Severity    | Major                              |
+-------------+------------------------------------+
| Time To Fix | Instant (5 mins)                   |
+-------------+------------------------------------+



.. _several-instructions-on-the-same-line:

Several Instructions On The Same Line
#####################################


Usually, instructions do not share their line : one instruction, one line. 

This is good for readability, and help at understanding the code. This is especially important when fast-reading the code to find some special situation, where such double-meaning line way have an impact.

.. code-block:: php

   <?php
   
   switch ($x) {
       // Is it a fallthrough or not ? 
       case 1:
           doSomething(); break;
   
       // Easily spotted break.
       case 1:
           doSomethingElse(); 
           break;
   
       default : 
           doDefault(); 
           break;
   }
   
   ?>


See also `Object Calisthenics, rule # 5 <http://williamdurand.fr/2013/06/03/object-calisthenics/#one-dot-per-line>`_.

+-------------+--------------------------------------------------------------------------------------------------+
| Short name  | Structures/OneLineTwoInstructions                                                                |
+-------------+--------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                   |
+-------------+--------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                            |
+-------------+--------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                 |
+-------------+--------------------------------------------------------------------------------------------------+
| Examples    | :ref:`piwigo-structures-onelinetwoinstructions`, :ref:`tine20-structures-onelinetwoinstructions` |
+-------------+--------------------------------------------------------------------------------------------------+



.. _short-open-tags:

Short Open Tags
###############


Usage of short open tags is discouraged. The following files were found to be impacted by the short open tag directive at compilation time. They must be reviewed to ensure no &lt;? tags are found in the code.

+-------------+--------------------------+
| Short name  | Php/ShortOpenTagRequired |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Major                    |
+-------------+--------------------------+
| Time To Fix | Quick (30 mins)          |
+-------------+--------------------------+



.. _short-syntax-for-arrays:

Short Syntax For Arrays
#######################


Arrays written with the new short syntax. 

PHP 5.4 introduced the new short syntax, with square brackets. The previous syntax, based on the array() keyword is still available.

.. code-block:: php

   <?php
   
   // All PHP versions array
   $a = array(1, 2, 3);
   
   // PHP 5.4+ arrays
   $a = [1, 2, 3];
   
   ?>


See also `Array <http://php.net/manual/en/language.types.array.php>`_.

+-------------+---------------------------+
| Short name  | Arrays/ArrayNSUsage       |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP53` |
+-------------+---------------------------+
| Severity    | Critical                  |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _should-be-single-quote:

Should Be Single Quote
######################


Use single quote for simple strings.

Static content inside a string, that has no single quotes nor escape sequence (such as \n or \t), should be using single quote delimiter, instead of double quote. 

.. code-block:: php

   <?php
   
   $a = abc;
   
   // This one is using a special sequence
   $b = cde\n;
   
   // This one is using two special sequences
   $b = \x03\u{1F418};
   
   ?>


If you have too many of them, don't loose your time switching them all. If you have a few of them, it may be good for consistence.

+-------------+-----------------------------------------------------------------------------------------------+
| Short name  | Type/ShouldBeSingleQuote                                                                      |
+-------------+-----------------------------------------------------------------------------------------------+
| Themes      | :ref:`Coding Conventions <coding-conventions>`                                                |
+-------------+-----------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                         |
+-------------+-----------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                              |
+-------------+-----------------------------------------------------------------------------------------------+
| ClearPHP    | `no-double-quote <https://github.com/dseguy/clearPHP/tree/master/rules/no-double-quote.md>`__ |
+-------------+-----------------------------------------------------------------------------------------------+



.. _should-chain-exception:

Should Chain Exception
######################


Chain exception to provide more context.

When catching an exception and rethrowing another one, it is recommended to chain the exception : this means providing the original exception, so that the final recipient has a chance to track the origin of the problem. This doesn't change the thrown message, but provides more information.

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


See also `Exception\:\:`__construct <http://php.net/manual/en/language.oop5.decon.php>`_ <http://php.net/manual/en/exception.construct.php>`_.

+-------------+---------------------------------+
| Short name  | Structures/ShouldChainException |
+-------------+---------------------------------+
| Themes      | :ref:`Analyze`                  |
+-------------+---------------------------------+
| Severity    | Minor                           |
+-------------+---------------------------------+
| Time To Fix | Instant (5 mins)                |
+-------------+---------------------------------+



.. _should-have-destructor:

Should Have Destructor
######################


PHP destructors are called when the object has to be destroyed. By default, PHP calls recursively the destructor on internal objects, until everything is unset.

Unsetting objects and resources explicitely in the destructor is a good practice to reduce the amount of memory in use. It helps PHP resource counter to keep the numbers low, and easier to clean. This is a major advantage for long running scripts.

.. code-block:: php

   <?php
   
   class x {
       function __construct() {
           $this->p = new y();
       }
   
       function __destruct() {
           print __METHOD__.PHP_EOL;
           unset($this->p);
       }
   }
   
   class y {
       function __construct() {
           print __METHOD__.PHP_EOL;
           $this->p = new y();
       }
   
       function __destruct() {
           print __METHOD__.PHP_EOL;
           unset($this->p);
       }
   }
   
   $a = (new x);
   sleep(1);
   
   // This increment the resource counter by one for the property.
   $p = $a->p;
   unset($a);
   sleep(3);
   
   print 'end'.PHP_EOL;
   // Y destructor is only called here, as the object still exists in $p.
   
   ?>


See also `Destructor <http://php.net/manual/en/language.oop5.decon.php#language.oop5.decon.destructor>`_, and 
         `Php Destructors <https://stackoverflow.com/questions/3566155/php-destructors>`_.



Suggestions
^^^^^^^^^^^

* Add a destruct method to the class to help clean at destruction time.

+-------------+------------------------------+
| Short name  | Classes/ShouldHaveDestructor |
+-------------+------------------------------+
| Themes      | :ref:`Suggestions`           |
+-------------+------------------------------+
| Severity    | Minor                        |
+-------------+------------------------------+
| Time To Fix | Slow (1 hour)                |
+-------------+------------------------------+



.. _should-make-alias:

Should Make Alias
#################


Long names should be aliased.

Aliased names are easy to read at the beginning of the script; they may be changed at one point, and update the whole code at the same time. 
Finally, short names makes the rest of the code readable. 

.. code-block:: php

   <?php
   
   namespace x\y\z;
   
   use a\b\c\d\e\f\g as Object;
   
   // long name, difficult to read, prone to change.
   new a\b\c\d\e\f\g();
   
   // long name, difficult to read, prone to silent dead code if namespace change.
   if ($o instanceof a\b\c\d\e\f\g) {
       
   }
   
   // short names Easy to update all at once.
   new Object();
   if ($o instanceof Object) {
       
   }
   
   ?>

+-------------+----------------------------+
| Short name  | Namespaces/ShouldMakeAlias |
+-------------+----------------------------+
| Themes      | :ref:`Analyze`             |
+-------------+----------------------------+
| Severity    | Minor                      |
+-------------+----------------------------+
| Time To Fix | Quick (30 mins)            |
+-------------+----------------------------+



.. _should-make-ternary:

Should Make Ternary
###################


Ternary operators are the best when assigning values to a variable.

This way, they are less verbose, compatible with assignation and easier to read.

.. code-block:: php

   <?php
       // verbose if then structure
       if ($a == 3) {
           $b = 2;
       } else {
           $b = 3;
       }
   
       // compact ternary call
       $b = ($a == 3) ? 2 : 3;
   
       // verbose if then structure
       // Works with short assignations and simple expressions
       if ($a != 3) {
           $b += 2 - $a * 4;
       } else {
           $b += 3;
       }
   
       // compact ternary call
       $b += ($a != 3) ? 2 - $a * 4 : 3;
   
   ?>

+-------------+------------------------------+
| Short name  | Structures/ShouldMakeTernary |
+-------------+------------------------------+
| Themes      | :ref:`Analyze`               |
+-------------+------------------------------+
| Severity    | Minor                        |
+-------------+------------------------------+
| Time To Fix | Instant (5 mins)             |
+-------------+------------------------------+



.. _should-preprocess-chr:

Should Preprocess Chr
#####################


Replace literal `chr() <http://www.php.net/chr>`_ calls with their escape sequence.

`chr() <http://www.php.net/chr>`_ is a functioncall, that cannot be cached. It is only resolved at execution time. 
On the other hand, literal values are pre-processed by PHP and may be cached.

.. code-block:: php

   <?php
   
   // This is easier on PHP
   $a = "0000 is great!";
   
   // This is slow
   $a = chr(80), chr(72), chr(80), chr(32), ' is great!';
   
   // This would be the best with this example, but it is not always possible
   $a = 'PHP is great!';
   
   ?>


This is a micro-optimisation. 

See also `Escape sequences <http://php.net/manual/en/regexp.reference.escape.php>`_.

+------------+----------------------+
| Short name | Php/ShouldPreprocess |
+------------+----------------------+
| Themes     | none                 |
+------------+----------------------+



.. _should-typecast:

Should Typecast
###############


When typecasting, it is better to use the casting operator, such as ``(int)`` or ``(bool)``.

Functions such as `intval() <http://www.php.net/intval>`_ or `settype() <http://www.php.net/settype>`_ are always slower.

.. code-block:: php

   <?php
   
   // Fast version
   $int = (int) $X;
   
   // Slow version
   $int = intval($X);
   
   // Convert to base 8 : can't use (int) for that
   $int = intval($X, 8);
   
   
   ?>


This is a micro-optimisation, although such conversion may be use multiple time, leading to a larger performance increase.  

Note that `intval() <http://www.php.net/intval>`_ may also be used to convert an integer to another base.


Suggestions
^^^^^^^^^^^

* Use a typecast, instead of a functioncall.

+-------------+-------------------------------------+
| Short name  | Type/ShouldTypecast                 |
+-------------+-------------------------------------+
| Themes      | :ref:`Analyze`                      |
+-------------+-------------------------------------+
| Severity    | Minor                               |
+-------------+-------------------------------------+
| Time To Fix | Quick (30 mins)                     |
+-------------+-------------------------------------+
| Examples    | :ref:`openconf-type-shouldtypecast` |
+-------------+-------------------------------------+



.. _should-use-coalesce:

Should Use Coalesce
###################


PHP 7 introduced the ``??`` operator, that replaces longer structures to set default values when a variable is not set.

.. code-block:: php

   <?php
   
   // Fetches the request parameter user and results in 'nobody' if it doesn't exist
   $username = $_GET['user'] ?? 'nobody';
   // equivalent to: $username = isset($_GET['user']) ? $_GET['user'] : 'nobody';
    
   // Calls a hypothetical model-getting function, and uses the provided default if it fails
   $model = Model::get($id) ?? $default_model;
   // equivalent to: if (($model = Model::get($id)) === NULL) { $model = $default_model; }
   
   ?>


Sample extracted from PHP docs `Isset Ternary <https://wiki.php.net/rfc/isset_ternary>`_.

See also `New in PHP 7: null coalesce operator <https://lornajane.net/posts/2015/new-in-php-7-null-coalesce-operator>`_.

+-------------+----------------------------------------------------------------------------------+
| Short name  | Php/ShouldUseCoalesce                                                            |
+-------------+----------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Suggestions`                                               |
+-------------+----------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and more recent                                                     |
+-------------+----------------------------------------------------------------------------------+
| Severity    | Major                                                                            |
+-------------+----------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                  |
+-------------+----------------------------------------------------------------------------------+
| Examples    | :ref:`churchcrm-php-shouldusecoalesce`, :ref:`cleverstyle-php-shouldusecoalesce` |
+-------------+----------------------------------------------------------------------------------+



.. _should-use-constants:

Should Use Constants
####################


The following functions have related constants that should be used as arguments, instead of scalar literals, such as integers or strings.

.. code-block:: php

   <?php
   
   // The file is read and new lines are ignored.
   $lines = file('file.txt', FILE_IGNORE_NEW_LINES)
   
   // What is this doing, with 2 ? 
   $lines = file('file.txt', 2);
   
   ?>


See also `Bitmask Constant Arguments in PHP <https://medium.com/@liamhammett/bitmask-constant-arguments-in-php-cf32bf35c73>`_.

+-------------+------------------------------+
| Short name  | Functions/ShouldUseConstants |
+-------------+------------------------------+
| Themes      | :ref:`Analyze`               |
+-------------+------------------------------+
| Severity    | Minor                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _should-use-foreach:

Should Use Foreach
##################


Use foreach instead of for when traversing an array.

`Foreach() <http://php.net/manual/en/control-structures.foreach.php>`_ is the modern loop : it maps automatically every element of the array to a blind variable, and loop over it. This is faster and safer.

.. code-block:: php

   <?php
   
   // Foreach version
   foreach($array as $element) {
       doSomething($element);
   }
   
   // The above case may even be upgraded with array_map and a callback, 
   // for the simplest one of them
   $array = array_map('doSomething', $array);
   
   // For version (one of various alternatives)
   for($i = 0; $i < count($array); $i++) {
       $element = $array[$i];
       doSomething($element);
   }
   
   ?>

+-------------+-----------------------------+
| Short name  | Structures/ShouldUseForeach |
+-------------+-----------------------------+
| Themes      | :ref:`Suggestions`          |
+-------------+-----------------------------+
| Severity    | Minor                       |
+-------------+-----------------------------+
| Time To Fix | Instant (5 mins)            |
+-------------+-----------------------------+



.. _should-use-function:

Should Use Function
###################


Functioncalls that fall back to global scope should be using 'use function' or be fully namespaced. 

PHP searches for functions in the local namespaces, and in case it fails, makes the same search in the global scope. Anytime a native function is referenced this way, the search (and fail) happens. This slows down the scripts.

The speed bump range from 2 to 8 %, depending on the availability of functions in the local scope. The overall bump is about 1 µs per functioncall, which makes it a micro optimisation until a lot of function calls are made.

Based on one of `Marco Pivetta tweet <https://twitter.com/Ocramius/status/811504929357660160>`_.

.. code-block:: php

   <?php
   
   namespace X {
       use function strtolower as strtolower_aliased;
       
       // PHP searches for strtolower in X, fails, then falls back to global scope, succeeds.
       $a = strtolower($b);
   
       // PHP searches for strtolower in global scope, succeeds.
       $a = \strtolower($b);
   
       // PHP searches for strtolower_aliased in global scope, succeeds.
       $a = \strtolower_aliased($b);
   }
   
   ?>


See also `blog post <http://veewee.github.io/blog/optimizing-php-performance-by-fq-function-calls/>`_.

+-------------+-----------------------+
| Short name  | Php/ShouldUseFunction |
+-------------+-----------------------+
| Themes      | :ref:`Performances`   |
+-------------+-----------------------+
| Severity    | Minor                 |
+-------------+-----------------------+
| Time To Fix | Slow (1 hour)         |
+-------------+-----------------------+



.. _should-use-local-class:

Should Use Local Class
######################


Methods should use the defining class, or be functions.

Methods should use ``$this`` with another method or a property, or call ``parent\:\:``. Static methods should call another static method, or a static property. 
Methods which are overwritten by a child class are omitted : the parent class act as a default value for the children class, and this is correct.

.. code-block:: php

   <?php
   
   class foo {
       public function __construct() {
           // This method should do something locally, or be removed.
       }
   }
   
   class bar extends foo {
       private $a = 1;
       
       public function __construct() {
           // Calling parent:: is sufficient
           parent::__construct();
       }
   
       public function barbar() {
           // This is acting on the local object
           $this->a++;
       }
   
       public function barfoo($b) {
           // This has no action on the local object. It could be a function or a closure where needed
           return 3 + $b;
       }
   }
   
   ?>


Note that a method using a class constant is not considered as using the local class, for this analyzer.

+-------------+-----------------------------------------------------------------------------------------+
| Short name  | Classes/ShouldUseThis                                                                   |
+-------------+-----------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                          |
+-------------+-----------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                   |
+-------------+-----------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                           |
+-------------+-----------------------------------------------------------------------------------------+
| ClearPHP    | `not-a-method <https://github.com/dseguy/clearPHP/tree/master/rules/not-a-method.md>`__ |
+-------------+-----------------------------------------------------------------------------------------+



.. _should-use-math:

Should Use Math
###############


Use math operators to make the operation clearer.

.. code-block:: php

   <?php
   
   // Adding one to self
   $a *= 2;
   // same as above
   $a += $a;
   
   // Squaring oneself
   $a \*\*\= 2;
   // same as above
   $a *= $a;
   
   // Removing oneself
   $a = 0;
   // same as above
   $a -= $a;
   
   // Dividing oneself
   $a = 1;
   // same as above
   $a /= $a;
   
   // Dividing oneself
   $a = 0;
   // same as above
   $a %= $a;
   
   ?>


See also `Mathematical Functions <http://php.net/manual/en/book.math.php>`_.

+-------------+--------------------------+
| Short name  | Structures/ShouldUseMath |
+-------------+--------------------------+
| Themes      | :ref:`Suggestions`       |
+-------------+--------------------------+
| Severity    | Minor                    |
+-------------+--------------------------+
| Time To Fix | Instant (5 mins)         |
+-------------+--------------------------+



.. _should-use-operator:

Should Use Operator
###################


Some functions duplicate the feature of an operator. When in doubt, it is better to use the operator. 

Beware, some edge cases may apply. In particular, backward compatibility may prevent usage of newer features.

* `array_push() <http://www.php.net/array_push>`_ is equivalent to [] 
* `is_object() <http://www.php.net/is_object>`_ is equivalent to `instanceof <http://php.net/manual/en/language.operators.type.php>`_
* function_get_arg() and function_get_args() is equivalent to ellipsis : `... <http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list>`_
* `chr() <http://www.php.net/chr>`_ is equivalent to string escape sequences, such as ``\n``, ``\x69``, ``u{04699}``
* `call_user_func() <http://www.php.net/call_user_func>`_ is equivalent to ``$functionName(arguments)``, ``$object->$method(`... <http://php.net/manual/en/functions.arguments.php#functions.variable-arg-list>`_$arguments)``
* `is_null() <http://www.php.net/is_null>`_ is equivalent to ``=== null``
* php_version() is equivalent to ``PHP_VERSION`` (the constant)
* `is_array() <http://www.php.net/is_array>`_, `is_int() <http://www.php.net/is_int>`_, `is_object() <http://www.php.net/is_object>`_, etc. is equivalent to a scalar typehint




Suggestions
^^^^^^^^^^^

* Use [] instead of array_push()
* Use instanceof instead of is_object()
* Use ... instead of function_get_arg() and function_get_args()
* Use escape sequences instead of chr()
* Use dynamic function call instead of call_user_func()
* Use === null instead of is_null()
* Use PHP_VERSION instead of php_version()
* Use typehint instead of is_int(), is_string(), is_bool(), etc.

+-------------+-------------------------------------------------------------------------------------------+
| Short name  | Structures/ShouldUseOperator                                                              |
+-------------+-------------------------------------------------------------------------------------------+
| Themes      | :ref:`Suggestions`                                                                        |
+-------------+-------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                     |
+-------------+-------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                          |
+-------------+-------------------------------------------------------------------------------------------+
| Examples    | :ref:`zencart-structures-shoulduseoperator`, :ref:`sugarcrm-structures-shoulduseoperator` |
+-------------+-------------------------------------------------------------------------------------------+



.. _should-use-prepared-statement:

Should Use Prepared Statement
#############################


Modern databases provides support for prepared statement : it separates the query from the processed data and raise significantly the security. 

Building queries with concatenations is not recommended, though not always avoidable. When possible, use prepared statements.

.. code-block:: php

   <?php
   /* Execute a prepared statement by passing an array of values */
   
   $sql = 'SELECT name, colour, calories
       FROM fruit
       WHERE calories < :calories AND colour = :colour';
   $sth = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
   $sth->execute(array(':calories' => 150, ':colour' => 'red'));
   $red = $sth->fetchAll();
   ?>


Same code, without preparation : 

.. code-block:: php

   <?php
   
       $sql = 'SELECT name, color, calories FROM fruit WHERE calories < '.$conn-quote(150).' AND colour = '.$conn->quotes('red').' ORDER BY name';
       $sth = $conn->query($sql) as $row);
   }
   ?>

+-------------+-------------------------------------+
| Short name  | Security/ShouldUsePreparedStatement |
+-------------+-------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Security`     |
+-------------+-------------------------------------+
| Severity    | Major                               |
+-------------+-------------------------------------+
| Time To Fix | Slow (1 hour)                       |
+-------------+-------------------------------------+



.. _should-use-setcookie():

Should Use SetCookie()
######################


Use `setcookie() <http://www.php.net/setcookie>`_ or `setrawcookie() <http://www.php.net/setrawcookie>`_. Avoid using `header() <http://www.php.net/header>`_ to do so, as the PHP native functions are more convenient and easier to spot during a refactoring.

`setcookie() <http://www.php.net/setcookie>`_ applies some encoding internally, for the value of the cookie and the date of expiration. Rarely, this encoding has to be skipped : then, use setrawencoding().

Both functions help by giving a checklist of important attributes to be used with the cookie. 

.. code-block:: php

   <?php
   
   // same as below
   setcookie(myCookie, 'chocolate', time()+3600, /, , true, true);
   
   // same as above. Slots for path and domain are omitted, but should be used whenever possible
   header('Set-Cookie: myCookie=chocolate; Expires='.date('r', (time()+3600)).'; Secure; HttpOnly');
   
   ?>


See also : `Set-Cookie <https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie>`_.

+-------------+------------------+
| Short name  | Php/UseSetCookie |
+-------------+------------------+
| Themes      | :ref:`Analyze`   |
+-------------+------------------+
| Severity    | Major            |
+-------------+------------------+
| Time To Fix | Instant (5 mins) |
+-------------+------------------+



.. _should-use-array\_column():

Should Use array_column()
#########################


Avoid writing a whole slow loop, and use the native `array_column() <http://www.php.net/array_column>`_.

`array_column() <http://www.php.net/array_column>`_ is a native PHP function, that extract a property or a index from a array of object, or a multidimensional array. This prevents the usage of foreach to collect those values.

.. code-block:: php

   <?php
   
   $a = array(array('b' => 1), 
              array('b' => 2, 'c' => 3), 
              array(          'c' => 4)); // b doesn't always exists
   
   $bColumn = array_column($a, 'b');
   
   // Slow and cumbersome code
   $bColumn = array();
   foreach($a as $k => $v) {
       if (isset($v['b'])) {
           $bColumn[] = $v['b'];
       }
   }
   
   ?>


`array_column() <http://www.php.net/array_column>`_ is faster than `foreach() <http://php.net/manual/en/control-structures.foreach.php>`_ (with or without the `isset() <http://www.php.net/isset>`_ test) with 3 elements or more, and it is significantly faster beyond 5 elements. Memory consumption is the same.

See also `[blog] `array_column() <http://www.php.net/array_column>`_ <https://benramsey.com/projects/array-column/>`_.

+-------------+-----------------------------------------+
| Short name  | Php/ShouldUseArrayColumn                |
+-------------+-----------------------------------------+
| Themes      | :ref:`Performances`, :ref:`Suggestions` |
+-------------+-----------------------------------------+
| Severity    | Minor                                   |
+-------------+-----------------------------------------+
| Time To Fix | Quick (30 mins)                         |
+-------------+-----------------------------------------+



.. _should-use-array\_filter():

Should Use array_filter()
#########################


Should use `array_filter() <http://www.php.net/array_filter>`_.

`array_filter() <http://www.php.net/array_filter>`_ is a native PHP function, that extract elements from an array, based on a closure. 

.. code-block:: php

   <?php
   
   $a = range(0, 10); // integers from 0 to 10
   
   $odds = array_filter(function($x) { return $x % 2; });
   
   // Slow and cumbersome code
   $odds = array();
   foreach($a as $k => $v) {
       if ($a % 2 == 1) {
           $bColumn[] = $v;
       }
   }
   
   ?>


`array_column() <http://www.php.net/array_column>`_ is faster than `foreach() <http://php.net/manual/en/control-structures.foreach.php>`_ (with or without the `isset() <http://www.php.net/isset>`_ test) with 3 elements or more, and it is significantly faster beyond 5 elements. Memory consumption is the same.

See also `array_filter <https://php.net/array_filter>`_.

+-------------+--------------------------+
| Short name  | Php/ShouldUseArrayFilter |
+-------------+--------------------------+
| Themes      | :ref:`Suggestions`       |
+-------------+--------------------------+
| Severity    | Minor                    |
+-------------+--------------------------+
| Time To Fix | Slow (1 hour)            |
+-------------+--------------------------+



.. _should-use-session\_regenerateid():

Should Use session_regenerateid()
#################################


session_regenerateid() should be used when sessions are used.

When using sessions, a session ID is assigned to the user. It is a random number, used to connect the user and its data on the server. Actually, anyone with the session ID may have access to the data. This is why those session ID are so long and complex.

A good approach to protect the session ID is to reduce its lifespan : the shorter the time of use, the better. While changing the session ID at every hit on the page may no be possible, a more reasonable approach is to change the session id when an important action is about to take place. What important means is left to the application to decide.

Based on this philosophy, a code source that uses Zend\Session but never uses Zend\Session\:\:regenerateId() has to be updated.

.. code-block:: php

   <?php
   
       session_start();
       
       $id = (int) $_SESSION['id'];
       // no usage of session_regenerateid() anywhere triggers the analysis
       
       // basic regeneration every 20 hits on the page. 
       if (++$_SESSION['count'] > 20) {
           session_regenerateid();
       }
   
   ?>


See `session_regenerateid() <http://php.net/session_regenerate_id>`_ and `PHP Security Guide: Sessions <http://phpsec.org/projects/guide/4.html>`_.

+-------------+---------------------------------------+
| Short name  | Security/ShouldUseSessionRegenerateId |
+-------------+---------------------------------------+
| Themes      | :ref:`Security`                       |
+-------------+---------------------------------------+
| Severity    | Major                                 |
+-------------+---------------------------------------+
| Time To Fix | Slow (1 hour)                         |
+-------------+---------------------------------------+



.. _should-yield-with-key:

Should Yield With Key
#####################


`iterator_to_array() <http://www.php.net/iterator_to_array>`_ will overwrite generated values with the same key. 

PHP generators are based on the ``yield`` keyword. They also delegate some generating to other methods, with ``yield from``. 

When delegating, ``yield from`` uses the keys that are generated with ``yield``, and otherwise, it uses auto-generated index, starting with 0. 

The trap is that each ``yield from`` reset the index generation and start again with 0. Coupled with `iterator_to_array() <http://www.php.net/iterator_to_array>`_, this means that the final generated array may lack some values, while a `foreach() <http://php.net/manual/en/control-structures.foreach.php>`_ loop would yield all of them.

.. code-block:: php

   <?php 
   
   function g1() : Generator {
   	for ( $i = 0; $i < 4; $i++ ) { yield $i; }
   }
   
   function g2() : Generator {
   	for ( $i = 5; $i < 10; $i++ ) { yield $i; }
   }
   
   function aggregator() : Generator {
   	yield from g1();
   	yield from g2();
   }
   
   print_r(iterator_to_array());
   
   /*
   Array
   (
       [0] => 6
       [1] => 7
       [2] => 8
       [3] => 9
       [4] => 4  // Note that 4 and 5 still appears
       [5] => 5  // They are not overwritten by the second yield
   )
   */
   
   
   foreach ( aggregator() as $i ) {
   	print $i.PHP_EOL;
   }
   
   /*
   0  // Foreach has no overlap and yield it all.
   1
   2
   3
   4
   5
   6
   7
   8
   9
   */
   
   ?>


Thanks to `Holger Woltersdorf <https://twitter.com/hollodotme>`_ for `pointing this <https://twitter.com/hollodotme/status/1057909890566537217>`_.

See also `Generator syntax <http://php.net/manual/en/language.generators.syntax.php>`_ and 
         `Yielding values with keys <http://php.net/manual/en/language.generators.syntax.php#control-structures.yield.associative>`_.


Suggestions
^^^^^^^^^^^

* Use iterator_to_array() on each generator separatly, and use array_merge() to merge all the arrays.
* Always yield with distinct keys
* Avoid iterator_to_array() and use foreach()

+-------------+------------------------------+
| Short name  | Functions/ShouldYieldWithKey |
+-------------+------------------------------+
| Themes      | :ref:`Analyze`               |
+-------------+------------------------------+
| Severity    | Major                        |
+-------------+------------------------------+
| Time To Fix | Slow (1 hour)                |
+-------------+------------------------------+



.. _silently-cast-integer:

Silently Cast Integer
#####################


Those are integer literals that are cast to a float when running PHP. They are too big for the current PHP version, and PHP resorts to cast them into a float, which has a much larger capacity but a lower precision.

Compare your literals to ``PHP_MAX_INT`` (typically ``9223372036854775807``) and ``PHP_MIN_INT`` (typically ``-9223372036854775808``).
This applies to binary (``0b10101``...), octal (``0123123``...) and hexadecimal (``0xfffff``...) too. 

.. code-block:: php

   <?php
   
   echo 0b1010101101010110101011010101011010101011010101011010101011010111;
   //6173123008118052203
   echo 0b10101011010101101010110101010110101010110101010110101010110101111;
   //1.2346246016236E+19
   
   echo 0123123123123123123123;
   //1498121094048818771
   echo 01231231231231231231231;
   //1.1984968752391E+19
   
   echo 0x12309812311230;
   //5119979279159856
   echo 0x12309812311230fed;
   //2.0971435127439E+19
   
   echo 9223372036854775807; //PHP_MAX_INT
   //9223372036854775807
   echo 9223372036854775808;
   9.2233720368548E+18
   
   ?>


See also `Integer overflow <http://php.net/manual/en/language.types.integer.php#language.types.integer.overflow>`_.

+-------------+--------------------------+
| Short name  | Type/SilentlyCastInteger |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Minor                    |
+-------------+--------------------------+
| Time To Fix | Quick (30 mins)          |
+-------------+--------------------------+



.. _simple-global-variable:

Simple Global Variable
######################


The global keyword should only be used with simple variables. Since PHP 7, it cannot be used with complex or dynamic structures.

.. code-block:: php

   <?php
   
   // Forbidden in PHP 7
   global $normalGlobal;
   
   // Forbidden in PHP 7
   global $$variable->global ;
   
   // Tolerated in PHP 7
   global ${$variable->global};
   
   ?>

+-------------+---------------------------------+
| Short name  | Php/GlobalWithoutSimpleVariable |
+-------------+---------------------------------+
| Themes      | :ref:`CompatibilityPHP70`       |
+-------------+---------------------------------+
| Php Version | With PHP 7.0 and older          |
+-------------+---------------------------------+
| Severity    | Critical                        |
+-------------+---------------------------------+
| Time To Fix | Slow (1 hour)                   |
+-------------+---------------------------------+



.. _simple-switch:

Simple Switch
#############


Switches are faster when relying only on integers or strings.

Since PHP 7.2, simple switches that use only strings or integers are optimized. The gain is as great as the switch is big. 

.. code-block:: php

   <?php
   
   // Optimized switch. 
   switch($b) {
       case "a":
           break;
       case "b":
           break;
       case "c":
           break;
       case "d":
           break;
       default :
           break;
   }
   
   // Unoptimized switch. 
   // Try moving the foo() call in the default, to keep the rest of the switch optimized.
   switch($c) {
       case "a":
           break;
       case foo($b):
           break;
       case "c":
           break;
       case "d":
           break;
       default :
           break;
   }
   
   ?>


See also `PHP 7.2's "switch" optimisations <https://derickrethans.nl/php7.2-switch.html>`_.

+-------------+------------------------------+
| Short name  | Performances/SimpleSwitch    |
+-------------+------------------------------+
| Themes      | :ref:`Performances`          |
+-------------+------------------------------+
| Php Version | With PHP 7.2 and more recent |
+-------------+------------------------------+
| Severity    | Major                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _simplify-regex:

Simplify Regex
##############


PRCE regex are a powerful way to search inside strings, but they also come at the price of performance. When the query is simple enough, try using `strpos() <http://www.php.net/strpos>`_ or `stripos() <http://www.php.net/stripos>`_ instead.

.. code-block:: php

   <?php
   
   // simple preg calls
   if (preg_match('/a/', $string))  {}
   if (preg_match('/b/i', $string)) {} // case insensitive
   
   // light replacements
   if( strpos('a', $string)) {}
   if( stripos('b', $string)) {}       // case insensitive
   
   ?>

+-------------+-----------------------+
| Short name  | Structures/SimplePreg |
+-------------+-----------------------+
| Themes      | :ref:`Performances`   |
+-------------+-----------------------+
| Severity    | Major                 |
+-------------+-----------------------+
| Time To Fix | Quick (30 mins)       |
+-------------+-----------------------+



.. _slice-arrays-first:

Slice Arrays First
##################


Always start by reducing an array before applying some transformation on it. The shorter array will be processed faster. 

.. code-block:: php

   <?php
   
   // fast version
   $a = array_map('foo', array_slice($array, 2, 5));
   
   // slower version
   $a = array_slice(array_map('foo', $array), 2, 5);
   ?>


The gain produced here is greater with longer arrays, or greater reductions. They may also be used in loops. This is a micro-optimisation when used on short arrays.

+------------+-----------------------------------------+
| Short name | Arrays/SliceFirst                       |
+------------+-----------------------------------------+
| Themes     | :ref:`Performances`, :ref:`Suggestions` |
+------------+-----------------------------------------+
| Examples   | :ref:`wordpress-arrays-slicefirst`      |
+------------+-----------------------------------------+



.. _slow-functions:

Slow Functions
##############


Avoid using those slow native PHP functions, and replace them with alternatives.

.. code-block:: php

   <?php
   
   $array = source();
   
   // Slow extraction of distinct values
   $array = array_unique($array);
   
   // Much faster extraction of distinct values
   $array = array_keys(array_count_values($array));
   
   ?>


+--------------------------------------------------------------+--------------------------------------------------------------------------------------------------------------------------+
| Slow Function                                                |  Faster                                                                                                                  | 
+--------------------------------------------------------------+--------------------------------------------------------------------------------------------------------------------------+
| `array_diff() <http://www.php.net/array_diff>`_              |  `foreach() <http://php.net/manual/en/control-structures.foreach.php>`_                                                  | 
| `array_intersect() <http://www.php.net/array_intersect>`_    |  `foreach() <http://php.net/manual/en/control-structures.foreach.php>`_                                                  | 
| `array_key_exists() <http://www.php.net/array_key_exists>`_  |  `isset() <http://www.php.net/isset>`_                                                                                   | 
| `array_map() <http://www.php.net/array_map>`_                |  `foreach() <http://php.net/manual/en/control-structures.foreach.php>`_                                                  | 
| `array_search() <http://www.php.net/array_search>`_          |  `array_flip() <http://www.php.net/array_flip>`_ and `isset() <http://www.php.net/isset>`_                               | 
| `array_udiff() <http://www.php.net/array_udiff>`_            |  Use another way                                                                                                         | 
| `array_uintersect() <http://www.php.net/array_uintersect>`_  |  Use another way                                                                                                         | 
| `array_unshift() <http://www.php.net/array_unshift>`_        |  Use another way                                                                                                         | 
| `array_walk() <http://www.php.net/array_walk>`_              |  `foreach() <http://php.net/manual/en/control-structures.foreach.php>`_                                                  | 
| `in_array() <http://www.php.net/in_array>`_                  |  `isset() <http://www.php.net/isset>`_                                                                                   | 
| `preg_replace() <http://www.php.net/preg_replace>`_          |  `strpos() <http://www.php.net/strpos>`_                                                                                 | 
| `strstr() <http://www.php.net/strstr>`_                      |  `strpos() <http://www.php.net/strpos>`_                                                                                 | 
| `uasort() <http://www.php.net/uasort>`_                      |  Use another way                                                                                                         | 
| `uksort() <http://www.php.net/uksort>`_                      |  Use another way                                                                                                         | 
| `usort() <http://www.php.net/usort>`_                        |  Use another way                                                                                                         | 
| `array_unique() <http://www.php.net/array_unique>`_          |  `array_keys() <http://www.php.net/array_keys>`_ and `array_count_values() <http://www.php.net/array_count_values>`_     | 
+--------------------------------------------------------------+--------------------------------------------------------------------------------------------------------------------------+

`array_unique() <http://www.php.net/array_unique>`_ has been accelerated in PHP 7.2 and may be used directly.



Suggestions
^^^^^^^^^^^

* Replace the slow function with a faster version
* Remove the usage of the slow function

+-------------+---------------------------------------------------------------------------------------------------------------------+
| Short name  | Performances/SlowFunctions                                                                                          |
+-------------+---------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Performances`                                                                                                 |
+-------------+---------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                               |
+-------------+---------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                     |
+-------------+---------------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `avoid-those-slow-functions <https://github.com/dseguy/clearPHP/tree/master/rules/avoid-those-slow-functions.md>`__ |
+-------------+---------------------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`suitecrm-performances-slowfunctions`                                                                          |
+-------------+---------------------------------------------------------------------------------------------------------------------+



.. _sqlite3-requires-single-quotes:

Sqlite3 Requires Single Quotes
##############################


The escapeString() method from ``SQLite3`` doesn't escape ``"``, but only ``'``. 

.. code-block:: php

   <?php
   
   // OK. escapeString is OK with '
   $query = "SELECT * FROM table WHERE col = '".$sqlite->escapeString($x)."'";
   
   // This is vulnerable to " in $x
   $query = 'SELECT * FROM table WHERE col = "'.$sqlite->escapeString($x).'"';
   
   ?>


To properly handle quotes and ``NUL`` characters, use bindParam() instead.

Quote from the PHP manual comments : ``The reason this function doesn't escape double quotes is because double quotes are used with names (the equivalent of backticks in MySQL), as in table or column names, while single quotes are used for values.``

See also `SQLite3\:\:escapeString <http://php.net/manual/en/sqlite3.escapestring.php>`_.

+-------------+--------------------------------------+
| Short name  | Security/Sqlite3RequiresSingleQuotes |
+-------------+--------------------------------------+
| Themes      | :ref:`Security`                      |
+-------------+--------------------------------------+
| Severity    | Major                                |
+-------------+--------------------------------------+
| Time To Fix | Slow (1 hour)                        |
+-------------+--------------------------------------+



.. _static-loop:

Static Loop
###########


Static loop may be preprocessed.

It looks like the following loops are static : the same code is executed each time, without taking into account loop variables.

.. code-block:: php

   <?php
   
   // Static loop
   $total = 0;
   for($i = 0; $i < 10; $i++) {
       $total += $i;
   }
   
   // The above loop may be replaced by (with some math help)
   $total = 10 * (10  + 1) / 2;
   
   // Non-Static loop (the loop depends on the size of the array)
   $n = count($array);
   for($i = 0; $i < $n; $i++) {
       $total += $i;
   }
   
   ?>


It is possible to create loops that don't use any blind variables, though this is fairly rare. In particular, calling a method may update an internal pointer, like `next() <http://www.php.net/next>`_ or ``SimpleXMLIterator\:\:`next() <http://www.php.net/next>`_``. 

It is recommended to turn a static loop into an expression that avoid the loop. For example, replacing the sum of all integers by the ``function $n * ($n + 1) / 2``, or using `array_sum() <http://www.php.net/array_sum>`_.

This analysis doesn't detect usage of variables with ``compact``.

 

Suggestions
^^^^^^^^^^^

* Precalculate the result of that loop and removes it altogether
* Check that the loop is not missing a blind variable usage
* Replace the usage of a loop with a native PHP call : for example, with str_repeat(). Although the loop is still here, it usually reflects better the intend.

+-------------+-----------------------+
| Short name  | Structures/StaticLoop |
+-------------+-----------------------+
| Themes      | :ref:`Analyze`        |
+-------------+-----------------------+
| Severity    | Minor                 |
+-------------+-----------------------+
| Time To Fix | Slow (1 hour)         |
+-------------+-----------------------+



.. _static-methods-called-from-object:

Static Methods Called From Object
#################################


Static methods may be called without instantiating an object. As such, they never interact with the special variable '`$this <http://php.net/manual/en/language.oop5.basic.php>`_', as they do not depend on object existence. 

Besides this, static methods are normal methods that may be called directly from object context, to perform some utility task. 

To maintain code readability, it is recommended to call static method in a static way, rather than within object context.

.. code-block:: php

   <?php
       class x {
           static function y( ) {}
       }
       
       $z = new x( );
       
       $z->y( ); // Readability : no one knows it is a static call
       x::y( );  // Readability : here we know
   ?>

+-------------+---------------------------------------+
| Short name  | Classes/StaticMethodsCalledFromObject |
+-------------+---------------------------------------+
| Themes      | :ref:`Analyze`                        |
+-------------+---------------------------------------+
| Severity    | Minor                                 |
+-------------+---------------------------------------+
| Time To Fix | Quick (30 mins)                       |
+-------------+---------------------------------------+



.. _static-methods-can't-contain-$this:

Static Methods Can't Contain $this
##################################


Static methods are also called ``class methods`` : they may be called even if the class has no instantiated object. Thus, the local variable ``$this`` won't exist, PHP will set it to `NULL <http://php.net/manual/en/language.types.null.php>`_ as usual. 

.. code-block:: php

   <?php
   
   class foo {
       // Static method may access other static methods, or property, or none. 
       static function staticBar() {
           // This is not possible in a static method
           return self::otherStaticBar() . static::$staticProperty;
       }
   
       static function bar() {
           // This is not possible in a static method
           return $this->property;
       }
   }
   
   ?>


Either this is not a static method, which is fixed by removing the ``static`` keyword, or replace all `$this <http://php.net/manual/en/language.oop5.basic.php>`_ mention by static properties ``Class\:\:$property``.

See also `Static Keyword <http://php.net/manual/en/language.oop5.static.php>`_


Suggestions
^^^^^^^^^^^

* Remove any $this usage
* Turn any $this usage into a static call : $this->foo() => self\:\:foo()

+-------------+---------------------------------------------------------------------------------------------+
| Short name  | Classes/StaticContainsThis                                                                  |
+-------------+---------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                              |
+-------------+---------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                       |
+-------------+---------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                             |
+-------------+---------------------------------------------------------------------------------------------+
| ClearPHP    | `no-static-this <https://github.com/dseguy/clearPHP/tree/master/rules/no-static-this.md>`__ |
+-------------+---------------------------------------------------------------------------------------------+
| Examples    | :ref:`xataface-classes-staticcontainsthis`, :ref:`sugarcrm-classes-staticcontainsthis`      |
+-------------+---------------------------------------------------------------------------------------------+



.. _strange-name-for-constants:

Strange Name For Constants
##########################


Those constants looks like a typo from other names.

.. code-block:: php

   <?php
   
   // This code looks OK : DIRECTORY_SEPARATOR is a native PHP constant
   $path = $path . DIRECTORY_SEPARATOR . $file;
   
   // Strange name DIRECOTRY_SEPARATOR
   $path = $path . DIRECOTRY_SEPARATOR . $file;
   
   ?>

+-------------+--------------------------------+
| Short name  | Constants/StrangeName          |
+-------------+--------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Analyze` |
+-------------+--------------------------------+
| Severity    | Minor                          |
+-------------+--------------------------------+
| Time To Fix | Slow (1 hour)                  |
+-------------+--------------------------------+



.. _strange-name-for-variables:

Strange Name For Variables
##########################


Variables with strange names. They might be a typo, or bear strange patterns.

Any variable with three identical letter in a row are considered as strange. 2 letters in a row is classic, and while three letters may happen, it is rare enough. 

A list of classic typo is also used to find such variables.

This analysis is case-sensitive.

.. code-block:: php

   <?php
   
   class foo {
       function bar() {
           // Strange name $tihs
           return $tihs;
       }
       
       function barbar() {
           // variables with blocks of 3 times the same character are reported
           // Based on Alexandre Joly's tweet
           $aaa = $bab + $www; 
       }
   }
   
   ?>


See also `#QuandLeDevALaFleme <https://twitter.com/bsmt_nevers/status/949238391769653249>`_.

+-------------+-----------------------+
| Short name  | Variables/StrangeName |
+-------------+-----------------------+
| Themes      | none                  |
+-------------+-----------------------+
| Severity    | Minor                 |
+-------------+-----------------------+
| Time To Fix | Slow (1 hour)         |
+-------------+-----------------------+



.. _strict-comparison-with-booleans:

Strict Comparison With Booleans
###############################


Strict comparisons prevent from mistaking an error with a false. 

Booleans may be easily mistaken with other values, especially when the function may return integer or boolean as a normal course of action. 

It is encouraged to use strict comparison === or !== when booleans are involved in a comparison.

.. code-block:: php

   <?php
   
   // distinguish between : $b isn't in $a, and, $b is at the beginning of $a 
   if (strpos($a, $b) === 0) {
       doSomething();
   }
   
   // DOES NOT distinguish between : $b isn't in $a, and, $b is at the beginning of $a 
   if (strpos($a, $b)) {
       doSomething();
   }
   
   // will NOT mistake 1 and true
   $a = array(0, 1, 2, true);
   if (in_array($a, true, true)) {
       doSomething();
   }
   
   // will mistake 1 and true
   $a = array(0, 1, 2, true);
   if (in_array($a, true)) {
       doSomething();
   }
   
   ?>


`switch() <http://php.net/manual/en/control-structures.switch.php>`_ structures always uses == comparisons. 

Function `in_array() <http://www.php.net/in_array>`_ has a third parameter to make it use strict comparisons.

+-------------+------------------------------------+
| Short name  | Structures/BooleanStrictComparison |
+-------------+------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Suggestions` |
+-------------+------------------------------------+
| Severity    | Minor                              |
+-------------+------------------------------------+
| Time To Fix | Quick (30 mins)                    |
+-------------+------------------------------------+



.. _string-may-hold-a-variable:

String May Hold A Variable
##########################


Those strings looks like holding a variable. 

Single quotes and Nowdoc syntax may include $ signs that are treated as literals, and not replaced with a variable value. 

However, there are some potential variables in those strings, making it possible for an error : the variable was forgotten and will be published as such. It is worth checking the content and make sure those strings are not variables.

.. code-block:: php

   <?php
   
   $a = 2;
   
   // Explicit variable, but literal effect is needed
   echo '$a is '.$a;
   
   // One of the variable has been forgotten
   echo '$a is $a';
   
   // $CAD is not a variable, rather a currency unit
   $total = 12;
   echo $total.' $CAD';
   
   // $CAD is not a variable, rather a currency unit
   $total = 12;
   
   // Here, $total has been forgotten
   echo <<<'TEXT'
   $total $CAD
   TEXT;
   
   ?>

+-------------+--------------------------+
| Short name  | Type/StringHoldAVariable |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Minor                    |
+-------------+--------------------------+
| Time To Fix | Instant (5 mins)         |
+-------------+--------------------------+



.. _strings-with-strange-space:

Strings With Strange Space
##########################


An invisible space may be mistaken for a normal space. 

However, PHP does straight comparisons, and may fail at recognizing. This analysis reports when it finds such strange spaces inside strings.

PHP doesn't mistake space and tables for whitespace when tokenizing the code.

This analysis doesn't report Unicode Codepoint Notation : those are visible in the code.

.. code-block:: php

   <?php
   
   // PHP 7 notation, 
   $a = \u{3000};
   $b = ;
   
   // Displays false
   var_dump($a === $b);
   
   ?>


See also `Unicode spaces <https://www.cs.tut.fi/~jkorpela/chars/spaces.html>`_, and `disallow irregular whitespace (no-irregular-whitespace) <http://eslint.org/docs/rules/no-irregular-whitespace>`_.

+-------------+-----------------------------+
| Short name  | Type/StringWithStrangeSpace |
+-------------+-----------------------------+
| Themes      | :ref:`Analyze`              |
+-------------+-----------------------------+
| Severity    | Minor                       |
+-------------+-----------------------------+
| Time To Fix | Quick (30 mins)             |
+-------------+-----------------------------+



.. _strpos-too-much:

Strpos Too Much
###############


Strpos covers the whole string before reporting 0. If the expected string is expected be at the beginning, or a fixed place, it is more stable to use substr() for comparison.

The longer the haystack (the searched string), the more efficient is that trick. The string has to be 10k or more to have impact. 

.. code-block:: php

   <?php
   
   // This always reads the same amount of string
   if (substr($html, 0, 6) === '<html>') {
   
   }
   
   // When searching for a single character, $string[$position] is even faster
   if ($html[0] === '<') {
   
   }
   
   // This is the best way, however the needle is found
   if (strpos($html, '<html>') > 0) {
   
   }
   
   // When the search fails, the whole string has been read
   if (strpos($html, '<html>') === 0) {
   
   }
   
   ?>

+-------------+----------------------------+
| Short name  | Performances/StrposTooMuch |
+-------------+----------------------------+
| Themes      | :ref:`Analyze`             |
+-------------+----------------------------+
| Severity    | Minor                      |
+-------------+----------------------------+
| Time To Fix | Instant (5 mins)           |
+-------------+----------------------------+



.. _strpos()-like-comparison:

Strpos()-like Comparison
########################


The result of that function may be mistaken with an error.

`strpos() <http://www.php.net/strpos>`_, along with several PHP native functions, returns a string position, starting at 0, or false, in case of failure. 

.. code-block:: php

   <?php
   
   // This is the best comparison
   if (strpos($string, 'a') === false) { }
   
   // This is OK, as 2 won't be mistaken with false
   if (strpos($string, 'a') == 2) { }
   
   // strpos is one of the 26 functions that may behave this way
   if (preg_match($regex, $string)) { } 
   
   // This works like above, catching the value for later reuse
   if ($a = strpos($string, 'a')) { }
   
   // This misses the case where 'a' is the first char of the string
   if (strpos($string, 'a')) { }
   
   // This misses the case where 'a' is the first char of the string, just like above
   if (strpos($string, 'a') == 0) { }
   
   ?>


It is recommended to check the result of `strpos() <http://www.php.net/strpos>`_ with === or !==, so as to avoid confusing 0 and false. 

This analyzer list all the `strpos() <http://www.php.net/strpos>`_-like functions that are directly compared with == or !=. preg_match(), when its first argument is a literal, is omitted : this function only returns `NULL <http://php.net/manual/en/language.types.null.php>`_ in case of regex error.

+-------------+-----------------------------------------------------------------------------------------------------+
| Short name  | Structures/StrposCompare                                                                            |
+-------------+-----------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                      |
+-------------+-----------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                               |
+-------------+-----------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                     |
+-------------+-----------------------------------------------------------------------------------------------------+
| ClearPHP    | `strict-comparisons <https://github.com/dseguy/clearPHP/tree/master/rules/strict-comparisons.md>`__ |
+-------------+-----------------------------------------------------------------------------------------------------+
| Examples    | :ref:`piwigo-structures-strposcompare`, :ref:`thelia-structures-strposcompare`                      |
+-------------+-----------------------------------------------------------------------------------------------------+



.. _strtr-arguments:

Strtr Arguments
###############


`Strtr() <http://www.php.net/strtr>`_ replaces characters by others in a string. When using strings, `strtr() <http://www.php.net/strtr>`_ replaces characters as long as they have a replacement. All others are ignored.

In particular, `strtr() <http://www.php.net/strtr>`_ works on strings of the same size, and cannot be used to remove chars.

.. code-block:: php

   <?php
   
   $string = 'abcde';
   echo strtr($string, 'abc', 'AB');
   echo strtr($string, 'ab', 'ABC');
   // displays ABcde 
   // c is ignored each time
   
   // strtr can't remove a char
   echo strtr($string, 'a', '');
   // displays a
   
   ?>


See also `strtr <http://www.php.net/strtr>`_.



Suggestions
^^^^^^^^^^^

* Check the call to strtr() and make sure the arguments are of the same size
* Replace strtr() with str_replace(), which works with strings and array, not chars
* Replace strtr() with preg_match(), which works with patterns and not chars

+-------------+------------------------------------+
| Short name  | Php/StrtrArguments                 |
+-------------+------------------------------------+
| Themes      | :ref:`Analyze`                     |
+-------------+------------------------------------+
| Severity    | Major                              |
+-------------+------------------------------------+
| Time To Fix | Instant (5 mins)                   |
+-------------+------------------------------------+
| Examples    | :ref:`suitecrm-php-strtrarguments` |
+-------------+------------------------------------+



.. _substring-first:

Substring First
###############


Always start by reducing a string before applying some transformation on it. The shorter string will be processed faster. 

.. code-block:: php

   <?php
   
   // fast version
   $result = strtolower(substr($string, $offset, $length));
   
   // slower version
   $result = substr(strtolower($string), $offset, $length);
   ?>


The gain produced here is greater with longer strings, or greater reductions. They may also be used in loops. This is a micro-optimisation when used on short strings and single string reductions.

This works with any reduction function instead of substr(), like `trim() <http://www.php.net/trim>`_, iconv(), etc.

+-------------+-----------------------------------------+
| Short name  | Performances/SubstrFirst                |
+-------------+-----------------------------------------+
| Themes      | :ref:`Performances`, :ref:`Suggestions` |
+-------------+-----------------------------------------+
| Severity    | Minor                                   |
+-------------+-----------------------------------------+
| Time To Fix | Instant (5 mins)                        |
+-------------+-----------------------------------------+



.. _suspicious-comparison:

Suspicious Comparison
#####################


The comparison seems to be misplaced.

A comparison happens in the last argument, while the actual function expect another type : this may be the case of a badly placed parenthesis.

.. code-block:: php

   <?php
   
   // trim expect a string, a boolean is given.
   if (trim($str === '')){
   
   }
   
   // Just move the first closing parenthesis to give back its actual meaning
   if (trim($str) === ''){
   
   }
   
   ?>


Original idea by `Vladimir Reznichenko <https://twitter.com/kalessil>`_. 



Suggestions
^^^^^^^^^^^

* Remove the comparison altogether
* Move the comparison to its right place : that, or more the parenthesis.
* This may be what is intended : just leave it.

+-------------+---------------------------------------------------------------------------------------------------------+
| Short name  | Structures/SuspiciousComparison                                                                         |
+-------------+---------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                          |
+-------------+---------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                   |
+-------------+---------------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                        |
+-------------+---------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`phpipam-structures-suspiciouscomparison`, :ref:`expressionengine-structures-suspiciouscomparison` |
+-------------+---------------------------------------------------------------------------------------------------------+



.. _switch-fallthrough:

Switch Fallthrough
##################


A switch with fallthrough is prone to errors. 

A fallthrough happens when a case or default clause in a switch statement is not finished by a `break <http://php.net/manual/en/control-structures.break.php>`_ (or equivalent);
CWE report this as a security concern, unless well documented.

A fallthrough may be used as a feature. Then, it is indistinguishable from an error. 

When the case block is empty, this analysis doesn't report it : the case is then used as an alias.

.. code-block:: php

   <?php
   switch($variable) {
       case 1 :   // 1 is not reported, as it actually shares the same body as 33
       case 33 :  
           break ;
       case 2 : 
           break ;
       default: 
           ++$a;
       case 4 : 
           break ;
   }
   ?>


This analysis cannot take into account comments about the fallthrough. 

See also `CWE-484: Omitted `Break <http://php.net/manual/en/control-structures.break.php>`_ Statement in Switch <https://cwe.mitre.org/data/definitions/484.html>`_ and 
         `Rule: no-switch-case-fall-through <https://palantir.github.io/tslint/rules/no-switch-case-fall-through/>`_.

+-------------+------------------------+
| Short name  | Structures/Fallthrough |
+-------------+------------------------+
| Themes      | :ref:`Security`        |
+-------------+------------------------+
| Severity    | Minor                  |
+-------------+------------------------+
| Time To Fix | Instant (5 mins)       |
+-------------+------------------------+



.. _switch-to-switch:

Switch To Switch
################


The following structures are based on if / elseif / else. Since they have more than three conditions (not withstanding the final else), it is recommended to use the switch structure, so as to make this more readable.

On the other hand, `switch() <http://php.net/manual/en/control-structures.switch.php>`_ structures will less than 3 elements should be expressed as a if / else structure.

Note that if condition that uses strict typing (=== or !==) can't be converted to `switch() <http://php.net/manual/en/control-structures.switch.php>`_ as the latter only performs == or != comparisons.

.. code-block:: php

   <?php
   
   if ($a == 1) {
   
   } elseif ($a == 2) {
   
   } elseif ($a == 3) {
   
   } elseif ($a == 4) {
   
   } else {
   
   }
   
   // Better way to write long if/else lists
   switch ($a) {
       case 1 : 
           doSomething(1);
           break 1;
       
       case 2 : 
           doSomething(2);
           break 1;
   
       case 3 : 
           doSomething(3);
           break 1;
   
       case 4 : 
           doSomething(4);
           break 1;
       
       default :
           doSomething();
           break 1;
   }
   
   ?>

+-------------+---------------------------+
| Short name  | Structures/SwitchToSwitch |
+-------------+---------------------------+
| Themes      | :ref:`Analyze`            |
+-------------+---------------------------+
| Severity    | Minor                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _switch-with-too-many-default:

Switch With Too Many Default
############################


Switch statements should only hold one default, not more. Check the code and remove the extra default.  

PHP 7.0 won't compile a script that allows for several default cases. 

Multiple default happens often with large `switch() <http://php.net/manual/en/control-structures.switch.php>`_.

.. code-block:: php

   <?php
   
   switch($a) {
       case 1 : 
           break;
       default : 
           break;
       case 2 : 
           break;
       default :  // This default is never reached
           break;
   }
   
   ?>




Suggestions
^^^^^^^^^^^

* Remove the useless default : it may be the first, or the last. In case of ambiguity, keep the first, as it is the one being used at the moment.

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Structures/SwitchWithMultipleDefault                                                                       |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and older                                                                                     |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                            |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _switch-without-default:

Switch Without Default
######################


Always use a default statement in `switch() <http://php.net/manual/en/control-structures.switch.php>`_.

Switch statements hold a number of 'case' that cover all known situations, and a 'default' one which is executed when all other options are exhausted. 

.. code-block:: php

   <?php
   
   // Missing default
   switch($format) {
       case 'gif' : 
           processGif();
           break 1;
       
       case 'jpeg' : 
           processJpeg();
           break 1;
           
       case 'bmp' :
           throw new UnsupportedFormat($format);
   }
   // In case $format is not known, then switch is ignored and no processing happens, leading to preparation errors
   
   
   // switch with default
   switch($format) {
       case 'text' : 
           processText();
           break 1;
       
       case 'jpeg' : 
           processJpeg();
           break 1;
           
       case 'rtf' :
           throw new UnsupportedFormat($format);
           
       default :
           throw new UnknownFileFormat($format);
   }
   // In case $format is not known, an exception is thrown for processing 
   
   ?>


Most of the time, `switch() <http://php.net/manual/en/control-structures.switch.php>`_ do need a default case, so as to catch the odd situation where the 'value is not what it was expected'. This is a good place to catch unexpected values, to set a default behavior.

+-------------+-------------------------------------------------------------------------------------------------------------------+
| Short name  | Structures/SwitchWithoutDefault                                                                                   |
+-------------+-------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                                    |
+-------------+-------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                             |
+-------------+-------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                   |
+-------------+-------------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-switch-without-default <https://github.com/dseguy/clearPHP/tree/master/rules/no-switch-without-default.md>`__ |
+-------------+-------------------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`zencart-structures-switchwithoutdefault`, :ref:`traq-structures-switchwithoutdefault`                       |
+-------------+-------------------------------------------------------------------------------------------------------------------+



.. _ternary-in-concat:

Ternary In Concat
#################


Ternary operator has higher priority than dot '.' for concatenation. This means that : 

.. code-block:: php

   <?php
     print 'B'.$b.'C'. $b > 1 ? 'D' : 'E';
   ?>


prints actually 'E', instead of the awaited 'B0CE'.

To be safe, always add parenthesis when using ternary operator with concatenation.

+-------------+----------------------------+
| Short name  | Structures/TernaryInConcat |
+-------------+----------------------------+
| Themes      | :ref:`Analyze`             |
+-------------+----------------------------+
| Severity    | Critical                   |
+-------------+----------------------------+
| Time To Fix | Quick (30 mins)            |
+-------------+----------------------------+



.. _test-then-cast:

Test Then Cast
##############


A test is run on the value, but the cast value is later used. 

The cast may introduce a distortion to the value, and still lead to the unwanted situation. For example, comparing to 0, then later casting to an int. The comparison to 0 is done without casting, and as such, 0.1 is different from 0. Yet, (int) 0.1 is actually 0, leading to a Division by 0 error.

.. code-block:: php

   <?php
   
   // Here. $x may be different from 0, but (int) $x may be 0
   $x = 0.1;
   
   if ($x != 0) {
       $y = 4 / (int) $x;
   }
   
   // Safe solution : check the cast value.
   if ( (int) $x != 0) {
       $y = 4 / (int) $x;
   }
   
   ?>

+-------------+---------------------------------------------------------------------------------+
| Short name  | Structures/TestThenCast                                                         |
+-------------+---------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                  |
+-------------+---------------------------------------------------------------------------------+
| Severity    | Major                                                                           |
+-------------+---------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                |
+-------------+---------------------------------------------------------------------------------+
| Examples    | :ref:`dolphin-structures-testthencast`, :ref:`suitecrm-structures-testthencast` |
+-------------+---------------------------------------------------------------------------------+



.. _throw-functioncall:

Throw Functioncall
##################


The ``throw`` keyword expects to use an exception. Calling a function to prepare that exception before throwing it is possible, but forgetting the new keyword is also possible. 

.. code-block:: php

   <?php
   
   // Forgotten new
   throw \RuntimeException('error!');
   
   // Code is OK, function returns an exception
   throw getException(ERROR_TYPE, 'error!');
   
   function getException(ERROR_TYPE, $message) {
       return new \RuntimeException($messsage);
   }
   
   ?>


When the ``new`` keyword is forgotten, then the class constructor is used as a function name, and now exception is emitted, but an ``Undefined function`` fatal error is emitted. 

See also `Exceptions <http://php.net/manual/en/language.exceptions.php>`_.


Suggestions
^^^^^^^^^^^

* Add the new operator to the call
* Make sure the function is really a functioncall, not a class name

+-------------+----------------------------------------------+
| Short name  | Exceptions/ThrowFunctioncall                 |
+-------------+----------------------------------------------+
| Themes      | :ref:`Analyze`                               |
+-------------+----------------------------------------------+
| Severity    | Major                                        |
+-------------+----------------------------------------------+
| Time To Fix | Instant (5 mins)                             |
+-------------+----------------------------------------------+
| Examples    | :ref:`sugarcrm-exceptions-throwfunctioncall` |
+-------------+----------------------------------------------+



.. _throw-in-destruct:

Throw In Destruct
#################


According to the manual, ``Attempting to throw an exception from a destructor (called in the time of script termination) causes a fatal error.``

The destructor may be called during the lifespan of the script, but it is not certain. If the exception is thrown later, the script may end up with a fatal error. 

Thus, it is recommended to avoid throwing exceptions within the ``__destruct`` method of a class.

.. code-block:: php

   <?php
   
   // No exception thrown
   class Bar { 
       function __construct() {
           throw new Exception('__construct');
       }
   
       function __destruct() {
           $this->cleanObject();
       }
   }
   
   // Potential crash
   class Foo { 
       function __destruct() {
           throw new Exception('__destruct');
       }
   }
   
   ?>


See also `Constructors and Destructors <http://php.net/manual/en/language.oop5.decon.php>`_.

+-------------+-------------------------+
| Short name  | Classes/ThrowInDestruct |
+-------------+-------------------------+
| Themes      | :ref:`Analyze`          |
+-------------+-------------------------+
| Severity    | Major                   |
+-------------+-------------------------+
| Time To Fix | Quick (30 mins)         |
+-------------+-------------------------+



.. _throws-an-assignement:

Throws An Assignement
#####################


It is possible to throw an exception, and, in the same time, assign this exception to a variable.

However, the variable will never be used, as the exception is thrown, and any following code is not executed. 

.. code-block:: php

   <?php
   
       // $e is useful, though not by much
       $e = new() Exception();
       throw $e;
   
       // $e is useless
       throw $e = new() Exception();
   
   ?>


The assignment should be removed.

+-------------+----------------------------+
| Short name  | Structures/ThrowsAndAssign |
+-------------+----------------------------+
| Themes      | :ref:`Analyze`             |
+-------------+----------------------------+
| Severity    | Minor                      |
+-------------+----------------------------+
| Time To Fix | Instant (5 mins)           |
+-------------+----------------------------+



.. _timestamp-difference:

Timestamp Difference
####################


``time()`` and ``microtime()`` shouldn't be used to calculate duration. 

``time()`` and ``microtime()`` are subject to variations, depending on system clock variations, such as daylight saving time difference (every spring and fall, one hour variation), or leap seconds, happening on ``June, 30th`` or ``December 31th``, as announced by `IERS <https://www.iers.org/IERS/EN/Home/home_node.html>`_.

.. code-block:: php

   <?php
   
   // Calculating tomorow, same hour, the wrong way
   // tomorrow is not always in 86400s, especially in countries with daylight saving 
   $tomorrow = time()  + 86400; 
   
   // Good way to calculate tomorrow
   $datetime = new DateTime('tomorrow');
   
   ?>


When the difference may be rounded to a larger time unit (rounding the difference to days, or several hours), the variation may be ignored safely.

When the difference is very small, it requires a better way to measure time difference, such as `Ticks <http://php.net/manual/en/control-structures.declare.php#control-structures.declare.ticks>'_, 
`ext/hrtime <http://php.net/manual/en/book.hrtime.php>'_, or including a check on the actual time zone (``ini_get()`` with 'date.timezone'). 

See also `PHP DateTime difference – it’s a trap! <http://blog.codebusters.pl/en/php-datetime-difference-trap/>`_ and 
           `PHP Daylight savings bug? <https://stackoverflow.com/questions/22519091/php-daylight-savings-bug>`_.

+-------------+---------------------------------------------------------------------------------------------+
| Short name  | Structures/TimestampDifference                                                              |
+-------------+---------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                              |
+-------------+---------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                       |
+-------------+---------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                               |
+-------------+---------------------------------------------------------------------------------------------+
| Examples    | :ref:`zurmo-structures-timestampdifference`, :ref:`shopware-structures-timestampdifference` |
+-------------+---------------------------------------------------------------------------------------------+



.. _too-many-children:

Too Many Children
#################


Classes that have more than 15 children. It is worth checking if they cannot be refactored in anyway.

The threshold of 15 children can be configured. There is no technical limitation of the number of children and grand-children for a class. 

The analysis doesn't work recursively : only direct generations are counted. Only children that can be found in the code are counted. 

.. code-block:: php

   <?php
   
   // parent class
   // calling it grandparent to avoid confusion with 'parent'
   class grandparent {}
   
   
   class children1 extends grandparent {}
   class children2 extends grandparent {}
   class children3 extends grandparent {}
   class children4 extends grandparent {}
   class children5 extends grandparent {}
   class children6 extends grandparent {}
   class children7 extends grandparent {}
   class children8 extends grandparent {}
   class children9 extends grandparent {}
   class children11 extends grandparent {}
   class children12 extends grandparent {}
   class children13 extends grandparent {}
   class children14 extends grandparent {}
   class children15 extends grandparent {}
   class children16 extends grandparent {}
   class children17 extends grandparent {}
   class children18 extends grandparent {}
   class children19 extends grandparent {}
   
   ?>


See also `Why is subclassing too much bad (and hence why should we use prototypes to do away with it)? <https://softwareengineering.stackexchange.com/questions/137687/why-is-subclassing-too-much-bad-and-hence-why-should-we-use-prototypes-to-do-aw>`_.

+--------------------+---------+---------+--------------------------------------------------------+
| Name               | Default | Type    | Description                                            |
+--------------------+---------+---------+--------------------------------------------------------+
| childrenClassCount | 15      | integer | Threshold for too many children classes for one class. |
+--------------------+---------+---------+--------------------------------------------------------+



+-------------+-------------------------+
| Short name  | Classes/TooManyChildren |
+-------------+-------------------------+
| Themes      | :ref:`Suggestions`      |
+-------------+-------------------------+
| Severity    | Minor                   |
+-------------+-------------------------+
| Time To Fix | Slow (1 hour)           |
+-------------+-------------------------+



.. _too-many-finds:

Too Many Finds
##############


Too many methods called 'find*' in this class. It is may be time to consider the `Specification pattern <https://en.wikipedia.org/wiki/Specification_pattern>`_.

.. code-block:: php

   <?php
   
   // quite a fishy interface
   interface UserInterface {
       public function findByEmail($email);
       public function findByUsername($username);
       public function findByFirstName($firstname);
       public function findByLastName($lastname);
       public function findByName($name);
       public function findById($id);
   
       public function insert($user);
       public function update($user);
   }
   
   ?>


See also `On Taming Repository Classes in Doctrine <https://beberlei.de/2013/03/04/doctrine_repositories.html>`_ , 
`On Taming Repository Classes in Doctrine… Among other things. <http://blog.kevingomez.fr/2015/02/07/on-taming-repository-classes-in-doctrine-among-other-things/>`_,
`specifications <https://slides.pixelart.at/2017-02-04/fosdem/specifications/#/>`_.

+-------------+----------------------+
| Short name  | Classes/TooManyFinds |
+-------------+----------------------+
| Themes      | :ref:`Analyze`       |
+-------------+----------------------+
| Severity    | Minor                |
+-------------+----------------------+
| Time To Fix | Slow (1 hour)        |
+-------------+----------------------+



.. _too-many-injections:

Too Many Injections
###################


When a class is constructed with more than four dependencies, it should be split into smaller classes.

.. code-block:: php

   <?php
   
   // This class relies on 5 other instances. 
   // It is probably doing too much.
   class Foo {
       public function __construct(
               A $a, 
               B $b, 
               C $c,
               D $d
               E $e ) {
           $this->a = $a;
           $this->b = $b;
           $this->d = $d;
           $this->d = $d;
           $this->e = $e;
       }
   }
   
   ?>


See also `Dependency Injection Smells <http://seregazhuk.github.io/2017/05/04/di-smells/>`_.



Suggestions
^^^^^^^^^^^

* Split the class into smaller classes. Try to do less in that class.

+-----------------+---------+---------+-----------------------------------------------------------+
| Name            | Default | Type    | Description                                               |
+-----------------+---------+---------+-----------------------------------------------------------+
| injectionsCount | 5       | integer | Threshold for too many injected parameters for one class. |
+-----------------+---------+---------+-----------------------------------------------------------+



+-------------+-------------------------------------------------------------------------------------+
| Short name  | Classes/TooManyInjections                                                           |
+-------------+-------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                      |
+-------------+-------------------------------------------------------------------------------------+
| Severity    | Minor                                                                               |
+-------------+-------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                       |
+-------------+-------------------------------------------------------------------------------------+
| Examples    | :ref:`nextcloud-classes-toomanyinjections`, :ref:`thelia-classes-toomanyinjections` |
+-------------+-------------------------------------------------------------------------------------+



.. _too-many-local-variables:

Too Many Local Variables
########################


Too many local variables were found in the methods. When over 15 variables are found in such a method, a violation is reported.

Local variables exclude globals (imported with global) and arguments. Local variable include static variables.

When too many variables are used in a function, it is a code smells. The function is trying to do too much and needs extra space for juggling.
Beyond 15 variables, it becomes difficult to keep track of their name and usage, leading to confusion, overwriting or hijacking. 

.. code-block:: php

   <?php
   
   // This function is OK : 3 vars are arguments, 3 others are globals.
   function a20a3g3($a1, $a2, $a3) {
       global $a4, $a5, $a6;
       
       $a1  = 1;
       $a2  = 2;
       $a3  = 3 ;
       $a4  = 4 ;
       $a5  = 5 ;
       $a6  = 6 ;
       $a7  = 7 ;
       $a8  = 8 ;
       $a9  = 9 ;
       $a10 = 10;
       $a11  = 11;
       $a12  = 12;
       $a13  = 13 ;
       $a14  = 14 ;
       $a15  = 15 ;
       $a16  = 16 ;
       $a17  = 17 ;
       $a18  = 18 ;
       $a19  = 19 ;
       $a20 = 20;
   
   }
   
   // This function has too many variables
   function a20() {
       
       $a1  = 1;
       $a2  = 2;
       $a3  = 3 ;
       $a4  = 4 ;
       $a5  = 5 ;
       $a6  = 6 ;
       $a7  = 7 ;
       $a8  = 8 ;
       $a9  = 9 ;
       $a10 = 10;
       $a11  = 11;
       $a12  = 12;
       $a13  = 13 ;
       $a14  = 14 ;
       $a15  = 15 ;
       $a16  = 16 ;
       $a17  = 17 ;
       $a18  = 18 ;
       $a19  = 19 ;
       $a20 = 20;
   
   }
   
   ?>

+-------------------------------+---------+---------+------------------------------------------------------------------+
| Name                          | Default | Type    | Description                                                      |
+-------------------------------+---------+---------+------------------------------------------------------------------+
| tooManyLocalVariableThreshold | 15      | integer | Minimal number of variables in one function or method to report. |
+-------------------------------+---------+---------+------------------------------------------------------------------+



+-------------+-------------------------------------------------+
| Short name  | Functions/TooManyLocalVariables                 |
+-------------+-------------------------------------------------+
| Themes      | :ref:`Analyze`                                  |
+-------------+-------------------------------------------------+
| Severity    | Minor                                           |
+-------------+-------------------------------------------------+
| Time To Fix | Slow (1 hour)                                   |
+-------------+-------------------------------------------------+
| Examples    | :ref:`humo-gen-functions-toomanylocalvariables` |
+-------------+-------------------------------------------------+



.. _too-many-native-calls:

Too Many Native Calls
#####################


Avoid stuffing too many PHP native call inside another functioncall. 

For readability reasons, or, more often, for edge case handling, it is recommended to avoid nesting too many PHP native calls. 

This analysis reports any situation where more than 3 PHP native calls are nested.

.. code-block:: php

   <?php
   
   // Too many nested functions 
   $cleanArray = array_unique(array_keys(array_count_values(array_column($source, 'x'))));
   
   // Avoid warning when source is empty
   $extract = array_column($source, 'x');
   if (empty($extract)) {
       $cleanArray = array();
   } else {
       $cleanArray = array_unique(array_keys(array_count_values($extract)));
   }
   
   // This is not readable, although it is short. 
   // It may easily get out of hand.
   echo chr(80), chr(72), chr(80), chr(32), ' is great!';
   
   ?>

+------------------+---------+---------+---------------------------------------------------+
| Name             | Default | Type    | Description                                       |
+------------------+---------+---------+---------------------------------------------------+
| nativeCallCounts | 3       | integer | Number of native calls found inside another call. |
+------------------+---------+---------+---------------------------------------------------+



+-------------+------------------------+
| Short name  | Php/TooManyNativeCalls |
+-------------+------------------------+
| Themes      | :ref:`Analyze`         |
+-------------+------------------------+
| Severity    | Minor                  |
+-------------+------------------------+
| Time To Fix | Quick (30 mins)        |
+-------------+------------------------+



.. _too-many-parameters:

Too Many Parameters
###################


Method has too many parameters : more than 8. 

A method that needs more than 8 parameters is trying to do too much : it should be reviewed and split into smaller methods. 

.. code-block:: php

   <?php
   
   // This methods has too many parameters.
   function alertSomeone($name, $email, $title, $message, $attachements, $signature, $bcc, $cc, $extra_headers) { 
       /* too much code here */ 
   }
   
   ?>


See also `How many parameters is too many ? <https://www.exakat.io/how-many-parameters-is-too-many/>`_ and 
         `Too Many Parameters <http://wiki.c2.com/?TooManyParameters>`_.

+-----------------+---------+---------+-----------------------------------------+
| Name            | Default | Type    | Description                             |
+-----------------+---------+---------+-----------------------------------------+
| parametersCount | 8       | integer | Minimal number of parameters to report. |
+-----------------+---------+---------+-----------------------------------------+



+------------+-----------------------------+
| Short name | Functions/TooManyParameters |
+------------+-----------------------------+
| Themes     | :ref:`Suggestions`          |
+------------+-----------------------------+



.. _trailing-comma-in-calls:

Trailing Comma In Calls
#######################


The last argument may be left empty. 

This feature was introduced in PHP 7.3. 

.. code-block:: php

   <?php
     
   // VCS friendly call
   // PHP 7.3 and more recent
   foo(1,
       2,
       3,
      );
   
   // backward compatible call
   // All PHP versions
   foo(1,
       2,
       3
      );
     
   ?>


See also `PHP RFC: Allow a trailing comma in function calls <https://wiki.php.net/rfc/trailing-comma-function-calls>`_.

+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Php/TrailingComma                                                                                                                                                                           |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP71`, :ref:`CompatibilityPHP72`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                                                                                                       |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                                                                                             |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------+



.. _typehinted-references:

Typehinted References
#####################


Typehinted arguments have no need for references. Since they are only an object, they are already a reference.

In fact, adding the & on the argument definition may lead to error like ``Only variables should be passed by reference``.

This applies to the 'object' type hint, but not the the others, such as int or bool.

.. code-block:: php

   <?php
       // a class
       class X {
           public $a = 3;
       }
   
       // typehinted reference
       //function foo(object &$x) works too
       function foo(X &$x) {
           $x->a = 1;
       
           return $x;
       }
   
       // Send an object 
       $y = foo(new X);
   
       // This prints 1;
       print $y->a;
   ?>


See also `Passing by reference <http://php.net/manual/en/language.references.pass.php>`_ and 
         `Objects and references <http://php.net/manual/en/language.oop5.references.php>`_.

+-------------+--------------------------------+
| Short name  | Functions/TypehintedReferences |
+-------------+--------------------------------+
| Themes      | :ref:`Analyze`                 |
+-------------+--------------------------------+
| Severity    | Minor                          |
+-------------+--------------------------------+
| Time To Fix | Instant (5 mins)               |
+-------------+--------------------------------+



.. _uncaught-exceptions:

Uncaught Exceptions
###################


The following exceptions are thrown in the code, but are never caught. 

.. code-block:: php

   <?php
   
   // This exception is throw, but not caught. It will lead to a fatal error.
   if ($message = check_for_error()) {
       throw new My\Exception($message);
   }
   
   // This exception is throw, and caught. 
   try {
       if ($message = check_for_error()) {
           throw new My\Exception($message);
       }
   } catch (\Exception $e) {
       doSomething();
   }
   
   ?>


Either they will lead to a fatal error, or they have to be caught by a larger application.

+-------------+-------------------------------+
| Short name  | Exceptions/UncaughtExceptions |
+-------------+-------------------------------+
| Themes      | :ref:`Analyze`                |
+-------------+-------------------------------+
| Severity    | Minor                         |
+-------------+-------------------------------+
| Time To Fix | Quick (30 mins)               |
+-------------+-------------------------------+



.. _unchecked-resources:

Unchecked Resources
###################


Resources are created, but never checked before being used. This is not safe.

Always check that resources are correctly created before using them.

.. code-block:: php

   <?php
   
   // always check that the resource is created correctly
   $fp = fopen($d,'r');
   if ($fp === false) {
       throw new Exception('File not found');
   } 
   $firstLine = fread($fp);
   
   // This directory is not checked : the path may not exist and return false
   $uncheckedDir = opendir($pathToDir);
   while(readdir($uncheckedDir)) {
       // do something()
   }
   
   // This file is not checked : the path may not exist or be unreadable and return false
   $fp = fopen($pathToFile);
   while($line = freads($fp)) {
       $text .= $line;
   }
   
   // unsafe one-liner : using bzclose on an unchecked resource
   bzclose(bzopen('file'));
   
   ?>


See also `resources <http://php.net/manual/en/language.types.resource.php>`_.

+-------------+-------------------------------------------------------------------------------------------------------------+
| Short name  | Structures/UncheckedResources                                                                               |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                              |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                       |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                               |
+-------------+-------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-unchecked-resources <https://github.com/dseguy/clearPHP/tree/master/rules/no-unchecked-resources.md>`__ |
+-------------+-------------------------------------------------------------------------------------------------------------+



.. _unconditional-break-in-loop:

Unconditional Break In Loop
###########################


An unconditional `break <http://php.net/manual/en/control-structures.break.php>`_ in a loop creates dead code. Since the `break <http://php.net/manual/en/control-structures.break.php>`_ is directly in the body of the loop, it is always executed, creating a strange loop that can only run once. 

Here, `break <http://php.net/manual/en/control-structures.break.php>`_ may also be a return, a goto or a `continue <http://php.net/manual/en/control-structures.continue.php>`_. They all branch out of the loop. Such statement are valid, but should be moderated with a condition. 

.. code-block:: php

   <?php
   
   // return in loop should be in 
   function summAll($array) {
       $sum = 0;
       
       foreach($array as $a) {
           // Stop at the first error
           if (is_string($a)) {
               return $sum;
           }
           $sum += $a;
       }
       
       return $sum;
   }
   
   // foreach loop used to collect first element in array
   function getFirst($array) {
       foreach($array as $a) {
           return $a;
       }
   }
   
   ?>

+-------------+---------------------------------+
| Short name  | Structures/UnconditionLoopBreak |
+-------------+---------------------------------+
| Themes      | :ref:`Analyze`                  |
+-------------+---------------------------------+
| Severity    | Major                           |
+-------------+---------------------------------+
| Time To Fix | Quick (30 mins)                 |
+-------------+---------------------------------+



.. _undeclared-static-property:

Undeclared Static Property
##########################


Use the right syntax when reaching a property. Static properties use the ``\:\:`` operator, and non-static properties use ``->``. 

Mistaking one of the other has two different reactions from PHP : ``Access to undeclared static property`` is a fatal error, while ``PHP Notice:  Accessing static property aa\:\:$a as non static`` is a notice.

.. code-block:: php

   <?php
   
   class a { 
       static public $a = 1;
       
       function foo() {
           echo self::$a; // right
           echo $this->a; // WRONG
       }
   }
   
   class b { 
       public $b = 1;
   
       function foo() {
           echo $this->$b;  // right
           echo b::$b;      // WRONG
       }
   }
   
   ?>


See also `Static Keyword <http://php.net/manual/en/language.oop5.static.php>`_.


Suggestions
^^^^^^^^^^^

* Match the property call with the definition
* Make the property static

+-------------+------------------------------------+
| Short name  | Classes/UndeclaredStaticProperty   |
+-------------+------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`ClassReview` |
+-------------+------------------------------------+
| Severity    | Critical                           |
+-------------+------------------------------------+
| Time To Fix | Quick (30 mins)                    |
+-------------+------------------------------------+



.. _undefined-\:\:class:

Undefined \:\:class
###################


``\:\:class`` doesn't check if a corresponding class exists. 

``\:\:class`` must be checked with a call to class_exists(). Otherwise, it may lead to a ``Class 'foo' not found`` or even silent dead code : this happens also with Catch and `instanceof <http://php.net/manual/en/language.operators.type.php>`_ commands with undefined classes. PHP doesn't raise an error in that case. 

.. code-block:: php

   <?php
   
   class foo() {}
   
   // prints foo
   echo foo::class; 
   
   // prints bar though bar doesn't exist.
   echo bar::class;
   
   ?>


See also `Class Constants <http://php.net/manual/en/language.oop5.constants.php>`_.

+-------------+------------------------------+
| Short name  | Classes/UndefinedStaticclass |
+-------------+------------------------------+
| Themes      | :ref:`Analyze`               |
+-------------+------------------------------+
| Severity    | Major                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _undefined-caught-exceptions:

Undefined Caught Exceptions
###########################


Those are exceptions that are caught in the code, but are not defined in the application. 

They may be externally defined, such as in core PHP, extensions or libraries. Make sure those exceptions are useful to your application : otherwise, they are dead code.

.. code-block:: php

   <?php
   
   try {
       library_function($some, $args);
       
   } catch (LibraryException $e) {
       // This exception is not defined, and probably belongs to Library
       print Library failed\n;
   
   } catch (OtherLibraryException $e) {
       // This exception is not defined, and probably do not belongs to this code
       print Library failed\n;
   
   } catch (\Exception $e) {
       // This exception is a PHP standard exception
       print Something went wrong, but not at Libary level\n;
   }
   
   ?>

+------------+-------------------------------+
| Short name | Exceptions/CaughtButNotThrown |
+------------+-------------------------------+
| Themes     | :ref:`Dead code <dead-code>`  |
+------------+-------------------------------+



.. _undefined-class-constants:

Undefined Class Constants
#########################


Class constants that are used, but never defined. This should yield a fatal error upon execution, but no feedback at compile level.

.. code-block:: php

   <?php
   
   class foo {
       const A = 1;
       define('B', 2);
   }
   
   // here, C is not defined in the code and is reported
   echo foo::A.foo::B.foo::C;
   
   ?>

+-------------+----------------------------+
| Short name  | Classes/UndefinedConstants |
+-------------+----------------------------+
| Themes      | none                       |
+-------------+----------------------------+
| Severity    | Major                      |
+-------------+----------------------------+
| Time To Fix | Quick (30 mins)            |
+-------------+----------------------------+



.. _undefined-classes:

Undefined Classes
#################


Those classes are used in the code, but there are no definition for them.

This may happens under normal conditions, if the application makes use of an unsupported extension, that defines extra classes; 
or if some external libraries, such as PEAR, are not provided during the analysis.

.. code-block:: php

   <?php
   
   // FPDF is a classic PDF class, that is usually omitted by Exakat. 
   $o = new FPDF();
   
   // Exakat reports undefined classes in instanceof
   // PHP ignores them
   if ($o instanceof SomeClass) {
       // doSomething();
   }
   
   // Classes may be used in typehint too
   function foo(TypeHintClass $x) {
       // doSomething();
   }
   
   ?>

+-------------+--------------------------+
| Short name  | Classes/UndefinedClasses |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Major                    |
+-------------+--------------------------+
| Time To Fix | Slow (1 hour)            |
+-------------+--------------------------+



.. _undefined-constants:

Undefined Constants
###################


Constants definition can't be located.

Those constants are not defined in the code, and will raise errors, or use the fallback mechanism of being treated like a string. 

.. code-block:: php

   <?php
   
   const A = 1;
   define('B', 2);
   
   // here, C is not defined in the code and is reported
   echo A.B.C;
   
   ?>


It is recommended to define them all, or to avoid using them.

See also `Constants <http://php.net/manual/en/language.constants.php>`_.

+-------------+-----------------------------------------------------------+
| Short name  | Constants/UndefinedConstants                              |
+-------------+-----------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Analyze`, :ref:`CompatibilityPHP72` |
+-------------+-----------------------------------------------------------+
| Severity    | Minor                                                     |
+-------------+-----------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                           |
+-------------+-----------------------------------------------------------+



.. _undefined-functions:

Undefined Functions
###################


Some functions are called, but not defined in the code. This means that the functions are probably defined in a missing library, or in an extension. If not, this will yield a Fatal error at execution.

.. code-block:: php

   <?php
   
   // Undefined function 
   foo($a);
   
   // valid function, as it belongs to the ext/yaml extension
   $parsed = yaml_parse($yaml);
   
   // This function is not defined in the a\b\c namespace, nor in the global namespace
   a\b\c\foo(); 
   
   ?>


See also `Functions <http://php.net/manual/en/language.functions.php>`_.

+-------------+------------------------------+
| Short name  | Functions/UndefinedFunctions |
+-------------+------------------------------+
| Themes      | :ref:`Analyze`               |
+-------------+------------------------------+
| Severity    | Major                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _undefined-insteadof:

Undefined Insteadof
###################


``Insteadof`` tries to replace a method with another, but it doesn't exists. This happens when the replacing class is refactored, and some of its definition are dropped. 

``Insteadof`` may replace a non-existing method with an existing one, but not the contrary. 

.. code-block:: php

   <?php
   
   trait A {
       function C (){}
   }
   
   trait B {
       function C (){}
   }
   
   class Talker {
       use A, B {
           B::C insteadof A;
           B::D insteadof A;
       }
   }
   
   new Talker();
   ?>


This error is not linted : it only appears at execution time. 

See also `Traits <http://php.net/manual/en/language.oop5.traits.php>`_.

+-------------+----------------------------------------+
| Short name  | Traits/UndefinedInsteadof              |
+-------------+----------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`LintButWontExec` |
+-------------+----------------------------------------+
| Severity    | Major                                  |
+-------------+----------------------------------------+
| Time To Fix | Instant (5 mins)                       |
+-------------+----------------------------------------+



.. _undefined-interfaces:

Undefined Interfaces
####################


Some typehints or ``instanceof`` that are relying on undefined interfaces or classes. They will always return false. Any condition based upon them are dead code.

.. code-block:: php

   <?php
   
   class var implements undefinedInterface {
       // If undefinedInterface is undefined, this code lints but doesn't run
   }
   
   if ($o instanceof undefinedInterface) {
       // This is silent dead code
   }
   
   function foo(undefinedInterface $a) {
       // This is dead code
       // it will probably be discovered at execution
   }
   
   ?>

+-------------+--------------------------------+
| Short name  | Interfaces/UndefinedInterfaces |
+-------------+--------------------------------+
| Themes      | :ref:`Analyze`                 |
+-------------+--------------------------------+
| Severity    | Major                          |
+-------------+--------------------------------+
| Time To Fix | Quick (30 mins)                |
+-------------+--------------------------------+



.. _undefined-parent:

Undefined Parent
################


List of properties and methods that are accessed using ``parent`` keyword but are not defined in the parent classes. 

This may compile but, eventually yields a fatal error during execution.

.. code-block:: php

   <?php
   
   class theParent {
       // No bar() method
       // private bar() method is not accessible to theChild 
   }
   
   class theChild extends theParent {
       function foo() {
           // bar is defined in theChild, but not theParent
           parent::bar();
       }
       
       function bar() {
       
       }
   }
   
   ?>


Note that if the parent is defined using ``extends someClass`` but ``someClass`` is not available in the tested code, it will not be reported : it may be in composer, another dependency, or just missing.

See also `parent <http://php.net/manual/en/keyword.parent.php>`_.


Suggestions
^^^^^^^^^^^

* Remove the usage of the found method
* Add a definition for the method in the appropriate parent
* Fix the name of the method, and replace it with a valid definition
* Change 'parent' with 'self' if the method is eventually defined in the current class
* Change 'parent' with another object, if the method has been defined in another class
* Add the 'extends' keyword to the class, to actually have a parent class

+-------------+---------------------------+
| Short name  | Classes/UndefinedParentMP |
+-------------+---------------------------+
| Themes      | :ref:`Analyze`            |
+-------------+---------------------------+
| Severity    | Minor                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _undefined-properties:

Undefined Properties
####################


List of properties that are not explicitly defined in the class, its parents or traits.

.. code-block:: php

   <?php
   
   class foo {
       // property definition
       private bar = 2;
       
       function foofoo() {
           // $this->bar is defined in the class
           // $this->barbar is NOT defined in the class
           return $this->bar + $this->barbar;
       }
   }
   
   ?>


See also `Properties <http://php.net/manual/en/language.oop5.properties.php>`_.

+-------------+---------------------------------------------------------------------------------------------------------------+
| Short name  | Classes/UndefinedProperty                                                                                     |
+-------------+---------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                                |
+-------------+---------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                         |
+-------------+---------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                               |
+-------------+---------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-undefined-properties <https://github.com/dseguy/clearPHP/tree/master/rules/no-undefined-properties.md>`__ |
+-------------+---------------------------------------------------------------------------------------------------------------+



.. _undefined-trait:

Undefined Trait
###############


Those traits are undefined. 

When the using class or trait is instantiated, PHP emits a a fatal error.

.. code-block:: php

   <?php
   
   use Composer/Component/someTrait as externalTrait;
   
   trait t {
       function foo() {}
   }
   
   // This class uses trait that are all known
   class hasOnlyDefinedTrait {
       use t, externalTrait;
   }
   
   // This class uses trait that are unknown
   class hasUndefinedTrait {
       use unknownTrait, t, externalTrait;
   }
   ?>

+-------------+----------------------------------------+
| Short name  | Traits/UndefinedTrait                  |
+-------------+----------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`LintButWontExec` |
+-------------+----------------------------------------+
| Severity    | Critical                               |
+-------------+----------------------------------------+
| Time To Fix | Quick (30 mins)                        |
+-------------+----------------------------------------+



.. _undefined-variable:

Undefined Variable
##################


Variable that is used before any creation. 

It is recommended to use a default value for every variable used. When not specified, the default value is set to ``NULL`` by PHP.

.. code-block:: php

   <?php
   
   // Adapted from the PHP manual
   $var = 'Bob';
   $Var = 'Joe';
   // The following line may emit a warning : Undefined variable: $undefined
   echo $var, $Var, $undefined;      // outputs Bob, Joe,
   
   
   ?>


Variable may be created in various ways : assignation, arguments, foreach blind variables, static and global variables.

This analysis doesn't handle dynamic variables, such as ``$$x``. It also doesn't handle variables outside a method or function.

See also `Variable basics <http://php.net/manual/en/language.variables.basics.php>`_.

+-------------+-----------------------------+
| Short name  | Variables/UndefinedVariable |
+-------------+-----------------------------+
| Themes      | :ref:`Analyze`              |
+-------------+-----------------------------+
| Severity    | Minor                       |
+-------------+-----------------------------+
| Time To Fix | Quick (30 mins)             |
+-------------+-----------------------------+



.. _undefined-static\:\:-or-self\:\::

Undefined static\:\: Or self\:\:
################################


self and static refer to the current class, or one of its parent. The property or the method may be undefined.

.. code-block:: php

   <?php
   
   class x {
       static public function definedStatic() {}
       private definedStatic = 1;
       
       public function method() {
           self::definedStatic();
           self::undefinedStatic();
   
           static::definedStatic;
           static::undefinedStatic;
       }
   }
   
   ?>


See also `Late Static Bindings <http://php.net/manual/en/language.oop5.late-static-bindings.php>`_.



Suggestions
^^^^^^^^^^^

* Define the missing method or property
* Remove usage of that undefined method or property
* Fix name to call an actual local structure

+-------------+--------------------------------------------------------------------------------------+
| Short name  | Classes/UndefinedStaticMP                                                            |
+-------------+--------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                       |
+-------------+--------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                |
+-------------+--------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                      |
+-------------+--------------------------------------------------------------------------------------+
| Examples    | :ref:`xataface-classes-undefinedstaticmp`, :ref:`sugarcrm-classes-undefinedstaticmp` |
+-------------+--------------------------------------------------------------------------------------+



.. _unicode-escape-partial:

Unicode Escape Partial
######################


PHP 7 introduces a new escape sequence for strings : \u{hex}. It is backward incompatible with previous PHP versions for two reasons : 

PHP 7 will recognize en replace those sequences, while PHP 5 keep them intact.
PHP 7 will halt on partial Unicode Sequences, as it tries to understand them, but may fail. 

.. code-block:: php

   <?php
   
   echo \u{1F418}\n; 
   // PHP 5 displays the same string
   // PHP 7 displays : an elephant
   
   echo \u{NOT A UNICODE CODEPOINT}\n; 
   // PHP 5 displays the same string
   // PHP 7 emits a fatal error
   
   ?>


Is is recommended to check all those strings, and make sure they will behave correctly in PHP 7.

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Php/UnicodeEscapePartial                                                                                   |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and older                                                                                     |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                            |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _unicode-escape-syntax:

Unicode Escape Syntax
#####################


Usage of the Unicode Escape syntax, with the ``\u{xxxxx}`` format, available since PHP 7.0.

.. code-block:: php

   <?php
   
   // Produce an elephant icon in PHP 7.0+
   echo \u{1F418};
   
   // Produce the raw sequence in PHP 5.0
   echo \u{1F418};
   
   ?>


See also `PHP RFC: Unicode Codepoint Escape Syntax <https://wiki.php.net/rfc/unicode_escape>`_,
         `Code point <https://en.wikipedia.org/wiki/Code_point>`_ and 
         `Unicode <https://en.wikipedia.org/wiki/Unicode>`_.

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Php/UnicodeEscapeSyntax                                                                                    |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and more recent                                                                               |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                              |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _unitialized-properties:

Unitialized Properties
######################


Properties that are not initialized in the constructor, nor at definition. 

.. code-block:: php

   <?php
   
   class X {
       private $i1 = 1, $i2;
       protected $u1, $u2;
       
       function __construct() {
           $this->i2 = 1 + $this->u2;
       }
       
       function m() {
           echo $this->i1, $this->i2, $this->u1, $this->u2;
       }
   }
   ?>


With the above class, when m() is accessed right after instantiation, there will be a missing property. 
Using default values at property definition, or setting default values in the constructor ensures that the created object is consistent.

+-------------+------------------------------------+
| Short name  | Classes/UnitializedProperties      |
+-------------+------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Suggestions` |
+-------------+------------------------------------+
| Severity    | Major                              |
+-------------+------------------------------------+
| Time To Fix | Quick (30 mins)                    |
+-------------+------------------------------------+



.. _unknown-directive-name:

Unknown Directive Name
######################


Unknown directives names used in the code. 

The following list has directive mentionned in the code, that are not known from PHP or any extension. If this is due to a mistake, the directive must be fixed to be actually useful.

.. code-block:: php

   <?php
   
   // non-existing directive
   $reporting_error = ini_get('reporting_error');
   $error_reporting = ini_get('error_reproting'); // Note the inversion
   if (ini_set('dump_globals')) {
       // doSomething()
   }
   
   // Correct directives
   $error_reporting = ini_get('reporting_error');
   if (ini_set('xdebug.dump_globals')) {
       // doSomething()
   }
   
   ?>

+-------------+-------------------+
| Short name  | Php/DirectiveName |
+-------------+-------------------+
| Themes      | :ref:`Analyze`    |
+-------------+-------------------+
| Severity    | Minor             |
+-------------+-------------------+
| Time To Fix | Quick (30 mins)   |
+-------------+-------------------+



.. _unknown-pcre2-option:

Unknown Pcre2 Option
####################


``PCRE2`` supports different options, compared to ``PCRE1``. ``PCRE2`` was adopted with PHP 7.3. 

The ``S`` modifier : it used to tell PCRE to spend more time studying the regex, so as to be faster at execution. This is now the default behavior, and may be dropped from the regex.

The ``X`` modifier : ``X`` is still existing with ``PCRE2``, though it is now the default for ``PCRE2``, and not for PHP as time of writing. In particular, ``Any backslash in a pattern that is followed by a letter that has no special meaning causes an error, thus reserving these combinations for future expansion. ``. It is recommended to avoid using useless sequence \s in regex to get ready for that change. All the following letters ``gijkmoqyFIJMOTY`` . Note that ``clLpPuU`` are valid ``PRCE`` sequences, and are probably failing for other reasons. 

.. code-block:: php

   <?php
   
   // \y has no meaning. With X option, this leads to a regex compilation error, and a failed test.
   preg_match('/ye\y/', $string);
   preg_match('/ye\y/X', $string);
   
   ?>


See also `Pattern Modifiers <http://php.net/manual/en/reference.pcre.pattern.modifiers.php>`_ and 
         `PHP RFC: PCRE2 migration <https://wiki.php.net/rfc/pcre2-migration>`_.

+-------------+-------------------------------------------+
| Short name  | Php/UnknownPcre2Option                    |
+-------------+-------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`CompatibilityPHP73` |
+-------------+-------------------------------------------+
| Severity    | Minor                                     |
+-------------+-------------------------------------------+
| Time To Fix | Instant (5 mins)                          |
+-------------+-------------------------------------------+



.. _unkown-regex-options:

Unkown Regex Options
####################


Regex support in PHP accepts the following list of options : ``eimsuxADJSUX``. 

All other letter used as option are not supported : depending on the situation, they may be ignored or raise an error.

.. code-block:: php

   <?php
   
   // all options are available
   if (preg_match('/\d+/isA', $string, $results)) { }
   
   // p and h are not regex options, p is double
   if (preg_match('/\d+/php', $string, $results)) { }
   
   ?>


See also `Pattern Modifiers <http://php.net/manual/en/reference.pcre.pattern.modifiers.php>`_

+-------------+------------------------------+
| Short name  | Structures/UnknownPregOption |
+-------------+------------------------------+
| Themes      | :ref:`Analyze`               |
+-------------+------------------------------+
| Severity    | Major                        |
+-------------+------------------------------+
| Time To Fix | Slow (1 hour)                |
+-------------+------------------------------+



.. _unpreprocessed-values:

Unpreprocessed Values
#####################


Preprocessing values is the preparation of values before PHP executes the code. 

There is no macro language in PHP, that prepares the code before compilation, bringing some comfort and short syntax. Most of the time, one uses PHP itself to preprocess data. 

For example : 

.. code-block:: php

   <?php
       $days_en = 'monday,tuesday,wednesday,thursday,friday,saturday,sunday';
       $days_zh = '星期－,星期二,星期三,星期四,星期五,星期六,星期日';
   
       $days = explode(',', $lang === 'en' ? $days_en : $days_zh); 
   ?>


could be written 

.. code-block:: php

   <?php
       if ($lang === 'en') {
           $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
       } else {
           $days = ['星期－', '星期二', '星期三', '星期四', '星期五', '星期六', '星期日'];
       }
   ?>


and avoid preprocessing the string into an array first. 

Preprocessing could be done anytime the script includes all the needed values to process the expression. 



Suggestions
^^^^^^^^^^^

* Preprocess the values and hardcode them in PHP. Do not use PHP to calculate something at the last moment.
* Use already processed values, or cache to avoid calculating the value each hit.
* Create a class that export the data in the right format for every situation, including the developer's comfort.

+-------------+---------------------------------------------------------------------------------------------------+
| Short name  | Structures/Unpreprocessed                                                                         |
+-------------+---------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                    |
+-------------+---------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                             |
+-------------+---------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                  |
+-------------+---------------------------------------------------------------------------------------------------+
| ClearPHP    | `always-preprocess <https://github.com/dseguy/clearPHP/tree/master/rules/always-preprocess.md>`__ |
+-------------+---------------------------------------------------------------------------------------------------+
| Examples    | :ref:`zurmo-structures-unpreprocessed`, :ref:`piwigo-structures-unpreprocessed`                   |
+-------------+---------------------------------------------------------------------------------------------------+



.. _unreachable-class-constant:

Unreachable Class Constant
##########################


Class constants may be unreachable due to visibility configuration. 

Since PHP 7.1, class constants support visibility. Their usage may be restricted to the current class, or ``private``, to classes that extends or are extended by the current class, or ``protected``. They may also be ``public``, just like it was before. 

.. code-block:: php

   <?php
   
   class Foo{
       private const PRIVATE = 1;
               const PUBLIC = 3;
   }
   
   // PHP 7.1- and older
   echo Foo::PUBLIC;
   
   // This is not accessible
   echo Foo::PRIVATE;
   
   ?>


See also `Class Constant <http://php.net/manual/en/language.oop5.constants.php>`_ and 
         `PHP RFC: Support Class Constant Visibility <https://wiki.php.net/rfc/class_const_visibility>`_.


Suggestions
^^^^^^^^^^^

* Make the class constant protected, when the call to the constant is inside a related class.
* Create another constant, that may be accessible
* Make the class constant public

+-------------+-----------------------------+
| Short name  | Classes/UnreachableConstant |
+-------------+-----------------------------+
| Themes      | :ref:`ClassReview`          |
+-------------+-----------------------------+
| Severity    | Major                       |
+-------------+-----------------------------+
| Time To Fix | Slow (1 hour)               |
+-------------+-----------------------------+



.. _unreachable-code:

Unreachable Code
################


Code may be unreachable, because other instructions prevent its reaching. 
For example, it be located after throw, return, `exit() <http://www.php.net/exit>`_, `die() <http://www.php.net/die>`_, goto, `break <http://php.net/manual/en/control-structures.break.php>`_ or `continue <http://php.net/manual/en/control-structures.continue.php>`_ : this way, it cannot be reached, as the previous instruction will divert the engine to another part of the code. 

.. code-block:: php

   <?php
   
   function foo() {
       $a++;
       return $a;
       $b++;      // $b++ can't be reached;
   }
   
   function bar() {
       if ($a) {
           return $a;
       } else {
           return $b;
       }
       $b++;      // $b++ can't be reached;
   }
   
   foreach($a as $b) {
       $c += $b;
       if ($c > 10) {
           continue 1;
       } else {
           $c--;
           continue;
       }
       $d += $e;   // this can't be reached
   }
   
   $a = 1;
   goto B;
   class foo {}    // Definitions are accessible, but not functioncalls
   B: 
   echo $a;
   
   
   ?>


This is dead code, that may be removed.

+-------------+-----------------------------------------------------------------------------------------+
| Short name  | Structures/UnreachableCode                                                              |
+-------------+-----------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Dead code <dead-code>`, :ref:`Suggestions`                        |
+-------------+-----------------------------------------------------------------------------------------+
| Severity    | Major                                                                                   |
+-------------+-----------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                        |
+-------------+-----------------------------------------------------------------------------------------+
| ClearPHP    | `no-dead-code <https://github.com/dseguy/clearPHP/tree/master/rules/no-dead-code.md>`__ |
+-------------+-----------------------------------------------------------------------------------------+



.. _unresolved-catch:

Unresolved Catch
################


Catch clauses do not check for Exception existence. 

Catch clauses check that the emitted expression is of the requested Class, but if that class doesn't exist in the code, the catch clause is always false. This is dead code.

.. code-block:: php

   <?php
   
   try {
       // doSomething()
   } catch {TypoedExxeption $e) { // Do not exist Exception
       // Fix this exception
   } catch {Stdclass $e) {        // Exists, but is not an exception
       // Fix this exception
   } catch {Exception $e) {        // Actual and effective catch
       // Fix this exception
   }
   ?>

+-------------+-------------------------------------------------------------------------------------------------------+
| Short name  | Classes/UnresolvedCatch                                                                               |
+-------------+-------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Dead code <dead-code>`                                                                          |
+-------------+-------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                 |
+-------------+-------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                       |
+-------------+-------------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-unresolved-catch <https://github.com/dseguy/clearPHP/tree/master/rules/no-unresolved-catch.md>`__ |
+-------------+-------------------------------------------------------------------------------------------------------+



.. _unresolved-classes:

Unresolved Classes
##################


The following classes are instantiated in the code, but their definition couldn't be found. 

Check for namespaces and aliases and make sure they are correctly configured.

.. code-block:: php

   <?php
   
   class Foo extends Bar {
       private function foobar() {
           // here, parent is not resolved, as Bar is not defined in the code.
           return parent::$prop;
       }
   }
   
   ?>

+-------------+---------------------------+
| Short name  | Classes/UnresolvedClasses |
+-------------+---------------------------+
| Themes      | :ref:`Analyze`            |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _unresolved-instanceof:

Unresolved Instanceof
#####################


The `instanceof <http://php.net/manual/en/language.operators.type.php>`_ operator doesn't confirm if the compared class exists. 

It checks if an variable is of a specific class. However, if the referenced class doesn't exist, because of a bug, a missed inclusion or a typo, the operator always fails, without a warning. 

.. code-block:: php

   <?php
   
   namespace X {
       class C {}
       
       // This is OK, as C is defined in X
       if ($o instanceof C) { }
   
       // This is not OK, as C is not defined in global
       // instanceof respects namespaces and use expressions
       if ($o instanceof \C) { }
   
       // This is not OK, as undefinedClass
       if ($o instanceof undefinedClass) { }
   
       // This is not OK, as $class is now a full namespace. It actually refers to \c, which doesn't exist
       $class = 'C';
       if ($o instanceof $class) { }
   }
   ?>


Make sure the following classes are well defined.

See also `instanceof <http://php.net/`instanceof <http://php.net/manual/en/language.operators.type.php>`_>`_.

+-------------+-----------------------------------------------------------------------------------------------------------------+
| Short name  | Classes/UnresolvedInstanceof                                                                                    |
+-------------+-----------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Dead code <dead-code>`                                                                    |
+-------------+-----------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                           |
+-------------+-----------------------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                                |
+-------------+-----------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-unresolved-instanceof <https://github.com/dseguy/clearPHP/tree/master/rules/no-unresolved-instanceof.md>`__ |
+-------------+-----------------------------------------------------------------------------------------------------------------+



.. _unresolved-use:

Unresolved Use
##############


The following use instructions cannot be resolved to a class or a namespace. They should be dropped or fixed.

.. code-block:: php

   <?php
   
   namespace A {
       // class B is defined
       class B {}
       // class C is not defined
   }
   
   namespace X/Y {
   
       use A/B;  // This use is valid
       use A/C;  // This use point to nothing.
   
       new B();
       new C();
   }
   
   ?>


Use expression are options for the current namespace. 

See also `Using namespaces: Aliasing/Importing <http://php.net/manual/en/language.namespaces.importing.php>`_.

+-------------+---------------------------------------------------------------------------------------------------+
| Short name  | Namespaces/UnresolvedUse                                                                          |
+-------------+---------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                    |
+-------------+---------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                             |
+-------------+---------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                     |
+-------------+---------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-unresolved-use <https://github.com/dseguy/clearPHP/tree/master/rules/no-unresolved-use.md>`__ |
+-------------+---------------------------------------------------------------------------------------------------+



.. _unserialize-second-arg:

Unserialize Second Arg
######################


Since PHP 7, `unserialize() <http://www.php.net/unserialize>`_ function has a second argument that limits the classes that may be unserialized. In case of a breach, this is limiting the classes accessible from `unserialize() <http://www.php.net/unserialize>`_. 

On way to exploit unserialize, is to make PHP unserialized the data to an available class, may be one that may be auto-loaded.

.. code-block:: php

   <?php
   
   // safe unserialization : only the expected class will be extracted
   $serialized = 'O:7:dbClass:0:{}';
   $var = unserialize($serialized, ['dbClass']);
   $var->connect();
   
   // unsafe unserialization : $var may be of any type that was in the serialized string
   // although, here, this is working well.
   $serialized = 'O:7:dbClass:0:{}';
   $var = unserialize($serialized);
   $var->connect();
   
   // unsafe unserialization : $var is not of the expected type.
   // and, here, this will lead to disaster.
   $serialized = 'O:10:debugClass:0:{}';
   $var = unserialize($serialized);
   $var->connect();
   
   ?>


See also `unserialize() <http://www.php.net/unserialize>`_, 
         `Securely Implementing (De)Serialization in PHP <https://paragonie.com/blog/2016/04/securely-implementing-de-serialization-in-php>`_.


Suggestions
^^^^^^^^^^^

* Add a list of class as second argument of any call to unserialize(). This is valid for PHP 7.0 and later.

+-------------+---------------------------------------------------------------------------------------------+
| Short name  | Security/UnserializeSecondArg                                                               |
+-------------+---------------------------------------------------------------------------------------------+
| Themes      | :ref:`Security`                                                                             |
+-------------+---------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and more recent                                                                |
+-------------+---------------------------------------------------------------------------------------------+
| Severity    | Critical                                                                                    |
+-------------+---------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                             |
+-------------+---------------------------------------------------------------------------------------------+
| Examples    | :ref:`piwigo-security-unserializesecondarg`, :ref:`livezilla-security-unserializesecondarg` |
+-------------+---------------------------------------------------------------------------------------------+



.. _unset-in-foreach:

Unset In Foreach
################


Unset applied to the variables of a ``foreach`` loop are useless, as they are copies and not the actual value. Even if the value is a reference, unsetting it has no effect on the original array : the only effect may be indirect, on elements inside an array, or on properties inside an object.

.. code-block:: php

   <?php
   
   // When unset is useless
   $array = [1, 2, 3];
   foreach($array as $a) {
       unset($a);
   }
   
   print_r($array); // still [1, 2, 3]
   
   foreach($array as $b => &$a) {
       unset($a);
   }
   
   print_r($array); // still [1, 2, 3]
   
   // When unset is useful
   $array = [ [ 'c' => 1] ]; // Array in array
   foreach($array as &$a) {
       unset(&$a['c']);
   }
   
   print_r($array); // now [ ['c' => null] ]
   
   ?>


See also `foreach <http://php.net/manual/en/control-structures.foreach.php>`_.

+-------------+----------------------------------------------+
| Short name  | Structures/UnsetInForeach                    |
+-------------+----------------------------------------------+
| Themes      | :ref:`Dead code <dead-code>`, :ref:`Analyze` |
+-------------+----------------------------------------------+
| Severity    | Major                                        |
+-------------+----------------------------------------------+
| Time To Fix | Quick (30 mins)                              |
+-------------+----------------------------------------------+



.. _unthrown-exception:

Unthrown Exception
##################


These are exceptions that are defined in the code but never thrown. 

.. code-block:: php

   <?php
   
   //This exception is defined but never used in the code.
   class myUnusedException extends \Exception {}
   
   //This exception is defined and used in the code.
   class myUsedException extends \Exception {}
   
   throw new myUsedException('I was called');
   
   ?>


See also `Exceptions <http://php.net/manual/en/language.exceptions.php>`_.

+-------------+-------------------------------------------------------------------------------------------------------------+
| Short name  | Exceptions/Unthrown                                                                                         |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Dead code <dead-code>`                                                                |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                       |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                             |
+-------------+-------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-unthrown-exceptions <https://github.com/dseguy/clearPHP/tree/master/rules/no-unthrown-exceptions.md>`__ |
+-------------+-------------------------------------------------------------------------------------------------------------+



.. _unused-arguments:

Unused Arguments
################


Those arguments are not used in the method or function. 

Unused arguments should be removed in functions : they are just dead code.

Unused argument may have to stay in methods, as the signature is actually defined in the parent class. 

.. code-block:: php

   <?php
   
   // $unused is in the signature, but not used. 
   function foo($unused, $b, $c) {
       return $b + $c;
   }
   ?>

+-------------+---------------------------+
| Short name  | Functions/UnusedArguments |
+-------------+---------------------------+
| Themes      | :ref:`Analyze`            |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _unused-classes:

Unused Classes
##############


The following classes are never explicitly used in the code.

Note that this may be valid in case the current code is a library or framework, since it defines classes that are used by other (unprovided) codes.
Also, this analyzer may find classes that are, in fact, dynamically loaded. 

.. code-block:: php

   <?php
   
   class unusedClasss {}
   class usedClass {}
   
   $y = new usedClass();
   
   ?>

+-------------+------------------------------+
| Short name  | Classes/UnusedClass          |
+-------------+------------------------------+
| Themes      | :ref:`Dead code <dead-code>` |
+-------------+------------------------------+
| Severity    | Major                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _unused-constants:

Unused Constants
################


Those constants are defined in the code but never used. Defining unused constants slow down the application, as they are executed and stored in PHP hashtables. 

.. code-block:: php

   <?php
   
   // const-defined constant
   const USED_CONSTANT  = 0;
   const UNUSED_CONSTANT = 1 + USED_CONSTANT;
   
   // define-defined constant
   define('ANOTHER_UNUSED_CONSTANT', 3);
   
   ?>


It is recommended to comment them out, and only define them when it is necessary.

+-------------+------------------------------+
| Short name  | Constants/UnusedConstants    |
+-------------+------------------------------+
| Themes      | :ref:`Dead code <dead-code>` |
+-------------+------------------------------+
| Severity    | Minor                        |
+-------------+------------------------------+
| Time To Fix | Instant (5 mins)             |
+-------------+------------------------------+



.. _unused-functions:

Unused Functions
################


The functions below are unused. They look like dead code.

Recursive functions, level 1, are detected : they are only reported when a call from outside the function is made. Recursive functions calls of higher level (A calls B calls A) are not handled.

.. code-block:: php

   <?php
   
   function used() {}
   // The 'unused' function is defined but never called
   function unused() {}
   
   // The 'used' function is called at least once
   used();
   
   ?>

+-------------+------------------------------+
| Short name  | Functions/UnusedFunctions    |
+-------------+------------------------------+
| Themes      | :ref:`Dead code <dead-code>` |
+-------------+------------------------------+
| Severity    | Minor                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



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

+-------------+-------------------------+
| Short name  | Structures/UnusedGlobal |
+-------------+-------------------------+
| Themes      | :ref:`Analyze`          |
+-------------+-------------------------+
| Severity    | Minor                   |
+-------------+-------------------------+
| Time To Fix | Quick (30 mins)         |
+-------------+-------------------------+



.. _unused-inherited-variable-in-closure:

Unused Inherited Variable In Closure
####################################


Some closures forgot to make usage of inherited variables.

`Closure <http://php.net/manual/en/class.closure.php>`_ have two separate set of incoming variables : the arguments (between parenthesis) and the inherited variables, in the 'use' clause. Inherited variables are extracted from the local environment at creation time, and keep their value until execution. 

The reported closures are requesting some local variables, but do not make any usage of them. They may be considered as dead code.  

.. code-block:: php

   <?php
   
   // In this closure, $y is forgotten, but $u is used.
   $a = function ($y) use ($u) { return $u; };
   
   // In this closure, $u is forgotten
   $a = function ($y, $z) use ($u) { return $u; };
   
   ?>


See also `Anonymous functions <http://php.net/manual/en/functions.anonymous.php>`_.

+-------------+----------------------------------------------+
| Short name  | Functions/UnusedInheritedVariable            |
+-------------+----------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Dead code <dead-code>` |
+-------------+----------------------------------------------+
| Severity    | Major                                        |
+-------------+----------------------------------------------+
| Time To Fix | Quick (30 mins)                              |
+-------------+----------------------------------------------+



.. _unused-interfaces:

Unused Interfaces
#################


Some interfaces are defined but not used. 

They should be removed, as they are probably dead code.

.. code-block:: php

   <?php
   
   interface used {}
   interface unused {}
   
   // Used by implementation
   class c implements used {}
   
   // Used by extension
   interface j implements used {}
   
   $x = new c;
   
   // Used in a instanceof
   var_dump($x instanceof used); 
   
   // Used in a typehint
   function foo(Used $x) {}
   
   ?>

+-------------+--------------------------------------------------+
| Short name  | Interfaces/UnusedInterfaces                      |
+-------------+--------------------------------------------------+
| Themes      | :ref:`Dead code <dead-code>`, :ref:`Suggestions` |
+-------------+--------------------------------------------------+
| Severity    | Minor                                            |
+-------------+--------------------------------------------------+
| Time To Fix | Instant (5 mins)                                 |
+-------------+--------------------------------------------------+



.. _unused-label:

Unused Label
############


Some labels have been defined in the code, but they are not used. They may be removed as they are dead code.

.. code-block:: php

   <?php
   
   $a = 0;
   A: 
   
       ++$a;
       
       // A loop. A: is used
       if ($a < 10) { goto A; }
   
   // B is never called explicitely. This is useless.
   B: 
   
   ?>


There is no analysis for undefined goto call, as PHP checks that goto has a destination label at compile time : 

See also `Goto <http://php.net/manual/en/control-structures.goto.php>`_.

+-------------+------------------------------+
| Short name  | Structures/UnusedLabel       |
+-------------+------------------------------+
| Themes      | :ref:`Dead code <dead-code>` |
+-------------+------------------------------+
| Severity    | Minor                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _unused-methods:

Unused Methods
##############


Those methods are never called as methods. 

They are probably dead code, unless they are called dynamically.

.. code-block:: php

   <?php
   
   class foo {
       public function used() {
           $this->used();
       }
   
       public function unused() {
           $this->used();
       }
   }
   
   class bar extends foo {
       public function some() {
           $this->used();
       }
   }
   
   $a = new foo();
   $a->used();
   
   ?>

+-------------+------------------------------+
| Short name  | Classes/UnusedMethods        |
+-------------+------------------------------+
| Themes      | :ref:`Dead code <dead-code>` |
+-------------+------------------------------+
| Severity    | Minor                        |
+-------------+------------------------------+
| Time To Fix | Slow (1 hour)                |
+-------------+------------------------------+



.. _unused-private-methods:

Unused Private Methods
######################


Private methods that are not used are dead code. 

Private methods are reserved for the defining class. Thus, they must be used with the current class, with ``$this`` or ``self\:\:``.

.. code-block:: php

   <?php
   
   class Foo {
       // Those methods are used
       private function method() {}
       private static function staticMethod() {}
   
       // Those methods are not used
       private function unusedMethod() {}
       private static function staticUnusedMethod() {}
       
       public function bar() {
           self::staticMethod();
           $this->method();
       }
   }
   
   ?>

+-------------+------------------------------+
| Short name  | Classes/UnusedPrivateMethod  |
+-------------+------------------------------+
| Themes      | :ref:`Dead code <dead-code>` |
+-------------+------------------------------+
| Severity    | Minor                        |
+-------------+------------------------------+
| Time To Fix | Quick (30 mins)              |
+-------------+------------------------------+



.. _unused-private-properties:

Unused Private Properties
#########################


Unused static properties should be removed. 

Unused private properties are dead code. They are usually leftovers of development or refactorisation : they used to have a mission, but are now left. 

Being private, those properties are only accessible to the current class or trait. As such, validating the 

.. code-block:: php

   <?php
   
   class foo {
       // This is a used property (see bar method)
       private $used = 1;
   
       // This is an unused property
       private $unused = 2;
       
       function bar($a) {
           $this->used += $a;
           
           return $this->used;
       }
   }
   
   ?>




Suggestions
^^^^^^^^^^^

* Remove the property altogether
* Check if the property wasn't forgotten in the rest of the class
* Check if the property is correctly named
* Change the visibility to protected or public : may be a visibility refactoring was too harsh

+-------------+----------------------------------------------------------------------------------------------+
| Short name  | Classes/UnusedPrivateProperty                                                                |
+-------------+----------------------------------------------------------------------------------------------+
| Themes      | :ref:`Dead code <dead-code>`                                                                 |
+-------------+----------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                        |
+-------------+----------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                              |
+-------------+----------------------------------------------------------------------------------------------+
| Examples    | :ref:`openemr-classes-unusedprivateproperty`, :ref:`phpadsnew-classes-unusedprivateproperty` |
+-------------+----------------------------------------------------------------------------------------------+



.. _unused-protected-methods:

Unused Protected Methods
########################


The following protected methods are unused in children class. As such, they may be considered for being private.

Methods reported by this analysis are not used by children, yet they are protected.

.. code-block:: php

   <?php
   
   class Foo {
       // This method is not used
       protected function unusedBar() {}
       protected function usedInFoo() {}
       protected function usedInFooFoo() {}
       
       public function bar2() {
           // some code
           $this->usedInFoo();
       }
   }
   
   class FooFoo extends Foo {
       protected function bar() {}
       
       public function bar2() {
           // some code
           $this->usedInFooFoo();
       }
   }
   
   class someOtherClass {
       protected function bar() {
           // This is not related to foo.
           $this->unusedbar();
       }
   }
   
   ?>


No usage of those methods were found. 

This analysis is impacted by dynamic method calls.

+-------------+--------------------------------+
| Short name  | Classes/UnusedProtectedMethods |
+-------------+--------------------------------+
| Themes      | :ref:`Dead code <dead-code>`   |
+-------------+--------------------------------+
| Severity    | Major                          |
+-------------+--------------------------------+
| Time To Fix | Slow (1 hour)                  |
+-------------+--------------------------------+



.. _unused-returned-value:

Unused Returned Value
#####################


The function called returns a value, which is ignored. 

Usually, this is a sign of dead code, or a missed check on the results of the functioncall. At times, it may be a valid call if the function has voluntarily no return value. 

It is recommended to add a check on the return value, or remove the call. 

.. code-block:: php

   <?php
   
   // simplest form
   function foo() {
       return 1;
   }
   
   foo();
   
   // In case of multiple return, any one that returns something means that return value is meaningful
   function bar() {
       if (rand(0, 1)) {
           return 1;
       } else {
           return ;
       }
   }
   
   bar();
   
   ?>


Note that this analysis ignores functions that return void (same meaning that PHP 7.1 : return ; or no return in the function body).

+-------------+----------------------------------------------+
| Short name  | Functions/UnusedReturnedValue                |
+-------------+----------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Dead code <dead-code>` |
+-------------+----------------------------------------------+
| Severity    | Minor                                        |
+-------------+----------------------------------------------+
| Time To Fix | Quick (30 mins)                              |
+-------------+----------------------------------------------+



.. _unused-use:

Unused Use
##########


Unused use statements. They may be removed, as they clutter the code and slows PHP by forcing it to search in this list for nothing.

.. code-block:: php

   <?php
   
   use A as B; // Used in a new call.
   use Unused; // Never used. May be removed
   
   $a = new B();
   
   ?>

+-------------+---------------------------------------------------------------------------------------------+
| Short name  | Namespaces/UnusedUse                                                                        |
+-------------+---------------------------------------------------------------------------------------------+
| Themes      | :ref:`Dead code <dead-code>`                                                                |
+-------------+---------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                       |
+-------------+---------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                            |
+-------------+---------------------------------------------------------------------------------------------+
| ClearPHP    | `no-useless-use <https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-use.md>`__ |
+-------------+---------------------------------------------------------------------------------------------+



.. _unusual-case-for-php-functions:

Unusual Case For PHP Functions
##############################


Usually, PHP functions are written all in lower case.

.. code-block:: php

   <?php
   
   // All uppercases PHP functions
   ECHO STRTOLOWER('This String');
   
   ?>

+-------------+------------------------------------------------+
| Short name  | Php/UpperCaseFunction                          |
+-------------+------------------------------------------------+
| Themes      | :ref:`Coding Conventions <coding-conventions>` |
+-------------+------------------------------------------------+
| Severity    | Minor                                          |
+-------------+------------------------------------------------+
| Time To Fix | Instant (5 mins)                               |
+-------------+------------------------------------------------+



.. _upload-filename-injection:

Upload Filename Injection
#########################


When receiving a file via Upload, it is recommended to store it under a self-generated name. Any storage that uses the original filename, or even a part of it may be vulnerable to injections.

.. code-block:: php

   <?php
   
   // Security error ! the $_FILES['upload']['filename'] is provided by the sender.
   // 'a.<script>alert(\'a\')</script>'; may lead to a HTML injection.
   $extension = substr( strrchr($_FILES['upload']['name'], '.') ,1);
   if (!in_array($extension, array('gif', 'jpeg', 'jpg')) { 
       // process error
       continue;
   }
   // Md5 provides a name without special characters
   $name = md5($_FILES['upload']['filename']);
   if(@move_uploaded_file($_FILES['upload']['tmp_name'], '/var/no-www/upload/'.$name.'.'.$extension)) {
       safeStoring($name.'.'.$extension, $_FILES['upload']['filename']);
   }
   
   // Security error ! the $_FILES['upload']['filename'] is provided by the sender.
   if(@move_uploaded_file($_FILES['upload']['tmp_name'], $_FILES['upload']['filename'])) {
       safeStoring($_FILES['upload']['filename']);
   }
   
   // Security error ! the $_FILES['upload']['filename'] is provided by the sender.
   // 'a.<script>alert('a')</script>'; may lead to a HTML injection.
   $extension = substr( strrchr($_FILES['upload']['name'], '.') ,1);
   $name = md5($_FILES['upload']['filename']);
   if(@move_uploaded_file($_FILES['upload']['tmp_name'], $name.'.'.$extension)) {
       safeStoring($name.'.'.$extension, $_FILES['upload']['filename']);
   }
   
   ?>


It is highly recommended to validate any incoming file, generate a name for it, and store the result in a folder outside the web folder. Also, avoid accepting PHP scripts, if possible.

See also `[CVE-2017-6090] <https://cxsecurity.com/issue/WLB-2017100031>`_, 
`CWE-616: Incomplete Identification of Uploaded File Variables <https://cwe.mitre.org/data/definitions/616.html>`_, 
`Why File Upload Forms are a Major Security Threat <https://www.acunetix.com/websitesecurity/upload-forms-threat/>`_.

+-------------+----------------------------------+
| Short name  | Security/UploadFilenameInjection |
+-------------+----------------------------------+
| Themes      | :ref:`Security`                  |
+-------------+----------------------------------+
| Severity    | Major                            |
+-------------+----------------------------------+
| Time To Fix | Instant (5 mins)                 |
+-------------+----------------------------------+



.. _use-===-null:

Use === null
############


It is faster to use === null instead of `is_null() <http://www.php.net/is_null>`_.

.. code-block:: php

   <?php
   
   // Operator === is fast
   if ($a === null) {
   
   }
   
   // Function call is slow 
   if (is_null($a)) {
   
   }
   
   
   ?>

+-------------+---------------------------------------------------------------------------------------------------------------------+
| Short name  | Php/IsnullVsEqualNull                                                                                               |
+-------------+---------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                                      |
+-------------+---------------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                               |
+-------------+---------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                     |
+-------------+---------------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `avoid-those-slow-functions <https://github.com/dseguy/clearPHP/tree/master/rules/avoid-those-slow-functions.md>`__ |
+-------------+---------------------------------------------------------------------------------------------------------------------+



.. _use-basename-suffix:

Use Basename Suffix
###################


`basename() <http://www.php.net/basename>`_ will remove extension when it is provided. The second argument will be removed from the name of the file.

.. code-block:: php

   <?php
   
   $path = 'phar:///path/to/file.php';
   
   // Don't forget the . 
   $filename = basename($path, '.php');
   
   // Too much work for this
   $filename = substr(basename($path), 0, -4);
   
   ?>


Using `basename() <http://www.php.net/basename>`_ instead of substr() or else, makes the intention clear.

See also `basename <http://www.php.net/basename>`_.

+------------+---------------------------+
| Short name | Structures/BasenameSuffix |
+------------+---------------------------+
| Themes     | :ref:`Suggestions`        |
+------------+---------------------------+



.. _use-class-operator:

Use Class Operator
##################


Use ``\:\:class`` to hardcode class names, instead of strings.

This is actually faster than strings, which are parsed at execution time, while ``\:\:class`` is compiled, making it faster to execute. 

It is also capable to handle aliases, making the code easier to maintain. 

.. code-block:: php

   <?php
   
   namespace foo\bar;
   
   use foo\bar\X as B;
   
   class X {}
   
   $className = '\foo\bar\X';
   
   $className = foo\bar\X::class;
   
   $className = B\X;
   
   $object = new $className;
   
   ?>


This is not possible when building the name of the class with concatenation.

This is a micro-optimization.

+-------------+-------------------------------------+
| Short name  | Classes/UseClassOperator            |
+-------------+-------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Performances` |
+-------------+-------------------------------------+
| Severity    | Minor                               |
+-------------+-------------------------------------+
| Time To Fix | Instant (5 mins)                    |
+-------------+-------------------------------------+



.. _use-const-and-functions:

Use Const And Functions
#######################


Since PHP 5.6 it is possible to import specific functions or constants from other namespaces.

.. code-block:: php

   <?php
   
   namespace A {
       const X = 1;
       function foo() { echo __FUNCTION__; }
   }
   
   namespace My{
       use function A\foo;
       use constant A\X;
   
       echo foo(X);
   }
   
   ?>


See also `Using namespaces: Aliasing/Importing <http://php.net/manual/en/language.namespaces.importing.php>`_.

+-------------+---------------------------------------------------------------------------------+
| Short name  | Namespaces/UseFunctionsConstants                                                |
+-------------+---------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55` |
+-------------+---------------------------------------------------------------------------------+
| Php Version | With PHP 5.6 and more recent                                                    |
+-------------+---------------------------------------------------------------------------------+
| Severity    | Major                                                                           |
+-------------+---------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                   |
+-------------+---------------------------------------------------------------------------------+



.. _use-constant-as-arguments:

Use Constant As Arguments
#########################


Some methods and functions are defined to be used with constants as arguments. Those constants are made to be meaningful and readable, keeping the code maintenable. It is recommended to use such constants as soon as they are documented.

.. code-block:: php

   <?php
   
   // Turn off all error reporting
   // 0 and -1 are accepted 
   error_reporting(0);
   
   // Report simple running errors
   error_reporting(E_ERROR | E_WARNING | E_PARSE);
   
   // The first argument can be one of INPUT_GET, INPUT_POST, INPUT_COOKIE, INPUT_SERVER, or INPUT_ENV.
   $search_html = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);
   
   // sort accepts one of SORT_REGULAR, SORT_NUMERIC, SORT_STRING, SORT_LOCALE_STRING, SORT_NATURAL
   // SORT_FLAG_CASE may be added, and combined with SORT_STRING or SORT_NATURAL
   sort($fruits);
   
   ?>

+-------------+----------------------------------------------------------------------------------------------------+
| Short name  | Functions/UseConstantAsArguments                                                                   |
+-------------+----------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                     |
+-------------+----------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                              |
+-------------+----------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                    |
+-------------+----------------------------------------------------------------------------------------------------+
| Examples    | :ref:`tikiwiki-functions-useconstantasarguments`, :ref:`shopware-functions-useconstantasarguments` |
+-------------+----------------------------------------------------------------------------------------------------+



.. _use-count-recursive:

Use Count Recursive
###################


The code could use the recursive version of count.

The second argument of count, when set to ``COUNT_RECURSIVE``, count recursively the elements. It also counts the elements themselves. 

.. code-block:: php

   <?php
   
   $array = array( array(1,2,3), array(4,5,6));
   
   print (count($array, COUNT_RECURSIVE) - count($array, COUNT_NORMAL));
   
   $count = 0;
   foreach($array as $a) {
       $count += count($a);
   }
   print $count;
   
   ?>


See also `count <http://php.net/count>`_.

+-------------+------------------------------+
| Short name  | Structures/UseCountRecursive |
+-------------+------------------------------+
| Themes      | :ref:`Suggestions`           |
+-------------+------------------------------+
| Severity    | Minor                        |
+-------------+------------------------------+
| Time To Fix | Slow (1 hour)                |
+-------------+------------------------------+



.. _use-instanceof:

Use Instanceof
##############


The ``instanceof`` operator is a more precise alternative to ``is_object()``. It is also faster.

`instanceof <http://php.net/manual/en/language.operators.type.php>`_ checks for an variable to be of a class or its parents or the interfaces it implements. 
Once ``instanceof`` has been used, the actual attributes available (properties, constants, methods) are known, unlike with ``is_object()``.

Last, ``instanceof`` may be upgraded to Typehint, by moving it to the method signature. 

.. code-block:: php

   <?php
   
   class Foo {
   
       // Don't use is_object
       public function bar($o) {
           if (!is_object($o)) { return false; } // Classic argument check
           return $o->method();
       }
   
       // use instanceof
       public function bar($o) {
           if ($o instanceof myClass) {  // Now, we know which methods are available
               return $o->method();
           }
           
           return false; } // Default behavior
       }
   
       // use of typehinting
       // in case $o is not of the right type, exception is raised automatically
       public function bar(myClass $o) {
           return $o->method();
       }
   }
   
   ?>


``instanceof`` and ``is_object()`` may not be always interchangeable. Consider using `isset() <http://www.php.net/isset>`_ on a known property for a simple check on objects. You may also consider `is_string() <http://www.php.net/is_string>`_, `is_integer() <http://www.php.net/is_integer>`_ or `is_scalar() <http://www.php.net/is_scalar>`_, in particular instead of ``!`is_object() <http://www.php.net/is_object>`_``.

The ``instanceof`` operator is also faster than the ``is_object()`` functioncall. 

See also `Type Operators <http://php.net/manual/en/language.operators.type.php#language.operators.type>`_ and 
         `is_object <http://php.net/manual/en/function.is-object.php>`_.


Suggestions
^^^^^^^^^^^

* Use instanceof and remove is_object()
* Create a high level interface to check a whole family of classes, instead of testing them individually
* Use typehint when possible
* Avoid mixing scalar types and objects in the same variable

+-------------+-----------------------------------------------------------------------------+
| Short name  | Classes/UseInstanceof                                                       |
+-------------+-----------------------------------------------------------------------------+
| Themes      | none                                                                        |
+-------------+-----------------------------------------------------------------------------+
| Severity    | Major                                                                       |
+-------------+-----------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                             |
+-------------+-----------------------------------------------------------------------------+
| Examples    | :ref:`teampass-classes-useinstanceof`, :ref:`zencart-classes-useinstanceof` |
+-------------+-----------------------------------------------------------------------------+



.. _use-list-with-foreach:

Use List With Foreach
#####################


`Foreach() <http://php.net/manual/en/control-structures.foreach.php>`_ structures accepts list() as blind key. If the loop-value is an array with a fixed structure, it is possible to extract the values directly into variables with explicit names.

.. code-block:: php

   <?php
   
   // Short way to assign variables
   // Works on PHP 7.1, where list() accepts keys.
   foreach($names as list('first' => $first, 'last' => $last)) {
       doSomething($first, $last);
   }
   
   // Short way to assign variables
   // Works on all PHP versions with numerically indexed arrays.
   foreach($names as list($first, $last)) {
       doSomething($first, $last);
   }
   
   // Long way to assign variables
   foreach($names as $name) {
       $first = $name['first'];
       $last = $name['last'];
       
       doSomething($first, $last);
   }
   
   ?>


See also `list <http://php.net/manual/en/function.list.php>`_ and `foreach <http://php.net/manual/en/control-structures.foreach.php>`_.

+-------------+------------------------------------------------+
| Short name  | Structures/UseListWithForeach                  |
+-------------+------------------------------------------------+
| Themes      | :ref:`Suggestions`                             |
+-------------+------------------------------------------------+
| Severity    | Minor                                          |
+-------------+------------------------------------------------+
| Time To Fix | Quick (30 mins)                                |
+-------------+------------------------------------------------+
| Examples    | :ref:`mediawiki-structures-uselistwithforeach` |
+-------------+------------------------------------------------+



.. _use-lower-case-for-parent,-static-and-self:

Use Lower Case For Parent, Static And Self
##########################################


The special parent, static and self keywords needed to be lowercase to be useable. This was fixed in PHP 5.5; otherwise, they would yield a 'PHP Fatal error:  Class 'PARENT' not found'.

parent, static and self are traditionally written in lowercase only. Mixed case and Upper case are both valid, though.

.. code-block:: php

   <?php
   
   class foo {
       const aConstante = 233;
       
       function method() {
           // Wrong case, error with PHP 5.4.* and older
           echo SELF::aConstante;
           
           // Always right. 
           echo self::aConstante;
       }
   }
   
   ?>


Until PHP 5.5, non-lowercase version of those keywords are generating a bug.

+-------------+------------------------------------------------------+
| Short name  | Php/CaseForPSS                                       |
+-------------+------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP53` |
+-------------+------------------------------------------------------+
| Php Version | With PHP 5.5 and older                               |
+-------------+------------------------------------------------------+
| Severity    | Minor                                                |
+-------------+------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                     |
+-------------+------------------------------------------------------+



.. _use-named-boolean-in-argument-definition:

Use Named Boolean In Argument Definition
########################################


Boolean in argument definitions is confusing. 

It is recommended to use explicit constant names, instead. They are more readable. They also allow for easy replacement when the code evolve and has to replace those booleans by strings. This works even also with classes, and class constants.

.. code-block:: php

   <?php
   
   function flipImage($im, $horizontal = NO_HORIZONTAL_FLIP, $vertical = NO_VERTICAL_FLIP) { }
   
   // with constants
   const HORIZONTAL_FLIP = true;
   const NO_HORIZONTAL_FLIP = true;
   const VERTICAL_FLIP = true;
   const NO_VERTICAL_FLIP = true;
   
   rotateImage($im, HORIZONTAL_FLIP, NO_VERTICAL_FLIP);
   
   
   // without constants 
   function flipImage($im, $horizontal = false, $vertical = false) { }
   
   rotateImage($im, true, false);
   
   ?>


See also `Flag Argument <https://martinfowler.com/bliki/FlagArgument.html>`_, to avoid boolean altogether.

+-------------+--------------------------------+
| Short name  | Functions/AvoidBooleanArgument |
+-------------+--------------------------------+
| Themes      | :ref:`Analyze`                 |
+-------------+--------------------------------+
| Severity    | Minor                          |
+-------------+--------------------------------+
| Time To Fix | Slow (1 hour)                  |
+-------------+--------------------------------+



.. _use-nullable-type:

Use Nullable Type
#################


The code uses nullable type, available since PHP 7.1.

Nullable Types are an option to type hint : they allow the passing value to be null, or another type. 

According to the authors of the feature : 'It is common in many programming languages including PHP to allow a variable to be of some type or null. This null often indicates an error or lack of something to return.'

.. code-block:: php

   <?php
   
   function foo(?string $a = 'abc') : ?string {
       return $a.b;
   }
   
   ?>


See also `Type declarations <http://php.net/manual/en/functions.arguments.php#functions.arguments.type-declaration>`_ and 
         `PHP RFC: Nullable Types <https://wiki.php.net/rfc/nullable_types>`_.

+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Short name  | Php/UseNullableType                                                                                                                   |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP70`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.1 and more recent                                                                                                          |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                                                 |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                                       |
+-------------+---------------------------------------------------------------------------------------------------------------------------------------+



.. _use-php-object-api:

Use PHP Object API
##################


OOP API is the modern version of the PHP API.

When PHP offers the alternative between procedural and OOP api for the same features, it is recommended to use the OOP API. 

Often, this least to more compact code, as methods are shorter, and there is no need to bring the resource around. Lots of new extensions are directly written in OOP form too.

OOP / procedural alternatives are available for `mysqli <http://php.net/manual/en/book.mysqli.php>`_, `tidy <http://php.net/manual/en/book.tidy.php>`_, `cairo <http://php.net/manual/en/book.cairo.php>`_, `finfo <http://php.net/manual/en/book.fileinfo.php>`_, and some others.

.. code-block:: php

   <?php
   /// OOP version
   $mysqli = new mysqli(localhost, my_user, my_password, world);
   
   /* check connection */
   if ($mysqli->connect_errno) {
       printf(Connect failed: %s\n, $mysqli->connect_error);
       exit();
   }
   
   /* Create table doesn't return a resultset */
   if ($mysqli->query(CREATE TEMPORARY TABLE myCity LIKE City) === TRUE) {
       printf(Table myCity successfully created.\n);
   }
   
   /* Select queries return a resultset */
   if ($result = $mysqli->query(SELECT Name FROM City LIMIT 10)) {
       printf(Select returned %d rows.\n, $result->num_rows);
   
       /* free result set */
       $result->close();
   }
   ?>


.. code-block:: php

   <?php
   /// Procedural version
   $link = mysqli_connect(localhost, my_user, my_password, world);
   
   /* check connection */
   if (mysqli_connect_errno()) {
       printf(Connect failed: %s\n, mysqli_connect_error());
       exit();
   }
   
   /* Create table doesn't return a resultset */
   if (mysqli_query($link, CREATE TEMPORARY TABLE myCity LIKE City) === TRUE) {
       printf(Table myCity successfully created.\n);
   }
   
   ?>

+-------------+---------------------------------------------------------------------------------------------+
| Short name  | Php/UseObjectApi                                                                            |
+-------------+---------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                              |
+-------------+---------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                       |
+-------------+---------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                               |
+-------------+---------------------------------------------------------------------------------------------+
| ClearPHP    | `use-object-api <https://github.com/dseguy/clearPHP/tree/master/rules/use-object-api.md>`__ |
+-------------+---------------------------------------------------------------------------------------------+



.. _use-php7-encapsed-strings:

Use PHP7 Encapsed Strings
#########################


PHP 7 has optimized the handling of double-quoted strings. In particular, double-quoted strings are much less memory hungry than classic concatenations. 

PHP allocate memory at the end of the double-quoted string, making only one call to the allocator. On the other hand, concatenations are allocated each time they include dynamic content, leading to higher memory consumption. 

.. code-block:: php

   <?php
   
   $bar = 'bar';
    
   /* PHP 7 optimized this */
   $a = "foo and $bar";
    
   /* This is PHP 5 code (aka, don't use it) */
   $a = 'foo and ' . $bar;
   
   // Constants can't be used with double quotes
   $a = 'foo and ' . __DIR__;
   $a = foo and __DIR__; // __DIR__ is not interpolated
   
   ?>


Concatenations are still needed with constants, static constants, magic constants, functionstatic properties or static methods. 

See also `PHP 7 performance improvements (3/5): Encapsed strings optimization <https://blog.blackfire.io/php-7-performance-improvements-encapsed-strings-optimization.html>`_.

+------------+----------------------------------+
| Short name | Performances/PHP7EncapsedStrings |
+------------+----------------------------------+
| Themes     | :ref:`Performances`              |
+------------+----------------------------------+



.. _use-pathinfo:

Use Pathinfo
############


Use `pathinfo() <http://www.php.net/pathinfo>`_ function instead of string manipulations.

`pathinfo() <http://www.php.net/pathinfo>`_ is more efficient and readable and string functions.

.. code-block:: php

   <?php
   
   $filename = '/path/to/file.php';
   
   // With pathinfo();
   $details = pathinfo($filename);
   print $details['extension'];  // also capture php
   
   // With string functions (other solutions possible)
   $ext = substr($filename, - strpos(strreverse($filename), '.')); // Capture php
   
   ?>


When the path contains UTF-8 characters, `pathinfo() <http://www.php.net/pathinfo>`_ may strip them. There, string functions are necessary.

+-------------+-----------------+
| Short name  | Php/UsePathinfo |
+-------------+-----------------+
| Themes      | :ref:`Analyze`  |
+-------------+-----------------+
| Severity    | Minor           |
+-------------+-----------------+
| Time To Fix | Quick (30 mins) |
+-------------+-----------------+



.. _use-positive-condition:

Use Positive Condition
######################


Whenever possible, use a positive condition. 

Positive conditions are easier to understand, and lead to less understanding problems.
Negative conditions are not reported when else is not present. 

.. code-block:: php

   <?php
   
   // This is a positive condition
   if ($a == 'b') {
       doSomething();
   } else {
       doSomethingElse();
   }
   
   if (!empty($a)) {
       doSomething();
   } else {
       doSomethingElse();
   }
   
   // This is a negative condition
   if ($a == 'b') {
       doSomethingElse();
   } else {
       doSomething();
   }
   
   // No need to force $a == 'b' with empty else
   if ($a != 'b') {
       doSomethingElse();
   } 
   
   
   ?>



Suggestions
^^^^^^^^^^^

* Invert the code in the if branches, and the condition

+-------------+------------------------------------------------------------------------------------------------------+
| Short name  | Structures/UsePositiveCondition                                                                      |
+-------------+------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                       |
+-------------+------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                |
+-------------+------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`spip-structures-usepositivecondition`, :ref:`expressionengine-structures-usepositivecondition` |
+-------------+------------------------------------------------------------------------------------------------------+



.. _use-system-tmp:

Use System Tmp
##############


It is recommended to avoid hardcoding the temporary file. It is better to rely on the system's temporary folder, which is accessible with `sys_get_temp_dir() <http://www.php.net/sys_get_temp_dir>`_.

.. code-block:: php

   <?php
   
   // Where the tmp is : 
   file_put_contents(sys_get_temp_dir().'/tempFile.txt', $content);
   
   
   // Avoid hard-coding tmp folder : 
   // On Linux-like systems
   file_put_contents('/tmp/tempFile.txt', $content);
   
   // On Windows systems
   file_put_contents('C:\WINDOWS\TEMP\tempFile.txt', $content);
   
   ?>

+-------------+-------------------------+
| Short name  | Structures/UseSystemTmp |
+-------------+-------------------------+
| Themes      | :ref:`Analyze`          |
+-------------+-------------------------+
| Severity    | Major                   |
+-------------+-------------------------+
| Time To Fix | Slow (1 hour)           |
+-------------+-------------------------+



.. _use-the-blind-var:

Use The Blind Var
#################


When in a loop, it is faster to rely on the blind var, rather than the original source.

When the key is referenced in the foreach loop, it is faster to use the available container to access a value for reading.

Note that it is also faster to use the value with a reference to handle the writings.

.. code-block:: php

   <?php
   
   // Reaching $source[$key] via $value is faster
   foreach($source as $key => $value) {
       $coordinates = array('x' => $value[0],
                            'y' => $value[1]);
   }
   
   // Reaching $source[$key] via $source is slow
   foreach($source as $key => $value) {
       $coordinates = array('x' => $source[$key][0],
                            'y' => $source[$key][1]);
   }
   
   ?>

+-------------+--------------------------+
| Short name  | Performances/UseBlindVar |
+-------------+--------------------------+
| Themes      | :ref:`Performances`      |
+-------------+--------------------------+
| Severity    | Minor                    |
+-------------+--------------------------+
| Time To Fix | Instant (5 mins)         |
+-------------+--------------------------+



.. _use-with-fully-qualified-name:

Use With Fully Qualified Name
#############################


Use statement doesn't require a fully qualified name.

PHP manual recommends not to use fully qualified name (starting with \) when using the 'use' statement : they are "the leading backslash is unnecessary and not recommended, as import names must be fully qualified, and are not processed relative to the current namespace".

.. code-block:: php

   <?php
   
   // Recommended way to write a use statement.
   use  A\B\C\D as E;
   
   // No need to use the initial \
   use \A\B\C\D as F;
   
   ?>

+-------------+----------------------------------------------------------------+
| Short name  | Namespaces/UseWithFullyQualifiedNS                             |
+-------------+----------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Coding Conventions <coding-conventions>` |
+-------------+----------------------------------------------------------------+
| Severity    | Minor                                                          |
+-------------+----------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                  |
+-------------+----------------------------------------------------------------+



.. _use-const:

Use const
#########


The const keyword may be used to define constant, just like the `define() <http://www.php.net/define>`_ function. 

When defining a constant, it is recommended to use 'const' when the features of the constant are not dynamical (name or value are known at compile time). 
This way, constant will be defined at compile time, and not at execution time. 

.. code-block:: php

   <?php
     //Do
     const A = 1;
     // Don't 
     define('A', 1);
     
   ?>


`define() <http://www.php.net/define>`_ function is useful when the constant is not known at compile time, or when case sensitivity is necessary.

.. code-block:: php

   <?php
     // Read $a in database or config file
     define('A', $a);
   
     // Read $a in database or config file
     define('B', 1, true);
     echo b;
   ?>

+-------------+----------------------------------------------------------------+
| Short name  | Constants/ConstRecommended                                     |
+-------------+----------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Coding Conventions <coding-conventions>` |
+-------------+----------------------------------------------------------------+
| Severity    | Minor                                                          |
+-------------+----------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                  |
+-------------+----------------------------------------------------------------+



.. _use-is\_countable:

Use is_countable
################


is_countable() checks if a variables holds a value that can be counted. It is recommended to use it before calling `count() <http://www.php.net/count>`_.

is_countable() accepts arrays and object whose class implements \countable.

.. code-block:: php

   <?php
   
   function foo($arg) {
       if (!is_countable($arg)) {
           // $arg cannot be passed to count()
           return 0
       }
       return count($arg);
   }
   
   function bar($arg) {
       if (!is_array($arg) and !$x instanceof \Countable) {
           // $arg cannot be passed to count()
           return 0
       }
   
       return count($arg);
   }
   
   ?>


See also `PHP RFC: is_countable <https://wiki.php.net/rfc/is-countable>`_.

+-------------+------------------------------+
| Short name  | Php/CouldUseIsCountable      |
+-------------+------------------------------+
| Themes      | :ref:`Suggestions`           |
+-------------+------------------------------+
| Php Version | With PHP 7.3 and more recent |
+-------------+------------------------------+



.. _use-json\_decode()-options:

Use json_decode() Options
#########################


json_decode() returns objects by default, unless the second argument is set to ``TRUE`` or ``JSON_OBJECT_AS_ARRAY``. Then, it returns arrays.

Avoid casting the returned value from json_decode(), and use the second argument to directly set the correct type.

.. code-block:: php

   <?php
   
   $json = '{a:b}';
   
   // Good syntax
   $array = json_decode($json, JSON_OBJECT_AS_ARRAY);
   
   // GoToo much work
   $array = (array) json_decode($json);
   
   ?>


Note that all objects will be turned into arrays, recursively. If you're expecting an array of objects, don't use the ``JSON_OBJECT_AS_ARRAY`` constant, and change your JSON code.

Note that ``JSON_OBJECT_AS_ARRAY`` is the only constant : there is no defined constant to explicitly ask for an object as returned value. 

See also `json_decode <http://php.net/json_decode>`_.

+-------------+---------------------------+
| Short name  | Structures/JsonWithOption |
+-------------+---------------------------+
| Themes      | :ref:`Suggestions`        |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _use-password\_hash():

Use password_hash()
###################


`password_hash() <http://www.php.net/password_hash>`_ and password_check() are a better choice to replace the use of `crypt() <http://www.php.net/crypt>`_ to check password.

PHP 5.5 introduced these functions.

.. code-block:: php

   <?php
   
   $password = 'rasmuslerdorf';
   $hash = 'y$YCFsG6elYca568hBi2pZ0.3LDL5wjgxct1N8w/oLR/jfHsiQwCqTS';
   
   // The cost parameter can change over time as hardware improves
   $options = array('cost' => 11);
   
   // Verify stored hash against plain-text password
   if (password_verify($password, $hash)) {
       // Check if a newer hashing algorithm is available
       // or the cost has changed
       if (password_needs_rehash($hash, PASSWORD_DEFAULT, $options)) {
           // If so, create a new hash, and replace the old one
           $newHash = password_hash($password, PASSWORD_DEFAULT, $options);
       }
   
       // Log user in
   }
   ?>


See also `Password hashing <http://php.net/manual/en/book.password.php>`_.

+-------------+------------------------------+
| Short name  | Php/Password55               |
+-------------+------------------------------+
| Themes      | :ref:`CompatibilityPHP55`    |
+-------------+------------------------------+
| Php Version | With PHP 5.5 and more recent |
+-------------+------------------------------+
| Severity    | Major                        |
+-------------+------------------------------+
| Time To Fix | Slow (1 hour)                |
+-------------+------------------------------+



.. _use-pathinfo()-arguments:

Use pathinfo() Arguments
########################


`pathinfo() <http://www.php.net/pathinfo>`_ has a second argument to select only useful data. 

It is twice faster to get only one element from `pathinfo() <http://www.php.net/pathinfo>`_ than get the four of them, and use only one.

This analysis reports `pathinfo() <http://www.php.net/pathinfo>`_ usage, without second argument, where only one or two indices are used, afte the call.

.. code-block:: php

   <?php
   
   // This could use only PATHINFO_BASENAME
   function foo_db() {
       $a = pathinfo($file2);
       return $a['basename'];
   }
   
   // This could be 2 calls, with PATHINFO_BASENAME and PATHINFO_DIRNAME.
   function foo_de() {
       $a = pathinfo($file3);
       return $a['dirname'].'/'.$a['basename'];
   }
   
   // This is OK : 3 calls to pathinfo() is slower than array access.
   function foo_deb() {
       $a = pathinfo($file4);
       return  $a['dirname'].'/'.$a['filename'].'.'.$a['extension'];
   }
   
   ?>


Depending on the situation, the functions `dirname() <http://www.php.net/dirname>`_ and `basename() <http://www.php.net/basename>`_ may also be used. They are even faster, when only fetching those data.

See also `list <http://php.net/manual/en/function.list.php>`_.

+-------------+-----------------------------------------------------------------------------+
| Short name  | Php/UsePathinfoArgs                                                         |
+-------------+-----------------------------------------------------------------------------+
| Themes      | :ref:`Performances`                                                         |
+-------------+-----------------------------------------------------------------------------+
| Severity    | Minor                                                                       |
+-------------+-----------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                             |
+-------------+-----------------------------------------------------------------------------+
| Examples    | :ref:`zend-config-php-usepathinfoargs`, :ref:`thinkphp-php-usepathinfoargs` |
+-------------+-----------------------------------------------------------------------------+



.. _use-random\_int():

Use random_int()
################


`rand() <http://www.php.net/rand>`_ and `mt_rand() <http://www.php.net/mt_rand>`_ should be replaced with random_int().

At worse, `rand() <http://www.php.net/rand>`_ should be replaced with `mt_rand() <http://www.php.net/mt_rand>`_, which is a drop-in replacement and `srand() <http://www.php.net/srand>`_ by `mt_srand() <http://www.php.net/mt_srand>`_. 

random_int() replaces `rand() <http://www.php.net/rand>`_, and has no seeding function like `srand() <http://www.php.net/srand>`_.

Other sources of entropy that should be replaced by random_int() : `microtime() <http://www.php.net/microtime>`_, `uniqid() <http://www.php.net/uniqid>`_, time(). Those a often combined with hashing functions and mixed with other sources of entropy, such as a salt.

.. code-block:: php

   <?php
   
   // Avoid using this
   $random = rand(0, 10);
   
   // Drop-in replacement
   $random = mt_rand(0, 10);
   
   // Even better but different : 
   // valid with PHP 7.0+
   try {
       $random = random_int(0, 10);
   } catch (\Exception $e) {
       // process case of not enoug random values
   }
   
   // This is also a source of entropy, based on srand()
   // random_int() is a drop-in replacement here
   $a = sha256(uniqid());
   
   ?>


Since PHP 7, random_int() along with random_bytes(), provides cryptographically secure pseudo-random bytes, which are good to be used
when security is involved. openssl_random_pseudo_bytes() may be used when the OpenSSL extension is available.

See also `CSPRNG <http://php.net/manual/en/book.csprng.php>`_ and `OpenSSL <http://php.net/manual/en/book.openssl.php>`_.


Suggestions
^^^^^^^^^^^

* Use random_bytes() and randon_int(). At least, use them as a base for random data, and then add extra prefix and suffix, and a hash call on top.

+-------------+-------------------------------------------------------------+
| Short name  | Php/BetterRand                                              |
+-------------+-------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Security`, :ref:`CompatibilityPHP71`  |
+-------------+-------------------------------------------------------------+
| Severity    | Major                                                       |
+-------------+-------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                            |
+-------------+-------------------------------------------------------------+
| Examples    | :ref:`thelia-php-betterrand`, :ref:`fuelcms-php-betterrand` |
+-------------+-------------------------------------------------------------+



.. _use-session\_start()-options:

Use session_start() Options
###########################


It is possible to set the session's option at session_start() call, skipping the usage of session_option().

This way, session's options are set in one call, saving several hits.

This is available since PHP 7.0. It is recommended to set those values in the ``php.ini`` file, whenever possible. 

.. code-block:: php

   <?php
   
   // PHP 7.0
   session_start(['session.name' => 'mySession',
                  'session.cookie_httponly' => 1,
                  'session.gc_maxlifetime' => 60 * 60);
   
   // PHP 5.6- old way 
   ini_set ('session.name', 'mySession');
   ini_set(session.cookie_httponly, 1); 
   ini_set('session.gc_maxlifetime', 60 * 60);
   session_start();
   
   ?>

+-------------+------------------------------+
| Short name  | Php/UseSessionStartOptions   |
+-------------+------------------------------+
| Themes      | :ref:`Suggestions`           |
+-------------+------------------------------+
| Php Version | With PHP 7.0 and more recent |
+-------------+------------------------------+



.. _used-once-property:

Used Once Property
##################


Property used once in their defining class. 

Properties used in one method only may be used several times, and read only. This may be a class constant. Such properties are meant to be overwritten by an extending class, and that's possible with class constants. 

Setting properties with default values is a good way to avoid littering the code with literal values, and provide a single point of update (by extension, or by hardcoding) for all those situations. A constant is definitely better suited for this task.

.. code-block:: php

   <?php
   
   class foo {
       private $defaultCols = '*';
       cont DEFAULT_COLUMNS = '*';
   
       // $this->defaultCols holds a default value. Should be a constant.
       function bar($table, $cols) {
           // This is necessary to activate usage of default values
           if (empty($cols)) {
               $cols = $this->defaultCols;
           }
           $res = $this->query('SELECT '.$cols.' FROM '.$table);
           // ....
       }
   
       // Upgraded version of bar, with default values
       function bar2($table, $cols = self::DEFAULT_COLUMNS) {
           $res = $this->query('SELECT '.$cols.' FROM '.$table);
           // .....
       }
   }
   
   ?>



Suggestions
^^^^^^^^^^^

* Remove the property, as it is probably not unused
* Add another usage of the property where it is useful

+-------------+--------------------------+
| Short name  | Classes/UsedOnceProperty |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Minor                    |
+-------------+--------------------------+
| Time To Fix | Slow (1 hour)            |
+-------------+--------------------------+



.. _used-once-variables:

Used Once Variables
###################


This is the list of used once variables. 

.. code-block:: php

   <?php
   
   // The variables below never appear again in the code
   $writtenOnce = 1;
   
   foo($readOnce);
   
   ?>


Such variables are useless. Variables must be used at least twice : once for writing, once for reading, at least. It is recommended to remove them.

In special situations, variables may be used once : 

+ PHP predefined variables, as they are already initialized. They are omitted in this analyze.
+ Interface function's arguments, since the function has no body; They are omitted in this analyze.
+ Dynamically created variables ($$x, ${`$this <http://php.net/manual/en/language.oop5.basic.php>`_->y} or also using extract), as they are runtime values and can't be determined at static code time. They are reported for manual review.
+ Dynamically included files will provide in-scope extra variables.

The current analyzer count variables at the application level, and not at a method scope level.

+-------------+---------------------------------------------------------------------------------------+
| Short name  | Variables/VariableUsedOnce                                                            |
+-------------+---------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                        |
+-------------+---------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                 |
+-------------+---------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                      |
+-------------+---------------------------------------------------------------------------------------+
| Examples    | :ref:`shopware-variables-variableusedonce`, :ref:`vanilla-variables-variableusedonce` |
+-------------+---------------------------------------------------------------------------------------+



.. _used-once-variables-(in-scope):

Used Once Variables (In Scope)
##############################


This is the list of used once variables, scope by scope. Those variables are used once in a function, a method, a class or a namespace. In any case, this means the variable is read or written, while it should be used at least twice. 

.. code-block:: php

   <?php
   
   function foo() {
       // The variables below never appear twice, inside foo()
       $writtenOnce = 1;
   
       foo($readOnce);
       // They do appear again in other functions, or in global space. 
   }
   
   function bar() {
       $writtenOnce = 1;
       foo($readOnce);
   }
   
   ?>

+-------------+-------------------------------------+
| Short name  | Variables/VariableUsedOnceByContext |
+-------------+-------------------------------------+
| Themes      | :ref:`Analyze`                      |
+-------------+-------------------------------------+
| Severity    | Minor                               |
+-------------+-------------------------------------+
| Time To Fix | Quick (30 mins)                     |
+-------------+-------------------------------------+



.. _useless-abstract-class:

Useless Abstract Class
######################


Those classes are marked 'abstract' and they are never extended. This way, they won't be instantiated nor used. 

Abstract classes that have only static methods are omitted here : one usage of such classes are Utilities classes, which only offer static methods. 

.. code-block:: php

   <?php
   
   // Never extended class : this is useless
   abstract class foo {}
   
   // Extended class
   abstract class bar {
       public function barbar() {}
   }
   
   class bar2 extends bar {}
   
   // Utility class : omitted here
   abstract class bar {
       public static function barbar() {}
   }
   
   ?>

+-------------+-------------------------+
| Short name  | Classes/UselessAbstract |
+-------------+-------------------------+
| Themes      | :ref:`Analyze`          |
+-------------+-------------------------+
| Severity    | Minor                   |
+-------------+-------------------------+
| Time To Fix | Slow (1 hour)           |
+-------------+-------------------------+



.. _useless-alias:

Useless Alias
#############


It is not possible to declare an alias of a method with the same name. 

PHP reports that `Trait method f has not been applied, because there are collisions with other trait methods on x`, which is a way to say that the alias will be in conflict with the method name. 

When the method is the only one bearing a name, and being imported, there is no need to alias it. When the method is imported in several traits, the keyword `insteadof` is available to solve the conflict.

.. code-block:: php

   <?php
   
   trait t {
       function h() {}
   }
   
   class x {
       use t { 
           // This is possible
           t::f as g; 
   
           // This is not possible, as the alias is in conflict with itself
           // alias are case insensitive
           t::f as f; 
       }
   }
   
   ?>



This code lint but doesn't execute.



Suggestions
^^^^^^^^^^^

* Remove the alias
* Fix the alias or the origin method name
* Switch to insteadof, and avoid as keyword

+-------------+----------------------------------------+
| Short name  | Traits/UselessAlias                    |
+-------------+----------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`LintButWontExec` |
+-------------+----------------------------------------+
| Severity    | Major                                  |
+-------------+----------------------------------------+
| Time To Fix | Instant (5 mins)                       |
+-------------+----------------------------------------+



.. _useless-brackets:

Useless Brackets
################


Standalone brackets have no use. Brackets are used to delimit a block of code, and are used by control statements. They may also be used to protect variables in strings. 

Standalone brackets may be a left over of an old instruction, or a misunderstanding of the alternative syntax.

.. code-block:: php

   <?php
   
   // The following brackets are useless : they are a leftover from an older instruction
   // if (DEBUG) 
   {
       $a = 1;
   }
   
   // Here, the extra brackets are useless
   for($a = 2; $a < 5; $a++) : {
       $b++;
   } endfor;
   
   ?>

+-------------+----------------------------+
| Short name  | Structures/UselessBrackets |
+-------------+----------------------------+
| Themes      | :ref:`Analyze`             |
+-------------+----------------------------+
| Severity    | Minor                      |
+-------------+----------------------------+
| Time To Fix | Instant (5 mins)           |
+-------------+----------------------------+



.. _useless-casting:

Useless Casting
###############


There is no need to overcast returned values.

.. code-block:: php

   <?php
   
   // trim always returns a string : cast is useless
   $a = (string) trim($b);
   
   // strpos doesn't always returns an integer : cast is useful
   $a = (boolean) strpos($b, $c);
   
   // comparison don't need casting, nor parenthesis
   $c = (bool) ($b > 2);
   
   ?>


See also `Type juggling <http://php.net/manual/en/language.types.type-juggling.php>`_.

+-------------+---------------------------+
| Short name  | Structures/UselessCasting |
+-------------+---------------------------+
| Themes      | :ref:`Analyze`            |
+-------------+---------------------------+
| Severity    | Minor                     |
+-------------+---------------------------+
| Time To Fix | Instant (5 mins)          |
+-------------+---------------------------+



.. _useless-catch:

Useless Catch
#############


Catch clause should handle the exception with some work. 

Among the task of a catch clause : log the exception, clean any mess that was introduced, fail graciously. 

.. code-block:: php

   <?php
   
   function foo($a) {
       try {
           $b = doSomething($a);
       } catch (Throwable $e) {
           // No log of the exception : no one knows it happened.
           
           // return immediately ? 
           return false;
       }
       
       $b->complete();
       
       return $b;
   }
   
   ?>


See also `Exceptions <http://php.net/manual/en/language.exceptions.php>`_.

+-------------+-------------------------+
| Short name  | Exceptions/UselessCatch |
+-------------+-------------------------+
| Themes      | :ref:`Analyze`          |
+-------------+-------------------------+
| Severity    | Minor                   |
+-------------+-------------------------+
| Time To Fix | Slow (1 hour)           |
+-------------+-------------------------+



.. _useless-check:

Useless Check
#############


Situation where the condition is useless. 

.. code-block:: php

   <?php
   
   // Checking for type is good. 
   if (is_array($array)) {
       foreach($array as $a) {
           doSomething($a);
       }
   }
   
   // Foreach on empty arrays doesn't start. Checking is useless
   if (!empty($array)) {
       foreach($array as $a) {
           doSomething($a);
       }
   }
   
   ?>

+-------------+-------------------------+
| Short name  | Structures/UselessCheck |
+-------------+-------------------------+
| Themes      | :ref:`Analyze`          |
+-------------+-------------------------+
| Severity    | Minor                   |
+-------------+-------------------------+
| Time To Fix | Instant (5 mins)        |
+-------------+-------------------------+



.. _useless-constructor:

Useless Constructor
###################


Class constructor that have empty bodies are useless. They may be removed.

.. code-block:: php

   <?php
   
   class X {
       function __construct() {
           // Do nothing
       }
   }
   
   class Y extends X {
       // Useful constructor, as it prevents usage of the parent
       function __construct() {
           // Do nothing
       }
   }
   
   ?>

+-------------+----------------------------+
| Short name  | Classes/UselessConstructor |
+-------------+----------------------------+
| Themes      | :ref:`Analyze`             |
+-------------+----------------------------+
| Severity    | Minor                      |
+-------------+----------------------------+
| Time To Fix | Slow (1 hour)              |
+-------------+----------------------------+



.. _useless-final:

Useless Final
#############


When a class is declared final, all of its methods are also final by default. 

There is no need to declare them individually final.

.. code-block:: php

   <?php
   
       final class foo {
           // Useless final, as the whole class is final
           final function method() { }
       }
   
       class bar {
           // Useful final, as the whole class is not final
           final function method() { }
       }
   
   ?>


See also `Final keyword <http://php.net/manual/en/language.oop5.final.php>`_, and `When to declare final <https://ocramius.github.io/blog/when-to-declare-classes-final/>`_.

+-------------+-------------------------------------------------------------------------------------------------+
| Short name  | Classes/UselessFinal                                                                            |
+-------------+-------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                  |
+-------------+-------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                           |
+-------------+-------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                |
+-------------+-------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-useless-final <https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-final.md>`__ |
+-------------+-------------------------------------------------------------------------------------------------+



.. _useless-global:

Useless Global
##############


Global are useless in two cases. First, on super-globals, which are always globals, like `$_GET <http://php.net/manual/en/reserved.variables.get.php>`_. Secondly, on variables that are not used.

.. code-block:: php

   <?php
   
   // $_POST is already a global : it is in fact a global everywhere
   global $_POST;
   
   // $unused is useless
   function foo() {
       global $used, $unused;
       
       ++$used;
   }
   
   ?>


Also, PHP has superglobals, a special team of variables that are always available, whatever the context. 
They are : $GLOBALS, $_SERVER, `$_GET <http://php.net/manual/en/reserved.variables.get.php>`_, `$_POST <http://php.net/manual/en/reserved.variables.post.php>`_, $_FILES, $_COOKIE, $_SESSION, `$_REQUEST <http://php.net/manual/en/reserved.variables.request.php>`_ and `$_ENV <http://php.net/manual/en/reserved.variables.env.php>`_.

+-------------+--------------------------+
| Short name  | Structures/UselessGlobal |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Minor                    |
+-------------+--------------------------+
| Time To Fix | Quick (30 mins)          |
+-------------+--------------------------+



.. _useless-instructions:

Useless Instructions
####################


Those instructions are useless, or contains useless parts. 

For example, an addition whose result is not stored in a variable, or immediately reused, does nothing : it is actually performed, and the result is lost. Just plain lost. 

Here the useless instructions that are spotted : 

.. code-block:: php

   <?php
   
   // Concatenating with an empty string is useless.
   $string = 'This part '.$is.' usefull but '.$not.'';
   
   // This is a typo, that PHP turns into a constant, then a string, then nothing.
   continue;
   
   // Empty string in a concatenation
   $a = 'abc' . '';
   
   // Returning expression, whose result is not used (additions, comparisons, properties, closures, new without =, ...)
   1 + 2;
   
   // Returning post-incrementation
   function foo($a) {
       return $a++;
   }
   
   // array_replace() with only one argument
   $replaced = array_replace($array);
   // array_replace() is OK with ... 
   $replaced = array_replace(...$array);
   
   // @ operator on source array, in foreach, or when assigning literals
   $array = @array(1,2,3);
   
   // Multiple comparisons in a for loop : only the last is actually used.
   for($i = 0; $j = 0; $j < 10, $i < 20; ++$j, ++$i) {
       print $i.' '.$j.PHP_EOL;
   }
   
   ?>

+-------------+-------------------------------------------------------------------------------------------------------------+
| Short name  | Structures/UselessInstruction                                                                               |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                              |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                       |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                             |
+-------------+-------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-useless-instruction <https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-instruction.md>`__ |
+-------------+-------------------------------------------------------------------------------------------------------------+



.. _useless-interfaces:

Useless Interfaces
##################


The interfaces below are defined and are implemented by some classes. 

However, they are never used to enforce an object's class in the code, using `instanceof <http://php.net/manual/en/language.operators.type.php>`_ or in a typehint. 
As they are currently used, those interfaces may be removed without change in behavior.

.. code-block:: php

   <?php
       // only defined interface but never enforced
       interface i {};
       class c implements i {} 
   ?>


Interfaces should be used in Typehint or with the `instanceof <http://php.net/manual/en/language.operators.type.php>`_ operator. 

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

+-------------+-----------------------------------------------------------------------------------------------------------+
| Short name  | Interfaces/UselessInterfaces                                                                              |
+-------------+-----------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                            |
+-------------+-----------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                     |
+-------------+-----------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                           |
+-------------+-----------------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-useless-interfaces <https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-interfaces.md>`__ |
+-------------+-----------------------------------------------------------------------------------------------------------+



.. _useless-parenthesis:

Useless Parenthesis
###################


Situations where parenthesis are not necessary, and may be removed.

Parenthesis group several elements together, and allows for a more readable expression. They are used with logical and mathematical expressions. They are necessary when the precedence of the operators are not the intended execution order : for example, when an addition must be performed before the multiplication.

Sometimes, the parenthesis provide the same execution order than the default order : they are deemed useless. 

.. code-block:: php

   <?php
   
       if ( ($condition) ) {}
       while( ($condition) ) {}
       do $a++; while ( ($condition) );
       
       switch ( ($a) ) {}
       $y = (1);
       ($y) == (1);
       
       f(($x));
   
       // = has precedence over == 
       ($a = $b) == $c;
       
       ($a++);
       
       // No need for parenthesis in default values
       function foo($c = ( 1 + 2) ) {}
   ?>


See also `Operators Precedence <http://php.net/manual/en/language.operators.precedence.php>`_.

+-------------+-----------------------------------------------------------------------------------------------+
| Short name  | Structures/UselessParenthesis                                                                 |
+-------------+-----------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                |
+-------------+-----------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                         |
+-------------+-----------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                              |
+-------------+-----------------------------------------------------------------------------------------------+
| Examples    | :ref:`mautic-structures-uselessparenthesis`, :ref:`woocommerce-structures-uselessparenthesis` |
+-------------+-----------------------------------------------------------------------------------------------+



.. _useless-referenced-argument:

Useless Referenced Argument
###########################


The argument has a reference, but is only used for reading. 

This is probably a development artefact that was forgotten. It is better to remove it. 

.. code-block:: php

   <?php
   
   function foo($a, &$b, &$c) {
       // $c is passed by reference, but only read. This is useless.
       $b = $c + $a;
   }
   
   ?>




Suggestions
^^^^^^^^^^^

* Remove the useless & from the argument
* Make an actual use of the argument before the end of the method

+-------------+----------------------------------------------------------------------------------------------------------+
| Short name  | Functions/UselessReferenceArgument                                                                       |
+-------------+----------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                           |
+-------------+----------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                    |
+-------------+----------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                            |
+-------------+----------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`woocommerce-functions-uselessreferenceargument`, :ref:`magento-functions-uselessreferenceargument` |
+-------------+----------------------------------------------------------------------------------------------------------+



.. _useless-return:

Useless Return
##############


The spotted functions or methods have a return statement, but this statement is useless. This is the case for constructor and destructors, whose return value are ignored or inaccessible.

When return is void, and the last element in a function, it is also useless.

.. code-block:: php

   <?php
   
   class foo {
       function __construct() {
           // return is not used by PHP
           return 2;
       }
   }
   
   function bar(&$a) {
       $a++;
       // The last return, when empty, is useless
       return;
   }
   
   ?>



Suggestions
^^^^^^^^^^^

* Remove the return expression. Keep any other calculation.

+-------------+---------------------------------------------------------------------------------+
| Short name  | Functions/UselessReturn                                                         |
+-------------+---------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                  |
+-------------+---------------------------------------------------------------------------------+
| Severity    | Minor                                                                           |
+-------------+---------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                |
+-------------+---------------------------------------------------------------------------------+
| Examples    | :ref:`thinkphp-functions-uselessreturn`, :ref:`vanilla-functions-uselessreturn` |
+-------------+---------------------------------------------------------------------------------+



.. _useless-switch:

Useless Switch
##############


This switch has only one case. It may very well be replaced by a ifthen structure.

.. code-block:: php

   <?php
   switch($a) {
       case 1:
           doSomething();
           break;
   }
   
   // Same as 
   
   if ($a == 1) {
       doSomething();
   }
   ?>

+-------------+--------------------------+
| Short name  | Structures/UselessSwitch |
+-------------+--------------------------+
| Themes      | :ref:`Analyze`           |
+-------------+--------------------------+
| Severity    | Major                    |
+-------------+--------------------------+
| Time To Fix | Instant (5 mins)         |
+-------------+--------------------------+



.. _useless-unset:

Useless Unset
#############


There are situations where trying to remove a variable is actually useless. 

PHP ignores any command that tries to unset a global variable, a static variable, or a blind variable from a foreach loop. 

This is different from the garbage collector, which is run on its own schedule. It is also different from an explicit unset, aimed at freeing memory early : those are useful.

.. code-block:: php

   <?php
   
   function foo($a) {
       // unsetting arguments is useless
       unset($a);
       
       global $b;
       // unsetting global variable has no effect 
       unset($b);
   
       static $c;
       // unsetting static variable has no effect 
       unset($c);
       
       foreach($d as &$e){
           // unsetting a blind variable is useless
           (unset) $e;
       }
       // Unsetting a blind variable AFTER the loop is good.
       unset($e);
   }
   
   ?>


See also `unset <http://php.net/unset>`_.


Suggestions
^^^^^^^^^^^

* Remove the unset
* Set the variable to null : the effect is the same on memory, but the variable keeps its existence.
* Omit unsetting variables, and wait for the end of the scope. That way, PHP free memory en mass.

+-------------+-------------------------------------------------------------------------------------------------+
| Short name  | Structures/UselessUnset                                                                         |
+-------------+-------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                  |
+-------------+-------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                           |
+-------------+-------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                 |
+-------------+-------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-useless-unset <https://github.com/dseguy/clearPHP/tree/master/rules/no-useless-unset.md>`__ |
+-------------+-------------------------------------------------------------------------------------------------+
| Examples    | :ref:`tine20-structures-uselessunset`, :ref:`typo3-structures-uselessunset`                     |
+-------------+-------------------------------------------------------------------------------------------------+



.. _uses-default-values:

Uses Default Values
###################


Default values are provided to methods so as to make it convenient to use. However, with new versions, those values may change. For example, in PHP 5.4, `htmlentities() <http://www.php.net/htmlentities>`_ switched from ``Latin1`` to ``UTF-8`` default encoding.

.. code-block:: php

   <?php
   
   $string = Eu não sou o pão;
   
   echo htmlentities($string);
   
   // PHP 5.3 : Eu n&Atilde;&pound;o sou o p&Atilde;&pound;o
   // PHP 5.4 : Eu n&atilde;o sou o p&atilde;o
   
   // Stable across versions
   echo htmlentities($string, 'UTF8');
   
   ?>


As much as possible, it is recommended to use explicit values in those methods, so as to prevent from being surprise at a future PHP evolution. 

This analyzer tend to report a lot of false positives, including usage of `count() <http://www.php.net/count>`_. `Count() <http://www.php.net/count>`_ indeed has a second argument for recursive counts, and a default value. This may be ignored safely.

+-------------+--------------------------------+
| Short name  | Functions/UsesDefaultArguments |
+-------------+--------------------------------+
| Themes      | :ref:`Analyze`                 |
+-------------+--------------------------------+
| Severity    | Minor                          |
+-------------+--------------------------------+
| Time To Fix | Quick (30 mins)                |
+-------------+--------------------------------+



.. _using-$this-outside-a-class:

Using $this Outside A Class
###########################


``$this`` is a special variable, that should only be used in a class context. 

Until PHP 7.1, ``$this`` may be used as an argument in a function or a method, a global, a static : while this is legit, it sounds confusing enough to avoid it.

.. code-block:: php

   <?php
   
   function foo($this) {
       echo $this;
   }
   
   // A closure can be bound to an object at later time. It is valid usage.
   $closure = function ($x) {
       echo $this->foo($x);
   }
   
   ?>


Starting with PHP 7.1, the PHP engine check thoroughly that ``$this`` is used in an appropriate manner, and raise fatal errors in case it isn't. 

Yet, it is possible to find ``$this`` outside a class : if the file is included inside a class, then ``$this`` will be recognized and validated. If the file is included outside a class context, it will yield a fatal error : ``Using `$this <http://php.net/manual/en/language.oop5.basic.php>`_ when not in object context``.

See also `Closure\:\:bind <http://php.net/manual/en/closure.bind.php>`_ and 
         `The Basics <http://php.net/manual/en/language.oop5.basic.php>`_.

+-------------+-------------------------------------------------------------------+
| Short name  | Classes/UsingThisOutsideAClass                                    |
+-------------+-------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`CompatibilityPHP71`, :ref:`LintButWontExec` |
+-------------+-------------------------------------------------------------------+
| Php Version | With PHP 7.0 and older                                            |
+-------------+-------------------------------------------------------------------+
| Severity    | Critical                                                          |
+-------------+-------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                  |
+-------------+-------------------------------------------------------------------+



.. _usort-sorting-in-php-7.0:

Usort Sorting In PHP 7.0
########################


`Usort() <http://www.php.net/usort>`_, `uksort() <http://www.php.net/uksort>`_ and `uasort() <http://www.php.net/uasort>`_ behavior has changed in PHP 7. Values that are equals (based on the user-provided method) may be sorted differently than in PHP 5. 

If this sorting is important, it is advised to add extra comparison in the user-function and avoid returning 0 (thus, depending on default implementation). 

.. code-block:: php

   <?php
   
   $a = [ 2, 4, 3, 6];
   
   function noSort($a) { return $a > 5; }
   
   usort($a, 'noSort');
   print_r($a);
   
   ?>


In PHP 5, the results is :::

   
   Array
   (
       [0] => 3
       [1] => 4
       [2] => 2
       [3] => 6
   )
   


in PHP 7, the result is :::

   
   Array
   (
       [0] => 2
       [1] => 4
       [2] => 3
       [3] => 6
   )

+-------------+---------------------------+
| Short name  | Php/UsortSorting          |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP70` |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Slow (1 hour)             |
+-------------+---------------------------+



.. _var-keyword:

Var Keyword
###########


Var was used in PHP 4 to mark properties as public. Nowadays, new keywords are available : public, protected, private. Var is equivalent to public. 

It is recommended to avoid using var, and explicitly use the new keywords.

.. code-block:: php

   <?php
   
   class foo {
       public $bar = 1;
       // Avoid var
       //var $bar = 1; 
   }
   
   ?>


See also `Visibility <http://php.net/manual/en/language.oop5.visibility.php>`_.


Suggestions
^^^^^^^^^^^

* It is recommended to avoid using var, and explicitly use the new keywords : private, protected, public

+-------------+---------------------------------------------------------------------------------------------------------+
| Short name  | Classes/OldStyleVar                                                                                     |
+-------------+---------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                          |
+-------------+---------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                   |
+-------------+---------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                         |
+-------------+---------------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-php4-class-syntax <https://github.com/dseguy/clearPHP/tree/master/rules/no-php4-class-syntax.md>`__ |
+-------------+---------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`xataface-classes-oldstylevar`                                                                     |
+-------------+---------------------------------------------------------------------------------------------------------+



.. _variable-global:

Variable Global
###############


Variable global such are valid in PHP 5.6, but no in PHP 7.0. They should be replaced with ${$foo->bar}.

.. code-block:: php

   <?php
   
   // Forbidden in PHP 7
   global $normalGlobal;
   
   // Forbidden in PHP 7
   global $$variable->global ;
   
   // Tolerated in PHP 7
   global ${$variable->global};
   
   ?>

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Structures/VariableGlobal                                                                                  |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and older                                                                                     |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                            |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _weak-typing:

Weak Typing
###########


The test on a variable is not enough. The variable is checked for null, then used as an object or an array.

.. code-block:: php

   <?php
   
   if ($a !== null) {
       echo $a->b;
   }
   
   ?>


See also `From assumptions to assertions <https://rskuipers.com/entry/from-assumptions-to-assertions>`_.


Suggestions
^^^^^^^^^^^

* Use instanceof when checking for objects
* Use is_array() when checking for arrays. Also consider is_string(), is_int(), etc.
* Use typehint when the variable is an argument

+-------------+----------------------------------+
| Short name  | Classes/WeakType                 |
+-------------+----------------------------------+
| Themes      | :ref:`Analyze`                   |
+-------------+----------------------------------+
| Severity    | Minor                            |
+-------------+----------------------------------+
| Time To Fix | Instant (5 mins)                 |
+-------------+----------------------------------+
| Examples    | :ref:`teampass-classes-weaktype` |
+-------------+----------------------------------+



.. _while(list()-=-each()):

While(List() = Each())
######################


This code structure is quite old : it should be replace by the more modern and efficient foreach.

This structure is deprecated since PHP 7.2. It may disappear in the future.

.. code-block:: php

   <?php
   
       while(list($key, $value) = each($array)) {
           doSomethingWith($key) and $value();
       }
   
       foreach($array as $key => $value) {
           doSomethingWith($key) and $value();
       }
   ?>
 

See also `PHP RFC: Deprecations for PHP 7.2 : `Each() <http://www.php.net/each>`_ <https://wiki.php.net/rfc/deprecations_php_7_2#each>`_.

+-------------+---------------------------------------------------------+
| Short name  | Structures/WhileListEach                                |
+-------------+---------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Performances`, :ref:`Suggestions` |
+-------------+---------------------------------------------------------+
| Severity    | Minor                                                   |
+-------------+---------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                        |
+-------------+---------------------------------------------------------+



.. _written-only-variables:

Written Only Variables
######################


Those variables are being written, but never read. This way, they are useless and should be removed, or read at some point.

.. code-block:: php

   <?php
   
   // $a is used multiple times, but never read
   $a = 'a';
   $a .= 'b';
   
   $b = 3; 
   //$b is actually read once
   $a .= $b + 3; 
   
   ?>

+-------------+-----------------------------------------------------------------------------------------------------+
| Short name  | Variables/WrittenOnlyVariable                                                                       |
+-------------+-----------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                      |
+-------------+-----------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                               |
+-------------+-----------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                       |
+-------------+-----------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-unused-variable <https://github.com/dseguy/clearPHP/tree/master/rules/no-unused-variable.md>`__ |
+-------------+-----------------------------------------------------------------------------------------------------+



.. _wrong-number-of-arguments:

Wrong Number Of Arguments
#########################


Those functioncalls are made with too many or too few arguments. 

When the number arguments is wrong for native functions, PHP emits a warning. 
When the number arguments is too small for custom functions, PHP raises an exception. 
When the number arguments is too high for custom functions, PHP ignores the arguments. Such arguments should be handled with the variadic operator, or with `func_get_args() <http://www.php.net/func_get_args>`_ family of functions.

.. code-block:: php

   <?php
   
   echo strtoupper('This function is', 'ignoring arguments');
   //Warning: strtoupper() expects exactly 1 parameter, 2 given in Command line code on line 1
   
   echo strtoupper();
   //Warning: strtoupper() expects exactly 1 parameter, 0 given in Command line code on line 1
   
   function foo($argument) {}
   echo foo();
   //Fatal error: Uncaught ArgumentCountError: Too few arguments to function foo(), 0 passed in /Users/famille/Desktop/analyzeG3/test.php on line 10 and exactly 1 expected in /Users/famille/Desktop/analyzeG3/test.php:3
   
   echo foo('This function is', 'ignoring arguments');
   
   ?>


It is recommended to check the signature of the methods, and fix the arguments.

+-------------+-------------------------------------------------------------------------------------------------------------+
| Short name  | Functions/WrongNumberOfArguments                                                                            |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                              |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                       |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                             |
+-------------+-------------------------------------------------------------------------------------------------------------+
| ClearPHP    | `no-missing-argument.md <https://github.com/dseguy/clearPHP/tree/master/rules/no-missing-argument.md.md>`__ |
+-------------+-------------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`xataface-functions-wrongnumberofarguments`                                                            |
+-------------+-------------------------------------------------------------------------------------------------------------+



.. _wrong-number-of-arguments-in-methods:

Wrong Number Of Arguments In Methods
####################################


Those methods are called with a wrong number of arguments : too many or too few. Check the signature.

.. code-block:: php

   <?php
   
   class Foo {
       private function Bar($a, $b) {
           return $a + $b;
       }
       
       public function foobar() {
           $this->Bar(1);
           
           // Good amount
           $this->Bar(1, 2);
           
           // Too Many
           $this->Bar(1, 2, 3);
       }
   }
   
   ?>


Methods with a variable number of argument, either using ellipsis or `func_get_args() <http://www.php.net/func_get_args>`_ are ignored. 

PHP emits an error at runtime, when arguments are not enough : ''. PHP doesn't emit an error when too many arguments are provided.



Suggestions
^^^^^^^^^^^

* Adapt the call to use one of the right number of arguments : this means dropping the extra ones, or adding the missing ones
* Adapt the signature of the method, and use a default value

+-------------+-----------------------------------------+
| Short name  | Functions/WrongNumberOfArgumentsMethods |
+-------------+-----------------------------------------+
| Themes      | :ref:`Analyze`                          |
+-------------+-----------------------------------------+
| Severity    | Major                                   |
+-------------+-----------------------------------------+
| Time To Fix | Quick (30 mins)                         |
+-------------+-----------------------------------------+



.. _wrong-optional-parameter:

Wrong Optional Parameter
########################


Wrong placement of optional parameters.

PHP parameters are optional when they defined with a default value, like this : 

.. code-block:: php

   <?php
       function x($arg = 1) {
           // PHP code here
       }
   ?>


When a function have both compulsory and optional parameters, the compulsory ones should appear first, and the optional should appear last : 

.. code-block:: php

   <?php
       function x($arg, $arg2 = 2) {
           // PHP code here
       }
   ?>


PHP will solve this problem at runtime, assign values in the same other, but will miss some of the default values and emits warnings. 

It is better to put all the optional parameters at the end of the method's signature.



Suggestions
^^^^^^^^^^^

* Give default values to all but first parameters. Null is a good default value, as PHP will use it if not told otherwise. 
* Remove default values to all but last parameters. That is probably a weak solution.
* Change the order of the values, so default-valued parameters are at the end. This will probably have impact on the rest of the code, as the API is changing.

+-------------+--------------------------------------------------------------------------------------------------+
| Short name  | Functions/WrongOptionalParameter                                                                 |
+-------------+--------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                                                                   |
+-------------+--------------------------------------------------------------------------------------------------+
| Severity    | Minor                                                                                            |
+-------------+--------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                  |
+-------------+--------------------------------------------------------------------------------------------------+
| Examples    | :ref:`fuelcms-functions-wrongoptionalparameter`, :ref:`vanilla-functions-wrongoptionalparameter` |
+-------------+--------------------------------------------------------------------------------------------------+



.. _wrong-parameter-type:

Wrong Parameter Type
####################


The expected parameter is not of the correct type. Check PHP documentation to know which is the right format to be used.

.. code-block:: php

   <?php
   
   // substr() shouldn't work on integers.
   // the first argument is first converted to string, and it is 123456.
   echo substr(123456, 0, 4); // display 1234
   
   // substr() shouldn't work on boolean
   // the first argument is first converted to string, and it is 1, and not t
   echo substr(true, 0, 1); // displays 1
   
   // substr() works correctly on strings.
   echo substr(123456, 0, 4);
   
   ?>

+-------------+------------------------------------------+
| Short name  | Php/InternalParameterType                |
+-------------+------------------------------------------+
| Themes      | :ref:`Analyze`                           |
+-------------+------------------------------------------+
| Severity    | Major                                    |
+-------------+------------------------------------------+
| Time To Fix | Quick (30 mins)                          |
+-------------+------------------------------------------+
| Examples    | :ref:`zencart-php-internalparametertype` |
+-------------+------------------------------------------+



.. _wrong-range-check:

Wrong Range Check
#################


The interval check should use && and not ||. 

.. code-block:: php

   <?php
   
   //interval correctly checked a is between 2 and 999
   if ($a > 1 && $a < 1000) {}
   
   //interval incorrectly checked : a is 2 or more ($a < 1000 is never checked)
   if ($a > 1 || $a < 1000) {}
   
   ?>

+-------------+-----------------------+
| Short name  | Structures/WrongRange |
+-------------+-----------------------+
| Themes      | :ref:`Analyze`        |
+-------------+-----------------------+
| Severity    | Major                 |
+-------------+-----------------------+
| Time To Fix | Instant (5 mins)      |
+-------------+-----------------------+



.. _wrong-fopen()-mode:

Wrong fopen() Mode
##################


Wrong file opening for `fopen() <http://www.php.net/fopen>`_.

`fopen() <http://www.php.net/fopen>`_ has a few modes, as described in the documentation : 'r', 'r+', for reading;  'w', 'w+' for writing; 'a', 'a+' for appending; 'x', 'x+' for modifying; 'c', 'c+' for writing and locking, 't' for text files and windows only.
An optional 'b' may be used to make the `fopen() <http://www.php.net/fopen>`_ call more portable and binary safe. Another optional 't' may be used to make the `fopen() <http://www.php.net/fopen>`_ call process automatically text input : this one should be avoided. 

.. code-block:: php

   <?php
   
   // open the file for reading, in binary mode
   $fp = fopen('/tmp/php.txt', 'rb');
   
   // New option e in PHP 7.0.16 and 7.1.2 (beware of compatibility)
   $fp = fopen('/tmp/php.txt', 'rbe');
   
   // Unknown option x
   $fp = fopen('/tmp/php.txt', 'rbx');
   
   ?>


Any other values are not understood by PHP. 

Suggestions
^^^^^^^^^^^

* Check the docs, choose the right opening mode.

+-------------+--------------------------------------------------------------+
| Short name  | Php/FopenMode                                                |
+-------------+--------------------------------------------------------------+
| Themes      | :ref:`Analyze`                                               |
+-------------+--------------------------------------------------------------+
| Severity    | Major                                                        |
+-------------+--------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                              |
+-------------+--------------------------------------------------------------+
| Examples    | :ref:`tikiwiki-php-fopenmode`, :ref:`humo-gen-php-fopenmode` |
+-------------+--------------------------------------------------------------+



.. _yoda-comparison:

Yoda Comparison
###############


Yoda comparison is a way to write conditions which places literal values on the left side. 

.. code-block:: php

   <?php
     if (1 == $a) {
       // Then condition
     } 
   ?>


The objective is to avoid mistaking a comparison to an assignation. If the comparison operator is mistaken, but the literal is on the left, then an error will be triggered, instead of a silent bug. 

.. code-block:: php

   <?php
       // error in comparison! 
       if ($a = 1) {
           // Then condition
       } 
   ?>


See also `Yoda Conditions <https://en.wikipedia.org/wiki/Yoda_conditions>`_, 
`Yoda Conditions: To Yoda or Not to Yoda <https://knowthecode.io/yoda-conditions-yoda-not-yoda>`_.

+-------------+------------------------------------------------+
| Short name  | Structures/YodaComparison                      |
+-------------+------------------------------------------------+
| Themes      | :ref:`Coding Conventions <coding-conventions>` |
+-------------+------------------------------------------------+
| Severity    | Minor                                          |
+-------------+------------------------------------------------+
| Time To Fix | Slow (1 hour)                                  |
+-------------+------------------------------------------------+



.. _\_\_dir\_\_-then-slash:

__DIR__ Then Slash
##################


`__DIR__ <http://php.net/manual/en/language.constants.predefined.php>`_ must be concatenated with a string starting with /.

The magic constant `__DIR__ <http://php.net/manual/en/language.constants.predefined.php>`_ holds the name of the current directory, without final /. When it is used to build path, then the following path fragment must start with /. Otherwise, two directories names will be merged together. 

.. code-block:: php

   <?php
   
   // __DIR__ = /a/b/c
   // $filePath = /a/b/c/g.php
   
   // /a/b/c/d/e/f.txt : correct path
   echo __DIR__.'/d/e/f.txt';
   echo dirname($filePath).'/d/e/f.txt';
   
   // /a/b/cd/e/f.txt : most probably incorrect path
   echo __DIR__.'d/e/f.txt';
   echo dirname($filePath).'d/e/f.txt';
   
   ?>

+-------------+-------------------------+
| Short name  | Structures/DirThenSlash |
+-------------+-------------------------+
| Themes      | :ref:`Analyze`          |
+-------------+-------------------------+
| Severity    | Major                   |
+-------------+-------------------------+
| Time To Fix | Instant (5 mins)        |
+-------------+-------------------------+



.. _\_\_debuginfo()-usage:

__debugInfo() Usage
###################


The magic method `__debugInfo() <http://php.net/manual/en/language.oop5.magic.php>`_ provides a custom way to dump an object. 

It has been introduced in PHP 5.6. In the previous versions of PHP, this method is ignored and won't be called when debugging.

.. code-block:: php

   <?php
   
   // PHP 5.6 or later
   class foo {
       private $bar = 1;
       private $reallyHidden = 2;
       
       function __debugInfo() {
           return ['bar' => $this->bar,
                   'reallyHidden' => 'Secret'];
       }
   }
   
   $f = new Foo();
   var_dump($f);
   
   /* Displays : 
   object(foo)#1 (2) {
     [bar]=>
     int(1)
     [reallyHidden]=>
     string(6) Secret
   }
   */
   
   ?>


See also `Magic methods <http://php.net/manual/en/language.oop5.magic.php>`_.

+-------------+---------------------------------------------------------------------------------+
| Short name  | Php/debugInfoUsage                                                              |
+-------------+---------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55` |
+-------------+---------------------------------------------------------------------------------+
| Php Version | With PHP 5.6 and more recent                                                    |
+-------------+---------------------------------------------------------------------------------+
| Severity    | Minor                                                                           |
+-------------+---------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                   |
+-------------+---------------------------------------------------------------------------------+
| Examples    | :ref:`dolibarr-php-debuginfousage`                                              |
+-------------+---------------------------------------------------------------------------------+



.. _\_\_tostring()-throws-exception:

__toString() Throws Exception
#############################


Magical method `__toString() <http://php.net/manual/en/language.oop5.magic.php>`_ can't throw exceptions.

In fact, `__toString() <http://php.net/manual/en/language.oop5.magic.php>`_ may not let an exception pass. If it throw an exception, but must catch it. If an underlying method throws an exception, it must be caught.

.. code-block:: php

   <?php
   
   class myString {
       private $string = null;
       
       public function __construct($string) {
           $this->string = $string;
       }
       
       public function __toString() {
           // Do not throw exceptions in __toString
           if (!is_string($this->string)) {
               throw new Exception("$this->string is not a string!!");
           }
           
           return $this->string;
       }
   }   
   
   ?>


A fatal error is displayed, when an exception is not intercepted in the `__toString() <http://php.net/manual/en/language.oop5.magic.php>`_ function. 

\:\:

    PHP Fatal error:  Method myString\:\:`__toString() <http://php.net/manual/en/language.oop5.magic.php>`_ must not throw an exception, caught Exception: 'Exception message' in ``file.php``

See also `__toString() <http://php.net/manual/en/language.oop5.magic.php>`_.

+-------------+------------------------------------+
| Short name  | Structures/toStringThrowsException |
+-------------+------------------------------------+
| Themes      | :ref:`Analyze`                     |
+-------------+------------------------------------+
| Severity    | Major                              |
+-------------+------------------------------------+
| Time To Fix | Quick (30 mins)                    |
+-------------+------------------------------------+



.. _crypt()-without-salt:

crypt() Without Salt
####################


PHP requires a salt when calling `crypt() <http://www.php.net/crypt>`_. 5.5 and previous versions didn't require it. Salt is a simple string, that is usually only known by the application.

According to the manual : The salt parameter is optional. However, `crypt() <http://www.php.net/crypt>`_ creates a weak hash without the salt. PHP 5.6 or later raise an E_NOTICE error without it. Make sure to specify a strong enough salt for better security.

.. code-block:: php

   <?php
   // Set the password
   $password = 'mypassword';
   
   // Get the hash, letting the salt be automatically generated
   // This generates a notice after PHP 5.6
   $hash = crypt($password);
   
   
   $hash = crypt($password);
   
   ?>


See also `crypt <http://www.php.net/crypt>`_.

+-------------+-----------------------------+
| Short name  | Structures/CryptWithoutSalt |
+-------------+-----------------------------+
| Themes      | :ref:`CompatibilityPHP54`   |
+-------------+-----------------------------+
| Php Version | With PHP 5.6 and older      |
+-------------+-----------------------------+
| Severity    | Minor                       |
+-------------+-----------------------------+
| Time To Fix | Instant (5 mins)            |
+-------------+-----------------------------+



.. _error\_reporting()-with-integers:

error_reporting() With Integers
###############################


Using named constants with error_reporting is strongly encouraged to ensure compatibility for future versions. As error levels are added, the range of integers increases, so older integer-based error levels will not always behave as expected. (Adapted from the documentation).

.. code-block:: php

   <?php
   
   // This is ready for PHP next version
   error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT & ~E_NOTICE & ~E_WARNING);
   
   // This is not ready for PHP next version
   error_reporting(2047);
   
   // -1 and 0 are omitted, as they will be valid even is constants changes.
   error_reporting(-1);
   error_reporting(0);
   
   ?>


See also `directive error_reporting <http://php.net/manual/en/errorfunc.configuration.php#ini.error-reporting>`_ and 
         `error_reporting <http://php.net/manual/en/function.error-reporting.php>`_.

+-------------+------------------------------------------------------+
| Short name  | Structures/ErrorReportingWithInteger                 |
+-------------+------------------------------------------------------+
| Themes      | :ref:`Analyze`                                       |
+-------------+------------------------------------------------------+
| Severity    | Minor                                                |
+-------------+------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                     |
+-------------+------------------------------------------------------+
| Examples    | :ref:`sugarcrm-structures-errorreportingwithinteger` |
+-------------+------------------------------------------------------+



.. _eval()-without-try:

eval() Without Try
##################


``eval()`` emits a ``ParseError`` exception with PHP 7 and later. Catching this exception is the recommended way to handle errors when using the ``eval()`` function.

.. code-block:: php

   <?php
   
   $code = 'This is no PHP code.';
   
   //PHP 5 style
   eval($code);
   // Ends up with a Fatal error, at execution time
   
   //PHP 7 style
   try {
       eval($code);
   } catch (ParseError $e) {
       cleanUpAfterEval();
   }
   
   ?>


Note that it will catch situations where ``eval()`` is provided with code that can't be used, but it will not catch security problems. Avoid using ``eval()`` with incoming data.

+-------------+---------------------------------+
| Short name  | Structures/EvalWithoutTry       |
+-------------+---------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Security` |
+-------------+---------------------------------+
| Php Version | With PHP 7.0 and more recent    |
+-------------+---------------------------------+
| Severity    | Critical                        |
+-------------+---------------------------------+
| Time To Fix | Quick (30 mins)                 |
+-------------+---------------------------------+



.. _ext/apc:

ext/apc
#######


Extension Alternative PHP Cache.

The Alternative PHP Cache (APC) is a free and open opcode cache for PHP. Its goal is to provide a free, open, and robust framework for caching and optimizing PHP intermediate code.

This extension is considered unmaintained and dead. 

.. code-block:: php

   <?php
      $bar = 'BAR';
      apc_add('foo', $bar);
      var_dump(apc_fetch('foo'));
      echo PHP_EOL;
   
      $bar = 'NEVER GETS SET';
      apc_add('foo', $bar);
      var_dump(apc_fetch('foo'));
      echo PHP_EOL;
   ?>


See also `Alternative PHP Cache <http://php.net/apc>`_.

+-------------+---------------------------+
| Short name  | Extensions/Extapc         |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP55` |
+-------------+---------------------------+
| Php Version | With PHP 7.0 and older    |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Slow (1 hour)             |
+-------------+---------------------------+



.. _ext/dba:

ext/dba
#######


Extension ext/dba.

These functions build the foundation for accessing Berkeley DB style databases.

.. code-block:: php

   <?php
   
   $id = dba_open('/tmp/test.db', 'n', 'db2');
   
   if (!$id) {
       echo 'dba_open failed'.PHP_EOL;
       exit;
   }
   
   dba_replace('key', 'This is an example!', $id);
   
   if (dba_exists('key', $id)) {
       echo dba_fetch('key', $id);
       dba_delete('key', $id);
   }
   
   dba_close($id);
   ?>


See also `Database (dbm-style) Abstraction Layer <http://php.net/manual/en/book.dba.php>`_.

+-------------+---------------------------+
| Short name  | Extensions/Extdba         |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP53` |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Slow (1 hour)             |
+-------------+---------------------------+



.. _ext/ereg:

ext/ereg
########


Extension ext/ereg.

.. code-block:: php

   <?php
   if (ereg ('([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})', $date, $regs)) {
       echo $regs[3].'.'.$regs[2].'.'.$regs[1];
   } else {
       echo 'Invalid date format: '.$date;
   }
   ?>


See also `Ereg <http://php.net/manual/en/function.ereg.php>`_.

+-------------+---------------------------+
| Short name  | Extensions/Extereg        |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP70` |
+-------------+---------------------------+
| Php Version | With PHP 7.0 and older    |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _ext/fdf:

ext/fdf
#######


Extension ext/fdf.

Forms Data Format (`FDF <http://www.adobe.com/devnet/acrobat/fdftoolkit.html>`_) is a format for handling forms within PDF documents.

.. code-block:: php

   <?php
   $outfdf = fdf_create();
   fdf_set_value($outfdf, 'volume', $volume, 0);
   
   fdf_set_file($outfdf, 'http:/testfdf/resultlabel.pdf');
   fdf_save($outfdf, 'outtest.fdf');
   fdf_close($outfdf);
   Header('Content-type: application/vnd.fdf');
   $fp = fopen('outtest.fdf', 'r');
   fpassthru($fp);
   unlink('outtest.fdf');
   ?>


See also `Form Data Format <http://php.net/manual/en/book.fdf.php>`_.

+-------------+---------------------------+
| Short name  | Extensions/Extfdf         |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP53` |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Slow (1 hour)             |
+-------------+---------------------------+



.. _ext/mcrypt:

ext/mcrypt
##########


Extension for mcrypt.

This extension has been deprecated as of PHP 7.1.0 and moved to PECL as of PHP 7.2.0.

This is an interface to the mcrypt library, which supports a wide variety of block algorithms such as DES, TripleDES, Blowfish (default), 3-WAY, SAFER-SK64, SAFER-SK128, TWOFISH, TEA, RC2 and GOST in CBC, OFB, CFB and ECB cipher modes. Additionally, it supports RC6 and IDEA which are considered 'non-free'. CFB/OFB are 8bit by default.

.. code-block:: php

   <?php
       # --- ENCRYPTION ---
   
       # the key should be random binary, use scrypt, bcrypt or PBKDF2 to
       # convert a string into a key
       # key is specified using hexadecimal
       $key = pack('H*', 'bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3');
       
       # show key size use either 16, 24 or 32 byte keys for AES-128, 192
       # and 256 respectively
       $key_size =  strlen($key);
       echo 'Key size: ' . $key_size . PHP_EOL;
       
       $plaintext = 'This string was AES-256 / CBC / ZeroBytePadding encrypted.';
   
       # create a random IV to use with CBC encoding
       $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
       $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
       
       # creates a cipher text compatible with AES (Rijndael block size = 128)
       # to keep the text confidential 
       # only suitable for encoded input that never ends with value 00h
       # (because of default zero padding)
       $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key,
                                    $plaintext, MCRYPT_MODE_CBC, $iv);
   
       # prepend the IV for it to be available for decryption
       $ciphertext = $iv . $ciphertext;
       
       # encode the resulting cipher text so it can be represented by a string
       $ciphertext_base64 = base64_encode($ciphertext);
   
       echo  $ciphertext_base64 . PHP_EOL;
   
       # === WARNING ===
   
       # Resulting cipher text has no integrity or authenticity added
       # and is not protected against padding oracle attacks.
       
       # --- DECRYPTION ---
       
       $ciphertext_dec = base64_decode($ciphertext_base64);
       
       # retrieves the IV, iv_size should be created using mcrypt_get_iv_size()
       $iv_dec = substr($ciphertext_dec, 0, $iv_size);
       
       # retrieves the cipher text (everything except the $iv_size in the front)
       $ciphertext_dec = substr($ciphertext_dec, $iv_size);
   
       # may remove 00h valued characters from end of plain text
       $plaintext_dec = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key,
                                       $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec);
       
       echo  $plaintext_dec . PHP_EOL;
   ?>


See also `extension mcrypt <http://www.php.net/manual/en/book.mcrypt.php>`_ and `mcrypt <http://mcrypt.sourceforge.net/>`_.

+-------------+---------------------------+
| Short name  | Extensions/Extmcrypt      |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP71` |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Slow (1 hour)             |
+-------------+---------------------------+



.. _ext/mhash:

ext/mhash
#########


Extension mhash (obsolete since PHP 5.3.0).

This extension provides functions, intended to work with `mhash <http://mhash.sourceforge.net/>`_.

.. code-block:: php

   <?php
   $input = 'what do ya want for nothing?';
   $hash = mhash(MHASH_MD5, $input);
   echo 'The hash is ' . bin2hex($hash) . '<br />'.PHP_EOL;
   $hash = mhash(MHASH_MD5, $input, 'Jefe');
   echo 'The hmac is ' . bin2hex($hash) . '<br />'.PHP_EOL;
   ?>


See also `Extension mhash <http://php.net/manual/en/book.mhash.php>`_.

+-------------+---------------------------+
| Short name  | Extensions/Extmhash       |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP54` |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Slow (1 hour)             |
+-------------+---------------------------+



.. _ext/ming:

ext/ming
########


Extension ext/ming, to create swf files with PHP.

Ming is an open-source (LGPL) library which allows you to create SWF ('Flash') format movies. 

.. code-block:: php

   <?php
     $s = new SWFShape();
     $f = $s->addFill(0xff, 0, 0);
     $s->setRightFill($f);
   
     $s->movePenTo(-500, -500);
     $s->drawLineTo(500, -500);
     $s->drawLineTo(500, 500);
     $s->drawLineTo(-500, 500);
     $s->drawLineTo(-500, -500);
   
     $p = new SWFSprite();
     $i = $p->add($s);
     $i->setDepth(1);
     $p->nextFrame();
   
     for ($n=0; $n<5; ++$n) {
       $i->rotate(-15);
       $p->nextFrame();
     }
   
     $m = new SWFMovie();
     $m->setBackground(0xff, 0xff, 0xff);
     $m->setDimension(6000, 4000);
   
     $i = $m->add($p);
     $i->setDepth(1);
     $i->moveTo(-500,2000);
     $i->setName('box');
   
     $m->add(new SWFAction('/box.x += 3;'));
     $m->nextFrame();
     $m->add(new SWFAction('gotoFrame(0); play();'));
     $m->nextFrame();
   
     header('Content-type: application/x-shockwave-flash');
     $m->output();
   ?>


See also `Ming (flash) <http://www.libming.org/>`_ and `Ming <http://www.libming.org/>`_.

+-------------+---------------------------+
| Short name  | Extensions/Extming        |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP53` |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Slow (1 hour)             |
+-------------+---------------------------+



.. _ext/mysql:

ext/mysql
#########


Extension for MySQL (Original MySQL API).

This extension is deprecated as of PHP 5.5.0, and has been removed as of PHP 7.0.0. Instead, either the mysqli or PDO_MySQL extension should be used. See also the MySQL API Overview for further help while choosing a MySQL API.

.. code-block:: php

   <?php
   $result = mysql_query('SELECT * WHERE 1=1');
   if (!$result) {
       die('Invalid query: ' . mysql_error());
   }
   
   ?>


See also `Original MySQL API <http://www.php.net/manual/en/book.mysql.php>`_ and `MySQL <http://www.mysql.com/>`_.

+-------------+---------------------------+
| Short name  | Extensions/Extmysql       |
+-------------+---------------------------+
| Themes      | :ref:`CompatibilityPHP55` |
+-------------+---------------------------+
| Php Version | With PHP 7.0 and older    |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Slow (1 hour)             |
+-------------+---------------------------+



.. _filter\_input()-as-a-source:

filter_input() As A Source
##########################


The filter_input() and filter_input_array() functions access directly to ``$_GET``. They represent a source for external data just like ``$_GET``, ``$_POST``, etc.

The main feature of filter_input() is that it is already filtered. The main drawback is that ``FILTER_FLAG_NONE`` is the ``none`` filter, and that default configuration is `FILTER_UNSAFE_RAW`.

The filter extension keeps access to the incoming data, even after the super globals, such as ``$_GET``, are unset.

.. code-block:: php

   <?php
   
   // Removing $_GET
   $_GET = [];
   
   // with the default : FILTER_UNSAFE_RAW, this means XSS
   echo filter_input(INPUT_GET, 'i');
   
   // Same as above : 
   echo filter_var(_GET, 'i');
   
   ?>


Thanks to `Frederic Bouchery <https://twitter.com/FredBouchery/>`_ for reporting this `special case <https://twitter.com/FredBouchery/status/1049297213598457857>`_.

See also `Data filtering <http://php.net/manual/en/book.filter.php>`_.

+-------------+----------------------------+
| Short name  | Security/FilterInputSource |
+-------------+----------------------------+
| Themes      | :ref:`Security`            |
+-------------+----------------------------+
| Severity    | Minor                      |
+-------------+----------------------------+
| Time To Fix | Slow (1 hour)              |
+-------------+----------------------------+



.. _fputcsv()-in-loops:

fputcsv() In Loops
##################


`fputcsv() <http://www.php.net/fputcsv>`_ is slow when called on each row. It actually flushes the data to the disk each time, and that results in a inefficient dump to the disk, each call.

To speed up this process, it is recommended to dump the csv to memory first, then dump the memory to the disk, in larger chunks. Since `fputcsv() <http://www.php.net/fputcsv>`_ works only on stream, it is necessary to use a memory stream.

.. code-block:: php

   <?php
   
   // Speedy yet memory intensive version
   $f = fopen('php://memory', 'w+');
   foreach($data_source as $row) {
       // You may configure fputcsv as usual
       fputcsv($f, $row);
   }
   rewind($f); // Important
   $fp = fopen('final.csv', 'w+');
   fputs($fp, stream_get_contents($f));
   fclose($fp);
   fclose($f);
   
   // Slower version
   $fp = fopen('final.csv', 'w+');
   foreach($data_source as $row) {
       // You may configure fputcsv as usual
       fputcsv($fp, $row);
   }
   fclose($fp);
   ?>


The speed improvement is significant on small rows, while it may be less significant on largers rows : with more data in the rows, the file buffer may fill up more efficiently. On small rows, the speed gain is up to 7 times. 

See also ` `


Suggestions
^^^^^^^^^^^

*

+-------------+-------------------------+
| Short name  | Performances/CsvInLoops |
+-------------+-------------------------+
| Themes      | :ref:`Performances`     |
+-------------+-------------------------+
| Severity    | Minor                   |
+-------------+-------------------------+
| Time To Fix | Quick (30 mins)         |
+-------------+-------------------------+



.. _func\_get\_arg()-modified:

func_get_arg() Modified
#######################


`func_get_arg() <http://www.php.net/func_get_arg>`_ and `func_get_args() <http://www.php.net/func_get_args>`_ used to report the calling value of the argument until PHP 7. Since PHP 7, it is reporting the value of the argument at calling time, which may have been modified by a previous instruction. 

.. code-block:: php

   <?php
   
   function x($a) {
       $a++;
       print func_get_arg(0);
   }
   
   x(0);
   ?>


This code will display 1 in PHP 7, and 0 in PHP 5.

+-------------+-------------------------------------------+
| Short name  | Functions/funcGetArgModified              |
+-------------+-------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`CompatibilityPHP70` |
+-------------+-------------------------------------------+
| Severity    | Major                                     |
+-------------+-------------------------------------------+
| Time To Fix | Quick (30 mins)                           |
+-------------+-------------------------------------------+



.. _idn\_to\_ascii()-new-default:

idn_to_ascii() New Default
##########################


The default parameter value of idn_to_ascii() and idn_to_utf8() is now INTL_IDNA_VARIANT_UTS46 instead of the deprecated INTL_IDNA_VARIANT_2003.

.. code-block:: php

   <?php
   
   echo idn_to_ascii('täst.de'); 
   
   ?>


See also `idn_to_ascii <http://php.net/manual/en/function.idn-to-ascii.php>`_,
         `idn_to_utf8 <http://php.net/manual/en/function.idn-to-utf8.php>`_ and
         `Unicode IDNA Compatibility Processing <http://unicode.org/reports/tr46/>`_.

+------------+---------------------------+
| Short name | Php/IdnUts46              |
+------------+---------------------------+
| Themes     | :ref:`CompatibilityPHP74` |
+------------+---------------------------+



.. _include\_once()-usage:

include_once() Usage
####################


include_once() and require_once() functions should be avoided for performances reasons.

.. code-block:: php

   <?php
   
   // Including a library. 
   include 'lib/helpers.inc';
   
   // Including a library, and avoiding double inclusion
   include_once 'lib/helpers.inc';
   
   ?>


Try using autoload for loading classes, or use include() or require() and make it possible to include several times the same file without errors.

+-------------+----------------------+
| Short name  | Structures/OnceUsage |
+-------------+----------------------+
| Themes      | :ref:`Analyze`       |
+-------------+----------------------+
| Severity    | Minor                |
+-------------+----------------------+
| Time To Fix | Quick (30 mins)      |
+-------------+----------------------+



.. _isset()-with-constant:

isset() With Constant
#####################


Until PHP 7, it was possible to use arrays as constants, but it was not possible to test them with `isset <http://www.php.net/isset>`_.

.. code-block:: php

   <?php
   const X = [1,2,3];
   
   if (isset(X[4])) {}
   ?>


This would yield an error : ``Cannot use `isset() <http://www.php.net/isset>`_ on the result of an expression (you can use "null !== expression" instead)``. This is a backward incompatibility.

+-------------+------------------------------------------------------------------------------------------------------------+
| Short name  | Structures/IssetWithConstant                                                                               |
+-------------+------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`CompatibilityPHP53`, :ref:`CompatibilityPHP54`, :ref:`CompatibilityPHP55`, :ref:`CompatibilityPHP56` |
+-------------+------------------------------------------------------------------------------------------------------------+
| Php Version | With PHP 7.0 and more recent                                                                               |
+-------------+------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                      |
+-------------+------------------------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                                           |
+-------------+------------------------------------------------------------------------------------------------------------+



.. _list()-may-omit-variables:

list() May Omit Variables
#########################


Simply omit any unused variable in a list() call. 

list() is the only PHP function that accepts to have omitted arguments. If the following code makes no usage of a listed variable, just omit it. 

.. code-block:: php

   <?php
       // No need for '2', so no assignation
       list ($a, , $b) = array(1, 2, 3);
           // works with PHP 7.1 short syntax
            [$a, , $b] = array(1, 2, 3);
   
       // No need for '2', so no assignation
       list ($a, $c, $b) = array(1, 2, 3);
   ?>


See also `list <http://php.net/manual/en/function.list.php>`_.

+-------------+------------------------------------+
| Short name  | Structures/ListOmissions           |
+-------------+------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Suggestions` |
+-------------+------------------------------------+
| Severity    | Minor                              |
+-------------+------------------------------------+
| Time To Fix | Quick (30 mins)                    |
+-------------+------------------------------------+



.. _mcrypt\_create\_iv()-with-default-values:

mcrypt_create_iv() With Default Values
######################################


Avoid using `mcrypt_create_iv() <http://php.net/manual/en/function.mcrypt-create-iv.php>`_ default values.

`mcrypt_create_iv() <http://php.net/manual/en/function.mcrypt-create-iv.php>`_ used to have ``MCRYPT_DEV_RANDOM`` as default values, and in PHP 5.6, it now uses ``MCRYPT_DEV_URANDOM``.

.. code-block:: php

   <?php
       $size = mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CFB);
       // mcrypt_create_iv is missing the second argument
       $iv = mcrypt_create_iv($size);
   
   // Identical to the line below
   //    $iv = mcrypt_create_iv($size, MCRYPT_DEV_RANDOM);
   
   ?>


If the code doesn't have a second argument, it relies on the default value. It is recommended to set explicitly the value, so has to avoid problems while migrating.

See also `mcrypt_create_iv() <http://php.net/manual/en/function.mcrypt-create-iv.php>`_.

+-------------+----------------------------------------+
| Short name  | Structures/McryptcreateivWithoutOption |
+-------------+----------------------------------------+
| Themes      | :ref:`CompatibilityPHP70`              |
+-------------+----------------------------------------+
| Php Version | With PHP 5.6 and older                 |
+-------------+----------------------------------------+
| Severity    | Minor                                  |
+-------------+----------------------------------------+
| Time To Fix | Instant (5 mins)                       |
+-------------+----------------------------------------+



.. _move\_uploaded\_file-instead-of-copy:

move_uploaded_file Instead Of copy
##################################


Always use `move_uploaded_file() <http://www.php.net/move_uploaded_file>`_ with uploaded files. Avoid using copy or rename with uploaded file. 

`move_uploaded_file() <http://www.php.net/move_uploaded_file>`_ checks to ensure that the file designated by filename is a valid upload file (meaning that it was uploaded via PHP's HTTP POST upload mechanism).

.. code-block:: php

   <?php
   
       // $a->file was filled with $_FILES at some point
       move_uploaded_file($a->file['tmp_name'], $target);
   
       // $a->file was filled with $_FILES at some point
       rename($a->file['tmp_name'], $target);
   
   ?>


See also `move_uploaded_file <http://php.net/move_uploaded_file>`_ and 
         `Uploading Files with PHP <https://www.sitepoint.com/file-uploads-with-php/>`_.

+-------------+---------------------------+
| Short name  | Security/MoveUploadedFile |
+-------------+---------------------------+
| Themes      | :ref:`Security`           |
+-------------+---------------------------+
| Severity    | Major                     |
+-------------+---------------------------+
| Time To Fix | Quick (30 mins)           |
+-------------+---------------------------+



.. _parse\_str()-warning:

parse_str() Warning
###################


The `parse_str() <http://www.php.net/parse_str>`_ function parses a query string and assigns the resulting variables to the local scope. This may create a unexpected number of variables, and even overwrite the existing one.

.. code-block:: php

   <?php
     function foo( ) {
       global $a;
       
       echo $a;
     }
   
     parse_str('a=1'); // No second parameter
     foo( );
     // displays 1
   ?>


Always use an empty variable a second parameter to `parse_str() <http://www.php.net/parse_str>`_, so as to collect the incoming values, and then, filter them in that array.

+-------------+-------------------------------------------------------------------------------------------------------+
| Short name  | Security/parseUrlWithoutParameters                                                                    |
+-------------+-------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Security`                                                                                       |
+-------------+-------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                 |
+-------------+-------------------------------------------------------------------------------------------------------+
| Time To Fix | Slow (1 hour)                                                                                         |
+-------------+-------------------------------------------------------------------------------------------------------+
| ClearPHP    | `know-your-variables <https://github.com/dseguy/clearPHP/tree/master/rules/know-your-variables.md>`__ |
+-------------+-------------------------------------------------------------------------------------------------------+



.. _preg\_match\_all()-flag:

preg_match_all() Flag
#####################


preg_match_all() has an option to configure the structure of the results : it is either by capturing parenthesis (by default), or by result sets. 

The second option is the most interesting when the following `foreach() <http://php.net/manual/en/control-structures.foreach.php>`_ loop has to manipulate several captured strings at the same time. No need to use an index in the first array and use it in the other arrays.

.. code-block:: php

   <?php
   $string = 'ababab';
   
   // default behavior
   preg_match_all('/(a)(b)/', $string, $r);
   $found = '';
   foreach($r[1] as $id => $s) {
       $found .= $s.$r[2][$id];
   }
   
   // better behavior
   preg_match_all('/(a)(b)/', $string, $r, PREG_SET_ORDER);
   $found = '';
   foreach($r as $s) {
       $found .= $s[1].$s[2];
   }
   
   ?>


The second syntax is easier to read and may be marginally faster to execute (preg_match_all() and `foreach()) <http://php.net/manual/en/control-structures.foreach.php>`_.

+-------------+----------------------+
| Short name  | Php/PregMatchAllFlag |
+-------------+----------------------+
| Themes      | :ref:`Suggestions`   |
+-------------+----------------------+
| Severity    | Minor                |
+-------------+----------------------+
| Time To Fix | Quick (30 mins)      |
+-------------+----------------------+



.. _preg\_replace-with-option-e:

preg_replace With Option e
##########################


`preg_replace() <http://www.php.net/preg_replace>`_ supported the /e option until PHP 7.0. It allowed the use of `eval() <http://www.php.net/eval>`_'ed expression as replacement. This has been dropped in PHP 7.0, for security reasons.

`preg_replace() <http://www.php.net/preg_replace>`_ with /e option may be replaced with `preg_replace_callback() <http://www.php.net/preg_replace_callback>`_ and a closure, or `preg_replace_callback_array() <http://www.php.net/preg_replace_callback_array>`_ and an array of closures.

.. code-block:: php

   <?php
   
   // preg_replace with /e
   $string = 'abcde';
   
   // PHP 5.6 and older usage of /e
   $replaced = preg_replace('/c/e', 'strtoupper($0)', $string);
   
   // PHP 7.0 and more recent
   // With one replacement
   $replaced = preg_replace_callback('/c/', function ($x) { return strtoupper($x[0]); }, $string);
   
   // With several replacements, preventing multiple calls to preg_replace_callback
   $replaced = preg_replace_callback_array(array('/c/' => function ($x) { return strtoupper($x[0]); },
                                                 '/[a-b]/' => function ($x) { return strtolower($x[0]); }), $string);
   ?>




Suggestions
^^^^^^^^^^^

* Replace call to preg_replace() and /e with preg_replace_callback() or preg_replace_callback_array()

+-------------+------------------------------------------------------------------------------------------------------------------+
| Short name  | Structures/pregOptionE                                                                                           |
+-------------+------------------------------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`CompatibilityPHP70`, :ref:`Security`, :ref:`CompatibilityPHP71`, :ref:`CompatibilityPHP72` |
+-------------+------------------------------------------------------------------------------------------------------------------+
| Severity    | Major                                                                                                            |
+-------------+------------------------------------------------------------------------------------------------------------------+
| Time To Fix | Quick (30 mins)                                                                                                  |
+-------------+------------------------------------------------------------------------------------------------------------------+
| Examples    | :ref:`edusoho-structures-pregoptione`                                                                            |
+-------------+------------------------------------------------------------------------------------------------------------------+



.. _self,-parent,-static-outside-class:

self, parent, static Outside Class
##################################


self, parent and static should be called inside a class or trait. PHP lint won't report those situations. 

self, parent and static may be used in a trait : their actual value will be only known at execution time, when the trait is used.

.. code-block:: php

   <?php
   // In the examples, self, parent and static may be used interchangeably
   
   // This raises a Fatal error
   //Fatal error: Uncaught Error: Cannot access static:: when no class scope is active
   new static();
   
   // static calls
   echo self::CONSTANTE;
   echo self::$property;
   echo self::method();
   
   // as a type hint
   function foo(static $x) {
       doSomething();
   }
   
   // as a instanceof
   if ($x instanceof static) {
       doSomething();
   }
   
   ?>


Such syntax problem is only revealed at execution time : PHP raises a Fatal error. 

The origin of the problem is usually a method that was moved outside a class, at least temporarily.

See also `Scope Resolution Operator (\:\:) <http://php.net/manual/en/language.oop5.paamayim-nekudotayim.php>`_.

+-------------+----------------------------------------+
| Short name  | Classes/NoPSSOutsideClass              |
+-------------+----------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`LintButWontExec` |
+-------------+----------------------------------------+
| Severity    | Major                                  |
+-------------+----------------------------------------+
| Time To Fix | Slow (1 hour)                          |
+-------------+----------------------------------------+



.. _set\_exception\_handler()-warning:

set_exception_handler() Warning
###############################


The `set_exception_handler() <http://www.php.net/set_exception_handler>`_ callable function has to be adapted to PHP 7 : ``Exception`` is not the right typehint, it is now ``Throwable``. 

When in doubt about backward compatibility, just drop the typehint. Otherwise, use ``Throwable``.

.. code-block:: php

   <?php
   
   // PHP 5.6- typehint 
   class foo { function bar(\Exception $e) {} }
   
   // PHP 7+ typehint 
   class foo { function bar(Throwable $e) {} }
   
   // PHP 5 and PHP 7 compatible typehint (note : there is none)
   class foo { function bar($e) {} }
   
   set_exception_handler(foo);
   
   ?>

+-------------+-----------------------------+
| Short name  | Php/SetExceptionHandlerPHP7 |
+-------------+-----------------------------+
| Themes      | :ref:`CompatibilityPHP70`   |
+-------------+-----------------------------+
| Severity    | Major                       |
+-------------+-----------------------------+
| Time To Fix | Slow (1 hour)               |
+-------------+-----------------------------+



.. _time()-vs-strtotime():

time() Vs strtotime()
#####################


time() is actually faster than strtotime('now').

.. code-block:: php

   <?php
   
   // Faster version
   $a = time();
   
   // Slower version
   $b = strtotime('now');
   
   ?>


This is a micro-optimisation. Relative gain is real, but small unless the function is used many times.



Suggestions
^^^^^^^^^^^

* Replace strtotime('now') with time(). Don't change strtotime with other value then 'now'.

+-------------+-------------------------------------------------+
| Short name  | Performances/timeVsstrtotime                    |
+-------------+-------------------------------------------------+
| Themes      | :ref:`Performances`                             |
+-------------+-------------------------------------------------+
| Severity    | Minor                                           |
+-------------+-------------------------------------------------+
| Time To Fix | Instant (5 mins)                                |
+-------------+-------------------------------------------------+
| Examples    | :ref:`woocommerce-performances-timevsstrtotime` |
+-------------+-------------------------------------------------+



.. _var\_dump()...-usage:

var_dump()... Usage
###################


`var_dump() <http://www.php.net/var_dump>`_, `print_r() <http://www.php.net/print_r>`_ or `var_export() <http://www.php.net/var_export>`_ should not be left in any production code. They are debugging functions.

.. code-block:: php

   <?php
   
   if ($error) {
       // Debugging usage of var_dump
       // And major security problem 
       var_dump($query);
       
       // This is OK : the $query is logged, and not displayed
       $this->log(print_r($query, true));
   }
   
   ?>


They may be tolerated during development time, but must be removed so as not to have any chance to be run in production.

+-------------+-------------------------------------------------------------------------------------------+
| Short name  | Structures/VardumpUsage                                                                   |
+-------------+-------------------------------------------------------------------------------------------+
| Themes      | :ref:`Analyze`, :ref:`Security`                                                           |
+-------------+-------------------------------------------------------------------------------------------+
| Severity    | Critical                                                                                  |
+-------------+-------------------------------------------------------------------------------------------+
| Time To Fix | Instant (5 mins)                                                                          |
+-------------+-------------------------------------------------------------------------------------------+
| ClearPHP    | `no-debug-code <https://github.com/dseguy/clearPHP/tree/master/rules/no-debug-code.md>`__ |
+-------------+-------------------------------------------------------------------------------------------+



