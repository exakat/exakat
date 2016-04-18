.. _Recipes:

Recipes
*******

Presentation
############

Analysis are grouped in different standard recipes, that may be run independantly. Each recipe has a focus target, 

Recipes runs all its analysis and any needed dependency.

Recipes are configured with the -T option, when running exakat in command line.

::

   php exakat.phar analyze -p <project> -T <Security/DirectInjection>



List of recipes
###############

Here is the list of the current recipes supported by Exakat Engine.

+--------------------------------------+------------------------------------------------------------------------------------------------------+
|Name                                  | Description                                                                                          |
+--------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Security`                       | Check the code for common security bad practices, especially in the Web environnement.               |
+--------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Performances`                   | Check the code for slow code.                                                                        |
+--------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Dead code <dead-code>`          | Check the unused code or unreachable code.                                                           |
+--------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Analyze`                        | Check for common best practices.                                                                     |
+--------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP70`             | List features that are incompatible with PHP 7.0. This recipe is helpful for checking compatibility. |
+--------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP71`             | List features that are incompatible with PHP 7.1. This recipe is helpful for forward compatibility,  |
|                                      | and it currently under developpement.                                                                |
+--------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP56`             | List features that are incompatible with PHP 5.6. This recipe is helpful for backward compatibility. |
+--------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP55`             | List features that are incompatible with PHP 5.5. This recipe is helpful for backward compatibility. |
+--------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP54`             | List features that are incompatible with PHP 5.4. This recipe is helpful for backward compatibility. |
+--------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP53`             | List features that are incompatible with PHP 5.3. This recipe is helpful for backward compatibility. |
+--------------------------------------+------------------------------------------------------------------------------------------------------+

Recipes details
###############

.. comment: The rest of the document is automatically generated. Don't modify it manually. 
.. comment: Recipes details
.. comment: Generation date : Mon, 18 Apr 2016 12:13:02 +0000
.. comment: Generation hash : 397a53d2f5b908369371b771df79433868b1e49b


.. _analyze:

Analyze
+++++++

Total : 243 analysis

* :ref:`$HTTP\_RAW\_POST\_DATA`
* :ref:`$this Belongs To Classes <$this-belongs-to-classes>`
* :ref:`$this Is Not An Array <$this-is-not-an-array>`
* :ref:`$this is not for static methods <$this-is-not-for-static-methods>`
* :ref:`<?= usage <<?=-usage>`
* :ref:`Abstract Static Methods <abstract-static-methods>`
* :ref:`Access Protected Structures <access-protected-structures>`
* :ref:`Accessing Private <accessing-private>`
* :ref:`Adding Zero <adding-zero>`
* :ref:`Aliases Usage <aliases-usage>`
* :ref:`Already Parents Interface <already-parents-interface>`
* :ref:`Altering Foreach Without Reference <altering-foreach-without-reference>`
* :ref:`Ambiguous Index <ambiguous-index>`
* :ref:`Argument should be typehinted <argument-should-be-typehinted>`
* :ref:`Assign Default To Properties <assign-default-to-properties>`
* :ref:`Avoid Parenthesis <avoid-parenthesis>`
* :ref:`Avoid array\_unique() <avoid-array\_unique()>`
* :ref:`Break Outside Loop <break-outside-loop>`
* :ref:`Break With 0 <break-with-0>`
* :ref:`Break With Non Integer <break-with-non-integer>`
* :ref:`Buried Assignation <buried-assignation>`
* :ref:`Calltime Pass By Reference <calltime-pass-by-reference>`
* :ref:`Cant Extend Final <cant-extend-final>`
* :ref:`Case After Default <case-after-default>`
* :ref:`Case For Parent, Static And Self <case-for-parent,-static-and-self>`
* :ref:`Catch Overwrite Variable <catch-overwrite-variable>`
* :ref:`Class Name Case Difference <class-name-case-difference>`
* :ref:`Class, Interface Or Trait With Identical Names <class,-interface-or-trait-with-identical-names>`
* :ref:`Classes Mutually Extending Each Other <classes-mutually-extending-each-other>`
* :ref:`Close Tags <close-tags>`
* :ref:`Closure May Use $this <closure-may-use-$this>`
* :ref:`Common Alternatives <common-alternatives>`
* :ref:`Compared comparison <compared-comparison>`
* :ref:`Concrete Visibility <concrete-visibility>`
* :ref:`Confusing Names <confusing-names>`
* :ref:`Constant Class <constant-class>`
* :ref:`Constants Created Outside Its Namespace <constants-created-outside-its-namespace>`
* :ref:`Constants With Strange Names <constants-with-strange-names>`
* :ref:`Could Be Class Constant <could-be-class-constant>`
* :ref:`Could Be Static <could-be-static>`
* :ref:`Could Use Short Assignation <could-use-short-assignation>`
* :ref:`Could use self <could-use-self>`
* :ref:`Dangling Array References <dangling-array-references>`
* :ref:`Deep Definitions <deep-definitions>`
* :ref:`Deprecated Code <deprecated-code>`
* :ref:`Directive Name <directive-name>`
* :ref:`Don't Change Incomings <don't-change-incomings>`
* :ref:`Double Assignation <double-assignation>`
* :ref:`Double Instruction <double-instruction>`
* :ref:`Echo With Concat <echo-with-concat>`
* :ref:`Else If Versus Elseif <else-if-versus-elseif>`
* :ref:`Empty Classes <empty-classes>`
* :ref:`Empty Function <empty-function>`
* :ref:`Empty Instructions <empty-instructions>`
* :ref:`Empty Interfaces <empty-interfaces>`
* :ref:`Empty List <empty-list>`
* :ref:`Empty Namespace <empty-namespace>`
* :ref:`Empty Traits <empty-traits>`
* :ref:`Empty Try Catch <empty-try-catch>`
* :ref:`Eval() Usage <eval()-usage>`
* :ref:`Exit() Usage <exit()-usage>`
* :ref:`For Using Functioncall <for-using-functioncall>`
* :ref:`Foreach Needs Reference Array <foreach-needs-reference-array>`
* :ref:`Foreach Reference Is Not Modified <foreach-reference-is-not-modified>`
* :ref:`Forgotten Visibility <forgotten-visibility>`
* :ref:`Forgotten Whitespace <forgotten-whitespace>`
* :ref:`Fully Qualified Constants <fully-qualified-constants>`
* :ref:`Function Subscripting, Old Style <function-subscripting,-old-style>`
* :ref:`Functions In Loop Calls <functions-in-loop-calls>`
* :ref:`Functions Removed In PHP 5.4 <functions-removed-in-php-5.4>`
* :ref:`Global Usage <global-usage>`
* :ref:`Hardcoded Passwords <hardcoded-passwords>`
* :ref:`Hash Algorithms <hash-algorithms>`
* :ref:`Htmlentities Calls <htmlentities-calls>`
* :ref:`Identical Conditions <identical-conditions>`
* :ref:`Iffectations`
* :ref:`Implement Is For Interface <implement-is-for-interface>`
* :ref:`Implicit global <implicit-global>`
* :ref:`Incompilable Files <incompilable-files>`
* :ref:`Indices Are Int Or String <indices-are-int-or-string>`
* :ref:`Instantiating Abstract Class <instantiating-abstract-class>`
* :ref:`Invalid Constant Name <invalid-constant-name>`
* :ref:`List With Appends <list-with-appends>`
* :ref:`Locally Unused Property <locally-unused-property>`
* :ref:`Logical Mistakes <logical-mistakes>`
* :ref:`Logical should use &&, \|\|, ^ <logical-should-use-&&,-||,-^>`
* :ref:`Lone Blocks <lone-blocks>`
* :ref:`Lost References <lost-references>`
* :ref:`Magic Visibility <magic-visibility>`
* :ref:`Malformed Octal <malformed-octal>`
* :ref:`Multiple Class Declarations <multiple-class-declarations>`
* :ref:`Multiple Constant Definition <multiple-constant-definition>`
* :ref:`Multiple Definition Of The Same Argument <multiple-definition-of-the-same-argument>`
* :ref:`Multiple Index Definition <multiple-index-definition>`
* :ref:`Multiples Identical Case <multiples-identical-case>`
* :ref:`Multiply By One <multiply-by-one>`
* :ref:`Must Return Methods <must-return-methods>`
* :ref:`Namespace With Fully Qualified Name <namespace-with-fully-qualified-name>`
* :ref:`Negative Power <negative-power>`
* :ref:`Nested Ternary <nested-ternary>`
* :ref:`Never Used Properties <never-used-properties>`
* :ref:`No Choice <no-choice>`
* :ref:`No Direct Call To MagicMethod <no-direct-call-to-magicmethod>`
* :ref:`No Direct Usage <no-direct-usage>`
* :ref:`No Hardcoded Hash <no-hardcoded-hash>`
* :ref:`No Hardcoded Ip <no-hardcoded-ip>`
* :ref:`No Hardcoded Path <no-hardcoded-path>`
* :ref:`No Hardcoded Port <no-hardcoded-port>`
* :ref:`No Implied If <no-implied-if>`
* :ref:`No Parenthesis For Language Construct <no-parenthesis-for-language-construct>`
* :ref:`No Public Access <no-public-access>`
* :ref:`No Real Comparison <no-real-comparison>`
* :ref:`No Self Referencing Constant <no-self-referencing-constant>`
* :ref:`No array\_merge In Loops <no-array\_merge-in-loops>`
* :ref:`Non Ascii Variables <non-ascii-variables>`
* :ref:`Non Static Methods Called In A Static <non-static-methods-called-in-a-static>`
* :ref:`Non-constant Index In Array <non-constant-index-in-array>`
* :ref:`Not Definitions Only <not-definitions-only>`
* :ref:`Not Not <not-not>`
* :ref:`Not Substr One <not-substr-one>`
* :ref:`Null On New <null-on-new>`
* :ref:`Objects Don't Need References <objects-don't-need-references>`
* :ref:`Old Style Constructor <old-style-constructor>`
* :ref:`Old Style \_\_autoload() <old-style-\_\_autoload()>`
* :ref:`One Letter Functions <one-letter-functions>`
* :ref:`One Variable String <one-variable-string>`
* :ref:`Only Variable Returned By Reference <only-variable-returned-by-reference>`
* :ref:`Or Die <or-die>`
* :ref:`Overwritten Exceptions <overwritten-exceptions>`
* :ref:`Overwritten Literals <overwritten-literals>`
* :ref:`PHP Keywords As Names <php-keywords-as-names>`
* :ref:`Parent, Static Or Self Outside Class <parent,-static-or-self-outside-class>`
* :ref:`Phpinfo`
* :ref:`Pre-increment`
* :ref:`Preprocess Arrays <preprocess-arrays>`
* :ref:`Preprocessable`
* :ref:`Print And Die <print-and-die>`
* :ref:`Property Could Be Private <property-could-be-private>`
* :ref:`Property/Variable Confusion <property/variable-confusion>`
* :ref:`Queries In Loops <queries-in-loops>`
* :ref:`Redeclared PHP Functions <redeclared-php-functions>`
* :ref:`Redefined Constants <redefined-constants>`
* :ref:`Redefined Default <redefined-default>`
* :ref:`Relay Function <relay-function>`
* :ref:`Repeated print() <repeated-print()>`
* :ref:`Sequences In For <sequences-in-for>`
* :ref:`Several Instructions On The Same Line <several-instructions-on-the-same-line>`
* :ref:`Short Open Tags <short-open-tags>`
* :ref:`Should Be Single Quote <should-be-single-quote>`
* :ref:`Should Chain Exception <should-chain-exception>`
* :ref:`Should Typecast <should-typecast>`
* :ref:`Should Use $this <should-use-$this>`
* :ref:`Should Use Constants <should-use-constants>`
* :ref:`Should Use Prepared Statement <should-use-prepared-statement>`
* :ref:`Silently Cast Integer <silently-cast-integer>`
* :ref:`Static Loop <static-loop>`
* :ref:`Static Methods Called From Object <static-methods-called-from-object>`
* :ref:`Static Methods Can't Contain $this <static-methods-can't-contain-$this>`
* :ref:`Strict comparison with booleans <strict-comparison-with-booleans>`
* :ref:`String May Hold A Variable <string-may-hold-a-variable>`
* :ref:`Strpos Comparison <strpos-comparison>`
* :ref:`Switch To Switch <switch-to-switch>`
* :ref:`Switch With Too Many Default <switch-with-too-many-default>`
* :ref:`Switch Without Default <switch-without-default>`
* :ref:`Ternary In Concat <ternary-in-concat>`
* :ref:`Throws An Assignement <throws-an-assignement>`
* :ref:`Timestamp Difference <timestamp-difference>`
* :ref:`Uncaught Exceptions <uncaught-exceptions>`
* :ref:`Unchecked Resources <unchecked-resources>`
* :ref:`Undefined Class Constants <undefined-class-constants>`
* :ref:`Undefined Classes <undefined-classes>`
* :ref:`Undefined Constants <undefined-constants>`
* :ref:`Undefined Functions <undefined-functions>`
* :ref:`Undefined Interfaces <undefined-interfaces>`
* :ref:`Undefined Parent <undefined-parent>`
* :ref:`Undefined Properties <undefined-properties>`
* :ref:`Undefined Trait <undefined-trait>`
* :ref:`Undefined static:: Or self:: <undefined-static\:\:-or-self\:\:>`
* :ref:`Unkown PCRE Options <unkown-pcre-options>`
* :ref:`Unpreprocessed Values <unpreprocessed-values>`
* :ref:`Unreachable Code <unreachable-code>`
* :ref:`Unresolved Classes <unresolved-classes>`
* :ref:`Unresolved Instanceof <unresolved-instanceof>`
* :ref:`Unresolved Use <unresolved-use>`
* :ref:`Unset In Foreach <unset-in-foreach>`
* :ref:`Unthrown Exception <unthrown-exception>`
* :ref:`Unused Arguments <unused-arguments>`
* :ref:`Unused Classes <unused-classes>`
* :ref:`Unused Constants <unused-constants>`
* :ref:`Unused Functions <unused-functions>`
* :ref:`Unused Global <unused-global>`
* :ref:`Unused Interfaces <unused-interfaces>`
* :ref:`Unused Label <unused-label>`
* :ref:`Unused Methods <unused-methods>`
* :ref:`Unused Static Methods <unused-static-methods>`
* :ref:`Unused Static Properties <unused-static-properties>`
* :ref:`Unused Trait <unused-trait>`
* :ref:`Unused Use <unused-use>`
* :ref:`Use === null <use-===-null>`
* :ref:`Use Constant As Arguments <use-constant-as-arguments>`
* :ref:`Use Instanceof <use-instanceof>`
* :ref:`Use Object Api <use-object-api>`
* :ref:`Use Pathinfo <use-pathinfo>`
* :ref:`Use With Fully Qualified Name <use-with-fully-qualified-name>`
* :ref:`Use const <use-const>`
* :ref:`Use random\_int() <use-random\_int()>`
* :ref:`Used Once Variables (In Scope) <used-once-variables-(in-scope)>`
* :ref:`Used Once Variables <used-once-variables>`
* :ref:`Useless Abstract Class <useless-abstract-class>`
* :ref:`Useless Brackets <useless-brackets>`
* :ref:`Useless Constructor <useless-constructor>`
* :ref:`Useless Final <useless-final>`
* :ref:`Useless Global <useless-global>`
* :ref:`Useless Instructions <useless-instructions>`
* :ref:`Useless Interfaces <useless-interfaces>`
* :ref:`Useless Parenthesis <useless-parenthesis>`
* :ref:`Useless Unset <useless-unset>`
* :ref:`Useless return <useless-return>`
* :ref:`Uses Default Values <uses-default-values>`
* :ref:`Using $this Outside A Class <using-$this-outside-a-class>`
* :ref:`Var`
* :ref:`While(List() = Each()) <while(list()-=-each())>`
* :ref:`Written Only Variables <written-only-variables>`
* :ref:`Wrong Number Of Arguments <wrong-number-of-arguments>`
* :ref:`Wrong Optional Parameter <wrong-optional-parameter>`
* :ref:`Wrong Parameter Type <wrong-parameter-type>`
* :ref:`\_\_toString() Throws Exception <\_\_tostring()-throws-exception>`
* :ref:`crypt() Without Salt <crypt()-without-salt>`
* :ref:`error\_reporting() With Integers <error\_reporting()-with-integers>`
* :ref:`eval() Without Try <eval()-without-try>`
* :ref:`ext/apc`
* :ref:`ext/fann`
* :ref:`ext/fdf`
* :ref:`ext/mysql`
* :ref:`ext/sqlite`
* :ref:`fopen() Mode <fopen()-mode>`
* :ref:`func\_get\_arg() Modified <func\_get\_arg()-modified>`
* :ref:`include\_once() Usage <include\_once()-usage>`
* :ref:`list() May Omit Variables <list()-may-omit-variables>`
* :ref:`mcrypt\_create\_iv() With Default Values <mcrypt\_create\_iv()-with-default-values>`
* :ref:`preg\_match\_all() Flag <preg\_match\_all()-flag>`
* :ref:`preg\_replace With Option e <preg\_replace-with-option-e>`
* :ref:`var\_dump()... Usage <var\_dump()...-usage>`

.. _compatibilityphp53:

CompatibilityPHP53
++++++++++++++++++

Total : 48 analysis

* :ref:`... Usage <...-usage>`
* :ref:`::class`
* :ref:`Anonymous Classes <anonymous-classes>`
* :ref:`Binary Glossary <binary-glossary>`
* :ref:`Break With 0 <break-with-0>`
* :ref:`Cant Use Return Value In Write Context <cant-use-return-value-in-write-context>`
* :ref:`Case For Parent, Static And Self <case-for-parent,-static-and-self>`
* :ref:`Class Const With Array <class-const-with-array>`
* :ref:`Closure May Use $this <closure-may-use-$this>`
* :ref:`Const With Array <const-with-array>`
* :ref:`Constant Scalar Expressions <constant-scalar-expressions>`
* :ref:`Define With Array <define-with-array>`
* :ref:`Dereferencing String And Arrays <dereferencing-string-and-arrays>`
* :ref:`Exponent Usage <exponent-usage>`
* :ref:`Foreach With list() <foreach-with-list()>`
* :ref:`Function Subscripting <function-subscripting>`
* :ref:`Hash Algorithms incompatible with PHP 5.3 <hash-algorithms-incompatible-with-php-5.3>`
* :ref:`Hexadecimal In String <hexadecimal-in-string>`
* :ref:`Isset With Constant <isset-with-constant>`
* :ref:`List With Appends <list-with-appends>`
* :ref:`Magic Visibility <magic-visibility>`
* :ref:`Methodcall On New <methodcall-on-new>`
* :ref:`Mixed Keys <mixed-keys>`
* :ref:`New Functions In PHP 5.4 <new-functions-in-php-5.4>`
* :ref:`New Functions In PHP 5.5 <new-functions-in-php-5.5>`
* :ref:`New Functions In PHP 5.6 <new-functions-in-php-5.6>`
* :ref:`No List With String <no-list-with-string>`
* :ref:`Null On New <null-on-new>`
* :ref:`PHP 7.0 New Classes <php-7.0-new-classes>`
* :ref:`PHP 7.0 New Functions <php-7.0-new-functions>`
* :ref:`PHP 7.0 New Interfaces <php-7.0-new-interfaces>`
* :ref:`PHP5 Indirect Variable Expression <php5-indirect-variable-expression>`
* :ref:`PHP7 Dirname <php7-dirname>`
* :ref:`Php 7 Indirect Expression <php-7-indirect-expression>`
* :ref:`Php7 Relaxed Keyword <php7-relaxed-keyword>`
* :ref:`Scalar Typehint Usage <scalar-typehint-usage>`
* :ref:`Short Syntax For Arrays <short-syntax-for-arrays>`
* :ref:`Unicode Escape Partial <unicode-escape-partial>`
* :ref:`Unicode Escape Syntax <unicode-escape-syntax>`
* :ref:`Use Const And Functions <use-const-and-functions>`
* :ref:`Usort Sorting In PHP 7.0 <usort-sorting-in-php-7.0>`
* :ref:`Variable Global <variable-global>`
* :ref:`\*\* For Exponent <**-for-exponent>`
* :ref:`\_\_debugInfo()`
* :ref:`eval() Without Try <eval()-without-try>`
* :ref:`ext/dba`
* :ref:`ext/fdf`
* :ref:`ext/ming`

.. _compatibilityphp54:

CompatibilityPHP54
++++++++++++++++++

Total : 43 analysis

* :ref:`... Usage <...-usage>`
* :ref:`::class`
* :ref:`Anonymous Classes <anonymous-classes>`
* :ref:`Break With Non Integer <break-with-non-integer>`
* :ref:`Calltime Pass By Reference <calltime-pass-by-reference>`
* :ref:`Cant Use Return Value In Write Context <cant-use-return-value-in-write-context>`
* :ref:`Case For Parent, Static And Self <case-for-parent,-static-and-self>`
* :ref:`Class Const With Array <class-const-with-array>`
* :ref:`Const With Array <const-with-array>`
* :ref:`Constant Scalar Expressions <constant-scalar-expressions>`
* :ref:`Define With Array <define-with-array>`
* :ref:`Dereferencing String And Arrays <dereferencing-string-and-arrays>`
* :ref:`Exponent Usage <exponent-usage>`
* :ref:`Foreach With list() <foreach-with-list()>`
* :ref:`Functions Removed In PHP 5.4 <functions-removed-in-php-5.4>`
* :ref:`Hash Algorithms incompatible with PHP 5.4/5 <hash-algorithms-incompatible-with-php-5.4/5>`
* :ref:`Hexadecimal In String <hexadecimal-in-string>`
* :ref:`Isset With Constant <isset-with-constant>`
* :ref:`List With Appends <list-with-appends>`
* :ref:`Magic Visibility <magic-visibility>`
* :ref:`Mixed Keys <mixed-keys>`
* :ref:`New Functions In PHP 5.5 <new-functions-in-php-5.5>`
* :ref:`New Functions In PHP 5.6 <new-functions-in-php-5.6>`
* :ref:`No List With String <no-list-with-string>`
* :ref:`Null On New <null-on-new>`
* :ref:`PHP 7.0 New Classes <php-7.0-new-classes>`
* :ref:`PHP 7.0 New Functions <php-7.0-new-functions>`
* :ref:`PHP 7.0 New Interfaces <php-7.0-new-interfaces>`
* :ref:`PHP5 Indirect Variable Expression <php5-indirect-variable-expression>`
* :ref:`PHP7 Dirname <php7-dirname>`
* :ref:`Php 7 Indirect Expression <php-7-indirect-expression>`
* :ref:`Php7 Relaxed Keyword <php7-relaxed-keyword>`
* :ref:`Scalar Typehint Usage <scalar-typehint-usage>`
* :ref:`Unicode Escape Partial <unicode-escape-partial>`
* :ref:`Unicode Escape Syntax <unicode-escape-syntax>`
* :ref:`Use Const And Functions <use-const-and-functions>`
* :ref:`Usort Sorting In PHP 7.0 <usort-sorting-in-php-7.0>`
* :ref:`Variable Global <variable-global>`
* :ref:`\*\* For Exponent <**-for-exponent>`
* :ref:`\_\_debugInfo()`
* :ref:`crypt() Without Salt <crypt()-without-salt>`
* :ref:`eval() Without Try <eval()-without-try>`
* :ref:`mcrypt\_create\_iv() With Default Values <mcrypt\_create\_iv()-with-default-values>`

.. _compatibilityphp55:

CompatibilityPHP55
++++++++++++++++++

Total : 41 analysis

* :ref:`... Usage <...-usage>`
* :ref:`Anonymous Classes <anonymous-classes>`
* :ref:`Break With Non Integer <break-with-non-integer>`
* :ref:`Calltime Pass By Reference <calltime-pass-by-reference>`
* :ref:`Class Const With Array <class-const-with-array>`
* :ref:`Const With Array <const-with-array>`
* :ref:`Constant Scalar Expressions <constant-scalar-expressions>`
* :ref:`Define With Array <define-with-array>`
* :ref:`Empty With Expression <empty-with-expression>`
* :ref:`Exponent Usage <exponent-usage>`
* :ref:`Functions Removed In PHP 5.4 <functions-removed-in-php-5.4>`
* :ref:`Functions Removed In PHP 5.5 <functions-removed-in-php-5.5>`
* :ref:`Hash Algorithms incompatible with PHP 5.4/5 <hash-algorithms-incompatible-with-php-5.4/5>`
* :ref:`Hexadecimal In String <hexadecimal-in-string>`
* :ref:`Isset With Constant <isset-with-constant>`
* :ref:`List With Appends <list-with-appends>`
* :ref:`Magic Visibility <magic-visibility>`
* :ref:`New Functions In PHP 5.6 <new-functions-in-php-5.6>`
* :ref:`No List With String <no-list-with-string>`
* :ref:`Null On New <null-on-new>`
* :ref:`PHP 7.0 New Classes <php-7.0-new-classes>`
* :ref:`PHP 7.0 New Functions <php-7.0-new-functions>`
* :ref:`PHP 7.0 New Interfaces <php-7.0-new-interfaces>`
* :ref:`PHP5 Indirect Variable Expression <php5-indirect-variable-expression>`
* :ref:`PHP7 Dirname <php7-dirname>`
* :ref:`Php 7 Indirect Expression <php-7-indirect-expression>`
* :ref:`Php7 Relaxed Keyword <php7-relaxed-keyword>`
* :ref:`Scalar Typehint Usage <scalar-typehint-usage>`
* :ref:`Unicode Escape Partial <unicode-escape-partial>`
* :ref:`Unicode Escape Syntax <unicode-escape-syntax>`
* :ref:`Use Const And Functions <use-const-and-functions>`
* :ref:`Use password\_hash() <use-password\_hash()>`
* :ref:`Usort Sorting In PHP 7.0 <usort-sorting-in-php-7.0>`
* :ref:`Variable Global <variable-global>`
* :ref:`\*\* For Exponent <**-for-exponent>`
* :ref:`\_\_debugInfo()`
* :ref:`crypt() Without Salt <crypt()-without-salt>`
* :ref:`eval() Without Try <eval()-without-try>`
* :ref:`ext/apc`
* :ref:`ext/mysql`
* :ref:`mcrypt\_create\_iv() With Default Values <mcrypt\_create\_iv()-with-default-values>`

.. _compatibilityphp56:

CompatibilityPHP56
++++++++++++++++++

Total : 34 analysis

* :ref:`$HTTP\_RAW\_POST\_DATA`
* :ref:`Anonymous Classes <anonymous-classes>`
* :ref:`Break With Non Integer <break-with-non-integer>`
* :ref:`Calltime Pass By Reference <calltime-pass-by-reference>`
* :ref:`Define With Array <define-with-array>`
* :ref:`Empty With Expression <empty-with-expression>`
* :ref:`Functions Removed In PHP 5.4 <functions-removed-in-php-5.4>`
* :ref:`Functions Removed In PHP 5.5 <functions-removed-in-php-5.5>`
* :ref:`Hash Algorithms incompatible with PHP 5.4/5 <hash-algorithms-incompatible-with-php-5.4/5>`
* :ref:`Hexadecimal In String <hexadecimal-in-string>`
* :ref:`Isset With Constant <isset-with-constant>`
* :ref:`List With Appends <list-with-appends>`
* :ref:`Magic Visibility <magic-visibility>`
* :ref:`No List With String <no-list-with-string>`
* :ref:`Non Static Methods Called In A Static <non-static-methods-called-in-a-static>`
* :ref:`Null On New <null-on-new>`
* :ref:`PHP 7.0 New Classes <php-7.0-new-classes>`
* :ref:`PHP 7.0 New Functions <php-7.0-new-functions>`
* :ref:`PHP 7.0 New Interfaces <php-7.0-new-interfaces>`
* :ref:`PHP5 Indirect Variable Expression <php5-indirect-variable-expression>`
* :ref:`PHP7 Dirname <php7-dirname>`
* :ref:`Php 7 Indirect Expression <php-7-indirect-expression>`
* :ref:`Php7 Relaxed Keyword <php7-relaxed-keyword>`
* :ref:`Scalar Typehint Usage <scalar-typehint-usage>`
* :ref:`Unicode Escape Partial <unicode-escape-partial>`
* :ref:`Unicode Escape Syntax <unicode-escape-syntax>`
* :ref:`Use password\_hash() <use-password\_hash()>`
* :ref:`Usort Sorting In PHP 7.0 <usort-sorting-in-php-7.0>`
* :ref:`Variable Global <variable-global>`
* :ref:`crypt() Without Salt <crypt()-without-salt>`
* :ref:`eval() Without Try <eval()-without-try>`
* :ref:`ext/apc`
* :ref:`ext/mysql`
* :ref:`mcrypt\_create\_iv() With Default Values <mcrypt\_create\_iv()-with-default-values>`

.. _compatibilityphp70:

CompatibilityPHP70
++++++++++++++++++

Total : 31 analysis

* :ref:`$HTTP\_RAW\_POST\_DATA`
* :ref:`Break Outside Loop <break-outside-loop>`
* :ref:`Break With Non Integer <break-with-non-integer>`
* :ref:`Calltime Pass By Reference <calltime-pass-by-reference>`
* :ref:`Empty List <empty-list>`
* :ref:`Empty With Expression <empty-with-expression>`
* :ref:`Foreach Dont Change Pointer <foreach-dont-change-pointer>`
* :ref:`Functions Removed In PHP 5.4 <functions-removed-in-php-5.4>`
* :ref:`Functions Removed In PHP 5.5 <functions-removed-in-php-5.5>`
* :ref:`Hash Algorithms incompatible with PHP 5.4/5 <hash-algorithms-incompatible-with-php-5.4/5>`
* :ref:`Hexadecimal In String <hexadecimal-in-string>`
* :ref:`List With Appends <list-with-appends>`
* :ref:`Multiple Definition Of The Same Argument <multiple-definition-of-the-same-argument>`
* :ref:`Non Static Methods Called In A Static <non-static-methods-called-in-a-static>`
* :ref:`PHP 70 Removed Directive <php-70-removed-directive>`
* :ref:`PHP 70 Removed Functions <php-70-removed-functions>`
* :ref:`Parenthesis As Parameter <parenthesis-as-parameter>`
* :ref:`Php 7 Indirect Expression <php-7-indirect-expression>`
* :ref:`Reserved Keywords In PHP 7 <reserved-keywords-in-php-7>`
* :ref:`Setlocale Needs Constants <setlocale-needs-constants>`
* :ref:`Simple Global Variable <simple-global-variable>`
* :ref:`Use password\_hash() <use-password\_hash()>`
* :ref:`Usort Sorting In PHP 7.0 <usort-sorting-in-php-7.0>`
* :ref:`crypt() Without Salt <crypt()-without-salt>`
* :ref:`ext/apc`
* :ref:`ext/ereg`
* :ref:`ext/mysql`
* :ref:`func\_get\_arg() Modified <func\_get\_arg()-modified>`
* :ref:`mcrypt\_create\_iv() With Default Values <mcrypt\_create\_iv()-with-default-values>`
* :ref:`preg\_replace With Option e <preg\_replace-with-option-e>`
* :ref:`set\_exception\_handler() Warning <set\_exception\_handler()-warning>`

.. _compatibilityphp71:

CompatibilityPHP71
++++++++++++++++++

Total : 36 analysis

* :ref:`$HTTP\_RAW\_POST\_DATA`
* :ref:`Break Outside Loop <break-outside-loop>`
* :ref:`Break With Non Integer <break-with-non-integer>`
* :ref:`Calltime Pass By Reference <calltime-pass-by-reference>`
* :ref:`Empty List <empty-list>`
* :ref:`Empty With Expression <empty-with-expression>`
* :ref:`Foreach Dont Change Pointer <foreach-dont-change-pointer>`
* :ref:`Functions Removed In PHP 5.4 <functions-removed-in-php-5.4>`
* :ref:`Functions Removed In PHP 5.5 <functions-removed-in-php-5.5>`
* :ref:`Hash Algorithms incompatible with PHP 5.4/5 <hash-algorithms-incompatible-with-php-5.4/5>`
* :ref:`Hexadecimal In String <hexadecimal-in-string>`
* :ref:`List With Appends <list-with-appends>`
* :ref:`Multiple Definition Of The Same Argument <multiple-definition-of-the-same-argument>`
* :ref:`New Functions In PHP 5.4 <new-functions-in-php-5.4>`
* :ref:`New Functions In PHP 5.5 <new-functions-in-php-5.5>`
* :ref:`Non Static Methods Called In A Static <non-static-methods-called-in-a-static>`
* :ref:`PHP 7.0 New Classes <php-7.0-new-classes>`
* :ref:`PHP 7.0 New Functions <php-7.0-new-functions>`
* :ref:`PHP 7.0 New Interfaces <php-7.0-new-interfaces>`
* :ref:`PHP 70 Removed Directive <php-70-removed-directive>`
* :ref:`PHP 70 Removed Functions <php-70-removed-functions>`
* :ref:`PHP Keywords As Names <php-keywords-as-names>`
* :ref:`Parenthesis As Parameter <parenthesis-as-parameter>`
* :ref:`Reserved Keywords In PHP 7 <reserved-keywords-in-php-7>`
* :ref:`Setlocale Needs Constants <setlocale-needs-constants>`
* :ref:`Simple Global Variable <simple-global-variable>`
* :ref:`Use password\_hash() <use-password\_hash()>`
* :ref:`Usort Sorting In PHP 7.0 <usort-sorting-in-php-7.0>`
* :ref:`crypt() Without Salt <crypt()-without-salt>`
* :ref:`ext/apc`
* :ref:`ext/ereg`
* :ref:`ext/mysql`
* :ref:`func\_get\_arg() Modified <func\_get\_arg()-modified>`
* :ref:`mcrypt\_create\_iv() With Default Values <mcrypt\_create\_iv()-with-default-values>`
* :ref:`preg\_replace With Option e <preg\_replace-with-option-e>`
* :ref:`set\_exception\_handler() Warning <set\_exception\_handler()-warning>`

.. _dead-code:

Dead code
+++++++++

Total : 19 analysis

* :ref:`Cant Extend Final <cant-extend-final>`
* :ref:`Empty Instructions <empty-instructions>`
* :ref:`Empty Namespace <empty-namespace>`
* :ref:`Exception Order <exception-order>`
* :ref:`Locally Unused Property <locally-unused-property>`
* :ref:`Unreachable Code <unreachable-code>`
* :ref:`Unresolved Catch <unresolved-catch>`
* :ref:`Unresolved Instanceof <unresolved-instanceof>`
* :ref:`Unset In Foreach <unset-in-foreach>`
* :ref:`Unthrown Exception <unthrown-exception>`
* :ref:`Unused Classes <unused-classes>`
* :ref:`Unused Constants <unused-constants>`
* :ref:`Unused Functions <unused-functions>`
* :ref:`Unused Interfaces <unused-interfaces>`
* :ref:`Unused Label <unused-label>`
* :ref:`Unused Methods <unused-methods>`
* :ref:`Unused Static Methods <unused-static-methods>`
* :ref:`Unused Static Properties <unused-static-properties>`
* :ref:`Unused Use <unused-use>`

.. _performances:

Performances
++++++++++++

Total : 14 analysis

* :ref:`Could Use Short Assignation <could-use-short-assignation>`
* :ref:`Echo With Concat <echo-with-concat>`
* :ref:`Echo With Concatenation <echo-with-concatenation>`
* :ref:`Eval() Usage <eval()-usage>`
* :ref:`For Using Functioncall <for-using-functioncall>`
* :ref:`Functions In Loop Calls <functions-in-loop-calls>`
* :ref:`Global Inside Loop <global-inside-loop>`
* :ref:`Join file() <join-file()>`
* :ref:`No array\_merge In Loops <no-array\_merge-in-loops>`
* :ref:`Not Substr One <not-substr-one>`
* :ref:`Pre-increment`
* :ref:`Simple Regex <simple-regex>`
* :ref:`Slow Functions <slow-functions>`
* :ref:`While(List() = Each()) <while(list()-=-each())>`

.. _security:

Security
++++++++

Total : 17 analysis

* :ref:`Avoid Those Crypto <avoid-those-crypto>`
* :ref:`Compare Hash <compare-hash>`
* :ref:`Direct Injection <direct-injection>`
* :ref:`Followed injections <followed-injections>`
* :ref:`Hardcoded Passwords <hardcoded-passwords>`
* :ref:`No Hardcoded Hash <no-hardcoded-hash>`
* :ref:`No Hardcoded Ip <no-hardcoded-ip>`
* :ref:`No Hardcoded Port <no-hardcoded-port>`
* :ref:`Random\_ Without Try <random\_-without-try>`
* :ref:`Register Globals <register-globals>`
* :ref:`Safe CurlOptions <safe-curloptions>`
* :ref:`Should Use Prepared Statement <should-use-prepared-statement>`
* :ref:`Sleep is a security risk <sleep-is-a-security-risk>`
* :ref:`Use random\_int() <use-random\_int()>`
* :ref:`parse\_str() Warning <parse\_str()-warning>`
* :ref:`preg\_replace With Option e <preg\_replace-with-option-e>`
* :ref:`var\_dump()... Usage <var\_dump()...-usage>`

