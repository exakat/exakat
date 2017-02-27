.. Annex:

Annex
=====

* Supported Themes
* Supported Reports
* Supported PHP Extensions
* Supported Frameworks
* Recognized Libraries
* New analyzers
* External services

Supported Themes
----------------

Exakat groups analysis by themes. This way, analyzing 'Security' runs all possible analysis related to themes.

* All
* Analyze
* Appcontent
* Appinfo
* Cakephp
* Calisthenics
* ClearPHP
* Codacy
* Coding Conventions
* CompatibilityPHP53
* CompatibilityPHP54
* CompatibilityPHP55
* CompatibilityPHP56
* CompatibilityPHP70
* CompatibilityPHP71
* CompatibilityPHP72
* Custom
* Dead code
* Internal
* Inventory
* OneFile
* PHP recommendations
* Performances
* Portability
* Preferences
* RadwellCodes
* Security
* Unassigned
* Wordpress
* ZendFramework

Supported Reports
-----------------

Exakat produces various reports. Some are general, covering various aspects in a reference way; others focus on one aspect. 

  * Ambassador
  * Devoops
  * Text
  * Xml
  * Uml
  * PhpConfiguration
  * PhpCompilation
  * Inventories
  * Clustergrammer
  * FileDependencies
  * FileDependenciesHtml
  * ZendFramework
  * RadwellCode
  * FacetedJson
  * Json
  * OnepageJson
  * Codacy


Supported PHP Extensions
------------------------

PHP extensions are used to check for defined structures (classes, interfaces, etc.), identify dependencies and directives. 

PHP extensions should be provided with the list of structures they define (functions, class, constants, traits, variables, interfaces, namespaces), and directives. 

* ext/amqp
* ext/apache
* ext/apc
* ext/apcu
* ext/array
* ext/php-ast
* ext/bcmath
* ext/bzip2
* ext/cairo
* ext/calendar
* ext/com
* ext/crypto
* ext/ctype
* ext/curl
* ext/cyrus
* ext/date
* ext/dba
* ext/dio
* ext/dom
* ext/eaccelerator
* ext/enchant
* ext/ereg
* ext/ev
* ext/event
* ext/exif
* ext/expect
* ext/fann
* ext/fdf
* ext/ffmpeg
* ext/file
* ext/fileinfo
* ext/filter
* ext/fpm
* ext/ftp
* ext/gd
* ext/gearman
* Ext/geoip
* ext/gettext
* ext/gmagick
* ext/gmp
* ext/gnupgp
* ext/hash
* ext/pecl_http
* ext/ibase
* ext/iconv
* ext/iis
* ext/imagick
* ext/imap
* ext/info
* ext/inotify
* ext/intl
* ext/json
* ext/kdm5
* ext/ldap
* ext/libevent
* ext/libsodium
* ext/libxml
* ext/lua
* ext/mail
* ext/mailparse
* ext/math
* ext/mbstring
* ext/mcrypt
* ext/memcache
* ext/memcached
* ext/mhash
* ext/ming
* ext/mongo
* Ext/mongodb
* ext/mssql
* ext/mysql
* ext/mysqli
* ext/ncurses
* ext/newt
* ext/nsapi
* ext/ob
* ext/oci8
* ext/odbc
* ext/opcache
* ext/openssl
* ext/parsekit
* ext/pcntl
* ext/pcre
* ext/pdo
* ext/pgsql
* ext/phalcon
* ext/phar
* ext/posix
* ext/proctitle
* ext/pspell
* ext/rar
* ext/readline
* ext/recode
* ext/redis
* ext/reflexion
* ext/runkit
* ext/sem
* ext/sockets
* ext/shmop
* ext/simplexml
* ext/snmp
* ext/soap
* ext/sockets
* ext/spl
* ext/sqlite
* ext/sqlite3
* ext/sqlsrv
* ext/ssh2
* ext/standard
* String
* ext/suhosin
* ext/tidy
* ext/tokenizer
* ext/tokyotyrant
* ext/trader
* ext/v8js
* ext/wddx
* ext/wikidiff2
* ext/wincache
* ext/xcache
* ext/xdebug
* ext/xdiff
* ext/xhprof
* ext/xml
* ext/xmlreader
* ext/xmlrpc
* ext/xmlwriter
* ext/xsl
* ext/yaml
* ext/yis
* ext/zbarcode
* ext/zip
* ext/zlib
* ext/0mq

Supported Frameworks
--------------------

Frameworks are supported when they is an analysis related to them. Then, a selection of analysis may be dedicated to them. 

::
   php exakat.phar analysis -p <project> -T <Framework> 



* Cakephp
* Wordpress
* ZendFramework

Recognized Libraries
--------------------

Libraries that are popular, large and often included in repositories are identified early in the analysis process, and ignored. This prevents Exakat to analysis some code foreign to the current repository : it prevents false positives from this code, and make the analysis much lighter. The whole process is entirely automatic. 

Those libraries, or even some of the, may be included again in the analysis by commenting the ignored_dir[] line, in the projects/<project>/config.ini file. 

* `BBQ <https://github.com/eventio/bbq>`_
* `CI xmlRPC <http://apigen.juzna.cz/doc/ci-bonfire/Bonfire/class-CI_Xmlrpc.html>`_
* `CPDF <https://pear.php.net/reference/PhpDocumentor-latest/li_Cpdf.html>`_
* `DomPDF <https://github.com/dompdf/dompdf>`_
* `FPDF <http://www.fpdf.org/>`_
* `gettext Reader <http://pivotx.net/dev/docs/trunk/External/PHP-gettext/gettext_reader.html>`_
* `jpGraph <http://jpgraph.net/>`_
* `HTML2PDF <http://sourceforge.net/projects/phphtml2pdf/>`_
* `HTMLPurifier <http://htmlpurifier.org/>`_
* http_class
* `IDNA convert <https://github.com/phpWhois/idna-convert>`_
* `lessc <http://leafo.net/lessphp/>`_
* `lessc <http://leafo.net/lessphp/>`_
* `magpieRSS <http://magpierss.sourceforge.net/>`_
* `MarkDown Parser <http://processwire.com/apigen/class-Markdown_Parser.html>`_
* `Markdown <https://github.com/michelf/php-markdown>`_
* `mpdf <http://www.mpdf1.com/mpdf/index.php>`_
* oauthToken
* passwordHash
* `pChart <http://www.pchart.net/>`_
* `pclZip <http://www.phpconcept.net/pclzip/>`_
* `Propel <http://propelorm.org/>`_
* `phpExecl <https://phpexcel.codeplex.com/>`_
* `phpMailer <https://github.com/PHPMailer/PHPMailer>`_
* `qrCode <http://phpqrcode.sourceforge.net/>`_
* `Services_JSON <https://pear.php.net/package/Services_JSON>`_
* `sfYaml <https://github.com/fabpot-graveyard/yaml/blob/master/lib/sfYaml.php>`_
* `swift <http://swiftmailer.org/>`_
* `Smarty <http://www.smarty.net/>`_
* `tcpdf <http://www.tcpdf.org/>`_
* `text_diff <https://pear.php.net/package/Text_Diff>`_
* `text highlighter <https://pear.php.net/package/Text_Highlighter/>`_
* `tfpdf <http://www.fpdf.org/en/script/script92.php>`_
* UTF8
* `Yii <http://www.yiiframework.com/>`_
* `Zend Framework <http://framework.zend.com/>`_

New analyzers
-------------

List of analyzers, by version of introduction, newest to oldest. In parenthesis, the first element is the analyzer name, used with 'analyze -P' command, and the seconds, if any, are the recipes, used with the -T option. Recipes are separated by commas, as the same analysis may be used in several recipes.


* 0.10.3

  * Multiple Alias Definitions Per File (Namespaces/MultipleAliasDefinitionPerFile ; Analyze)
  * Property Used In One Method Only (Classes/PropertyUsedInOneMethodOnly ; Analyze)
  * Used Once Property (Classes/UsedOnceProperty ; Analyze)
  * __DIR__ Then Slash (Structures/DirThenSlash ; Analyze)
  * self, parent, static Outside Class (Classes/NoPSSOutsideClass)

* 0.10.2

  * Class Function Confusion (Php/ClassFunctionConfusion ; Analyze)
  * Forgotten Thrown (Exceptions/ForgottenThrown)
  * Should Use array_column() (Php/ShouldUseArrayColumn ; Analyze, Performances)
  * ext/libsodium (Extensions/Extlibsodium ; Appcontent)

* 0.10.1

  * Avoid Non Wordpress Globals (Wordpress/AvoidOtherGlobals ; Wordpress)
  * SQL queries (Type/Sql ; Inventory)
  * Strange Names For Methods (Classes/StrangeName)

* 0.10.0

  * Error_Log() Usage (Php/ErrorLogUsage ; Appinfo)
  * No Boolean As Default (Functions/NoBooleanAsDefault ; Analyze)
  * Raised Access Level (Classes/RaisedAccessLevel)
  * Use Prepare With Variables (Wordpress/WpdbPrepareForVariables ; ZendFramework)

* 0.9.9

  * PHP 7.2 Deprecations (Php/Php72Deprecation)
  * PHP 7.2 Removed Functions (Php/Php72RemovedFunctions ; CompatibilityPHP72)

* 0.9.8

  * Assigned Twice (Variables/AssignedTwiceOrMore ; Analyze, Codacy)
  * New Line Style (Structures/NewLineStyle ; Preferences)
  * New On Functioncall Or Identifier (Classes/NewOnFunctioncallOrIdentifier)

* 0.9.7

  * Avoid Large Array Assignation (Structures/NoAssignationInFunction ; Performances)
  * Could Be Protected Property (Classes/CouldBeProtectedProperty)
  * Long Arguments (Structures/LongArguments ; Analyze, Codacy)
  * ZendF/ZendTypehinting (ZendF/ZendTypehinting ; ZendFramework)

* 0.9.6

  * Fetch One Row Format (Performances/FetchOneRowFormat)
  * Performances/NoGlob (Performances/NoGlob ; Performances)

* 0.9.5

  * Ext/mongodb (Extensions/Extmongodb)
  * One Expression Brackets Consistency (Structures/OneExpressionBracketsConsistency ; Preferences)
  * Should Use Function Use (Php/ShouldUseFunction ; Performances)
  * ext/zbarcode (Extensions/Extzbarcode ; Appinfo)

* 0.9.4

  * Class Should Be Final By Ocramius (Classes/FinalByOcramius)
  * String (Extensions/Extstring ; Appinfo, Appcontent)
  * ext/mhash (Extensions/Extmhash ; Appinfo, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, Appcontent, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72)

* 0.9.3

  * Close Tags Consistency (Php/CloseTagsConsistency)
  * Unset() Or (unset) (Php/UnsetOrCast ; Preferences)
  * Wpdb Prepare Or Not (Wordpress/WpdbPrepareOrNot ; Wordpress)

* 0.9.2

  * $GLOBALS Or global (Php/GlobalsVsGlobal ; Preferences)
  * Illegal Name For Method (Classes/WrongName)
  * Too Many Local Variables (Functions/TooManyLocalVariables ; Analyze, Codacy)
  * Use Composer Lock (Composer/UseComposerLock ; Appinfo)
  * ext/ncurses (Extensions/Extncurses ; Appinfo)
  * ext/newt (Extensions/Extnewt ; Appinfo)
  * ext/nsapi (Extensions/Extnsapi ; Appinfo)

* 0.9.1

  * Avoid Using stdClass (Php/UseStdclass ; Analyze, OneFile, Codacy)
  * Avoid array_push() (Performances/AvoidArrayPush ; Performances, PHP recommendations)
  * Could Return Void (Functions/CouldReturnVoid)
  * Invalid Octal In String (Type/OctalInString ; Inventory, CompatibilityPHP71, CompatibilityPHP72)
  * Undefined Class 2.0 (ZendF/UndefinedClass20 ; ZendFramework)
  * Undefined Class 2.1 (ZendF/UndefinedClass21 ; ZendFramework)
  * Undefined Class 2.2 (ZendF/UndefinedClass22 ; ZendFramework)
  * Undefined Class 2.3 (ZendF/UndefinedClass23 ; ZendFramework)
  * Undefined Class 2.4 (ZendF/UndefinedClass24 ; ZendFramework)
  * Undefined Class 2.5 (ZendF/UndefinedClass25 ; ZendFramework)
  * Undefined Class 3.0 (ZendF/UndefinedClass30 ; ZendFramework)
  * Zend Interface (ZendF/ZendInterfaces ; ZendFramework)
  * Zend Trait (ZendF/ZendTrait ; ZendFramework)

* 0.9.0

  * Getting Last Element (Arrays/GettingLastElement)
  * Rethrown Exceptions (Exceptions/Rethrown ; Dead code)

* 0.8.9

  * Array() / [  ] Consistence (Arrays/ArrayBracketConsistence)
  * Bail Out Early (Structures/BailOutEarly ; Analyze, OneFile, Codacy)
  * Die Exit Consistence (Structures/DieExitConsistance ; Preferences)
  * Dont Change The Blind Var (Structures/DontChangeBlindKey ; Analyze, Codacy)
  * More Than One Level Of Indentation (Structures/OneLevelOfIndentation ; Calisthenics)
  * One Dot Or Object Operator Per Line (Structures/OneDotOrObjectOperatorPerLine ; Calisthenics)
  * PHP 7.1 Microseconds (Php/Php71microseconds ; CompatibilityPHP71, CompatibilityPHP72)
  * Unitialized Properties (Classes/UnitializedProperties ; Analyze, OneFile, Codacy)
  * Use Wordpress Functions (Wordpress/UseWpFunctions ; Wordpress)
  * Useless Check (Structures/UselessCheck ; Analyze, OneFile, Codacy)

* 0.8.7

  * Dont Echo Error (Security/DontEchoError ; Analyze, Security, Codacy)
  * No Isset With Empty (Structures/NoIssetWithEmpty ; Analyze, PHP recommendations, OneFile, RadwellCodes, Codacy)
  * Performances/timeVsstrtotime (Performances/timeVsstrtotime ; Performances, OneFile, RadwellCodes)
  * Use Class Operator (Classes/UseClassOperator)
  * Useless Casting (Structures/UselessCasting ; Analyze, PHP recommendations, OneFile, RadwellCodes, Codacy)
  * ext/rar (Extensions/Extrar ; Appinfo)

* 0.8.6

  * Boolean Value (Type/BooleanValue ; Appinfo)
  * Drop Else After Return (Structures/DropElseAfterReturn)
  * Modernize Empty With Expression (Structures/ModernEmpty ; Analyze, OneFile, Codacy)
  * Null Value (Type/NullValue ; Appinfo)
  * Use Positive Condition (Structures/UsePositiveCondition ; Analyze, OneFile, Codacy)

* 0.8.5

  * Is Zend Framework 1 Controller (ZendF/IsController ; ZendFramework)
  * Is Zend Framework 1 Helper (ZendF/IsHelper ; ZendFramework)
  * Should Make Ternary (Structures/ShouldMakeTernary ; Analyze, OneFile, Codacy)
  * Unused Returned Value (Functions/UnusedReturnedValue)

* 0.8.4

  * $HTTP_RAW_POST_DATA (Php/RawPostDataUsage ; Analyze, Appinfo, CompatibilityPHP56, CompatibilityPHP70, CompatibilityPHP71, Codacy)
  * $this Belongs To Classes Or Traits (Classes/ThisIsForClasses ; Analyze, Codacy)
  * $this Is Not An Array (Classes/ThisIsNotAnArray ; Analyze, Codacy)
  * $this Is Not For Static Methods (Classes/ThisIsNotForStatic ; Analyze, Codacy)
  * ** For Exponent (Php/NewExponent ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP53)
  * ... Usage (Php/EllipsisUsage ; Appinfo, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP53)
  * ::class (Php/StaticclassUsage ; CompatibilityPHP54, CompatibilityPHP53)
  * <?= Usage (Php/EchoTagUsage ; Analyze, Appinfo, Codacy)
  * @ Operator (Structures/Noscream ; Appinfo, ClearPHP)
  * Abstract Class Usage (Classes/Abstractclass ; Appinfo, Appcontent)
  * Abstract Methods Usage (Classes/Abstractmethods ; Appinfo, Appcontent)
  * Abstract Static Methods (Classes/AbstractStatic ; Analyze, Codacy)
  * Access Protected Structures (Classes/AccessProtected ; Analyze, Codacy)
  * Accessing Private (Classes/AccessPrivate ; Analyze, Codacy)
  * Action Should Bin In Controller (ZendF/ActionInController ; ZendFramework)
  * Adding Zero (Structures/AddZero ; Analyze, OneFile, ClearPHP, Codacy)
  * Aliases (Namespaces/Alias ; Appinfo)
  * Aliases Usage (Functions/AliasesUsage ; Analyze, OneFile, ClearPHP, Codacy)
  * All Uppercase Variables (Variables/VariableUppercase ; Coding Conventions)
  * Already Parents Interface (Interfaces/AlreadyParentsInterface ; Analyze, Codacy)
  * Altering Foreach Without Reference (Structures/AlteringForeachWithoutReference ; Analyze, ClearPHP, Codacy)
  * Alternative Syntax (Php/AlternativeSyntax ; Appinfo)
  * Always Positive Comparison (Structures/NeverNegative ; Analyze, Codacy)
  * Ambiguous Array Index (Arrays/AmbiguousKeys)
  * Anonymous Classes (Classes/Anonymous ; Appinfo, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53)
  * Argument Should Be Typehinted (Functions/ShouldBeTypehinted ; Analyze, ClearPHP, Codacy)
  * Arguments (Variables/Arguments ; )
  * Array Index (Arrays/Arrayindex ; Appinfo)
  * Arrays Is Modified (Arrays/IsModified ; Internal)
  * Arrays Is Read (Arrays/IsRead ; Internal)
  * Assertions (Php/AssertionUsage ; Appinfo)
  * Assign Default To Properties (Classes/MakeDefault ; Analyze, ClearPHP, Codacy)
  * Autoloading (Php/AutoloadUsage ; Appinfo)
  * Avoid Parenthesis (Structures/PrintWithoutParenthesis ; Analyze, Codacy)
  * Avoid Those Crypto (Security/AvoidThoseCrypto ; Security)
  * Avoid array_unique() (Structures/NoArrayUnique ; Performances)
  * Avoid get_class() (Structures/UseInstanceof ; Analyze, Codacy)
  * Avoid sleep()/usleep() (Security/NoSleep ; Security)
  * Bad Constants Names (Constants/BadConstantnames ; PHP recommendations)
  * Binary Glossary (Type/Binary ; Inventory, Appinfo, CompatibilityPHP53)
  * Blind Variables (Variables/Blind ; )
  * Bracketless Blocks (Structures/Bracketless ; Coding Conventions)
  * Break Outside Loop (Structures/BreakOutsideLoop ; Analyze, CompatibilityPHP70, CompatibilityPHP71, Codacy)
  * Break With 0 (Structures/Break0 ; Analyze, CompatibilityPHP53, OneFile, Codacy)
  * Break With Non Integer (Structures/BreakNonInteger ; Analyze, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, OneFile, CompatibilityPHP70, CompatibilityPHP71, Codacy)
  * Buried Assignation (Structures/BuriedAssignation ; Analyze, Codacy)
  * CakePHP 3.0 Deprecated Class (Cakephp/Cake30DeprecatedClass ; Cakephp)
  * CakePHP 3.3 Deprecated Class (Cakephp/Cake33DeprecatedClass ; Cakephp)
  * Calltime Pass By Reference (Structures/CalltimePassByReference ; Analyze, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP70, CompatibilityPHP71, Codacy)
  * Can't Disable Function (Security/CantDisableFunction ; Appinfo, Appcontent)
  * Can't Extend Final (Classes/CantExtendFinal ; Analyze, Dead code, Codacy)
  * Cant Use Return Value In Write Context (Php/CantUseReturnValueInWriteContext ; CompatibilityPHP54, CompatibilityPHP53)
  * Cast To Boolean (Structures/CastToBoolean ; Analyze, OneFile, Codacy)
  * Cast Usage (Php/CastingUsage ; Appinfo)
  * Catch Overwrite Variable (Structures/CatchShadowsVariable ; Analyze, ClearPHP, Codacy)
  * Caught Exceptions (Exceptions/CaughtExceptions ; )
  * Caught Expressions (Php/TryCatchUsage ; Appinfo)
  * Class Const With Array (Php/ClassConstWithArray ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP53)
  * Class Has Fluent Interface (Classes/HasFluentInterface ; )
  * Class Name Case Difference (Classes/WrongCase ; Analyze, Coding Conventions, RadwellCodes, Codacy)
  * Class Usage (Classes/ClassUsage ; )
  * Class, Interface Or Trait With Identical Names (Classes/CitSameName ; Analyze, Codacy)
  * Classes Mutually Extending Each Other (Classes/MutualExtension ; Analyze, Codacy)
  * Classes Names (Classes/Classnames ; Appinfo)
  * Clone Usage (Classes/CloningUsage ; Appinfo)
  * Close Tags (Php/CloseTags ; Coding Conventions)
  * Closure May Use $this (Php/ClosureThisSupport ; Analyze, CompatibilityPHP53, Codacy)
  * Closures Glossary (Functions/Closures ; Appinfo)
  * Coalesce (Php/Coalesce ; Appinfo, Appcontent)
  * Common Alternatives (Structures/CommonAlternatives ; Analyze, Codacy)
  * Compare Hash (Security/CompareHash ; Security, ClearPHP)
  * Compared Comparison (Structures/ComparedComparison ; Analyze, Codacy)
  * Composer Namespace (Composer/IsComposerNsname ; Appinfo, Internal)
  * Composer Usage (Composer/UseComposer ; Appinfo)
  * Composer's autoload (Composer/Autoload ; Appinfo)
  * Concrete Visibility (Interfaces/ConcreteVisibility ; Analyze, Codacy)
  * Conditional Structures (Structures/ConditionalStructures ; )
  * Conditioned Constants (Constants/ConditionedConstants ; Appinfo, Internal)
  * Conditioned Function (Functions/ConditionedFunctions ; Appinfo, Internal)
  * Confusing Names (Variables/CloseNaming ; Analyze, Codacy)
  * Const With Array (Php/ConstWithArray ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP53)
  * Constant Class (Classes/ConstantClass ; Analyze, Codacy)
  * Constant Comparison (Structures/ConstantComparisonConsistance ; Coding Conventions, Preferences)
  * Constant Conditions (Structures/ConstantConditions ; )
  * Constant Definition (Classes/ConstantDefinition ; Appinfo)
  * Constant Scalar Expression (Php/ConstantScalarExpression ; )
  * Constant Scalar Expressions (Structures/ConstantScalarExpression ; Appinfo, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP53)
  * Constants (Constants/Constantnames ; )
  * Constants Created Outside Its Namespace (Constants/CreatedOutsideItsNamespace ; Analyze, Codacy)
  * Constants Usage (Constants/ConstantUsage ; Appinfo)
  * Constants With Strange Names (Constants/ConstantStrangeNames ; Analyze, Codacy)
  * Constructors (Classes/Constructor ; Internal)
  * Continents (Type/Continents ; Inventory)
  * Could Be Class Constant (Classes/CouldBeClassConstant ; Analyze, Codacy)
  * Could Be Static (Structures/CouldBeStatic ; Analyze, OneFile, Codacy)
  * Could Use Alias (Namespaces/CouldUseAlias ; Analyze, OneFile, Codacy)
  * Could Use Short Assignation (Structures/CouldUseShortAssignation ; Analyze, Performances, OneFile, Codacy)
  * Could Use __DIR__ (Structures/CouldUseDir ; Analyze, Codacy)
  * Could Use self (Classes/ShouldUseSelf ; Analyze, Codacy)
  * Curly Arrays (Arrays/CurlyArrays ; Coding Conventions)
  * Custom Class Usage (Classes/AvoidUsing ; Custom)
  * Custom Constant Usage (Constants/CustomConstantUsage ; )
  * Dangling Array References (Structures/DanglingArrayReferences ; Analyze, PHP recommendations, ClearPHP, Codacy)
  * Deep Definitions (Functions/DeepDefinitions ; Analyze, Appinfo, Codacy)
  * Define With Array (Php/DefineWithArray ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53)
  * Defined Class Constants (Classes/DefinedConstants ; Internal)
  * Defined Exceptions (Exceptions/DefinedExceptions ; Appinfo)
  * Defined Parent MP (Classes/DefinedParentMP ; Internal)
  * Defined Properties (Classes/DefinedProperty ; Internal)
  * Defined static:: Or self:: (Classes/DefinedStaticMP ; Internal)
  * Definitions Only (Files/DefinitionsOnly ; Internal)
  * Dependant Trait (Traits/DependantTrait ; Analyze, Codacy)
  * Deprecated Code (Php/Deprecated ; Analyze, Codacy)
  * Deprecated Methodcalls in Cake 3.2 (Cakephp/Cake32DeprecatedMethods ; Cakephp)
  * Deprecated Methodcalls in Cake 3.3 (Cakephp/Cake33DeprecatedMethods ; Cakephp)
  * Deprecated Static calls in Cake 3.3 (Cakephp/Cake33DeprecatedStaticmethodcall ; Cakephp)
  * Deprecated Trait in Cake 3.3 (Cakephp/Cake33DeprecatedTraits ; Cakephp)
  * Dereferencing String And Arrays (Structures/DereferencingAS ; Appinfo, CompatibilityPHP54, CompatibilityPHP53)
  * Direct Injection (Security/DirectInjection ; Security)
  * Directives Usage (Php/DirectivesUsage ; Appinfo)
  * Don't Change Incomings (Structures/NoChangeIncomingVariables ; Analyze, Codacy)
  * Double Assignation (Structures/DoubleAssignation ; Analyze, Codacy)
  * Double Instructions (Structures/DoubleInstruction ; Analyze, Codacy)
  * Duplicate Calls (Structures/DuplicateCalls ; )
  * Dynamic Calls (Structures/DynamicCalls ; Appinfo, Internal)
  * Dynamic Class Constant (Classes/DynamicConstantCall ; Appinfo)
  * Dynamic Classes (Classes/DynamicClass ; Appinfo)
  * Dynamic Code (Structures/DynamicCode ; Appinfo)
  * Dynamic Function Call (Functions/Dynamiccall ; Appinfo, Internal)
  * Dynamic Methodcall (Classes/DynamicMethodCall ; Appinfo)
  * Dynamic New (Classes/DynamicNew ; Appinfo)
  * Dynamic Property (Classes/DynamicPropertyCall ; Appinfo)
  * Dynamically Called Classes (Classes/VariableClasses ; Appinfo)
  * Echo Or Print (Structures/EchoPrintConsistance ; Coding Conventions, Preferences)
  * Echo With Concat (Structures/EchoWithConcat ; Analyze, Performances, Codacy)
  * Else If Versus Elseif (Structures/ElseIfElseif ; Analyze, Codacy)
  * Else Usage (Structures/ElseUsage ; Appinfo, Appcontent, Calisthenics)
  * Email Addresses (Type/Email ; Inventory)
  * Empty Blocks (Structures/EmptyBlocks ; Analyze, Codacy)
  * Empty Classes (Classes/EmptyClass ; Analyze, Codacy)
  * Empty Function (Functions/EmptyFunction ; Analyze, Codacy)
  * Empty Instructions (Structures/EmptyLines ; Analyze, Dead code, Codacy)
  * Empty Interfaces (Interfaces/EmptyInterface ; Analyze, Codacy)
  * Empty List (Php/EmptyList ; Analyze, CompatibilityPHP70, CompatibilityPHP71, Codacy)
  * Empty Namespace (Namespaces/EmptyNamespace ; Analyze, Dead code, OneFile, Codacy)
  * Empty Slots In Arrays (Arrays/EmptySlots ; Coding Conventions)
  * Empty Traits (Traits/EmptyTrait ; Analyze, Codacy)
  * Empty Try Catch (Structures/EmptyTryCatch ; Analyze, Codacy)
  * Empty With Expression (Structures/EmptyWithExpression ; CompatibilityPHP55, CompatibilityPHP56, OneFile, CompatibilityPHP70, CompatibilityPHP71)
  * Error Messages (Structures/ErrorMessages ; Appinfo)
  * Eval() Usage (Structures/EvalUsage ; Analyze, Appinfo, Performances, OneFile, ClearPHP, Codacy)
  * Exception Order (Exceptions/AlreadyCaught ; Dead code)
  * Exit() Usage (Structures/ExitUsage ; Analyze, Appinfo, OneFile, ClearPHP, Codacy)
  * Exit-like Methods (Functions/KillsApp ; Internal)
  * Exponent Usage (Php/ExponentUsage ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP53)
  * Ext/geoip (Extensions/Extgeoip ; Appinfo)
  * External Config Files (Files/Services ; Internal)
  * Failed Substr Comparison (Structures/FailingSubstrComparison ; Analyze, Codacy)
  * File Is Component (Files/IsComponent ; Internal)
  * File Uploads (Structures/FileUploadUsage ; Appinfo)
  * File Usage (Structures/FileUsage ; Appinfo)
  * Final Class Usage (Classes/Finalclass ; Appinfo)
  * Final Methods Usage (Classes/Finalmethod ; Appinfo)
  * Fopen Binary Mode (Portability/FopenMode ; Portability)
  * For Using Functioncall (Structures/ForWithFunctioncall ; Analyze, Performances, ClearPHP, Codacy)
  * Foreach Don't Change Pointer (Php/ForeachDontChangePointer ; CompatibilityPHP70, CompatibilityPHP71)
  * Foreach Needs Reference Array (Structures/ForeachNeedReferencedSource ; Analyze, Codacy)
  * Foreach Reference Is Not Modified (Structures/ForeachReferenceIsNotModified ; Analyze, Codacy)
  * Foreach With list() (Structures/ForeachWithList ; CompatibilityPHP54, CompatibilityPHP53)
  * Forgotten Visibility (Classes/NonPpp ; Analyze, ClearPHP, Codacy)
  * Forgotten Whitespace (Structures/ForgottenWhiteSpace ; Analyze, Codacy)
  * Fully Qualified Constants (Namespaces/ConstantFullyQualified ; Analyze, Codacy)
  * Function Called With Other Case Than Defined (Functions/FunctionCalledWithOtherCase ; )
  * Function Subscripting (Structures/FunctionSubscripting ; Appinfo, CompatibilityPHP53)
  * Function Subscripting, Old Style (Structures/FunctionPreSubscripting ; Analyze, Codacy)
  * Functioncall Is Global (Functions/IsGlobal ; Internal)
  * Functions Glossary (Functions/Functionnames ; Appinfo)
  * Functions In Loop Calls (Functions/LoopCalling ; Analyze, Performances, Codacy)
  * Functions Removed In PHP 5.4 (Php/Php54RemovedFunctions ; Analyze, CompatibilityPHP54, Codacy)
  * Functions Removed In PHP 5.5 (Php/Php55RemovedFunctions ; CompatibilityPHP55)
  * Functions Using Reference (Functions/FunctionsUsingReference ; Appinfo, Appcontent)
  * GPRC Aliases (Security/GPRAliases ; Internal)
  * Global Code Only (Files/GlobalCodeOnly ; Internal)
  * Global Import (Namespaces/GlobalImport ; Internal)
  * Global In Global (Structures/GlobalInGlobal ; Appinfo)
  * Global Inside Loop (Structures/GlobalOutsideLoop ; Performances)
  * Global Usage (Structures/GlobalUsage ; Analyze, Appinfo, ClearPHP, Codacy)
  * Globals (Variables/Globals ; Internal)
  * Goto Names (Php/Gotonames ; Appinfo, ClearPHP)
  * HTTP Status Code (Type/HttpStatus ; Inventory)
  * Hardcoded Passwords (Functions/HardcodedPasswords ; Analyze, Security, OneFile, Codacy)
  * Has Magic Property (Classes/HasMagicProperty ; Internal)
  * Has Variable Arguments (Functions/VariableArguments ; Appinfo, Internal)
  * Hash Algorithms (Php/HashAlgos ; Analyze, Codacy)
  * Hash Algorithms Incompatible With PHP 5.3 (Php/HashAlgos53 ; CompatibilityPHP53)
  * Hash Algorithms Incompatible With PHP 5.4/5 (Php/HashAlgos54 ; CompatibilityPHP54)
  * Heredoc Delimiter Glossary (Type/Heredoc ; Appinfo)
  * Hexadecimal Glossary (Type/Hexadecimal ; Appinfo)
  * Hexadecimal In String (Type/HexadecimalString ; Inventory, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72)
  * Hidden Use Expression (Namespaces/HiddenUse ; Analyze, OneFile, Codacy)
  * Htmlentities Calls (Structures/Htmlentitiescall ; Analyze, Codacy)
  * Http Headers (Type/HttpHeader ; Inventory)
  * Identical Conditions (Structures/IdenticalConditions ; Analyze, Codacy)
  * If With Same Conditions (Structures/IfWithSameConditions ; Analyze, Codacy)
  * Iffectations (Structures/Iffectation ; Analyze, Codacy)
  * Implement Is For Interface (Classes/ImplementIsForInterface ; Analyze, Codacy)
  * Implicit Global (Structures/ImplicitGlobal ; Analyze, Codacy)
  * Inclusions (Structures/IncludeUsage ; Appinfo)
  * Incompilable Files (Php/Incompilable ; Analyze, Appinfo, ClearPHP, Codacy)
  * Inconsistent Concatenation (Structures/InconsistentConcatenation ; )
  * Indices Are Int Or String (Structures/IndicesAreIntOrString ; Analyze, OneFile, Codacy)
  * Indirect Injection (Security/IndirectInjection ; Security)
  * Instantiating Abstract Class (Classes/InstantiatingAbstractClass ; Analyze, Codacy)
  * Integer Glossary (Type/Integer ; Appinfo)
  * Interface Arguments (Variables/InterfaceArguments ; )
  * Interface Methods (Interfaces/InterfaceMethod ; )
  * Interfaces Glossary (Interfaces/Interfacenames ; Appinfo)
  * Interfaces Usage (Interfaces/InterfaceUsage ; )
  * Internally Used Properties (Classes/PropertyUsedInternally ; )
  * Internet Ports (Type/Ports ; Inventory)
  * Interpolation (Type/StringInterpolation ; Coding Conventions)
  * Invalid Constant Name (Constants/InvalidName ; Analyze, Codacy)
  * Is An Extension Class (Classes/IsExtClass ; )
  * Is An Extension Constant (Constants/IsExtConstant ; Internal)
  * Is An Extension Function (Functions/IsExtFunction ; Internal)
  * Is An Extension Interface (Interfaces/IsExtInterface ; Internal)
  * Is CLI Script (Files/IsCliScript ; Appinfo, Internal)
  * Is Composer Class (Composer/IsComposerClass ; Internal)
  * Is Composer Interface (Composer/IsComposerInterface ; Internal)
  * Is Extension Trait (Traits/IsExtTrait ; Internal)
  * Is Generator (Functions/IsGenerator ; Appinfo, Internal)
  * Is Global Constant (Constants/IsGlobalConstant ; Internal)
  * Is Interface Method (Classes/IsInterfaceMethod ; Internal)
  * Is Library (Project/IsLibrary ; )
  * Is Not Class Family (Classes/IsNotFamily ; Internal)
  * Is PHP Constant (Constants/IsPhpConstant ; Internal)
  * Is Upper Family (Classes/IsUpperFamily ; Internal)
  * Isset With Constant (Structures/IssetWithConstant ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53)
  * Join file() (Performances/JoinFile ; Performances)
  * Labels (Php/Labelnames ; Appinfo)
  * Linux Only Files (Portability/LinuxOnlyFiles ; Portability)
  * List Short Syntax (Php/ListShortSyntax ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, Internal, CompatibilityPHP53, CompatibilityPHP70)
  * List With Appends (Php/ListWithAppends ; Analyze, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP70, CompatibilityPHP71, Codacy)
  * List With Keys (Php/ListWithKeys ; Appinfo, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, Appcontent, CompatibilityPHP53, CompatibilityPHP70)
  * Locally Unused Property (Classes/LocallyUnusedProperty ; Analyze, Dead code, Codacy)
  * Locally Used Property (Classes/LocallyUsedProperty ; Internal)
  * Logical Mistakes (Structures/LogicalMistakes ; Analyze, Codacy)
  * Logical Should Use Symbolic Operators (Php/LogicalInLetters ; Analyze, OneFile, ClearPHP, Codacy)
  * Lone Blocks (Structures/LoneBlock ; Analyze, Codacy)
  * Lost References (Variables/LostReferences ; Analyze, Codacy)
  * Magic Constant Usage (Constants/MagicConstantUsage ; Appinfo)
  * Magic Methods (Classes/MagicMethod ; Appinfo)
  * Magic Visibility (Classes/toStringPss ; Analyze, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, Codacy)
  * Mail Usage (Structures/MailUsage ; Appinfo)
  * Make Global A Property (Classes/MakeGlobalAProperty ; Analyze, Codacy)
  * Make One Call With Array (Performances/MakeOneCall ; Performances)
  * Malformed Octal (Type/MalformedOctal ; Analyze, Codacy)
  * Mark Callable (Functions/MarkCallable ; Internal)
  * Md5 Strings (Type/Md5String ; Inventory)
  * Method Has Fluent Interface (Functions/HasFluentInterface ; )
  * Method Has No Fluent Interface (Functions/HasNotFluentInterface ; )
  * Methodcall On New (Php/MethodCallOnNew ; CompatibilityPHP53)
  * Methods Names (Classes/MethodDefinition ; )
  * Methods Without Return (Functions/WithoutReturn ; )
  * Mime Types (Type/MimeType ; Inventory)
  * Mixed Keys Arrays (Arrays/MixedKeys ; CompatibilityPHP54, CompatibilityPHP53)
  * Multidimensional Arrays (Arrays/Multidimensional ; Appinfo)
  * Multiple Alias Definitions (Namespaces/MultipleAliasDefinitions ; Analyze, Codacy)
  * Multiple Catch (Structures/MultipleCatch ; Appinfo, Internal)
  * Multiple Class Declarations (Classes/MultipleDeclarations ; Analyze, Codacy)
  * Multiple Classes In One File (Classes/MultipleClassesInFile ; Appinfo, Coding Conventions)
  * Multiple Constant Definition (Constants/MultipleConstantDefinition ; Analyze, Codacy)
  * Multiple Definition Of The Same Argument (Functions/MultipleSameArguments ; Analyze, OneFile, CompatibilityPHP70, ClearPHP, CompatibilityPHP71, Codacy)
  * Multiple Exceptions Catch() (Exceptions/MultipleCatch ; Appinfo, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP70)
  * Multiple Identical Trait Or Interface (Classes/MultipleTraitOrInterface ; Analyze, OneFile, Codacy)
  * Multiple Index Definition (Arrays/MultipleIdenticalKeys ; Analyze, OneFile, Codacy)
  * Multiple Return (Functions/MultipleReturn ; )
  * Multiples Identical Case (Structures/MultipleDefinedCase ; Analyze, OneFile, ClearPHP, Codacy)
  * Multiply By One (Structures/MultiplyByOne ; Analyze, OneFile, ClearPHP, Codacy)
  * Must Return Methods (Functions/MustReturn ; Analyze, Codacy)
  * Namespaces (Namespaces/NamespaceUsage ; Appinfo)
  * Namespaces Glossary (Namespaces/Namespacesnames ; Appinfo)
  * Negative Power (Structures/NegativePow ; Analyze, OneFile, Codacy)
  * Nested Ifthen (Structures/NestedIfthen ; Analyze, RadwellCodes, Codacy)
  * Nested Loops (Structures/NestedLoops ; Appinfo)
  * Nested Ternary (Structures/NestedTernary ; Analyze, ClearPHP, Codacy)
  * Never Used Properties (Classes/PropertyNeverUsed ; Analyze, Codacy)
  * New Functions In PHP 5.4 (Php/Php54NewFunctions ; CompatibilityPHP53, CompatibilityPHP71)
  * New Functions In PHP 5.5 (Php/Php55NewFunctions ; CompatibilityPHP54, CompatibilityPHP53, CompatibilityPHP71)
  * New Functions In PHP 5.6 (Php/Php56NewFunctions ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP53)
  * New Functions In PHP 7.0 (Php/Php70NewFunctions ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP71)
  * New Functions In PHP 7.1 (Php/Php71NewFunctions ; CompatibilityPHP71, CompatibilityPHP72)
  * No Choice (Structures/NoChoice ; Analyze, Codacy)
  * No Count With 0 (Performances/NotCountNull ; Performances)
  * No Direct Access (Structures/NoDirectAccess ; Appinfo)
  * No Direct Call To Magic Method (Classes/DirectCallToMagicMethod ; Analyze, Codacy)
  * No Direct Usage (Structures/NoDirectUsage ; Analyze, Codacy)
  * No Global Modification (Wordpress/NoGlobalModification ; Wordpress)
  * No Hardcoded Hash (Structures/NoHardcodedHash ; Analyze, Security, Codacy)
  * No Hardcoded Ip (Structures/NoHardcodedIp ; Analyze, Security, ClearPHP, Codacy)
  * No Hardcoded Path (Structures/NoHardcodedPath ; Analyze, ClearPHP, Codacy)
  * No Hardcoded Port (Structures/NoHardcodedPort ; Analyze, Security, ClearPHP, Codacy)
  * No Implied If (Structures/ImpliedIf ; Analyze, ClearPHP, Codacy)
  * No List With String (Php/NoListWithString ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53)
  * No Parenthesis For Language Construct (Structures/NoParenthesisForLanguageConstruct ; Analyze, ClearPHP, RadwellCodes, Codacy)
  * No Plus One (Structures/PlusEgalOne ; Coding Conventions, OneFile)
  * No Public Access (Classes/NoPublicAccess ; Analyze, Codacy)
  * No Real Comparison (Type/NoRealComparison ; Analyze, Codacy)
  * No Self Referencing Constant (Classes/NoSelfReferencingConstant ; Analyze, Codacy)
  * No String With Append (Php/NoStringWithAppend ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53)
  * No Substr() One (Structures/NoSubstrOne ; Analyze, Performances, CompatibilityPHP71, Codacy)
  * No array_merge() In Loops (Performances/ArrayMergeInLoops ; Analyze, Performances, ClearPHP, Codacy)
  * Non Ascii Variables (Variables/VariableNonascii ; Analyze, Codacy)
  * Non Static Methods Called In A Static (Classes/NonStaticMethodsCalledStatic ; Analyze, CompatibilityPHP56, CompatibilityPHP70, CompatibilityPHP71, Codacy)
  * Non-constant Index In Array (Arrays/NonConstantArray ; Analyze, Codacy)
  * Non-lowercase Keywords (Php/UpperCaseKeyword ; Coding Conventions, RadwellCodes)
  * Nonce Creation (Wordpress/NonceCreation ; Wordpress)
  * Normal Methods (Classes/NormalMethods ; Appcontent)
  * Normal Property (Classes/NormalProperty ; Appcontent)
  * Not Definitions Only (Files/NotDefinitionsOnly ; Analyze, Codacy)
  * Not Not (Structures/NotNot ; Analyze, OneFile, Codacy)
  * Not Same Name As File (Classes/NotSameNameAsFile ; )
  * Not Same Name As File (Classes/SameNameAsFile ; Internal)
  * Nowdoc Delimiter Glossary (Type/Nowdoc ; Appinfo)
  * Null Coalesce (Php/NullCoalesce ; Appinfo)
  * Null On New (Classes/NullOnNew ; Analyze, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, OneFile, Codacy)
  * Objects Don't Need References (Structures/ObjectReferences ; Analyze, OneFile, ClearPHP, Codacy)
  * Octal Glossary (Type/Octal ; Appinfo)
  * Old Style Constructor (Classes/OldStyleConstructor ; Analyze, Appinfo, OneFile, ClearPHP, Codacy)
  * Old Style __autoload() (Php/oldAutoloadUsage ; Analyze, OneFile, ClearPHP, Codacy)
  * One Letter Functions (Functions/OneLetterFunctions ; Analyze, Codacy)
  * One Object Operator Per Line (Classes/OneObjectOperatorPerLine ; Calisthenics)
  * One Variable String (Type/OneVariableStrings ; Analyze, RadwellCodes, Codacy)
  * Only Static Methods (Classes/OnlyStaticMethods ; Internal)
  * Only Variable Returned By Reference (Structures/OnlyVariableReturnedByReference ; Analyze, Codacy)
  * Or Die (Structures/OrDie ; Analyze, OneFile, ClearPHP, Codacy)
  * Overwriting Variable (Variables/Overwriting ; Analyze, Codacy)
  * Overwritten Class Const (Classes/OverwrittenConst ; Appinfo)
  * Overwritten Exceptions (Exceptions/OverwriteException ; Analyze, Codacy)
  * Overwritten Literals (Variables/OverwrittenLiterals ; Analyze, Codacy)
  * PHP 7.0 New Classes (Php/Php70NewClasses ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP71)
  * PHP 7.0 New Interfaces (Php/Php70NewInterfaces ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP71)
  * PHP 7.0 Removed Directives (Php/Php70RemovedDirective ; CompatibilityPHP70, CompatibilityPHP71)
  * PHP 7.1 Removed Directives (Php/Php71RemovedDirective ; CompatibilityPHP71, CompatibilityPHP72)
  * PHP 70 Removed Functions (Php/Php70RemovedFunctions ; CompatibilityPHP70, CompatibilityPHP71)
  * PHP Arrays Index (Arrays/Phparrayindex ; Appinfo)
  * PHP Bugfixes (Php/MiddleVersion ; Appinfo, Appcontent)
  * PHP Constant Usage (Constants/PhpConstantUsage ; Appinfo)
  * PHP Handlers Usage (Php/SetHandlers ; )
  * PHP Interfaces (Interfaces/Php ; )
  * PHP Keywords As Names (Php/ReservedNames ; Analyze, CompatibilityPHP71, Codacy)
  * PHP Sapi (Type/Sapi ; Internal)
  * PHP Variables (Variables/VariablePhp ; )
  * PHP5 Indirect Variable Expression (Variables/Php5IndirectExpression ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53)
  * PHP7 Dirname (Structures/PHP7Dirname ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53)
  * Parent, Static Or Self Outside Class (Classes/PssWithoutClass ; Analyze, Codacy)
  * Parenthesis As Parameter (Php/ParenthesisAsParameter ; CompatibilityPHP70, CompatibilityPHP71)
  * Pear Usage (Php/PearUsage ; Appinfo, Appcontent)
  * Perl Regex (Type/Pcre ; Inventory)
  * Php 7 Indirect Expression (Variables/Php7IndirectExpression ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP70)
  * Php 71 New Classes (Php/Php71NewClasses ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP70, CompatibilityPHP71)
  * Php7 Relaxed Keyword (Php/Php7RelaxedKeyword ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53)
  * Phpinfo (Structures/PhpinfoUsage ; Analyze, OneFile, Codacy)
  * Pre-increment (Performances/PrePostIncrement ; Analyze, Performances, Codacy)
  * Preprocess Arrays (Arrays/ShouldPreprocess ; Analyze, Codacy)
  * Preprocessable (Structures/ShouldPreprocess ; Analyze, Codacy)
  * Print And Die (Structures/PrintAndDie ; Analyze, Codacy)
  * Property Could Be Private (Classes/CouldBePrivate ; Analyze, Codacy)
  * Property Is Modified (Classes/IsModified ; Internal)
  * Property Is Read (Classes/IsRead ; Internal)
  * Property Names (Classes/PropertyDefinition ; Appinfo)
  * Property Used Above (Classes/PropertyUsedAbove ; Internal)
  * Property Used Below (Classes/PropertyUsedBelow ; Analyze, Codacy)
  * Property/Variable Confusion (Structures/PropertyVariableConfusion ; Analyze, Codacy)
  * Queries In Loops (Structures/QueriesInLoop ; Analyze, OneFile, Codacy)
  * Random Without Try (Structures/RandomWithoutTry ; Security)
  * Real Functions (Functions/RealFunctions ; Appcontent)
  * Real Glossary (Type/Real ; Appinfo)
  * Real Variables (Variables/RealVariables ; Appcontent)
  * Recursive Functions (Functions/Recursive ; Appinfo)
  * Redeclared PHP Functions (Functions/RedeclaredPhpFunction ; Analyze, Appinfo, Codacy)
  * Redefined Class Constants (Classes/RedefinedConstants ; Analyze, Codacy)
  * Redefined Default (Classes/RedefinedDefault ; Analyze, Codacy)
  * Redefined Methods (Classes/RedefinedMethods ; Appinfo)
  * Redefined PHP Traits (Traits/Php ; Appinfo)
  * Redefined Property (Classes/RedefinedProperty ; Appinfo)
  * References (Variables/References ; Appinfo)
  * Register Globals (Security/RegisterGlobals ; Security)
  * Relay Function (Functions/RelayFunction ; Analyze, Codacy)
  * Repeated print() (Structures/RepeatedPrint ; Analyze, Codacy)
  * Reserved Keywords In PHP 7 (Php/ReservedKeywords7 ; CompatibilityPHP70, CompatibilityPHP71)
  * Resources Usage (Structures/ResourcesUsage ; Appinfo)
  * Results May Be Missing (Structures/ResultMayBeMissing ; Analyze, Codacy)
  * Return ;  (Structures/ReturnVoid ; )
  * Return True False (Structures/ReturnTrueFalse ; Analyze, Codacy)
  * Return Typehint Usage (Php/ReturnTypehintUsage ; Appinfo, Internal)
  * Return With Parenthesis (Php/ReturnWithParenthesis ; Coding Conventions, PHP recommendations)
  * Safe CurlOptions (Security/CurlOptions ; Security)
  * Same Conditions (Structures/SameConditions ; Analyze, Codacy)
  * Scalar Typehint Usage (Php/ScalarTypehintUsage ; Appinfo, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53)
  * Sensitive Argument (Security/SensitiveArgument ; Internal)
  * Sequences In For (Structures/SequenceInFor ; Analyze, Codacy)
  * Setlocale() Uses Constants (Structures/SetlocaleNeedsConstants ; CompatibilityPHP70, CompatibilityPHP71)
  * Several Instructions On The Same Line (Structures/OneLineTwoInstructions ; Analyze, Codacy)
  * Shell Usage (Structures/ShellUsage ; Appinfo)
  * Short Open Tags (Php/ShortOpenTagRequired ; Analyze, Codacy)
  * Short Syntax For Arrays (Arrays/ArrayNSUsage ; Appinfo, CompatibilityPHP53)
  * Should Be Single Quote (Type/ShouldBeSingleQuote ; Coding Conventions, ClearPHP)
  * Should Chain Exception (Structures/ShouldChainException ; Analyze, Codacy)
  * Should Make Alias (Namespaces/ShouldMakeAlias ; Analyze, OneFile, Codacy)
  * Should Typecast (Type/ShouldTypecast ; Analyze, OneFile, Codacy)
  * Should Use Coalesce (Php/ShouldUseCoalesce ; Analyze, Codacy)
  * Should Use Constants (Functions/ShouldUseConstants ; Analyze, Codacy)
  * Should Use Local Class (Classes/ShouldUseThis ; Analyze, ClearPHP, Codacy)
  * Should Use Prepared Statement (Security/ShouldUsePreparedStatement ; Analyze, Security, Codacy)
  * Silently Cast Integer (Type/SilentlyCastInteger ; Analyze, Codacy)
  * Simple Global Variable (Php/GlobalWithoutSimpleVariable ; CompatibilityPHP70, CompatibilityPHP71)
  * Simplify Regex (Structures/SimplePreg ; Performances)
  * Slow Functions (Performances/SlowFunctions ; Performances, OneFile)
  * Special Integers (Type/SpecialIntegers ; Inventory)
  * Static Loop (Structures/StaticLoop ; Analyze, Codacy)
  * Static Methods (Classes/StaticMethods ; Appinfo)
  * Static Methods Called From Object (Classes/StaticMethodsCalledFromObject ; Analyze, Codacy)
  * Static Methods Can't Contain $this (Classes/StaticContainsThis ; Analyze, ClearPHP, Codacy)
  * Static Names (Classes/StaticCpm ; )
  * Static Properties (Classes/StaticProperties ; Appinfo)
  * Static Variables (Variables/StaticVariables ; Appinfo)
  * Strict Comparison With Booleans (Structures/BooleanStrictComparison ; Analyze, Codacy)
  * String May Hold A Variable (Type/StringHoldAVariable ; Analyze, Codacy)
  * String glossary (Type/String ; )
  * Strpos Comparison (Structures/StrposCompare ; Analyze, PHP recommendations, ClearPHP, Codacy)
  * Super Global Usage (Php/SuperGlobalUsage ; Appinfo)
  * Super Globals Contagion (Security/SuperGlobalContagion ; Internal)
  * Switch To Switch (Structures/SwitchToSwitch ; Analyze, RadwellCodes, Codacy)
  * Switch With Too Many Default (Structures/SwitchWithMultipleDefault ; Analyze, ClearPHP, Codacy)
  * Switch Without Default (Structures/SwitchWithoutDefault ; Analyze, ClearPHP, Codacy)
  * Ternary In Concat (Structures/TernaryInConcat ; Analyze, Codacy)
  * Test Class (Classes/TestClass ; Appinfo)
  * Throw (Php/ThrowUsage ; Appinfo)
  * Throw Functioncall (Exceptions/ThrowFunctioncall ; Analyze, Codacy)
  * Throw In Destruct (Classes/ThrowInDestruct ; Analyze, Codacy)
  * Thrown Exceptions (Exceptions/ThrownExceptions ; Appinfo)
  * Throws An Assignement (Structures/ThrowsAndAssign ; Analyze, Codacy)
  * Timestamp Difference (Structures/TimestampDifference ; Analyze, Codacy)
  * Too Many Children (Classes/TooManyChildren ; )
  * Trait Methods (Traits/TraitMethod ; )
  * Trait Names (Traits/Traitnames ; Appinfo)
  * Traits (Traits/TraitUsage ; Appinfo)
  * Trigger Errors (Php/TriggerErrorUsage ; Appinfo)
  * True False Inconsistant Case (Constants/InconsistantCase ; Preferences)
  * Try With Finally (Structures/TryFinally ; Appinfo, Internal)
  * Typehints (Functions/Typehints ; Appinfo)
  * URL list (Type/Url ; Inventory)
  * Uncaught Exceptions (Exceptions/UncaughtExceptions ; Analyze, Codacy)
  * Unchecked Resources (Structures/UncheckedResources ; Analyze, ClearPHP, Codacy)
  * Undefined Caught Exceptions (Exceptions/CaughtButNotThrown ; Dead code)
  * Undefined Class Constants (Classes/UndefinedConstants ; Analyze, Codacy)
  * Undefined Classes (Classes/UndefinedClasses ; Analyze, Codacy)
  * Undefined Classes (ZendF/UndefinedClasses ; )
  * Undefined Constants (Constants/UndefinedConstants ; Analyze, Codacy)
  * Undefined Functions (Functions/UndefinedFunctions ; Analyze, Codacy)
  * Undefined Interfaces (Interfaces/UndefinedInterfaces ; Analyze, Codacy)
  * Undefined Parent (Classes/UndefinedParentMP ; Analyze, Codacy)
  * Undefined Properties (Classes/UndefinedProperty ; Analyze, ClearPHP, Codacy)
  * Undefined Trait (Traits/UndefinedTrait ; Analyze, Codacy)
  * Undefined Zend 1.10 (ZendF/UndefinedClass110 ; ZendFramework)
  * Undefined Zend 1.11 (ZendF/UndefinedClass111 ; ZendFramework)
  * Undefined Zend 1.12 (ZendF/UndefinedClass112 ; ZendFramework)
  * Undefined Zend 1.8 (ZendF/UndefinedClass18 ; ZendFramework)
  * Undefined Zend 1.9 (ZendF/UndefinedClass19 ; ZendFramework)
  * Undefined static:: Or self:: (Classes/UndefinedStaticMP ; Analyze, Codacy)
  * Unescaped Variables In Templates (Wordpress/UnescapedVariables ; Wordpress)
  * Unicode Blocks (Type/UnicodeBlock ; Inventory)
  * Unicode Escape Partial (Php/UnicodeEscapePartial ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53)
  * Unicode Escape Syntax (Php/UnicodeEscapeSyntax ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53)
  * Unknown Directive Name (Php/DirectiveName ; Analyze, Codacy)
  * Unkown Regex Options (Structures/UnknownPregOption ; Analyze, Codacy)
  * Unpreprocessed Values (Structures/Unpreprocessed ; Analyze, OneFile, ClearPHP, Codacy)
  * Unreachable Code (Structures/UnreachableCode ; Analyze, Dead code, OneFile, ClearPHP, Codacy)
  * Unresolved Catch (Classes/UnresolvedCatch ; Dead code, ClearPHP)
  * Unresolved Classes (Classes/UnresolvedClasses ; Analyze, Codacy)
  * Unresolved Instanceof (Classes/UnresolvedInstanceof ; Analyze, Dead code, ClearPHP, Codacy)
  * Unresolved Use (Namespaces/UnresolvedUse ; Analyze, ClearPHP, Codacy)
  * Unserialize Second Arg (Security/UnserializeSecondArg ; Security)
  * Unset Arguments (Functions/UnsetOnArguments ; OneFile)
  * Unset In Foreach (Structures/UnsetInForeach ; Analyze, Dead code, OneFile, Codacy)
  * Unthrown Exception (Exceptions/Unthrown ; Analyze, Dead code, ClearPHP, Codacy)
  * Unused Arguments (Functions/UnusedArguments ; Analyze, Codacy)
  * Unused Classes (Classes/UnusedClass ; Analyze, Dead code, Codacy)
  * Unused Constants (Constants/UnusedConstants ; Analyze, Dead code, Codacy)
  * Unused Functions (Functions/UnusedFunctions ; Analyze, Dead code, Codacy)
  * Unused Global (Structures/UnusedGlobal ; Analyze, Codacy)
  * Unused Interfaces (Interfaces/UnusedInterfaces ; Analyze, Dead code, Codacy)
  * Unused Label (Structures/UnusedLabel ; Analyze, Dead code, Codacy)
  * Unused Methods (Classes/UnusedMethods ; Analyze, Dead code, Codacy)
  * Unused Protected Methods (Classes/UnusedProtectedMethods ; Dead code)
  * Unused Static Methods (Classes/UnusedPrivateMethod ; Analyze, Dead code, OneFile, Codacy)
  * Unused Static Properties (Classes/UnusedPrivateProperty ; Analyze, Dead code, OneFile, Codacy)
  * Unused Traits (Traits/UnusedTrait ; Analyze, Codacy)
  * Unused Use (Namespaces/UnusedUse ; Analyze, Dead code, ClearPHP, Codacy)
  * Unusual Case For PHP Functions (Php/UpperCaseFunction ; Coding Conventions)
  * Unverified Nonce (Wordpress/UnverifiedNonce ; Wordpress)
  * Usage Of class_alias() (Classes/ClassAliasUsage ; Appinfo)
  * Use $wpdb Api (Wordpress/UseWpdbApi ; Wordpress)
  * Use === null (Php/IsnullVsEqualNull ; Analyze, OneFile, RadwellCodes, Codacy)
  * Use Cli (Php/UseCli ; Appinfo)
  * Use Const And Functions (Namespaces/UseFunctionsConstants ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP53)
  * Use Constant (Structures/UseConstant ; PHP recommendations)
  * Use Constant As Arguments (Functions/UseConstantAsArguments ; Analyze, Codacy)
  * Use Instanceof (Classes/UseInstanceof ; Analyze, Codacy)
  * Use Lower Case For Parent, Static And Self (Php/CaseForPSS ; Analyze, CompatibilityPHP54, CompatibilityPHP53, Codacy)
  * Use Nullable Type (Php/UseNullableType ; CompatibilityPHP71, CompatibilityPHP72)
  * Use Object Api (Php/UseObjectApi ; Analyze, ClearPHP, Codacy)
  * Use Pathinfo (Php/UsePathinfo ; Analyze, Codacy)
  * Use System Tmp (Structures/UseSystemTmp ; Analyze, Codacy)
  * Use This (Classes/UseThis ; Internal)
  * Use Web (Php/UseWeb ; Appinfo)
  * Use With Fully Qualified Name (Namespaces/UseWithFullyQualifiedNS ; Analyze, Coding Conventions, PHP recommendations, Codacy)
  * Use const (Constants/ConstRecommended ; Analyze, Coding Conventions, Codacy)
  * Use password_hash() (Php/Password55 ; CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP70, CompatibilityPHP71)
  * Use random_int() (Php/BetterRand ; Analyze, Security, CompatibilityPHP71, CompatibilityPHP72, Codacy)
  * Used Classes (Classes/UsedClass ; Internal)
  * Used Functions (Functions/UsedFunctions ; Internal)
  * Used Interfaces (Interfaces/UsedInterfaces ; Internal)
  * Used Methods (Classes/UsedMethods ; Internal)
  * Used Once Variables (In Scope) (Variables/VariableUsedOnceByContext ; Analyze, OneFile, ClearPHP, Codacy)
  * Used Once Variables (Variables/VariableUsedOnce ; Analyze, OneFile, Codacy)
  * Used Protected Method (Classes/UsedProtectedMethod ; Dead code)
  * Used Static Methods (Classes/UsedPrivateMethod ; Internal)
  * Used Static Properties (Classes/UsedPrivateProperty ; Internal)
  * Used Trait (Traits/UsedTrait ; Internal)
  * Used Use (Namespaces/UsedUse ; )
  * Useless Abstract Class (Classes/UselessAbstract ; Analyze, Codacy)
  * Useless Brackets (Structures/UselessBrackets ; Analyze, RadwellCodes, Codacy)
  * Useless Constructor (Classes/UselessConstructor ; Analyze, Codacy)
  * Useless Final (Classes/UselessFinal ; Analyze, OneFile, ClearPHP, Codacy)
  * Useless Global (Structures/UselessGlobal ; Analyze, OneFile, Codacy)
  * Useless Instructions (Structures/UselessInstruction ; Analyze, OneFile, ClearPHP, Codacy)
  * Useless Interfaces (Interfaces/UselessInterfaces ; Analyze, ClearPHP, Codacy)
  * Useless Parenthesis (Structures/UselessParenthesis ; Analyze, Codacy)
  * Useless Return (Functions/UselessReturn ; Analyze, OneFile, Codacy)
  * Useless Switch (Structures/UselessSwitch ; Analyze, Codacy)
  * Useless Unset (Structures/UselessUnset ; Analyze, OneFile, ClearPHP, Codacy)
  * Uses Default Values (Functions/UsesDefaultArguments ; Analyze, Codacy)
  * Uses Environnement (Php/UsesEnv ; Appinfo, Appcontent)
  * Using $this Outside A Class (Classes/UsingThisOutsideAClass ; Analyze, CompatibilityPHP71, CompatibilityPHP72, Codacy)
  * Using Short Tags (Structures/ShortTags ; Appinfo)
  * Usort Sorting In PHP 7.0 (Php/UsortSorting ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP70, CompatibilityPHP71)
  * Var (Classes/OldStyleVar ; Analyze, OneFile, ClearPHP, Codacy)
  * Variable Constants (Constants/VariableConstant ; Appinfo)
  * Variable Is Modified (Variables/IsModified ; Internal)
  * Variable Is Read (Variables/IsRead ; Internal)
  * Variables Names (Variables/Variablenames ; )
  * Variables Variables (Variables/VariableVariables ; Appinfo)
  * Variables With Long Names (Variables/VariableLong ; )
  * Variables With One Letter Names (Variables/VariableOneLetter ; )
  * While(List() = Each()) (Structures/WhileListEach ; Analyze, Performances, OneFile, Codacy)
  * Wpdb Best Usage (Wordpress/WpdbBestUsage ; Wordpress)
  * Written Only Variables (Variables/WrittenOnlyVariable ; Analyze, OneFile, Codacy)
  * Wrong Class Location (ZendF/NotInThatPath ; ZendFramework)
  * Wrong Number Of Arguments (Functions/WrongNumberOfArguments ; Analyze, OneFile, Codacy)
  * Wrong Number Of Arguments In Methods (Functions/WrongNumberOfArgumentsMethods ; OneFile)
  * Wrong Optional Parameter (Functions/WrongOptionalParameter ; Analyze, Codacy)
  * Wrong Parameter Type (Php/InternalParameterType ; Analyze, OneFile, Codacy)
  * Wrong fopen() Mode (Php/FopenMode ; Analyze, Codacy)
  * Yield From Usage (Php/YieldFromUsage ; Appinfo, Appcontent)
  * Yield Usage (Php/YieldUsage ; Appinfo, Appcontent)
  * Yoda Comparison (Structures/YodaComparison ; Coding Conventions)
  * Zend Classes (ZendF/ZendClasses ; ZendFramework)
  * __debugInfo() usage (Php/debugInfoUsage ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP53)
  * __halt_compiler (Php/Haltcompiler ; Appinfo)
  * __toString() Throws Exception (Structures/toStringThrowsException ; Analyze, OneFile, Codacy)
  * charger_fonction() (Spip/chargerFonction ; )
  * crypt() Without Salt (Structures/CryptWithoutSalt ; Analyze, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP70, CompatibilityPHP71, Codacy)
  * error_reporting() With Integers (Structures/ErrorReportingWithInteger ; Analyze, Codacy)
  * eval() Without Try (Structures/EvalWithoutTry ; Analyze, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, Codacy)
  * ext/0mq (Extensions/Extzmq ; Appinfo)
  * ext/amqp (Extensions/Extamqp ; Appinfo)
  * ext/apache (Extensions/Extapache ; Appinfo)
  * ext/apc (Extensions/Extapc ; Analyze, Appinfo, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP70, CompatibilityPHP71, Codacy)
  * ext/apcu (Extensions/Extapcu ; Appinfo)
  * ext/array (Extensions/Extarray ; Appinfo)
  * ext/bcmath (Extensions/Extbcmath ; Appinfo)
  * ext/bzip2 (Extensions/Extbzip2 ; Appinfo)
  * ext/cairo (Extensions/Extcairo ; Appinfo)
  * ext/calendar (Extensions/Extcalendar ; Appinfo)
  * ext/com (Extensions/Extcom ; Appinfo)
  * ext/crypto (Extensions/Extcrypto ; Appinfo)
  * ext/ctype (Extensions/Extctype ; Appinfo)
  * ext/curl (Extensions/Extcurl ; Appinfo)
  * ext/cyrus (Extensions/Extcyrus ; Appinfo)
  * ext/date (Extensions/Extdate ; Appinfo)
  * ext/dba (Extensions/Extdba ; Appinfo, CompatibilityPHP53)
  * ext/dio (Extensions/Extdio ; Appinfo)
  * ext/dom (Extensions/Extdom ; Appinfo)
  * ext/eaccelerator (Extensions/Exteaccelerator ; Appinfo)
  * ext/enchant (Extensions/Extenchant ; Appinfo)
  * ext/ereg (Extensions/Extereg ; Appinfo, CompatibilityPHP70, CompatibilityPHP71)
  * ext/ev (Extensions/Extev ; Appinfo)
  * ext/event (Extensions/Extevent ; Appinfo)
  * ext/exif (Extensions/Extexif ; Appinfo)
  * ext/expect (Extensions/Extexpect ; Appinfo)
  * ext/fann (Extensions/Extfann ; Analyze, Appinfo, Codacy)
  * ext/fdf (Extensions/Extfdf ; Analyze, Appinfo, CompatibilityPHP53, Codacy)
  * ext/ffmpeg (Extensions/Extffmpeg ; Appinfo)
  * ext/file (Extensions/Extfile ; Appinfo)
  * ext/fileinfo (Extensions/Extfileinfo ; Appinfo)
  * ext/filter (Extensions/Extfilter ; Appinfo)
  * ext/fpm (Extensions/Extfpm ; Appinfo)
  * ext/ftp (Extensions/Extftp ; Appinfo)
  * ext/gd (Extensions/Extgd ; Appinfo)
  * ext/gearman (Extensions/Extgearman ; Appinfo)
  * ext/gettext (Extensions/Extgettext ; Appinfo)
  * ext/gmagick (Extensions/Extgmagick ; Appinfo)
  * ext/gmp (Extensions/Extgmp ; Appinfo)
  * ext/gnupgp (Extensions/Extgnupg ; Appinfo)
  * ext/hash (Extensions/Exthash ; Appinfo)
  * ext/ibase (Extensions/Extibase ; Appinfo)
  * ext/iconv (Extensions/Exticonv ; Appinfo)
  * ext/iis (Extensions/Extiis ; Appinfo, Portability)
  * ext/imagick (Extensions/Extimagick ; Appinfo)
  * ext/imap (Extensions/Extimap ; Appinfo)
  * ext/info (Extensions/Extinfo ; Appinfo)
  * ext/inotify (Extensions/Extinotify ; Appinfo)
  * ext/intl (Extensions/Extintl ; Appinfo)
  * ext/json (Extensions/Extjson ; Appinfo)
  * ext/kdm5 (Extensions/Extkdm5 ; Appinfo)
  * ext/ldap (Extensions/Extldap ; Appinfo)
  * ext/libevent (Extensions/Extlibevent ; Appinfo)
  * ext/libxml (Extensions/Extlibxml ; Appinfo)
  * ext/lua (Extensions/Extlua ; Appinfo)
  * ext/mail (Extensions/Extmail ; Appinfo)
  * ext/mailparse (Extensions/Extmailparse ; Appinfo)
  * ext/math (Extensions/Extmath ; Appinfo)
  * ext/mbstring (Extensions/Extmbstring ; Appinfo)
  * ext/mcrypt (Extensions/Extmcrypt ; Appinfo, CompatibilityPHP71, CompatibilityPHP72)
  * ext/memcache (Extensions/Extmemcache ; Appinfo)
  * ext/memcached (Extensions/Extmemcached ; Appinfo)
  * ext/ming (Extensions/Extming ; Appinfo, CompatibilityPHP53)
  * ext/mongo (Extensions/Extmongo ; Appinfo)
  * ext/mssql (Extensions/Extmssql ; Appinfo)
  * ext/mysql (Extensions/Extmysql ; Analyze, Appinfo, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP70, CompatibilityPHP71, Codacy)
  * ext/mysqli (Extensions/Extmysqli ; Appinfo)
  * ext/ob (Extensions/Extob ; Appinfo)
  * ext/oci8 (Extensions/Extoci8 ; Appinfo)
  * ext/odbc (Extensions/Extodbc ; Appinfo)
  * ext/opcache (Extensions/Extopcache ; Appinfo)
  * ext/openssl (Extensions/Extopenssl ; Appinfo)
  * ext/parsekit (Extensions/Extparsekit ; Appinfo)
  * ext/pcntl (Extensions/Extpcntl ; Appinfo)
  * ext/pcre (Extensions/Extpcre ; Appinfo)
  * ext/pdo (Extensions/Extpdo ; Appinfo)
  * ext/pecl_http (Extensions/Exthttp ; Appinfo, Appcontent)
  * ext/pgsql (Extensions/Extpgsql ; Appinfo)
  * ext/phalcon (Extensions/Extphalcon ; Appinfo)
  * ext/phar (Extensions/Extphar ; Appinfo)
  * ext/php-ast (Extensions/Extast ; Appinfo)
  * ext/posix (Extensions/Extposix ; Appinfo)
  * ext/proctitle (Extensions/Extproctitle ; Appinfo)
  * ext/pspell (Extensions/Extpspell ; Appinfo)
  * ext/readline (Extensions/Extreadline ; Appinfo)
  * ext/recode (Extensions/Extrecode ; Appinfo)
  * ext/redis (Extensions/Extredis ; Appinfo)
  * ext/reflexion (Extensions/Extreflection ; Appinfo)
  * ext/runkit (Extensions/Extrunkit ; Appinfo)
  * ext/sem (Extensions/Extsem ; Appinfo)
  * ext/shmop (Extensions/Extshmop ; Appinfo)
  * ext/simplexml (Extensions/Extsimplexml ; Appinfo)
  * ext/snmp (Extensions/Extsnmp ; Appinfo)
  * ext/soap (Extensions/Extsoap ; Appinfo)
  * ext/sockets (Extensions/Extsession ; Appinfo)
  * ext/sockets (Extensions/Extsockets ; Appinfo)
  * ext/spl (Extensions/Extspl ; Appinfo)
  * ext/sqlite (Extensions/Extsqlite ; Analyze, Appinfo, Codacy)
  * ext/sqlite3 (Extensions/Extsqlite3 ; Appinfo)
  * ext/sqlsrv (Extensions/Extsqlsrv ; Appinfo)
  * ext/ssh2 (Extensions/Extssh2 ; Appinfo)
  * ext/standard (Extensions/Extstandard ; Appinfo)
  * ext/suhosin (Extensions/Extsuhosin ; Appinfo)
  * ext/tidy (Extensions/Exttidy ; Appinfo)
  * ext/tokenizer (Extensions/Exttokenizer ; Appinfo)
  * ext/tokyotyrant (Extensions/Exttokyotyrant ; Appinfo)
  * ext/trader (Extensions/Exttrader ; Appinfo)
  * ext/v8js (Extensions/Extv8js ; Appinfo)
  * ext/wddx (Extensions/Extwddx ; Appinfo)
  * ext/wikidiff2 (Extensions/Extwikidiff2 ; Appinfo)
  * ext/wincache (Extensions/Extwincache ; Appinfo, Portability)
  * ext/xcache (Extensions/Extxcache ; Appinfo)
  * ext/xdebug (Extensions/Extxdebug ; Appinfo)
  * ext/xdiff (Extensions/Extxdiff ; Appinfo)
  * ext/xhprof (Extensions/Extxhprof ; Appinfo)
  * ext/xml (Extensions/Extxml ; Appinfo)
  * ext/xmlreader (Extensions/Extxmlreader ; Appinfo)
  * ext/xmlrpc (Extensions/Extxmlrpc ; Appinfo)
  * ext/xmlwriter (Extensions/Extxmlwriter ; Appinfo)
  * ext/xsl (Extensions/Extxsl ; Appinfo)
  * ext/yaml (Extensions/Extyaml ; Appinfo)
  * ext/yis (Extensions/Extyis ; Appinfo)
  * ext/zip (Extensions/Extzip ; Appinfo)
  * ext/zlib (Extensions/Extzlib ; Appinfo)
  * func_get_arg() Modified (Functions/funcGetArgModified ; Analyze, CompatibilityPHP70, CompatibilityPHP71, Codacy)
  * include_once() Usage (Structures/OnceUsage ; Analyze, Appinfo, Codacy)
  * list() May Omit Variables (Structures/ListOmissions ; Analyze, Codacy)
  * mcrypt_create_iv() With Default Values (Structures/McryptcreateivWithoutOption ; Analyze, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP70, CompatibilityPHP71, Codacy)
  * parse_str() Warning (Security/parseUrlWithoutParameters ; Security)
  * preg_match_all() Flag (Php/PregMatchAllFlag ; Analyze, Codacy)
  * preg_replace With Option e (Structures/pregOptionE ; Analyze, Security, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, Codacy)
  * set_exception_handler() Warning (Php/SetExceptionHandlerPHP7 ; CompatibilityPHP70, CompatibilityPHP71)
  * var_dump()... Usage (Structures/VardumpUsage ; Analyze, Security, ClearPHP, Codacy)

* 0.8.3

  * Variable Global (Structures/VariableGlobal)




External services
-----------------

List of external services whose configuration files has been commited in the code.

* [Apache](http://www.apache.org/) - .htaccess
* [Apple](http://www.apple.com/) - .DS_Store
* [appveyor](http://www.appveyor.com/) - appveyor.yml
* [ant](https://ant.apache.org/) - build.xml
* [artisan](http://laravel.com/docs/5.1/artisan) - artisan
* [atoum](http://atoum.org/) - .bootstrap.atoum.php,.atoum.php
* [arcanist](https://secure.phabricator.com/book/phabricator/article/arcanist_lint/) - .arclint, .arcconfig
* [box2](https://github.com/box-project/box2) - box.json
* [behat](http://docs.behat.org/en/v2.5/) - behat.yml.dist
* [bower](http://bower.io/) - bower.json, .bowerrc
* [bazaar](http://bazaar.canonical.com/en/) - .bzr
* [circleCI](https://circleci.com/) - circle.yml
* [codeception](http://codeception.com/) - codeception.yml
* [codeclimate](http://www.codeclimate.com/) - .codeclimate.yml
* [composer](https://getcomposer.org/) - composer.json, composer.lock
* [couscous](http://couscous.io/) - couscous.yml
* [Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer) - .php_cs
* [coveralls](https://coveralls.zendesk.com/) - .coveralls.yml
* [eslint](http://eslint.org/) - .eslintrc
* [git](https://git-scm.com/) - .git, .gitignore, .gitattributes, .gitmodules
* [gulpfile](http://gulpjs.com/) - .js
* [gush](https://github.com/gushphp/gush) - .gush.yml
* [mercurial](https://www.mercurial-scm.org/) - .hg, .hgtags
* [insight](https://insight.sensiolabs.com/) - .sensiolabs.yml
* [jshint](http://jshint.com/) - .jshintrc
* [npm](https://www.npmjs.com/) - package.json
* [phan](https://github.com/etsy/phan) - .phan
* [pharcc](https://github.com/cbednarski/pharcc) - .pharcc.yml
* [phpformatter](https://github.com/mmoreram/php-formatter) - .formatter.yml
* [phpmetrics](http://www.phpmetrics.org/) - .phpmetrics.yml.dist
* [phpsa](https://github.com/ovr/phpsa) - .phpsa.yml
* [phpspec](http://www.phpspec.net/en/latest/) - phpspec.yml
* [phpstan](https://github.com/phpstan) - phpstan.neon
* [phpswitch](https://github.com/jubianchi/phpswitch) - .phpswitch.yml
* [phpunit](https://phpunit.de/) - phpunit.xml.dist
* [psalm](https://getpsalm.org/) - psalm.xml
* [robo](https://robo.li/) - RoboFile.php
* [scrutinizer](https://scrutinizer-ci.com/) - .scrutinizer.yml
* [semantic versioning](http://semver.org/) - .semver
* [spip](http://www.spip.net/) - paquet.xml
* [storyplayer](https://datasift.github.io/storyplayer/) - storyplayer.json.dist
* [styleci](https://styleci.io/) - .styleci.yml
* [sublimelinter](http://www.sublimelinter.com/en/latest/) - .csslintrc
* [svn](https://subversion.apache.org/) - svn.revision, .svn
* [Robots.txt](http://www.robotstxt.org/) - robots.txt
* [travis](https://travis-ci.org/) - .travis.yml
* [Vagrant](https://www.vagrantup.com/) - Vagrantfile
* [Zend_Tool](https://framework.zend.com/) - zfproject.xml

External links
--------------

List of external links mentionned in this documentation.

* `ansible <http://docs.ansible.com/ansible/intro_installation.html>`_
* `Autoloading Classe <http://php.net/manual/en/language.oop5.autoload.php>`_
* `Backward incompatible changes PHP 7.0 <http://php.net/manual/en/migration70.incompatible.php>`_
* `bazaar <http://bazaar.canonical.com/en/>`_
* `Cake 3.0 migration guide <http://book.cakephp.org/3.0/en/appendices/3-0-migration-guide.html>`_
* `Cake 3.2 migration guide <http://book.cakephp.org/3.0/en/appendices/3-2-migration-guide.html>`_
* `Cake 3.3 migration guide <http://book.cakephp.org/3.0/en/appendices/3-3-migration-guide.html>`_
* `Class Reference/wpdb <https://codex.wordpress.org/Class_Reference/wpdb>`_
* `composer <https://getcomposer.org/>`_
* `curl <http://www.php.net/curl>`_
* `Docker <http://www.docker.com/>`_
* `Docker image <https://hub.docker.com/r/exakat/exakat/>`_
* `dotdeb instruction <https://www.dotdeb.org/instructions/>`_
* `Exakat <http://www.exakat.io/>`_
* `Exakat cloud <https://www.exakat.io/exakat-cloud/>`_
* `Exakat SAS <https://www.exakat.io/get-php-expertise/>`_
* `exakat.phar` archive from `exakat.io <http://www.exakat.io/>`_
* `ffmpeg-php <http://ffmpeg-php.sourceforge.net/>`_
* `git <https://git-scm.com/>`_
* `Github <https://github.com/exakat/exakat>`_
* `Global Variables <https://codex.wordpress.org/Global_Variables>`_
* `gremlin plug-in <https://github.com/thinkaurelius/neo4j-gremlin-plugin>`_
* `hash <http://www.php.net/hash>`_
* `hg <https://www.mercurial-scm.org/>`_
* `Internal Constructor Behavior <https://wiki.php.net/rfc/internal_constructor_behaviour>`_
* `Isset Ternary <https://wiki.php.net/rfc/isset_ternary>`_
* `List of function aliases <http://php.net/manual/en/aliases.php>`_
* `Logical Expressions in C/C++. Mistakes Made by Professionals <http://www.viva64.com/en/b/0390/>`_
* `Magic Hashes <https://blog.whitehatsec.com/magic-hashes/>`_
* `Marco Pivetti tweet <https://twitter.com/Ocramius/status/811504929357660160>`_
* `Neo4j <http://neo4j.com/>`_
* `No Dangling Reference <https://github.com/dseguy/clearPHP/blob/master/rules/no-dangling-reference.md>`_
* `phar <https://www.exakat.io/download-exakat/>`_
* `PHP Tags <http://php.net/manual/en/language.basic-syntax.phptags.php>`_
* `php-zbarcode <https://github.com/mkoppanen/php-zbarcode>`_
* `Putting glob to the test <https://www.phparch.com/2010/04/putting-glob-to-the-test/>`_
* `Scope Resolution Operator (::) <http://php.net/manual/en/language.oop5.paamayim-nekudotayim.php>`_
* `Semaphore <http://php.net/manual/en/book.sem.php>`_
* `sqlite3 <http://www.php.net/sqlite3>`_
* `Suhosin.org <https://suhosin.org/>`_
* `svn <https://subversion.apache.org/>`_
* `the docs online <http://exakat.readthedocs.io/en/latest/Rules.html>`_
* `The main PPA for PHP (5.6, 7.0, 7.1)  <https://launchpad.net/~ondrej/+archive/ubuntu/php>`_
* `tokenizer <http://www.php.net/tokenizer>`_
* `Tutorial 1: Let’s learn by example¶ <https://docs.phalconphp.com/en/latest/reference/tutorial.html>`_
* `vagrant <https://www.vagrantup.com/docs/installation/>`_
* `Vagrant file <https://github.com/exakat/exakat-vagrant>`_
* `When to declare classes final <http://ocramius.github.io/blog/when-to-declare-classes-final/>`_
* `XSL extension <http://php.net/manual/en/intro.xsl.php>`_


