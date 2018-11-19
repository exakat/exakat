.. _Themes:

Themes
******

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

+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|Name                                           | Description                                                                                          |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Analyze`                                 | Check for common best practices.                                                                     |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CakePHP <cakephp>`                       | Check for code used with the Slim Framework                                                          |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Dead code <dead-code>`                   | Check the unused code or unreachable code.                                                           |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP70`                      | List features that are incompatible with PHP 7.0.                                                    |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP71`                      | List features that are incompatible with PHP 7.1.                                                    |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP72`                      | List features that are incompatible with PHP 7.2. It is also known as php-src.                       |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP56`                      | List features that are incompatible with PHP 5.6.                                                    |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Performances`                            | Check the code for slow code.                                                                        |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Security`                                | Check the code for common security bad practices, especially in the Web environnement.               |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Slim Framework <slim>`                   | Check for code used with the Slim Framework                                                          |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Wordpress`                               | Check for code used with the Wordpress platform                                                      |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Zend Framework <zendframework>`          | Check for code used with the Zend Framework 3                                                        |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP55`                      | List features that are incompatible with PHP 5.5.                                                    |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP54`                      | List features that are incompatible with PHP 5.4.                                                    |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP53`                      | List features that are incompatible with PHP 5.3.                                                    |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Coding Conventions <coding-conventions>` | List coding conventions violations.                                                                  |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+

Note : in command line, don't forget to add quotes to recipes's names that include white space.

Recipes details
###############

.. comment: The rest of the document is automatically generated. Don't modify it manually. 
.. comment: Recipes details
.. comment: Generation date : Mon, 19 Nov 2018 18:52:14 +0000
.. comment: Generation hash : 845bf7cf4400c3fdd90b759b77798493c3e51f06


.. _analyze:

Analyze
+++++++

Total : 350 analysis

* :ref:`$this Belongs To Classes Or Traits <$this-belongs-to-classes-or-traits>`
* :ref:`$this Is Not An Array <$this-is-not-an-array>`
* :ref:`$this Is Not For Static Methods <$this-is-not-for-static-methods>`
* :ref:`@ Operator <@-operator>`
* :ref:`Abstract Or Implements <abstract-or-implements>`
* :ref:`Abstract Static Methods <abstract-static-methods>`
* :ref:`Access Protected Structures <access-protected-structures>`
* :ref:`Accessing Private <accessing-private>`
* :ref:`Adding Zero <adding-zero>`
* :ref:`Aliases Usage <aliases-usage>`
* :ref:`Already Parents Interface <already-parents-interface>`
* :ref:`Altering Foreach Without Reference <altering-foreach-without-reference>`
* :ref:`Alternative Syntax Consistence <alternative-syntax-consistence>`
* :ref:`Always Positive Comparison <always-positive-comparison>`
* :ref:`Ambiguous Array Index <ambiguous-array-index>`
* :ref:`Ambiguous Static <ambiguous-static>`
* :ref:`Ambiguous Visibilities <ambiguous-visibilities>`
* :ref:`Assert Function Is Reserved <assert-function-is-reserved>`
* :ref:`Assign Default To Properties <assign-default-to-properties>`
* :ref:`Assign With And <assign-with-and>`
* :ref:`Assigned Twice <assigned-twice>`
* :ref:`Avoid Optional Properties <avoid-optional-properties>`
* :ref:`Avoid Parenthesis <avoid-parenthesis>`
* :ref:`Avoid Using stdClass <avoid-using-stdclass>`
* :ref:`Avoid get_class() <avoid-get\_class()>`
* :ref:`Bad Constants Names <bad-constants-names>`
* :ref:`Bail Out Early <bail-out-early>`
* :ref:`Break Outside Loop <break-outside-loop>`
* :ref:`Buried Assignation <buried-assignation>`
* :ref:`Callback Needs Return <callback-needs-return>`
* :ref:`Can't Extend Final <can't-extend-final>`
* :ref:`Can't Throw Throwable <can't-throw-throwable>`
* :ref:`Cant Instantiate Class <cant-instantiate-class>`
* :ref:`Cast To Boolean <cast-to-boolean>`
* :ref:`Catch Overwrite Variable <catch-overwrite-variable>`
* :ref:`Check All Types <check-all-types>`
* :ref:`Check JSON <check-json>`
* :ref:`Class Could Be Final <class-could-be-final>`
* :ref:`Class Function Confusion <class-function-confusion>`
* :ref:`Class Name Case Difference <class-name-case-difference>`
* :ref:`Class Should Be Final By Ocramius <class-should-be-final-by-ocramius>`
* :ref:`Class, Interface Or Trait With Identical Names <class,-interface-or-trait-with-identical-names>`
* :ref:`Classes Mutually Extending Each Other <classes-mutually-extending-each-other>`
* :ref:`Common Alternatives <common-alternatives>`
* :ref:`Compared Comparison <compared-comparison>`
* :ref:`Concrete Visibility <concrete-visibility>`
* :ref:`Constant Class <constant-class>`
* :ref:`Constants Created Outside Its Namespace <constants-created-outside-its-namespace>`
* :ref:`Constants With Strange Names <constants-with-strange-names>`
* :ref:`Continue Is For Loop <continue-is-for-loop>`
* :ref:`Could Be Abstract Class <could-be-abstract-class>`
* :ref:`Could Be Else <could-be-else>`
* :ref:`Could Be Static <could-be-static>`
* :ref:`Could Make A Function <could-make-a-function>`
* :ref:`Could Use Alias <could-use-alias>`
* :ref:`Could Use Short Assignation <could-use-short-assignation>`
* :ref:`Could Use __DIR__ <could-use-\_\_dir\_\_>`
* :ref:`Could Use self <could-use-self>`
* :ref:`Could Use str_repeat() <could-use-str\_repeat()>`
* :ref:`Crc32() Might Be Negative <crc32()-might-be-negative>`
* :ref:`Dangling Array References <dangling-array-references>`
* :ref:`Deep Definitions <deep-definitions>`
* :ref:`Dependant Trait <dependant-trait>`
* :ref:`Deprecated Functions <deprecated-functions>`
* :ref:`Don't Change Incomings <don't-change-incomings>`
* :ref:`Don't Echo Error <don't-echo-error>`
* :ref:`Don't Read And Write In One Expression <don't-read-and-write-in-one-expression>`
* :ref:`Don't Send $this In Constructor <don't-send-$this-in-constructor>`
* :ref:`Don't Unset Properties <don't-unset-properties>`
* :ref:`Dont Change The Blind Var <dont-change-the-blind-var>`
* :ref:`Dont Mix ++ <dont-mix-++>`
* :ref:`Double Assignation <double-assignation>`
* :ref:`Double Instructions <double-instructions>`
* :ref:`Drop Else After Return <drop-else-after-return>`
* :ref:`Echo With Concat <echo-with-concat>`
* :ref:`Else If Versus Elseif <else-if-versus-elseif>`
* :ref:`Empty Blocks <empty-blocks>`
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
* :ref:`Failed Substr Comparison <failed-substr-comparison>`
* :ref:`Foreach Needs Reference Array <foreach-needs-reference-array>`
* :ref:`Foreach On Object <foreach-on-object>`
* :ref:`Foreach Reference Is Not Modified <foreach-reference-is-not-modified>`
* :ref:`Forgotten Interface <forgotten-interface>`
* :ref:`Forgotten Thrown <forgotten-thrown>`
* :ref:`Forgotten Visibility <forgotten-visibility>`
* :ref:`Forgotten Whitespace <forgotten-whitespace>`
* :ref:`Fully Qualified Constants <fully-qualified-constants>`
* :ref:`Function Subscripting, Old Style <function-subscripting,-old-style>`
* :ref:`Global Usage <global-usage>`
* :ref:`Hardcoded Passwords <hardcoded-passwords>`
* :ref:`Hash Algorithms <hash-algorithms>`
* :ref:`Hidden Use Expression <hidden-use-expression>`
* :ref:`Htmlentities Calls <htmlentities-calls>`
* :ref:`Identical Conditions <identical-conditions>`
* :ref:`Identical Consecutive Expression <identical-consecutive-expression>`
* :ref:`Identical On Both Sides <identical-on-both-sides>`
* :ref:`If With Same Conditions <if-with-same-conditions>`
* :ref:`Iffectations`
* :ref:`Illegal Name For Method <illegal-name-for-method>`
* :ref:`Implement Is For Interface <implement-is-for-interface>`
* :ref:`Implemented Methods Are Public <implemented-methods-are-public>`
* :ref:`Implicit Global <implicit-global>`
* :ref:`Implied If <implied-if>`
* :ref:`Inclusion Wrong Case <inclusion-wrong-case>`
* :ref:`Incompatible Signature Methods <incompatible-signature-methods>`
* :ref:`Incompilable Files <incompilable-files>`
* :ref:`Inconsistent Elseif <inconsistent-elseif>`
* :ref:`Indices Are Int Or String <indices-are-int-or-string>`
* :ref:`Instantiating Abstract Class <instantiating-abstract-class>`
* :ref:`Invalid Constant Name <invalid-constant-name>`
* :ref:`Invalid Pack Format <invalid-pack-format>`
* :ref:`Invalid Regex <invalid-regex>`
* :ref:`Is Actually Zero <is-actually-zero>`
* :ref:`Local Globals <local-globals>`
* :ref:`Logical Mistakes <logical-mistakes>`
* :ref:`Logical Should Use Symbolic Operators <logical-should-use-symbolic-operators>`
* :ref:`Logical To in_array <logical-to-in\_array>`
* :ref:`Lone Blocks <lone-blocks>`
* :ref:`Long Arguments <long-arguments>`
* :ref:`Lost References <lost-references>`
* :ref:`Make Global A Property <make-global-a-property>`
* :ref:`Method Collision Traits <method-collision-traits>`
* :ref:`Method Signature Must Be Compatible <method-signature-must-be-compatible>`
* :ref:`Mismatch Type And Default <mismatch-type-and-default>`
* :ref:`Mismatched Default Arguments <mismatched-default-arguments>`
* :ref:`Mismatched Ternary Alternatives <mismatched-ternary-alternatives>`
* :ref:`Mismatched Typehint <mismatched-typehint>`
* :ref:`Missing Cases In Switch <missing-cases-in-switch>`
* :ref:`Missing Include <missing-include>`
* :ref:`Missing New ? <missing-new-?>`
* :ref:`Missing Parenthesis <missing-parenthesis>`
* :ref:`Mistaken Concatenation <mistaken-concatenation>`
* :ref:`Mixed Concat And Interpolation <mixed-concat-and-interpolation>`
* :ref:`Modernize Empty With Expression <modernize-empty-with-expression>`
* :ref:`Multiple Alias Definitions <multiple-alias-definitions>`
* :ref:`Multiple Alias Definitions Per File <multiple-alias-definitions-per-file>`
* :ref:`Multiple Class Declarations <multiple-class-declarations>`
* :ref:`Multiple Constant Definition <multiple-constant-definition>`
* :ref:`Multiple Identical Trait Or Interface <multiple-identical-trait-or-interface>`
* :ref:`Multiple Index Definition <multiple-index-definition>`
* :ref:`Multiple Type Variable <multiple-type-variable>`
* :ref:`Multiples Identical Case <multiples-identical-case>`
* :ref:`Multiply By One <multiply-by-one>`
* :ref:`Must Call Parent Constructor <must-call-parent-constructor>`
* :ref:`Must Return Methods <must-return-methods>`
* :ref:`Negative Power <negative-power>`
* :ref:`Nested Ifthen <nested-ifthen>`
* :ref:`Nested Ternary <nested-ternary>`
* :ref:`Never Used Parameter <never-used-parameter>`
* :ref:`Never Used Properties <never-used-properties>`
* :ref:`Next Month Trap <next-month-trap>`
* :ref:`No Boolean As Default <no-boolean-as-default>`
* :ref:`No Choice <no-choice>`
* :ref:`No Class As Typehint <no-class-as-typehint>`
* :ref:`No Class In Global <no-class-in-global>`
* :ref:`No Direct Call To Magic Method <no-direct-call-to-magic-method>`
* :ref:`No Direct Usage <no-direct-usage>`
* :ref:`No Empty Regex <no-empty-regex>`
* :ref:`No Hardcoded Hash <no-hardcoded-hash>`
* :ref:`No Hardcoded Ip <no-hardcoded-ip>`
* :ref:`No Hardcoded Path <no-hardcoded-path>`
* :ref:`No Hardcoded Port <no-hardcoded-port>`
* :ref:`No Magic With Array <no-magic-with-array>`
* :ref:`No Need For Else <no-need-for-else>`
* :ref:`No Parenthesis For Language Construct <no-parenthesis-for-language-construct>`
* :ref:`No Public Access <no-public-access>`
* :ref:`No Real Comparison <no-real-comparison>`
* :ref:`No Reference For Ternary <no-reference-for-ternary>`
* :ref:`No Reference On Left Side <no-reference-on-left-side>`
* :ref:`No Return Used <no-return-used>`
* :ref:`No Self Referencing Constant <no-self-referencing-constant>`
* :ref:`No Substr() One <no-substr()-one>`
* :ref:`No array_merge() In Loops <no-array\_merge()-in-loops>`
* :ref:`No get_class() With Null <no-get\_class()-with-null>`
* :ref:`No isset() With empty() <no-isset()-with-empty()>`
* :ref:`Non Ascii Variables <non-ascii-variables>`
* :ref:`Non Static Methods Called In A Static <non-static-methods-called-in-a-static>`
* :ref:`Non-constant Index In Array <non-constant-index-in-array>`
* :ref:`Not A Scalar Type <not-a-scalar-type>`
* :ref:`Not Not <not-not>`
* :ref:`Objects Don't Need References <objects-don't-need-references>`
* :ref:`Old Style Constructor <old-style-constructor>`
* :ref:`Old Style __autoload() <old-style-\_\_autoload()>`
* :ref:`One Letter Functions <one-letter-functions>`
* :ref:`One Variable String <one-variable-string>`
* :ref:`Only Variable For Reference <only-variable-for-reference>`
* :ref:`Only Variable Passed By Reference <only-variable-passed-by-reference>`
* :ref:`Only Variable Returned By Reference <only-variable-returned-by-reference>`
* :ref:`Or Die <or-die>`
* :ref:`Overwriting Variable <overwriting-variable>`
* :ref:`Overwritten Exceptions <overwritten-exceptions>`
* :ref:`Overwritten Literals <overwritten-literals>`
* :ref:`PHP Keywords As Names <php-keywords-as-names>`
* :ref:`Parent First <parent-first>`
* :ref:`Parent, Static Or Self Outside Class <parent,-static-or-self-outside-class>`
* :ref:`Pathinfo() Returns May Vary <pathinfo()-returns-may-vary>`
* :ref:`Phpinfo`
* :ref:`Possible Infinite Loop <possible-infinite-loop>`
* :ref:`Pre-increment`
* :ref:`Preprocessable`
* :ref:`Print And Die <print-and-die>`
* :ref:`Printf Number Of Arguments <printf-number-of-arguments>`
* :ref:`Property Could Be Local <property-could-be-local>`
* :ref:`Property Used In One Method Only <property-used-in-one-method-only>`
* :ref:`Property Variable Confusion <property-variable-confusion>`
* :ref:`Queries In Loops <queries-in-loops>`
* :ref:`Randomly Sorted Arrays <randomly-sorted-arrays>`
* :ref:`Redeclared PHP Functions <redeclared-php-functions>`
* :ref:`Redefined Class Constants <redefined-class-constants>`
* :ref:`Redefined Default <redefined-default>`
* :ref:`Redefined Private Property <redefined-private-property>`
* :ref:`Relay Function <relay-function>`
* :ref:`Repeated Interface <repeated-interface>`
* :ref:`Repeated Regex <repeated-regex>`
* :ref:`Repeated print() <repeated-print()>`
* :ref:`Results May Be Missing <results-may-be-missing>`
* :ref:`Return True False <return-true-false>`
* :ref:`Same Conditions In Condition <same-conditions-in-condition>`
* :ref:`Same Variables Foreach <same-variables-foreach>`
* :ref:`Scalar Or Object Property <scalar-or-object-property>`
* :ref:`Sequences In For <sequences-in-for>`
* :ref:`Several Instructions On The Same Line <several-instructions-on-the-same-line>`
* :ref:`Short Open Tags <short-open-tags>`
* :ref:`Should Chain Exception <should-chain-exception>`
* :ref:`Should Make Alias <should-make-alias>`
* :ref:`Should Make Ternary <should-make-ternary>`
* :ref:`Should Typecast <should-typecast>`
* :ref:`Should Use Coalesce <should-use-coalesce>`
* :ref:`Should Use Constants <should-use-constants>`
* :ref:`Should Use Local Class <should-use-local-class>`
* :ref:`Should Use Prepared Statement <should-use-prepared-statement>`
* :ref:`Should Use SetCookie() <should-use-setcookie()>`
* :ref:`Should Yield With Key <should-yield-with-key>`
* :ref:`Silently Cast Integer <silently-cast-integer>`
* :ref:`Static Loop <static-loop>`
* :ref:`Static Methods Called From Object <static-methods-called-from-object>`
* :ref:`Static Methods Can't Contain $this <static-methods-can't-contain-$this>`
* :ref:`Strange Name For Constants <strange-name-for-constants>`
* :ref:`Strange Name For Variables <strange-name-for-variables>`
* :ref:`Strict Comparison With Booleans <strict-comparison-with-booleans>`
* :ref:`String May Hold A Variable <string-may-hold-a-variable>`
* :ref:`Strings With Strange Space <strings-with-strange-space>`
* :ref:`Strpos Too Much <strpos-too-much>`
* :ref:`Strpos()-like Comparison <strpos()-like-comparison>`
* :ref:`Strtr Arguments <strtr-arguments>`
* :ref:`Suspicious Comparison <suspicious-comparison>`
* :ref:`Switch To Switch <switch-to-switch>`
* :ref:`Switch Without Default <switch-without-default>`
* :ref:`Ternary In Concat <ternary-in-concat>`
* :ref:`Test Then Cast <test-then-cast>`
* :ref:`Throw Functioncall <throw-functioncall>`
* :ref:`Throw In Destruct <throw-in-destruct>`
* :ref:`Throws An Assignement <throws-an-assignement>`
* :ref:`Timestamp Difference <timestamp-difference>`
* :ref:`Too Many Finds <too-many-finds>`
* :ref:`Too Many Injections <too-many-injections>`
* :ref:`Too Many Local Variables <too-many-local-variables>`
* :ref:`Too Many Native Calls <too-many-native-calls>`
* :ref:`Typehinted References <typehinted-references>`
* :ref:`Uncaught Exceptions <uncaught-exceptions>`
* :ref:`Unchecked Resources <unchecked-resources>`
* :ref:`Unconditional Break In Loop <unconditional-break-in-loop>`
* :ref:`Undeclared Static Property <undeclared-static-property>`
* :ref:`Undefined Class Constants <undefined-class-constants>`
* :ref:`Undefined Classes <undefined-classes>`
* :ref:`Undefined Constants <undefined-constants>`
* :ref:`Undefined Functions <undefined-functions>`
* :ref:`Undefined Insteadof <undefined-insteadof>`
* :ref:`Undefined Interfaces <undefined-interfaces>`
* :ref:`Undefined Parent <undefined-parent>`
* :ref:`Undefined Properties <undefined-properties>`
* :ref:`Undefined Trait <undefined-trait>`
* :ref:`Undefined Variable <undefined-variable>`
* :ref:`Undefined \:\:class <undefined-\:\:class>`
* :ref:`Undefined static\:\: Or self\:\: <undefined-static\:\:-or-self\:\:>`
* :ref:`Unitialized Properties <unitialized-properties>`
* :ref:`Unknown Directive Name <unknown-directive-name>`
* :ref:`Unknown Pcre2 Option <unknown-pcre2-option>`
* :ref:`Unkown Regex Options <unkown-regex-options>`
* :ref:`Unpreprocessed Values <unpreprocessed-values>`
* :ref:`Unreachable Code <unreachable-code>`
* :ref:`Unresolved Classes <unresolved-classes>`
* :ref:`Unresolved Instanceof <unresolved-instanceof>`
* :ref:`Unresolved Use <unresolved-use>`
* :ref:`Unset In Foreach <unset-in-foreach>`
* :ref:`Unthrown Exception <unthrown-exception>`
* :ref:`Unused Arguments <unused-arguments>`
* :ref:`Unused Global <unused-global>`
* :ref:`Unused Inherited Variable In Closure <unused-inherited-variable-in-closure>`
* :ref:`Unused Returned Value <unused-returned-value>`
* :ref:`Use === null <use-===-null>`
* :ref:`Use Class Operator <use-class-operator>`
* :ref:`Use Constant As Arguments <use-constant-as-arguments>`
* :ref:`Use Instanceof <use-instanceof>`
* :ref:`Use Named Boolean In Argument Definition <use-named-boolean-in-argument-definition>`
* :ref:`Use Object Api <use-object-api>`
* :ref:`Use Pathinfo <use-pathinfo>`
* :ref:`Use Positive Condition <use-positive-condition>`
* :ref:`Use System Tmp <use-system-tmp>`
* :ref:`Use With Fully Qualified Name <use-with-fully-qualified-name>`
* :ref:`Use const <use-const>`
* :ref:`Use random_int() <use-random\_int()>`
* :ref:`Used Once Property <used-once-property>`
* :ref:`Used Once Variables (In Scope) <used-once-variables-(in-scope)>`
* :ref:`Used Once Variables <used-once-variables>`
* :ref:`Useless Abstract Class <useless-abstract-class>`
* :ref:`Useless Brackets <useless-brackets>`
* :ref:`Useless Casting <useless-casting>`
* :ref:`Useless Catch <useless-catch>`
* :ref:`Useless Check <useless-check>`
* :ref:`Useless Constructor <useless-constructor>`
* :ref:`Useless Final <useless-final>`
* :ref:`Useless Global <useless-global>`
* :ref:`Useless Instructions <useless-instructions>`
* :ref:`Useless Interfaces <useless-interfaces>`
* :ref:`Useless Parenthesis <useless-parenthesis>`
* :ref:`Useless Referenced Argument <useless-referenced-argument>`
* :ref:`Useless Return <useless-return>`
* :ref:`Useless Switch <useless-switch>`
* :ref:`Useless Unset <useless-unset>`
* :ref:`Uses Default Values <uses-default-values>`
* :ref:`Using $this Outside A Class <using-$this-outside-a-class>`
* :ref:`Var Keyword <var-keyword>`
* :ref:`Weak Typing <weak-typing>`
* :ref:`While(List() = Each()) <while(list()-=-each())>`
* :ref:`Written Only Variables <written-only-variables>`
* :ref:`Wrong Number Of Arguments <wrong-number-of-arguments>`
* :ref:`Wrong Optional Parameter <wrong-optional-parameter>`
* :ref:`Wrong Parameter Type <wrong-parameter-type>`
* :ref:`Wrong Range Check <wrong-range-check>`
* :ref:`Wrong fopen() Mode <wrong-fopen()-mode>`
* :ref:`__DIR__ Then Slash <\_\_dir\_\_-then-slash>`
* :ref:`__toString() Throws Exception <\_\_tostring()-throws-exception>`
* :ref:`error_reporting() With Integers <error\_reporting()-with-integers>`
* :ref:`eval() Without Try <eval()-without-try>`
* :ref:`func_get_arg() Modified <func\_get\_arg()-modified>`
* :ref:`include_once() Usage <include\_once()-usage>`
* :ref:`list() May Omit Variables <list()-may-omit-variables>`
* :ref:`preg_replace With Option e <preg\_replace-with-option-e>`
* :ref:`self, parent, static Outside Class <self,-parent,-static-outside-class>`
* :ref:`var_dump()... Usage <var\_dump()...-usage>`

.. _cakephp:

Cakephp
+++++++

Total : 17 analysis

* :ref:`CakePHP 2.5.0 Undefined Classes <cakephp-2.5.0-undefined-classes>`
* :ref:`CakePHP 2.6.0 Undefined Classes <cakephp-2.6.0-undefined-classes>`
* :ref:`CakePHP 2.7.0 Undefined Classes <cakephp-2.7.0-undefined-classes>`
* :ref:`CakePHP 2.8.0 Undefined Classes <cakephp-2.8.0-undefined-classes>`
* :ref:`CakePHP 2.9.0 Undefined Classes <cakephp-2.9.0-undefined-classes>`
* :ref:`CakePHP 3.0 Deprecated Class <cakephp-3.0-deprecated-class>`
* :ref:`CakePHP 3.0.0 Undefined Classes <cakephp-3.0.0-undefined-classes>`
* :ref:`CakePHP 3.1.0 Undefined Classes <cakephp-3.1.0-undefined-classes>`
* :ref:`CakePHP 3.2.0 Undefined Classes <cakephp-3.2.0-undefined-classes>`
* :ref:`CakePHP 3.3 Deprecated Class <cakephp-3.3-deprecated-class>`
* :ref:`CakePHP 3.3.0 Undefined Classes <cakephp-3.3.0-undefined-classes>`
* :ref:`CakePHP 3.4.0 Undefined Classes <cakephp-3.4.0-undefined-classes>`
* :ref:`CakePHP Used <cakephp-used>`
* :ref:`Deprecated Methodcalls in Cake 3.2 <deprecated-methodcalls-in-cake-3.2>`
* :ref:`Deprecated Methodcalls in Cake 3.3 <deprecated-methodcalls-in-cake-3.3>`
* :ref:`Deprecated Static calls in Cake 3.3 <deprecated-static-calls-in-cake-3.3>`
* :ref:`Deprecated Trait in Cake 3.3 <deprecated-trait-in-cake-3.3>`

.. _classreview:

ClassReview
+++++++++++

Total : 18 analysis

* :ref:`Avoid Self In Interface <avoid-self-in-interface>`
* :ref:`Classes/UnreachableConstant`
* :ref:`Could Be Class Constant <could-be-class-constant>`
* :ref:`Could Be Private Class Constant <could-be-private-class-constant>`
* :ref:`Could Be Protected Class Constant <could-be-protected-class-constant>`
* :ref:`Could Be Protected Method <could-be-protected-method>`
* :ref:`Could Be Protected Property <could-be-protected-property>`
* :ref:`Final Class Usage <final-class-usage>`
* :ref:`Final Methods Usage <final-methods-usage>`
* :ref:`Method Could Be Private Method <method-could-be-private-method>`
* :ref:`Property Could Be Private Property <property-could-be-private-property>`
* :ref:`Raised Access Level <raised-access-level>`
* :ref:`Redefined Property <redefined-property>`
* Classes/CouldBeAbstractClass
* Classes/CouldBeFinal
* Classes/PropertyCouldBeLocal
* Classes/UndeclaredStaticProperty
* Structures/CouldBeStatic

.. _coding-conventions:

Coding Conventions
++++++++++++++++++

Total : 21 analysis

* :ref:`All Uppercase Variables <all-uppercase-variables>`
* :ref:`Bracketless Blocks <bracketless-blocks>`
* :ref:`Close Tags <close-tags>`
* :ref:`Constant Comparison <constant-comparison>`
* :ref:`Curly Arrays <curly-arrays>`
* :ref:`Echo Or Print <echo-or-print>`
* :ref:`Empty Slots In Arrays <empty-slots-in-arrays>`
* :ref:`Heredoc Delimiter <heredoc-delimiter>`
* :ref:`Interpolation`
* :ref:`Multiple Classes In One File <multiple-classes-in-one-file>`
* :ref:`No Plus One <no-plus-one>`
* :ref:`Non-lowercase Keywords <non-lowercase-keywords>`
* :ref:`Order Of Declaration <order-of-declaration>`
* :ref:`Return With Parenthesis <return-with-parenthesis>`
* :ref:`Should Be Single Quote <should-be-single-quote>`
* :ref:`Unusual Case For PHP Functions <unusual-case-for-php-functions>`
* :ref:`Yoda Comparison <yoda-comparison>`
* Classes/WrongCase
* Constants/ConstRecommended
* Namespaces/UseWithFullyQualifiedNS
* Structures/MixedConcatInterpolation

.. _compatibilityphp53:

CompatibilityPHP53
++++++++++++++++++

Total : 74 analysis

* :ref:`Anonymous Classes <anonymous-classes>`
* :ref:`Binary Glossary <binary-glossary>`
* :ref:`Break With 0 <break-with-0>`
* :ref:`Cant Inherit Abstract Method <cant-inherit-abstract-method>`
* :ref:`Cant Use Return Value In Write Context <cant-use-return-value-in-write-context>`
* :ref:`Child Class Removes Typehint <child-class-removes-typehint>`
* :ref:`Class Const With Array <class-const-with-array>`
* :ref:`Closure May Use $this <closure-may-use-$this>`
* :ref:`Const Visibility Usage <const-visibility-usage>`
* :ref:`Const With Array <const-with-array>`
* :ref:`Constant Scalar Expressions <constant-scalar-expressions>`
* :ref:`Define With Array <define-with-array>`
* :ref:`Dereferencing String And Arrays <dereferencing-string-and-arrays>`
* :ref:`Direct Call To __clone <direct-call-to-\_\_clone>`
* :ref:`Ellipsis Usage <ellipsis-usage>`
* :ref:`Exponent Usage <exponent-usage>`
* :ref:`Flexible Heredoc <flexible-heredoc>`
* :ref:`Foreach With list() <foreach-with-list()>`
* :ref:`Function Subscripting <function-subscripting>`
* :ref:`Group Use Declaration <group-use-declaration>`
* :ref:`Group Use Trailing Comma <group-use-trailing-comma>`
* :ref:`Hash Algorithms Incompatible With PHP 5.3 <hash-algorithms-incompatible-with-php-5.3>`
* :ref:`Hash Algorithms Incompatible With PHP 7.1- <hash-algorithms-incompatible-with-php-7.1->`
* :ref:`Integer As Property <integer-as-property>`
* :ref:`List Short Syntax <list-short-syntax>`
* :ref:`List With Keys <list-with-keys>`
* :ref:`List With Reference <list-with-reference>`
* :ref:`Malformed Octal <malformed-octal>`
* :ref:`Methodcall On New <methodcall-on-new>`
* :ref:`Mixed Keys Arrays <mixed-keys-arrays>`
* :ref:`Multiple Definition Of The Same Argument <multiple-definition-of-the-same-argument>`
* :ref:`Multiple Exceptions Catch() <multiple-exceptions-catch()>`
* :ref:`New Functions In PHP 5.4 <new-functions-in-php-5.4>`
* :ref:`New Functions In PHP 5.5 <new-functions-in-php-5.5>`
* :ref:`New Functions In PHP 5.6 <new-functions-in-php-5.6>`
* :ref:`New Functions In PHP 7.0 <new-functions-in-php-7.0>`
* :ref:`New Functions In PHP 7.3 <new-functions-in-php-7.3>`
* :ref:`No List With String <no-list-with-string>`
* :ref:`No Reference For Static Property <no-reference-for-static-property>`
* :ref:`No Return For Generator <no-return-for-generator>`
* :ref:`No String With Append <no-string-with-append>`
* :ref:`No Substr Minus One <no-substr-minus-one>`
* :ref:`Null On New <null-on-new>`
* :ref:`PHP 7.0 New Classes <php-7.0-new-classes>`
* :ref:`PHP 7.0 New Interfaces <php-7.0-new-interfaces>`
* :ref:`PHP 7.0 Scalar Typehints <php-7.0-scalar-typehints>`
* :ref:`PHP 7.1 Scalar Typehints <php-7.1-scalar-typehints>`
* :ref:`PHP 7.2 Scalar Typehints <php-7.2-scalar-typehints>`
* :ref:`PHP 7.3 Last Empty Argument <php-7.3-last-empty-argument>`
* :ref:`PHP5 Indirect Variable Expression <php5-indirect-variable-expression>`
* :ref:`PHP7 Dirname <php7-dirname>`
* :ref:`Parenthesis As Parameter <parenthesis-as-parameter>`
* :ref:`Php 7 Indirect Expression <php-7-indirect-expression>`
* :ref:`Php 7.1 New Class <php-7.1-new-class>`
* :ref:`Php 7.2 New Class <php-7.2-new-class>`
* :ref:`Php7 Relaxed Keyword <php7-relaxed-keyword>`
* :ref:`Short Syntax For Arrays <short-syntax-for-arrays>`
* :ref:`Switch With Too Many Default <switch-with-too-many-default>`
* :ref:`Trailing Comma In Calls <trailing-comma-in-calls>`
* :ref:`Unicode Escape Partial <unicode-escape-partial>`
* :ref:`Unicode Escape Syntax <unicode-escape-syntax>`
* :ref:`Use Const And Functions <use-const-and-functions>`
* :ref:`Use Lower Case For Parent, Static And Self <use-lower-case-for-parent,-static-and-self>`
* :ref:`Use Nullable Type <use-nullable-type>`
* :ref:`Variable Global <variable-global>`
* :ref:`\:\:class`
* :ref:`__debugInfo() Usage <\_\_debuginfo()-usage>`
* :ref:`ext/dba`
* :ref:`ext/fdf`
* :ref:`ext/ming`
* :ref:`isset() With Constant <isset()-with-constant>`
* Classes/NonStaticMethodsCalledStatic
* Structures/ContinueIsForLoop
* Structures/NoGetClassNull

.. _compatibilityphp54:

CompatibilityPHP54
++++++++++++++++++

Total : 70 analysis

* :ref:`Break With Non Integer <break-with-non-integer>`
* :ref:`Calltime Pass By Reference <calltime-pass-by-reference>`
* :ref:`Functions Removed In PHP 5.4 <functions-removed-in-php-5.4>`
* :ref:`Hash Algorithms Incompatible With PHP 5.4/5.5 <hash-algorithms-incompatible-with-php-5.4/5.5>`
* :ref:`crypt() Without Salt <crypt()-without-salt>`
* :ref:`ext/mhash`
* Arrays/MixedKeys
* Classes/Anonymous
* Classes/CantInheritAbstractMethod
* Classes/ChildRemoveTypehint
* Classes/ConstVisibilityUsage
* Classes/IntegerAsProperty
* Classes/NonStaticMethodsCalledStatic
* Classes/NullOnNew
* Exceptions/MultipleCatch
* Functions/MultipleSameArguments
* Namespaces/UseFunctionsConstants
* Php/CantUseReturnValueInWriteContext
* Php/CaseForPSS
* Php/ClassConstWithArray
* Php/ConstWithArray
* Php/DefineWithArray
* Php/DirectCallToClone
* Php/EllipsisUsage
* Php/ExponentUsage
* Php/FlexibleHeredoc
* Php/GroupUseDeclaration
* Php/GroupUseTrailingComma
* Php/HashAlgos53
* Php/HashAlgos71
* Php/ListShortSyntax
* Php/ListWithKeys
* Php/ListWithReference
* Php/NoListWithString
* Php/NoReferenceForStaticProperty
* Php/NoReturnForGenerator
* Php/NoStringWithAppend
* Php/NoSubstrMinusOne
* Php/PHP70scalartypehints
* Php/PHP71scalartypehints
* Php/PHP72scalartypehints
* Php/PHP73LastEmptyArgument
* Php/ParenthesisAsParameter
* Php/Php55NewFunctions
* Php/Php56NewFunctions
* Php/Php70NewClasses
* Php/Php70NewFunctions
* Php/Php70NewInterfaces
* Php/Php71NewClasses
* Php/Php72NewClasses
* Php/Php73NewFunctions
* Php/Php7RelaxedKeyword
* Php/StaticclassUsage
* Php/TrailingComma
* Php/UnicodeEscapePartial
* Php/UnicodeEscapeSyntax
* Php/UseNullableType
* Php/debugInfoUsage
* Structures/ConstantScalarExpression
* Structures/ContinueIsForLoop
* Structures/DereferencingAS
* Structures/ForeachWithList
* Structures/IssetWithConstant
* Structures/NoGetClassNull
* Structures/PHP7Dirname
* Structures/SwitchWithMultipleDefault
* Structures/VariableGlobal
* Type/MalformedOctal
* Variables/Php5IndirectExpression
* Variables/Php7IndirectExpression

.. _compatibilityphp55:

CompatibilityPHP55
++++++++++++++++++

Total : 62 analysis

* :ref:`Functions Removed In PHP 5.5 <functions-removed-in-php-5.5>`
* :ref:`Use password_hash() <use-password\_hash()>`
* :ref:`ext/apc`
* :ref:`ext/mysql`
* Classes/Anonymous
* Classes/CantInheritAbstractMethod
* Classes/ChildRemoveTypehint
* Classes/ConstVisibilityUsage
* Classes/IntegerAsProperty
* Classes/NonStaticMethodsCalledStatic
* Classes/NullOnNew
* Exceptions/MultipleCatch
* Functions/MultipleSameArguments
* Namespaces/UseFunctionsConstants
* Php/ClassConstWithArray
* Php/ConstWithArray
* Php/DefineWithArray
* Php/DirectCallToClone
* Php/EllipsisUsage
* Php/ExponentUsage
* Php/FlexibleHeredoc
* Php/GroupUseDeclaration
* Php/GroupUseTrailingComma
* Php/HashAlgos53
* Php/HashAlgos54
* Php/HashAlgos71
* Php/ListShortSyntax
* Php/ListWithKeys
* Php/ListWithReference
* Php/NoListWithString
* Php/NoReferenceForStaticProperty
* Php/NoReturnForGenerator
* Php/NoStringWithAppend
* Php/NoSubstrMinusOne
* Php/PHP70scalartypehints
* Php/PHP71scalartypehints
* Php/PHP72scalartypehints
* Php/PHP73LastEmptyArgument
* Php/ParenthesisAsParameter
* Php/Php56NewFunctions
* Php/Php70NewClasses
* Php/Php70NewFunctions
* Php/Php70NewInterfaces
* Php/Php71NewClasses
* Php/Php72NewClasses
* Php/Php73NewFunctions
* Php/Php7RelaxedKeyword
* Php/TrailingComma
* Php/UnicodeEscapePartial
* Php/UnicodeEscapeSyntax
* Php/UseNullableType
* Php/debugInfoUsage
* Structures/ConstantScalarExpression
* Structures/ContinueIsForLoop
* Structures/IssetWithConstant
* Structures/NoGetClassNull
* Structures/PHP7Dirname
* Structures/SwitchWithMultipleDefault
* Structures/VariableGlobal
* Type/MalformedOctal
* Variables/Php5IndirectExpression
* Variables/Php7IndirectExpression

.. _compatibilityphp56:

CompatibilityPHP56
++++++++++++++++++

Total : 51 analysis

* :ref:`$HTTP_RAW_POST_DATA`
* Classes/Anonymous
* Classes/CantInheritAbstractMethod
* Classes/ChildRemoveTypehint
* Classes/ConstVisibilityUsage
* Classes/IntegerAsProperty
* Classes/NonStaticMethodsCalledStatic
* Classes/NullOnNew
* Exceptions/MultipleCatch
* Functions/MultipleSameArguments
* Php/DefineWithArray
* Php/DirectCallToClone
* Php/FlexibleHeredoc
* Php/GroupUseDeclaration
* Php/GroupUseTrailingComma
* Php/HashAlgos53
* Php/HashAlgos54
* Php/HashAlgos71
* Php/ListShortSyntax
* Php/ListWithKeys
* Php/ListWithReference
* Php/NoListWithString
* Php/NoReferenceForStaticProperty
* Php/NoReturnForGenerator
* Php/NoStringWithAppend
* Php/NoSubstrMinusOne
* Php/PHP70scalartypehints
* Php/PHP71scalartypehints
* Php/PHP72scalartypehints
* Php/PHP73LastEmptyArgument
* Php/ParenthesisAsParameter
* Php/Php70NewClasses
* Php/Php70NewFunctions
* Php/Php70NewInterfaces
* Php/Php71NewClasses
* Php/Php72NewClasses
* Php/Php73NewFunctions
* Php/Php7RelaxedKeyword
* Php/TrailingComma
* Php/UnicodeEscapePartial
* Php/UnicodeEscapeSyntax
* Php/UseNullableType
* Structures/ContinueIsForLoop
* Structures/IssetWithConstant
* Structures/NoGetClassNull
* Structures/PHP7Dirname
* Structures/SwitchWithMultipleDefault
* Structures/VariableGlobal
* Type/MalformedOctal
* Variables/Php5IndirectExpression
* Variables/Php7IndirectExpression

.. _compatibilityphp70:

CompatibilityPHP70
++++++++++++++++++

Total : 43 analysis

* :ref:`Foreach Don't Change Pointer <foreach-don't-change-pointer>`
* :ref:`Hexadecimal In String <hexadecimal-in-string>`
* :ref:`List With Appends <list-with-appends>`
* :ref:`Magic Visibility <magic-visibility>`
* :ref:`PHP 7.0 Removed Directives <php-7.0-removed-directives>`
* :ref:`PHP 70 Removed Functions <php-70-removed-functions>`
* :ref:`Reserved Keywords In PHP 7 <reserved-keywords-in-php-7>`
* :ref:`Setlocale() Uses Constants <setlocale()-uses-constants>`
* :ref:`Simple Global Variable <simple-global-variable>`
* :ref:`Usort Sorting In PHP 7.0 <usort-sorting-in-php-7.0>`
* :ref:`ext/ereg`
* :ref:`mcrypt_create_iv() With Default Values <mcrypt\_create\_iv()-with-default-values>`
* :ref:`set_exception_handler() Warning <set\_exception\_handler()-warning>`
* Classes/CantInheritAbstractMethod
* Classes/ChildRemoveTypehint
* Classes/ConstVisibilityUsage
* Classes/IntegerAsProperty
* Exceptions/MultipleCatch
* Functions/funcGetArgModified
* Php/EmptyList
* Php/FlexibleHeredoc
* Php/GroupUseTrailingComma
* Php/HashAlgos53
* Php/HashAlgos54
* Php/HashAlgos71
* Php/ListShortSyntax
* Php/ListWithKeys
* Php/ListWithReference
* Php/NoReferenceForStaticProperty
* Php/NoSubstrMinusOne
* Php/PHP71scalartypehints
* Php/PHP72scalartypehints
* Php/PHP73LastEmptyArgument
* Php/Php71NewClasses
* Php/Php72NewClasses
* Php/Php73NewFunctions
* Php/TrailingComma
* Php/UseNullableType
* Structures/BreakOutsideLoop
* Structures/ContinueIsForLoop
* Structures/NoGetClassNull
* Structures/pregOptionE
* Variables/Php7IndirectExpression

.. _compatibilityphp71:

CompatibilityPHP71
++++++++++++++++++

Total : 28 analysis

* :ref:`Invalid Octal In String <invalid-octal-in-string>`
* :ref:`New Functions In PHP 7.1 <new-functions-in-php-7.1>`
* :ref:`PHP 7.1 Microseconds <php-7.1-microseconds>`
* :ref:`PHP 7.1 Removed Directives <php-7.1-removed-directives>`
* :ref:`ext/mcrypt`
* Classes/CantInheritAbstractMethod
* Classes/ChildRemoveTypehint
* Classes/IntegerAsProperty
* Classes/UsingThisOutsideAClass
* Php/BetterRand
* Php/FlexibleHeredoc
* Php/GroupUseTrailingComma
* Php/HashAlgos53
* Php/HashAlgos54
* Php/ListWithReference
* Php/NoReferenceForStaticProperty
* Php/PHP72scalartypehints
* Php/PHP73LastEmptyArgument
* Php/Php70RemovedDirective
* Php/Php70RemovedFunctions
* Php/Php72NewClasses
* Php/Php73NewFunctions
* Php/TrailingComma
* Structures/ContinueIsForLoop
* Structures/NoGetClassNull
* Structures/NoSubstrOne
* Structures/pregOptionE
* Type/HexadecimalString

.. _compatibilityphp72:

CompatibilityPHP72
++++++++++++++++++

Total : 21 analysis

* :ref:`Avoid set_error_handler $context Argument <avoid-set\_error\_handler-$context-argument>`
* :ref:`Can't Count Non-Countable <can't-count-non-countable>`
* :ref:`Hash Will Use Objects <hash-will-use-objects>`
* :ref:`New Constants In PHP 7.2 <new-constants-in-php-7.2>`
* :ref:`New Functions In PHP 7.2 <new-functions-in-php-7.2>`
* :ref:`PHP 7.2 Deprecations <php-7.2-deprecations>`
* :ref:`PHP 7.2 Object Keyword <php-7.2-object-keyword>`
* :ref:`PHP 7.2 Removed Functions <php-7.2-removed-functions>`
* Constants/UndefinedConstants
* Php/FlexibleHeredoc
* Php/HashAlgos53
* Php/HashAlgos54
* Php/ListWithReference
* Php/NoReferenceForStaticProperty
* Php/PHP73LastEmptyArgument
* Php/Php72NewClasses
* Php/Php73NewFunctions
* Php/TrailingComma
* Structures/ContinueIsForLoop
* Structures/NoGetClassNull
* Structures/pregOptionE

.. _compatibilityphp73:

CompatibilityPHP73
++++++++++++++++++

Total : 8 analysis

* :ref:`Case Insensitive Constants <case-insensitive-constants>`
* :ref:`Compact Inexistant Variable <compact-inexistant-variable>`
* :ref:`PHP 7.3 Removed Functions <php-7.3-removed-functions>`
* Php/AssertFunctionIsReserved
* Php/Php73NewFunctions
* Php/UnknownPcre2Option
* Structures/ContinueIsForLoop
* Structures/DontReadAndWriteInOneExpression

.. _compatibilityphp74:

CompatibilityPHP74
++++++++++++++++++

Total : 2 analysis

* :ref:`Detect Current Class <detect-current-class>`
* :ref:`idn_to_ascii() New Default <idn\_to\_ascii()-new-default>`

.. _dead-code:

Dead code
+++++++++

Total : 24 analysis

* :ref:`Exception Order <exception-order>`
* :ref:`Locally Unused Property <locally-unused-property>`
* :ref:`Rethrown Exceptions <rethrown-exceptions>`
* :ref:`Undefined Caught Exceptions <undefined-caught-exceptions>`
* :ref:`Unresolved Catch <unresolved-catch>`
* :ref:`Unused Classes <unused-classes>`
* :ref:`Unused Constants <unused-constants>`
* :ref:`Unused Functions <unused-functions>`
* :ref:`Unused Interfaces <unused-interfaces>`
* :ref:`Unused Label <unused-label>`
* :ref:`Unused Methods <unused-methods>`
* :ref:`Unused Private Methods <unused-private-methods>`
* :ref:`Unused Private Properties <unused-private-properties>`
* :ref:`Unused Protected Methods <unused-protected-methods>`
* :ref:`Unused Use <unused-use>`
* Classes/CantExtendFinal
* Classes/UnresolvedInstanceof
* Exceptions/Unthrown
* Functions/UnusedInheritedVariable
* Functions/UnusedReturnedValue
* Namespaces/EmptyNamespace
* Structures/EmptyLines
* Structures/UnreachableCode
* Structures/UnsetInForeach

.. _lintbutwontexec:

LintButWontExec
+++++++++++++++

Total : 14 analysis

* Classes/AbstractOrImplements
* Classes/Finalclass
* Classes/Finalmethod
* Classes/IncompatibleSignature
* Classes/MutualExtension
* Classes/NoPSSOutsideClass
* Classes/NoSelfReferencingConstant
* Classes/UsingThisOutsideAClass
* Exceptions/CantThrow
* Functions/OnlyVariableForReference
* Interfaces/ConcreteVisibility
* Traits/MethodCollisionTraits
* Traits/UndefinedInsteadof
* Traits/UndefinedTrait

.. _performances:

Performances
++++++++++++

Total : 36 analysis

* :ref:`Avoid Concat In Loop <avoid-concat-in-loop>`
* :ref:`Avoid Large Array Assignation <avoid-large-array-assignation>`
* :ref:`Avoid array_push() <avoid-array\_push()>`
* :ref:`Avoid array_unique() <avoid-array\_unique()>`
* :ref:`Avoid glob() Usage <avoid-glob()-usage>`
* :ref:`Cache Variable Outside Loop <cache-variable-outside-loop>`
* :ref:`Closure Could Be A Callback <closure-could-be-a-callback>`
* :ref:`Do In Base <do-in-base>`
* :ref:`Double array_flip() <double-array\_flip()>`
* :ref:`Fetch One Row Format <fetch-one-row-format>`
* :ref:`For Using Functioncall <for-using-functioncall>`
* :ref:`Getting Last Element <getting-last-element>`
* :ref:`Global Inside Loop <global-inside-loop>`
* :ref:`Join file() <join-file()>`
* :ref:`Make One Call With Array <make-one-call-with-array>`
* :ref:`No Count With 0 <no-count-with-0>`
* :ref:`Processing Collector <processing-collector>`
* :ref:`Should Use Function <should-use-function>`
* :ref:`Should Use array_column() <should-use-array\_column()>`
* :ref:`Simple Switch <simple-switch>`
* :ref:`Simplify Regex <simplify-regex>`
* :ref:`Slice Arrays First <slice-arrays-first>`
* :ref:`Slow Functions <slow-functions>`
* :ref:`Substring First <substring-first>`
* :ref:`Use PHP7 Encapsed Strings <use-php7-encapsed-strings>`
* :ref:`Use The Blind Var <use-the-blind-var>`
* :ref:`Use pathinfo() Arguments <use-pathinfo()-arguments>`
* :ref:`time() Vs strtotime() <time()-vs-strtotime()>`
* Classes/UseClassOperator
* Performances/ArrayMergeInLoops
* Performances/PrePostIncrement
* Structures/CouldUseShortAssignation
* Structures/EchoWithConcat
* Structures/EvalUsage
* Structures/NoSubstrOne
* Structures/WhileListEach

.. _security:

Security
++++++++

Total : 38 analysis

* :ref:`Always Anchor Regex <always-anchor-regex>`
* :ref:`Avoid Those Hash Functions <avoid-those-hash-functions>`
* :ref:`Avoid sleep()/usleep() <avoid-sleep()/usleep()>`
* :ref:`Can't Disable Class <can't-disable-class>`
* :ref:`Compare Hash <compare-hash>`
* :ref:`Configure Extract <configure-extract>`
* :ref:`Direct Injection <direct-injection>`
* :ref:`Dynamic Library Loading <dynamic-library-loading>`
* :ref:`Encoded Simple Letters <encoded-simple-letters>`
* :ref:`Indirect Injection <indirect-injection>`
* :ref:`Mkdir Default <mkdir-default>`
* :ref:`No Net For Xml Load <no-net-for-xml-load>`
* :ref:`No Return Or Throw In Finally <no-return-or-throw-in-finally>`
* :ref:`Random Without Try <random-without-try>`
* :ref:`Register Globals <register-globals>`
* :ref:`Safe Curl Options <safe-curl-options>`
* :ref:`Session Lazy Write <session-lazy-write>`
* :ref:`Set Cookie Safe Arguments <set-cookie-safe-arguments>`
* :ref:`Should Use session_regenerateid() <should-use-session\_regenerateid()>`
* :ref:`Sqlite3 Requires Single Quotes <sqlite3-requires-single-quotes>`
* :ref:`Switch Fallthrough <switch-fallthrough>`
* :ref:`Unserialize Second Arg <unserialize-second-arg>`
* :ref:`Upload Filename Injection <upload-filename-injection>`
* :ref:`filter_input() As A Source <filter\_input()-as-a-source>`
* :ref:`move_uploaded_file Instead Of copy <move\_uploaded\_file-instead-of-copy>`
* :ref:`parse_str() Warning <parse\_str()-warning>`
* Functions/HardcodedPasswords
* Php/BetterRand
* Security/DontEchoError
* Security/ShouldUsePreparedStatement
* Structures/EvalUsage
* Structures/EvalWithoutTry
* Structures/NoHardcodedHash
* Structures/NoHardcodedIp
* Structures/NoHardcodedPort
* Structures/PhpinfoUsage
* Structures/VardumpUsage
* Structures/pregOptionE

.. _slim:

Slim
++++

Total : 25 analysis

* :ref:`No Echo In Route Callable <no-echo-in-route-callable>`
* :ref:`SlimPHP 1.0.0 Undefined Classes <slimphp-1.0.0-undefined-classes>`
* :ref:`SlimPHP 1.1.0 Undefined Classes <slimphp-1.1.0-undefined-classes>`
* :ref:`SlimPHP 1.2.0 Undefined Classes <slimphp-1.2.0-undefined-classes>`
* :ref:`SlimPHP 1.3.0 Undefined Classes <slimphp-1.3.0-undefined-classes>`
* :ref:`SlimPHP 1.5.0 Undefined Classes <slimphp-1.5.0-undefined-classes>`
* :ref:`SlimPHP 1.6.0 Undefined Classes <slimphp-1.6.0-undefined-classes>`
* :ref:`SlimPHP 2.0.0 Undefined Classes <slimphp-2.0.0-undefined-classes>`
* :ref:`SlimPHP 2.1.0 Undefined Classes <slimphp-2.1.0-undefined-classes>`
* :ref:`SlimPHP 2.2.0 Undefined Classes <slimphp-2.2.0-undefined-classes>`
* :ref:`SlimPHP 2.3.0 Undefined Classes <slimphp-2.3.0-undefined-classes>`
* :ref:`SlimPHP 2.4.0 Undefined Classes <slimphp-2.4.0-undefined-classes>`
* :ref:`SlimPHP 2.5.0 Undefined Classes <slimphp-2.5.0-undefined-classes>`
* :ref:`SlimPHP 2.6.0 Undefined Classes <slimphp-2.6.0-undefined-classes>`
* :ref:`SlimPHP 3.0.0 Undefined Classes <slimphp-3.0.0-undefined-classes>`
* :ref:`SlimPHP 3.1.0 Undefined Classes <slimphp-3.1.0-undefined-classes>`
* :ref:`SlimPHP 3.2.0 Undefined Classes <slimphp-3.2.0-undefined-classes>`
* :ref:`SlimPHP 3.3.0 Undefined Classes <slimphp-3.3.0-undefined-classes>`
* :ref:`SlimPHP 3.4.0 Undefined Classes <slimphp-3.4.0-undefined-classes>`
* :ref:`SlimPHP 3.5.0 Undefined Classes <slimphp-3.5.0-undefined-classes>`
* :ref:`SlimPHP 3.6.0 Undefined Classes <slimphp-3.6.0-undefined-classes>`
* :ref:`SlimPHP 3.7.0 Undefined Classes <slimphp-3.7.0-undefined-classes>`
* :ref:`SlimPHP 3.8.0 Undefined Classes <slimphp-3.8.0-undefined-classes>`
* :ref:`Use Slim <use-slim>`
* :ref:`Used Routes <used-routes>`

.. _suggestions:

Suggestions
+++++++++++

Total : 67 analysis

* :ref:`** For Exponent <**-for-exponent>`
* :ref:`Add Default Value <add-default-value>`
* :ref:`Argument Should Be Typehinted <argument-should-be-typehinted>`
* :ref:`Avoid Real <avoid-real>`
* :ref:`Could Be Static Closure <could-be-static-closure>`
* :ref:`Could Be Typehinted Callable <could-be-typehinted-callable>`
* :ref:`Could Return Void <could-return-void>`
* :ref:`Could Typehint <could-typehint>`
* :ref:`Could Use Compact <could-use-compact>`
* :ref:`Could Use Try <could-use-try>`
* :ref:`Could Use array_fill_keys <could-use-array\_fill\_keys>`
* :ref:`Could Use array_unique <could-use-array\_unique>`
* :ref:`Don't Loop On Yield <don't-loop-on-yield>`
* :ref:`Drop Substr Last Arg <drop-substr-last-arg>`
* :ref:`Empty With Expression <empty-with-expression>`
* :ref:`Find Key Directly <find-key-directly>`
* :ref:`Isset Multiple Arguments <isset-multiple-arguments>`
* :ref:`Named Regex <named-regex>`
* :ref:`One If Is Sufficient <one-if-is-sufficient>`
* :ref:`Possible Increment <possible-increment>`
* :ref:`Preprocess Arrays <preprocess-arrays>`
* :ref:`Reuse Variable <reuse-variable>`
* :ref:`Should Have Destructor <should-have-destructor>`
* :ref:`Should Preprocess Chr <should-preprocess-chr>`
* :ref:`Should Use Foreach <should-use-foreach>`
* :ref:`Should Use Math <should-use-math>`
* :ref:`Should Use Operator <should-use-operator>`
* :ref:`Should Use array_filter() <should-use-array\_filter()>`
* :ref:`Too Many Children <too-many-children>`
* :ref:`Too Many Parameters <too-many-parameters>`
* :ref:`Use Basename Suffix <use-basename-suffix>`
* :ref:`Use Count Recursive <use-count-recursive>`
* :ref:`Use List With Foreach <use-list-with-foreach>`
* :ref:`Use is_countable <use-is\_countable>`
* :ref:`Use json_decode() Options <use-json\_decode()-options>`
* :ref:`Use session_start() Options <use-session\_start()-options>`
* :ref:`preg_match_all() Flag <preg\_match\_all()-flag>`
* Arrays/RandomlySortedLiterals
* Arrays/SliceFirst
* Classes/ParentFirst
* Classes/ShouldUseSelf
* Classes/UnitializedProperties
* Exceptions/OverwriteException
* Functions/Closure2String
* Functions/CouldCentralize
* Functions/NeverUsedParameter
* Functions/NoReturnUsed
* Interfaces/AlreadyParentsInterface
* Interfaces/UnusedInterfaces
* Performances/SubstrFirst
* Php/CompactInexistant
* Php/DetectCurrentClass
* Php/LogicalInLetters
* Php/ShouldUseArrayColumn
* Php/ShouldUseCoalesce
* Structures/BooleanStrictComparison
* Structures/CouldUseDir
* Structures/DropElseAfterReturn
* Structures/EchoWithConcat
* Structures/ListOmissions
* Structures/MismatchedTernary
* Structures/NoParenthesisForLanguageConstruct
* Structures/NoSubstrOne
* Structures/PHP7Dirname
* Structures/RepeatedPrint
* Structures/UnreachableCode
* Structures/WhileListEach

.. _wordpress:

Wordpress
+++++++++

Total : 27 analysis

* :ref:`Avoid Double Prepare <avoid-double-prepare>`
* :ref:`Avoid Non Wordpress Globals <avoid-non-wordpress-globals>`
* :ref:`No Direct Input To Wpdb <no-direct-input-to-wpdb>`
* :ref:`No Global Modification <no-global-modification>`
* :ref:`Nonce Creation <nonce-creation>`
* :ref:`Prepare Placeholder <prepare-placeholder>`
* :ref:`Private Function Usage <private-function-usage>`
* :ref:`Strange Names For Methods <strange-names-for-methods>`
* :ref:`Unescaped Variables In Templates <unescaped-variables-in-templates>`
* :ref:`Unverified Nonce <unverified-nonce>`
* :ref:`Use $wpdb Api <use-$wpdb-api>`
* :ref:`Use Wordpress Functions <use-wordpress-functions>`
* :ref:`Using Short Tags <using-short-tags>`
* :ref:`Wordpress 4.0 Undefined Classes <wordpress-4.0-undefined-classes>`
* :ref:`Wordpress 4.1 Undefined Classes <wordpress-4.1-undefined-classes>`
* :ref:`Wordpress 4.2 Undefined Classes <wordpress-4.2-undefined-classes>`
* :ref:`Wordpress 4.3 Undefined Classes <wordpress-4.3-undefined-classes>`
* :ref:`Wordpress 4.4 Undefined Classes <wordpress-4.4-undefined-classes>`
* :ref:`Wordpress 4.5 Undefined Classes <wordpress-4.5-undefined-classes>`
* :ref:`Wordpress 4.6 Undefined Classes <wordpress-4.6-undefined-classes>`
* :ref:`Wordpress 4.7 Undefined Classes <wordpress-4.7-undefined-classes>`
* :ref:`Wordpress 4.8 Undefined Classes <wordpress-4.8-undefined-classes>`
* :ref:`Wordpress 4.9 Undefined Classes <wordpress-4.9-undefined-classes>`
* :ref:`Wordpress Usage <wordpress-usage>`
* :ref:`Wpdb Best Usage <wpdb-best-usage>`
* :ref:`Wpdb Prepare Or Not <wpdb-prepare-or-not>`
* Structures/EvalUsage

.. _zendframework:

ZendFramework
+++++++++++++

Total : 241 analysis

* :ref:`Action Should Be In Controller <action-should-be-in-controller>`
* :ref:`Avoid PHP Superglobals <avoid-php-superglobals>`
* :ref:`Defined View Property <defined-view-property>`
* :ref:`Error Messages <error-messages>`
* :ref:`Is Zend Framework 1 Controller <is-zend-framework-1-controller>`
* :ref:`Is Zend Framework 1 Helper <is-zend-framework-1-helper>`
* :ref:`Is Zend View File <is-zend-view-file>`
* :ref:`No Echo Outside View <no-echo-outside-view>`
* :ref:`Should Always Prepare <should-always-prepare>`
* :ref:`Should Regenerate Session Id <should-regenerate-session-id>`
* :ref:`Thrown Exceptions <thrown-exceptions>`
* :ref:`Undefined Class 2.0 <undefined-class-2.0>`
* :ref:`Undefined Class 2.1 <undefined-class-2.1>`
* :ref:`Undefined Class 2.2 <undefined-class-2.2>`
* :ref:`Undefined Class 2.3 <undefined-class-2.3>`
* :ref:`Undefined Class 2.4 <undefined-class-2.4>`
* :ref:`Undefined Class 2.5 <undefined-class-2.5>`
* :ref:`Undefined Class 3.0 <undefined-class-3.0>`
* :ref:`Undefined Zend 1.10 <undefined-zend-1.10>`
* :ref:`Undefined Zend 1.11 <undefined-zend-1.11>`
* :ref:`Undefined Zend 1.12 <undefined-zend-1.12>`
* :ref:`Undefined Zend 1.8 <undefined-zend-1.8>`
* :ref:`Undefined Zend 1.9 <undefined-zend-1.9>`
* :ref:`Wrong Class Location <wrong-class-location>`
* :ref:`ZF3 Usage Of Deprecated <zf3-usage-of-deprecated>`
* :ref:`Zend Classes <zend-classes>`
* :ref:`Zend Interface <zend-interface>`
* :ref:`Zend Trait <zend-trait>`
* :ref:`Zend Typehinting <zend-typehinting>`
* :ref:`Zend\Config`
* :ref:`zend-authentication 2.5.0 Undefined Classes <zend-authentication-2.5.0-undefined-classes>`
* :ref:`zend-authentication Usage <zend-authentication-usage>`
* :ref:`zend-barcode 2.5.0 Undefined Classes <zend-barcode-2.5.0-undefined-classes>`
* :ref:`zend-barcode 2.6.0 Undefined Classes <zend-barcode-2.6.0-undefined-classes>`
* :ref:`zend-barcode Usage <zend-barcode-usage>`
* :ref:`zend-cache 2.5.0 Undefined Classes <zend-cache-2.5.0-undefined-classes>`
* :ref:`zend-cache 2.6.0 Undefined Classes <zend-cache-2.6.0-undefined-classes>`
* :ref:`zend-cache 2.7.0 Undefined Classes <zend-cache-2.7.0-undefined-classes>`
* :ref:`zend-cache Usage <zend-cache-usage>`
* :ref:`zend-captcha 2.5.0 Undefined Classes <zend-captcha-2.5.0-undefined-classes>`
* :ref:`zend-captcha 2.6.0 Undefined Classes <zend-captcha-2.6.0-undefined-classes>`
* :ref:`zend-captcha 2.7.0 Undefined Classes <zend-captcha-2.7.0-undefined-classes>`
* :ref:`zend-captcha Usage <zend-captcha-usage>`
* :ref:`zend-code 2.5.0 Undefined Classes <zend-code-2.5.0-undefined-classes>`
* :ref:`zend-code 2.6.0 Undefined Classes <zend-code-2.6.0-undefined-classes>`
* :ref:`zend-code 3.0.0 Undefined Classes <zend-code-3.0.0-undefined-classes>`
* :ref:`zend-code 3.1.0 Undefined Classes <zend-code-3.1.0-undefined-classes>`
* :ref:`zend-code 3.2.0 Undefined Classes <zend-code-3.2.0-undefined-classes>`
* :ref:`zend-code Usage <zend-code-usage>`
* :ref:`zend-config 2.5.x <zend-config-2.5.x>`
* :ref:`zend-config 2.6.x <zend-config-2.6.x>`
* :ref:`zend-config 3.0.x <zend-config-3.0.x>`
* :ref:`zend-config 3.1.x <zend-config-3.1.x>`
* :ref:`zend-console 2.5.0 Undefined Classes <zend-console-2.5.0-undefined-classes>`
* :ref:`zend-console 2.6.0 Undefined Classes <zend-console-2.6.0-undefined-classes>`
* :ref:`zend-console Usage <zend-console-usage>`
* :ref:`zend-crypt 2.5.0 Undefined Classes <zend-crypt-2.5.0-undefined-classes>`
* :ref:`zend-crypt 2.6.0 Undefined Classes <zend-crypt-2.6.0-undefined-classes>`
* :ref:`zend-crypt 3.0.0 Undefined Classes <zend-crypt-3.0.0-undefined-classes>`
* :ref:`zend-crypt 3.1.0 Undefined Classes <zend-crypt-3.1.0-undefined-classes>`
* :ref:`zend-crypt 3.2.0 Undefined Classes <zend-crypt-3.2.0-undefined-classes>`
* :ref:`zend-crypt Usage <zend-crypt-usage>`
* :ref:`zend-db 2.5.0 Undefined Classes <zend-db-2.5.0-undefined-classes>`
* :ref:`zend-db 2.6.0 Undefined Classes <zend-db-2.6.0-undefined-classes>`
* :ref:`zend-db 2.7.0 Undefined Classes <zend-db-2.7.0-undefined-classes>`
* :ref:`zend-db 2.8.0 Undefined Classes <zend-db-2.8.0-undefined-classes>`
* :ref:`zend-db Usage <zend-db-usage>`
* :ref:`zend-debug 2.5.0 Undefined Classes <zend-debug-2.5.0-undefined-classes>`
* :ref:`zend-debug Usage <zend-debug-usage>`
* :ref:`zend-di 2.5.0 Undefined Classes <zend-di-2.5.0-undefined-classes>`
* :ref:`zend-di 2.6.0 Undefined Classes <zend-di-2.6.0-undefined-classes>`
* :ref:`zend-di Usage <zend-di-usage>`
* :ref:`zend-dom 2.5.0 Undefined Classes <zend-dom-2.5.0-undefined-classes>`
* :ref:`zend-dom 2.6.0 Undefined Classes <zend-dom-2.6.0-undefined-classes>`
* :ref:`zend-dom Usage <zend-dom-usage>`
* :ref:`zend-escaper 2.5.0 Undefined Classes <zend-escaper-2.5.0-undefined-classes>`
* :ref:`zend-escaper Usage <zend-escaper-usage>`
* :ref:`zend-eventmanager 2.5.0 Undefined Classes <zend-eventmanager-2.5.0-undefined-classes>`
* :ref:`zend-eventmanager 2.6.0 Undefined Classes <zend-eventmanager-2.6.0-undefined-classes>`
* :ref:`zend-eventmanager 3.0.0 Undefined Classes <zend-eventmanager-3.0.0-undefined-classes>`
* :ref:`zend-eventmanager 3.1.0 Undefined Classes <zend-eventmanager-3.1.0-undefined-classes>`
* :ref:`zend-eventmanager 3.2.0 Undefined Classes <zend-eventmanager-3.2.0-undefined-classes>`
* :ref:`zend-eventmanager Usage <zend-eventmanager-usage>`
* :ref:`zend-feed 2.5.0 Undefined Classes <zend-feed-2.5.0-undefined-classes>`
* :ref:`zend-feed 2.6.0 Undefined Classes <zend-feed-2.6.0-undefined-classes>`
* :ref:`zend-feed 2.7.0 Undefined Classes <zend-feed-2.7.0-undefined-classes>`
* :ref:`zend-feed 2.8.0 Undefined Classes <zend-feed-2.8.0-undefined-classes>`
* :ref:`zend-feed Usage <zend-feed-usage>`
* :ref:`zend-file 2.5.0 Undefined Classes <zend-file-2.5.0-undefined-classes>`
* :ref:`zend-file 2.6.0 Undefined Classes <zend-file-2.6.0-undefined-classes>`
* :ref:`zend-file 2.7.0 Undefined Classes <zend-file-2.7.0-undefined-classes>`
* :ref:`zend-file Usage <zend-file-usage>`
* :ref:`zend-filter 2.5.0 Undefined Classes <zend-filter-2.5.0-undefined-classes>`
* :ref:`zend-filter 2.6.0 Undefined Classes <zend-filter-2.6.0-undefined-classes>`
* :ref:`zend-filter 2.7.0 Undefined Classes <zend-filter-2.7.0-undefined-classes>`
* :ref:`zend-filter Usage <zend-filter-usage>`
* :ref:`zend-form 2.5.0 Undefined Classes <zend-form-2.5.0-undefined-classes>`
* :ref:`zend-form 2.6.0 Undefined Classes <zend-form-2.6.0-undefined-classes>`
* :ref:`zend-form 2.7.0 Undefined Classes <zend-form-2.7.0-undefined-classes>`
* :ref:`zend-form 2.8.0 Undefined Classes <zend-form-2.8.0-undefined-classes>`
* :ref:`zend-form 2.9.0 Undefined Classes <zend-form-2.9.0-undefined-classes>`
* :ref:`zend-form Usage <zend-form-usage>`
* :ref:`zend-http 2.5.0 Undefined Classes <zend-http-2.5.0-undefined-classes>`
* :ref:`zend-http 2.6.0 Undefined Classes <zend-http-2.6.0-undefined-classes>`
* :ref:`zend-http 2.7.0 Undefined Classes <zend-http-2.7.0-undefined-classes>`
* :ref:`zend-http Usage <zend-http-usage>`
* :ref:`zend-i18n 2.5.0 Undefined Classes <zend-i18n-2.5.0-undefined-classes>`
* :ref:`zend-i18n 2.6.0 Undefined Classes <zend-i18n-2.6.0-undefined-classes>`
* :ref:`zend-i18n 2.7.0 Undefined Classes <zend-i18n-2.7.0-undefined-classes>`
* :ref:`zend-i18n Usage <zend-i18n-usage>`
* :ref:`zend-i18n resources Usage <zend-i18n-resources-usage>`
* :ref:`zend-i18n-resources 2.5.x <zend-i18n-resources-2.5.x>`
* :ref:`zend-inputfilter 2.5.0 Undefined Classes <zend-inputfilter-2.5.0-undefined-classes>`
* :ref:`zend-inputfilter 2.6.0 Undefined Classes <zend-inputfilter-2.6.0-undefined-classes>`
* :ref:`zend-inputfilter 2.7.0 Undefined Classes <zend-inputfilter-2.7.0-undefined-classes>`
* :ref:`zend-inputfilter Usage <zend-inputfilter-usage>`
* :ref:`zend-json 2.5.0 Undefined Classes <zend-json-2.5.0-undefined-classes>`
* :ref:`zend-json 2.6.0 Undefined Classes <zend-json-2.6.0-undefined-classes>`
* :ref:`zend-json 3.0.0 Undefined Classes <zend-json-3.0.0-undefined-classes>`
* :ref:`zend-json Usage <zend-json-usage>`
* :ref:`zend-loader 2.5.0 Undefined Classes <zend-loader-2.5.0-undefined-classes>`
* :ref:`zend-loader Usage <zend-loader-usage>`
* :ref:`zend-log 2.5.0 Undefined Classes <zend-log-2.5.0-undefined-classes>`
* :ref:`zend-log 2.6.0 Undefined Classes <zend-log-2.6.0-undefined-classes>`
* :ref:`zend-log 2.7.0 Undefined Classes <zend-log-2.7.0-undefined-classes>`
* :ref:`zend-log 2.8.0 Undefined Classes <zend-log-2.8.0-undefined-classes>`
* :ref:`zend-log 2.9.0 Undefined Classes <zend-log-2.9.0-undefined-classes>`
* :ref:`zend-log Usage <zend-log-usage>`
* :ref:`zend-mail 2.5.0 Undefined Classes <zend-mail-2.5.0-undefined-classes>`
* :ref:`zend-mail 2.6.0 Undefined Classes <zend-mail-2.6.0-undefined-classes>`
* :ref:`zend-mail 2.7.0 Undefined Classes <zend-mail-2.7.0-undefined-classes>`
* :ref:`zend-mail 2.8.0 Undefined Classes <zend-mail-2.8.0-undefined-classes>`
* :ref:`zend-mail Usage <zend-mail-usage>`
* :ref:`zend-math 2.5.0 Undefined Classes <zend-math-2.5.0-undefined-classes>`
* :ref:`zend-math 2.6.0 Undefined Classes <zend-math-2.6.0-undefined-classes>`
* :ref:`zend-math 2.7.0 Undefined Classes <zend-math-2.7.0-undefined-classes>`
* :ref:`zend-math 3.0.0 Undefined Classes <zend-math-3.0.0-undefined-classes>`
* :ref:`zend-math Usage <zend-math-usage>`
* :ref:`zend-memory 2.5.0 Undefined Classes <zend-memory-2.5.0-undefined-classes>`
* :ref:`zend-memory Usage <zend-memory-usage>`
* :ref:`zend-mime 2.5.0 Undefined Classes <zend-mime-2.5.0-undefined-classes>`
* :ref:`zend-mime 2.6.0 Undefined Classes <zend-mime-2.6.0-undefined-classes>`
* :ref:`zend-mime Usage <zend-mime-usage>`
* :ref:`zend-modulemanager 2.5.0 Undefined Classes <zend-modulemanager-2.5.0-undefined-classes>`
* :ref:`zend-modulemanager 2.6.0 Undefined Classes <zend-modulemanager-2.6.0-undefined-classes>`
* :ref:`zend-modulemanager 2.7.0 Undefined Classes <zend-modulemanager-2.7.0-undefined-classes>`
* :ref:`zend-modulemanager 2.8.0 Undefined Classes <zend-modulemanager-2.8.0-undefined-classes>`
* :ref:`zend-modulemanager Usage <zend-modulemanager-usage>`
* :ref:`zend-mvc 2.5.x <zend-mvc-2.5.x>`
* :ref:`zend-mvc 2.6.x <zend-mvc-2.6.x>`
* :ref:`zend-mvc 2.7.x <zend-mvc-2.7.x>`
* :ref:`zend-mvc 3.0.x <zend-mvc-3.0.x>`
* :ref:`zend-mvc 3.1.0 Undefined Classes <zend-mvc-3.1.0-undefined-classes>`
* :ref:`zend-mvc Usage <zend-mvc-usage>`
* :ref:`zend-navigation 2.5.0 Undefined Classes <zend-navigation-2.5.0-undefined-classes>`
* :ref:`zend-navigation 2.6.0 Undefined Classes <zend-navigation-2.6.0-undefined-classes>`
* :ref:`zend-navigation 2.7.0 Undefined Classes <zend-navigation-2.7.0-undefined-classes>`
* :ref:`zend-navigation 2.8.0 Undefined Classes <zend-navigation-2.8.0-undefined-classes>`
* :ref:`zend-navigation Usage <zend-navigation-usage>`
* :ref:`zend-paginator 2.5.0 Undefined Classes <zend-paginator-2.5.0-undefined-classes>`
* :ref:`zend-paginator 2.6.0 Undefined Classes <zend-paginator-2.6.0-undefined-classes>`
* :ref:`zend-paginator 2.7.0 Undefined Classes <zend-paginator-2.7.0-undefined-classes>`
* :ref:`zend-paginator Usage <zend-paginator-usage>`
* :ref:`zend-progressbar 2.5.0 Undefined Classes <zend-progressbar-2.5.0-undefined-classes>`
* :ref:`zend-progressbar Usage <zend-progressbar-usage>`
* :ref:`zend-serializer 2.5.0 Undefined Classes <zend-serializer-2.5.0-undefined-classes>`
* :ref:`zend-serializer 2.6.0 Undefined Classes <zend-serializer-2.6.0-undefined-classes>`
* :ref:`zend-serializer 2.7.0 Undefined Classes <zend-serializer-2.7.0-undefined-classes>`
* :ref:`zend-serializer 2.8.0 Undefined Classes <zend-serializer-2.8.0-undefined-classes>`
* :ref:`zend-serializer Usage <zend-serializer-usage>`
* :ref:`zend-server 2.5.0 Undefined Classes <zend-server-2.5.0-undefined-classes>`
* :ref:`zend-server 2.6.0 Undefined Classes <zend-server-2.6.0-undefined-classes>`
* :ref:`zend-server 2.7.0 Undefined Classes <zend-server-2.7.0-undefined-classes>`
* :ref:`zend-server Usage <zend-server-usage>`
* :ref:`zend-servicemanager 2.5.0 Undefined Classes <zend-servicemanager-2.5.0-undefined-classes>`
* :ref:`zend-servicemanager 2.6.0 Undefined Classes <zend-servicemanager-2.6.0-undefined-classes>`
* :ref:`zend-servicemanager 2.7.0 Undefined Classes <zend-servicemanager-2.7.0-undefined-classes>`
* :ref:`zend-servicemanager 3.0.0 Undefined Classes <zend-servicemanager-3.0.0-undefined-classes>`
* :ref:`zend-servicemanager 3.1.0 Undefined Classes <zend-servicemanager-3.1.0-undefined-classes>`
* :ref:`zend-servicemanager 3.2.0 Undefined Classes <zend-servicemanager-3.2.0-undefined-classes>`
* :ref:`zend-servicemanager 3.3.0 Undefined Classes <zend-servicemanager-3.3.0-undefined-classes>`
* :ref:`zend-servicemanager Usage <zend-servicemanager-usage>`
* :ref:`zend-session 2.5.0 Undefined Classes <zend-session-2.5.0-undefined-classes>`
* :ref:`zend-session 2.6.0 Undefined Classes <zend-session-2.6.0-undefined-classes>`
* :ref:`zend-session 2.7.0 Undefined Classes <zend-session-2.7.0-undefined-classes>`
* :ref:`zend-session 2.8.0 Undefined Classes <zend-session-2.8.0-undefined-classes>`
* :ref:`zend-session Usage <zend-session-usage>`
* :ref:`zend-soap 2.5.0 Undefined Classes <zend-soap-2.5.0-undefined-classes>`
* :ref:`zend-soap 2.6.0 Undefined Classes <zend-soap-2.6.0-undefined-classes>`
* :ref:`zend-soap Usage <zend-soap-usage>`
* :ref:`zend-stdlib 2.5.0 Undefined Classes <zend-stdlib-2.5.0-undefined-classes>`
* :ref:`zend-stdlib 2.6.0 Undefined Classes <zend-stdlib-2.6.0-undefined-classes>`
* :ref:`zend-stdlib 2.7.0 Undefined Classes <zend-stdlib-2.7.0-undefined-classes>`
* :ref:`zend-stdlib 3.0.0 Undefined Classes <zend-stdlib-3.0.0-undefined-classes>`
* :ref:`zend-stdlib 3.1.0 Undefined Classes <zend-stdlib-3.1.0-undefined-classes>`
* :ref:`zend-stdlib Usage <zend-stdlib-usage>`
* :ref:`zend-tag 2.5.0 Undefined Classes <zend-tag-2.5.0-undefined-classes>`
* :ref:`zend-tag 2.6.0 Undefined Classes <zend-tag-2.6.0-undefined-classes>`
* :ref:`zend-tag Usage <zend-tag-usage>`
* :ref:`zend-test 2.5.0 Undefined Classes <zend-test-2.5.0-undefined-classes>`
* :ref:`zend-test 2.6.0 Undefined Classes <zend-test-2.6.0-undefined-classes>`
* :ref:`zend-test 3.0.0 Undefined Classes <zend-test-3.0.0-undefined-classes>`
* :ref:`zend-test 3.1.0 Undefined Classes <zend-test-3.1.0-undefined-classes>`
* :ref:`zend-test Usage <zend-test-usage>`
* :ref:`zend-text 2.5.0 Undefined Classes <zend-text-2.5.0-undefined-classes>`
* :ref:`zend-text 2.6.0 Undefined Classes <zend-text-2.6.0-undefined-classes>`
* :ref:`zend-text Usage <zend-text-usage>`
* :ref:`zend-uri 2.5.x <zend-uri-2.5.x>`
* :ref:`zend-uri`
* :ref:`zend-validator 2.6.x <zend-validator-2.6.x>`
* :ref:`zend-validator 2.6.x <zend-validator-2.6.x>`
* :ref:`zend-validator 2.7.x <zend-validator-2.7.x>`
* :ref:`zend-validator 2.8.x <zend-validator-2.8.x>`
* :ref:`zend-validator 2.9.0 Undefined Classes <zend-validator-2.9.0-undefined-classes>`
* :ref:`zend-validator Usage <zend-validator-usage>`
* :ref:`zend-view 2.5.0 Undefined Classes <zend-view-2.5.0-undefined-classes>`
* :ref:`zend-view 2.6.0 Undefined Classes <zend-view-2.6.0-undefined-classes>`
* :ref:`zend-view 2.7.0 Undefined Classes <zend-view-2.7.0-undefined-classes>`
* :ref:`zend-view 2.8.0 Undefined Classes <zend-view-2.8.0-undefined-classes>`
* :ref:`zend-view 2.9.0 Undefined Classes <zend-view-2.9.0-undefined-classes>`
* :ref:`zend-view Usage <zend-view-usage>`
* :ref:`zend-xmlrpc 2.5.0 Undefined Classes <zend-xmlrpc-2.5.0-undefined-classes>`
* :ref:`zend-xmlrpc 2.6.0 Undefined Classes <zend-xmlrpc-2.6.0-undefined-classes>`
* :ref:`zend-xmlrpc Usage <zend-xmlrpc-usage>`
* Namespaces/ShouldMakeAlias
* Structures/ExitUsage
* ZendF/Zf3Cache
* ZendF/Zf3Code25
* ZendF/Zf3Code26
* ZendF/Zf3Code30
* ZendF/Zf3Code31
* ZendF/Zf3Code32
* ZendF/Zf3Eventmanager
* ZendF/Zf3Eventmanager25
* ZendF/Zf3Eventmanager26
* ZendF/Zf3Eventmanager30
* ZendF/Zf3Eventmanager31
* ZendF/Zf3Test
* ZendF/Zf3Test25
* ZendF/Zf3Test26
* ZendF/Zf3Test30

