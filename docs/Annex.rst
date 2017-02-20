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

List of analyzers, by version of introduction, newest to oldest. 


* 0.10.2

  * Class Function Confusion (Php/ClassFunctionConfusion)
  * Forgotten Thrown (Exceptions/ForgottenThrown)
  * ext/libsodium (Extensions/Extlibsodium)

* 0.10.1

  * Avoid Non Wordpress Globals (Wordpress/AvoidOtherGlobals)
  * SQL queries (Type/Sql)
  * Strange Names For Methods (Classes/StrangeName)

* 0.10.0

  * Error_Log() Usage (Php/ErrorLogUsage)
  * No Boolean As Default (Functions/NoBooleanAsDefault)
  * Raised Access Level (Classes/RaisedAccessLevel)
  * Use Prepare With Variables (Wordpress/WpdbPrepareForVariables)

* 0.9.9

  * PHP 7.2 Deprecations (Php/Php72Deprecation)
  * PHP 7.2 Removed Functions (Php/Php72RemovedFunctions)

* 0.9.8

  * Assigned Twice (Variables/AssignedTwiceOrMore)
  * New Line Style (Structures/NewLineStyle)
  * New On Functioncall Or Identifier (Classes/NewOnFunctioncallOrIdentifier)

* 0.9.7

  * Avoid Large Array Assignation (Structures/NoAssignationInFunction)
  * Could Be Protected Property (Classes/CouldBeProtectedProperty)
  * Long Arguments (Structures/LongArguments)
  * ZendF/ZendTypehinting (ZendF/ZendTypehinting)

* 0.9.6

  * Fetch One Row Format (Performances/FetchOneRowFormat)
  * Performances/NoGlob (Performances/NoGlob)

* 0.9.5

  * Ext/mongodb (Extensions/Extmongodb)
  * One Expression Brackets Consistency (Structures/OneExpressionBracketsConsistency)
  * Should Use Function Use (Php/ShouldUseFunction)
  * ext/zbarcode (Extensions/Extzbarcode)

* 0.9.4

  * Class Should Be Final By Ocramius (Classes/FinalByOcramius)
  * String (Extensions/Extstring)
  * ext/mhash (Extensions/Extmhash)

* 0.9.3

  * Close Tags Consistency (Php/CloseTagsConsistency)
  * Unset() Or (unset) (Php/UnsetOrCast)
  * Wpdb Prepare Or Not (Wordpress/WpdbPrepareOrNot)

* 0.9.2

  * $GLOBALS Or global (Php/GlobalsVsGlobal)
  * Illegal Name For Method (Classes/WrongName)
  * Too Many Local Variables (Functions/TooManyLocalVariables)
  * Use Composer Lock (Composer/UseComposerLock)
  * ext/ncurses (Extensions/Extncurses)
  * ext/newt (Extensions/Extnewt)
  * ext/nsapi (Extensions/Extnsapi)

* 0.9.1

  * Avoid Using stdClass (Php/UseStdclass)
  * Avoid array_push() (Performances/AvoidArrayPush)
  * Could Return Void (Functions/CouldReturnVoid)
  * Invalid Octal In String (Type/OctalInString)
  * Undefined Class 2.0 (ZendF/UndefinedClass20)
  * Undefined Class 2.1 (ZendF/UndefinedClass21)
  * Undefined Class 2.2 (ZendF/UndefinedClass22)
  * Undefined Class 2.3 (ZendF/UndefinedClass23)
  * Undefined Class 2.4 (ZendF/UndefinedClass24)
  * Undefined Class 2.5 (ZendF/UndefinedClass25)
  * Undefined Class 3.0 (ZendF/UndefinedClass30)
  * Zend Interface (ZendF/ZendInterfaces)
  * Zend Trait (ZendF/ZendTrait)

* 0.9.0

  * Getting Last Element (Arrays/GettingLastElement)
  * Rethrown Exceptions (Exceptions/Rethrown)

* 0.8.9

  * Array() / [  ] Consistence (Arrays/ArrayBracketConsistence)
  * Bail Out Early (Structures/BailOutEarly)
  * Die Exit Consistence (Structures/DieExitConsistance)
  * Dont Change The Blind Var (Structures/DontChangeBlindKey)
  * More Than One Level Of Indentation (Structures/OneLevelOfIndentation)
  * One Dot Or Object Operator Per Line (Structures/OneDotOrObjectOperatorPerLine)
  * PHP 7.1 Microseconds (Php/Php71microseconds)
  * Unitialized Properties (Classes/UnitializedProperties)
  * Use Wordpress Functions (Wordpress/UseWpFunctions)
  * Useless Check (Structures/UselessCheck)

* 0.8.7

  * Dont Echo Error (Security/DontEchoError)
  * No Isset With Empty (Structures/NoIssetWithEmpty)
  * Performances/timeVsstrtotime (Performances/timeVsstrtotime)
  * Use Class Operator (Classes/UseClassOperator)
  * Useless Casting (Structures/UselessCasting)
  * ext/rar (Extensions/Extrar)

* 0.8.6

  * Boolean Value (Type/BooleanValue)
  * Drop Else After Return (Structures/DropElseAfterReturn)
  * Modernize Empty With Expression (Structures/ModernEmpty)
  * Null Value (Type/NullValue)
  * Use Positive Condition (Structures/UsePositiveCondition)

* 0.8.5

  * Is Zend Framework 1 Controller (ZendF/IsController)
  * Is Zend Framework 1 Helper (ZendF/IsHelper)
  * Should Make Ternary (Structures/ShouldMakeTernary)
  * Unused Returned Value (Functions/UnusedReturnedValue)

* 0.8.4

  * $HTTP_RAW_POST_DATA (Php/RawPostDataUsage)
  * $this Belongs To Classes Or Traits (Classes/ThisIsForClasses)
  * $this Is Not An Array (Classes/ThisIsNotAnArray)
  * $this Is Not For Static Methods (Classes/ThisIsNotForStatic)
  * ** For Exponent (Php/NewExponent)
  * ... Usage (Php/EllipsisUsage)
  * ::class (Php/StaticclassUsage)
  * <?= Usage (Php/EchoTagUsage)
  * @ Operator (Structures/Noscream)
  * Abstract Class Usage (Classes/Abstractclass)
  * Abstract Methods Usage (Classes/Abstractmethods)
  * Abstract Static Methods (Classes/AbstractStatic)
  * Access Protected Structures (Classes/AccessProtected)
  * Accessing Private (Classes/AccessPrivate)
  * Action Should Bin In Controller (ZendF/ActionInController)
  * Adding Zero (Structures/AddZero)
  * Aliases (Namespaces/Alias)
  * Aliases Usage (Functions/AliasesUsage)
  * All Uppercase Variables (Variables/VariableUppercase)
  * Already Parents Interface (Interfaces/AlreadyParentsInterface)
  * Altering Foreach Without Reference (Structures/AlteringForeachWithoutReference)
  * Alternative Syntax (Php/AlternativeSyntax)
  * Always Positive Comparison (Structures/NeverNegative)
  * Ambiguous Array Index (Arrays/AmbiguousKeys)
  * Anonymous Classes (Classes/Anonymous)
  * Argument Should Be Typehinted (Functions/ShouldBeTypehinted)
  * Arguments (Variables/Arguments)
  * Array Index (Arrays/Arrayindex)
  * Arrays Is Modified (Arrays/IsModified)
  * Arrays Is Read (Arrays/IsRead)
  * Assertions (Php/AssertionUsage)
  * Assign Default To Properties (Classes/MakeDefault)
  * Autoloading (Php/AutoloadUsage)
  * Avoid Parenthesis (Structures/PrintWithoutParenthesis)
  * Avoid Those Crypto (Security/AvoidThoseCrypto)
  * Avoid array_unique() (Structures/NoArrayUnique)
  * Avoid get_class() (Structures/UseInstanceof)
  * Avoid sleep()/usleep() (Security/NoSleep)
  * Bad Constants Names (Constants/BadConstantnames)
  * Binary Glossary (Type/Binary)
  * Blind Variables (Variables/Blind)
  * Bracketless Blocks (Structures/Bracketless)
  * Break Outside Loop (Structures/BreakOutsideLoop)
  * Break With 0 (Structures/Break0)
  * Break With Non Integer (Structures/BreakNonInteger)
  * Buried Assignation (Structures/BuriedAssignation)
  * CakePHP 3.0 Deprecated Class (Cakephp/Cake30DeprecatedClass)
  * CakePHP 3.3 Deprecated Class (Cakephp/Cake33DeprecatedClass)
  * Calltime Pass By Reference (Structures/CalltimePassByReference)
  * Can't Disable Function (Security/CantDisableFunction)
  * Can't Extend Final (Classes/CantExtendFinal)
  * Cant Use Return Value In Write Context (Php/CantUseReturnValueInWriteContext)
  * Cast To Boolean (Structures/CastToBoolean)
  * Cast Usage (Php/CastingUsage)
  * Catch Overwrite Variable (Structures/CatchShadowsVariable)
  * Caught Exceptions (Exceptions/CaughtExceptions)
  * Caught Expressions (Php/TryCatchUsage)
  * Class Const With Array (Php/ClassConstWithArray)
  * Class Has Fluent Interface (Classes/HasFluentInterface)
  * Class Name Case Difference (Classes/WrongCase)
  * Class Usage (Classes/ClassUsage)
  * Class, Interface Or Trait With Identical Names (Classes/CitSameName)
  * Classes Mutually Extending Each Other (Classes/MutualExtension)
  * Classes Names (Classes/Classnames)
  * Clone Usage (Classes/CloningUsage)
  * Close Tags (Php/CloseTags)
  * Closure May Use $this (Php/ClosureThisSupport)
  * Closures Glossary (Functions/Closures)
  * Coalesce (Php/Coalesce)
  * Common Alternatives (Structures/CommonAlternatives)
  * Compare Hash (Security/CompareHash)
  * Compared Comparison (Structures/ComparedComparison)
  * Composer Namespace (Composer/IsComposerNsname)
  * Composer Usage (Composer/UseComposer)
  * Composer's autoload (Composer/Autoload)
  * Concrete Visibility (Interfaces/ConcreteVisibility)
  * Conditional Structures (Structures/ConditionalStructures)
  * Conditioned Constants (Constants/ConditionedConstants)
  * Conditioned Function (Functions/ConditionedFunctions)
  * Confusing Names (Variables/CloseNaming)
  * Const With Array (Php/ConstWithArray)
  * Constant Class (Classes/ConstantClass)
  * Constant Comparison (Structures/ConstantComparisonConsistance)
  * Constant Conditions (Structures/ConstantConditions)
  * Constant Definition (Classes/ConstantDefinition)
  * Constant Scalar Expression (Php/ConstantScalarExpression)
  * Constant Scalar Expressions (Structures/ConstantScalarExpression)
  * Constants (Constants/Constantnames)
  * Constants Created Outside Its Namespace (Constants/CreatedOutsideItsNamespace)
  * Constants Usage (Constants/ConstantUsage)
  * Constants With Strange Names (Constants/ConstantStrangeNames)
  * Constructors (Classes/Constructor)
  * Continents (Type/Continents)
  * Could Be Class Constant (Classes/CouldBeClassConstant)
  * Could Be Static (Structures/CouldBeStatic)
  * Could Use Alias (Namespaces/CouldUseAlias)
  * Could Use Short Assignation (Structures/CouldUseShortAssignation)
  * Could Use __DIR__ (Structures/CouldUseDir)
  * Could Use self (Classes/ShouldUseSelf)
  * Curly Arrays (Arrays/CurlyArrays)
  * Custom Class Usage (Classes/AvoidUsing)
  * Custom Constant Usage (Constants/CustomConstantUsage)
  * Dangling Array References (Structures/DanglingArrayReferences)
  * Deep Definitions (Functions/DeepDefinitions)
  * Define With Array (Php/DefineWithArray)
  * Defined Class Constants (Classes/DefinedConstants)
  * Defined Exceptions (Exceptions/DefinedExceptions)
  * Defined Parent MP (Classes/DefinedParentMP)
  * Defined Properties (Classes/DefinedProperty)
  * Defined static:: Or self:: (Classes/DefinedStaticMP)
  * Definitions Only (Files/DefinitionsOnly)
  * Dependant Trait (Traits/DependantTrait)
  * Deprecated Code (Php/Deprecated)
  * Deprecated Methodcalls in Cake 3.2 (Cakephp/Cake32DeprecatedMethods)
  * Deprecated Methodcalls in Cake 3.3 (Cakephp/Cake33DeprecatedMethods)
  * Deprecated Static calls in Cake 3.3 (Cakephp/Cake33DeprecatedStaticmethodcall)
  * Deprecated Trait in Cake 3.3 (Cakephp/Cake33DeprecatedTraits)
  * Dereferencing String And Arrays (Structures/DereferencingAS)
  * Direct Injection (Security/DirectInjection)
  * Directives Usage (Php/DirectivesUsage)
  * Don't Change Incomings (Structures/NoChangeIncomingVariables)
  * Double Assignation (Structures/DoubleAssignation)
  * Double Instructions (Structures/DoubleInstruction)
  * Duplicate Calls (Structures/DuplicateCalls)
  * Dynamic Calls (Structures/DynamicCalls)
  * Dynamic Class Constant (Classes/DynamicConstantCall)
  * Dynamic Classes (Classes/DynamicClass)
  * Dynamic Code (Structures/DynamicCode)
  * Dynamic Function Call (Functions/Dynamiccall)
  * Dynamic Methodcall (Classes/DynamicMethodCall)
  * Dynamic New (Classes/DynamicNew)
  * Dynamic Property (Classes/DynamicPropertyCall)
  * Dynamically Called Classes (Classes/VariableClasses)
  * Echo Or Print (Structures/EchoPrintConsistance)
  * Echo With Concat (Structures/EchoWithConcat)
  * Else If Versus Elseif (Structures/ElseIfElseif)
  * Else Usage (Structures/ElseUsage)
  * Email Addresses (Type/Email)
  * Empty Blocks (Structures/EmptyBlocks)
  * Empty Classes (Classes/EmptyClass)
  * Empty Function (Functions/EmptyFunction)
  * Empty Instructions (Structures/EmptyLines)
  * Empty Interfaces (Interfaces/EmptyInterface)
  * Empty List (Php/EmptyList)
  * Empty Namespace (Namespaces/EmptyNamespace)
  * Empty Slots In Arrays (Arrays/EmptySlots)
  * Empty Traits (Traits/EmptyTrait)
  * Empty Try Catch (Structures/EmptyTryCatch)
  * Empty With Expression (Structures/EmptyWithExpression)
  * Error Messages (Structures/ErrorMessages)
  * Eval() Usage (Structures/EvalUsage)
  * Exception Order (Exceptions/AlreadyCaught)
  * Exit() Usage (Structures/ExitUsage)
  * Exit-like Methods (Functions/KillsApp)
  * Exponent Usage (Php/ExponentUsage)
  * Ext/geoip (Extensions/Extgeoip)
  * External Config Files (Files/Services)
  * Failed Substr Comparison (Structures/FailingSubstrComparison)
  * File Is Component (Files/IsComponent)
  * File Uploads (Structures/FileUploadUsage)
  * File Usage (Structures/FileUsage)
  * Final Class Usage (Classes/Finalclass)
  * Final Methods Usage (Classes/Finalmethod)
  * Fopen Binary Mode (Portability/FopenMode)
  * For Using Functioncall (Structures/ForWithFunctioncall)
  * Foreach Don't Change Pointer (Php/ForeachDontChangePointer)
  * Foreach Needs Reference Array (Structures/ForeachNeedReferencedSource)
  * Foreach Reference Is Not Modified (Structures/ForeachReferenceIsNotModified)
  * Foreach With list() (Structures/ForeachWithList)
  * Forgotten Visibility (Classes/NonPpp)
  * Forgotten Whitespace (Structures/ForgottenWhiteSpace)
  * Fully Qualified Constants (Namespaces/ConstantFullyQualified)
  * Function Called With Other Case Than Defined (Functions/FunctionCalledWithOtherCase)
  * Function Subscripting (Structures/FunctionSubscripting)
  * Function Subscripting, Old Style (Structures/FunctionPreSubscripting)
  * Functioncall Is Global (Functions/IsGlobal)
  * Functions Glossary (Functions/Functionnames)
  * Functions In Loop Calls (Functions/LoopCalling)
  * Functions Removed In PHP 5.4 (Php/Php54RemovedFunctions)
  * Functions Removed In PHP 5.5 (Php/Php55RemovedFunctions)
  * Functions Using Reference (Functions/FunctionsUsingReference)
  * GPRC Aliases (Security/GPRAliases)
  * Global Code Only (Files/GlobalCodeOnly)
  * Global Import (Namespaces/GlobalImport)
  * Global In Global (Structures/GlobalInGlobal)
  * Global Inside Loop (Structures/GlobalOutsideLoop)
  * Global Usage (Structures/GlobalUsage)
  * Globals (Variables/Globals)
  * Goto Names (Php/Gotonames)
  * HTTP Status Code (Type/HttpStatus)
  * Hardcoded Passwords (Functions/HardcodedPasswords)
  * Has Magic Property (Classes/HasMagicProperty)
  * Has Variable Arguments (Functions/VariableArguments)
  * Hash Algorithms (Php/HashAlgos)
  * Hash Algorithms Incompatible With PHP 5.3 (Php/HashAlgos53)
  * Hash Algorithms Incompatible With PHP 5.4/5 (Php/HashAlgos54)
  * Heredoc Delimiter Glossary (Type/Heredoc)
  * Hexadecimal Glossary (Type/Hexadecimal)
  * Hexadecimal In String (Type/HexadecimalString)
  * Hidden Use Expression (Namespaces/HiddenUse)
  * Htmlentities Calls (Structures/Htmlentitiescall)
  * Http Headers (Type/HttpHeader)
  * Identical Conditions (Structures/IdenticalConditions)
  * If With Same Conditions (Structures/IfWithSameConditions)
  * Iffectations (Structures/Iffectation)
  * Implement Is For Interface (Classes/ImplementIsForInterface)
  * Implicit Global (Structures/ImplicitGlobal)
  * Inclusions (Structures/IncludeUsage)
  * Incompilable Files (Php/Incompilable)
  * Inconsistent Concatenation (Structures/InconsistentConcatenation)
  * Indices Are Int Or String (Structures/IndicesAreIntOrString)
  * Indirect Injection (Security/IndirectInjection)
  * Instantiating Abstract Class (Classes/InstantiatingAbstractClass)
  * Integer Glossary (Type/Integer)
  * Interface Arguments (Variables/InterfaceArguments)
  * Interface Methods (Interfaces/InterfaceMethod)
  * Interfaces Glossary (Interfaces/Interfacenames)
  * Interfaces Usage (Interfaces/InterfaceUsage)
  * Internally Used Properties (Classes/PropertyUsedInternally)
  * Internet Ports (Type/Ports)
  * Interpolation (Type/StringInterpolation)
  * Invalid Constant Name (Constants/InvalidName)
  * Is An Extension Class (Classes/IsExtClass)
  * Is An Extension Constant (Constants/IsExtConstant)
  * Is An Extension Function (Functions/IsExtFunction)
  * Is An Extension Interface (Interfaces/IsExtInterface)
  * Is CLI Script (Files/IsCliScript)
  * Is Composer Class (Composer/IsComposerClass)
  * Is Composer Interface (Composer/IsComposerInterface)
  * Is Extension Trait (Traits/IsExtTrait)
  * Is Generator (Functions/IsGenerator)
  * Is Global Constant (Constants/IsGlobalConstant)
  * Is Interface Method (Classes/IsInterfaceMethod)
  * Is Library (Project/IsLibrary)
  * Is Not Class Family (Classes/IsNotFamily)
  * Is PHP Constant (Constants/IsPhpConstant)
  * Is Upper Family (Classes/IsUpperFamily)
  * Isset With Constant (Structures/IssetWithConstant)
  * Join file() (Performances/JoinFile)
  * Labels (Php/Labelnames)
  * Linux Only Files (Portability/LinuxOnlyFiles)
  * List Short Syntax (Php/ListShortSyntax)
  * List With Appends (Php/ListWithAppends)
  * List With Keys (Php/ListWithKeys)
  * Locally Unused Property (Classes/LocallyUnusedProperty)
  * Locally Used Property (Classes/LocallyUsedProperty)
  * Logical Mistakes (Structures/LogicalMistakes)
  * Logical Should Use Symbolic Operators (Php/LogicalInLetters)
  * Lone Blocks (Structures/LoneBlock)
  * Lost References (Variables/LostReferences)
  * Magic Constant Usage (Constants/MagicConstantUsage)
  * Magic Methods (Classes/MagicMethod)
  * Magic Visibility (Classes/toStringPss)
  * Mail Usage (Structures/MailUsage)
  * Make Global A Property (Classes/MakeGlobalAProperty)
  * Make One Call (Performances/MakeOneCall)
  * Malformed Octal (Type/MalformedOctal)
  * Mark Callable (Functions/MarkCallable)
  * Md5 Strings (Type/Md5String)
  * Method Has Fluent Interface (Functions/HasFluentInterface)
  * Method Has No Fluent Interface (Functions/HasNotFluentInterface)
  * Methodcall On New (Php/MethodCallOnNew)
  * Methods Names (Classes/MethodDefinition)
  * Methods Without Return (Functions/WithoutReturn)
  * Mime Types (Type/MimeType)
  * Mixed Keys Arrays (Arrays/MixedKeys)
  * Multidimensional Arrays (Arrays/Multidimensional)
  * Multiple Alias Definitions (Namespaces/MultipleAliasDefinitions)
  * Multiple Catch (Structures/MultipleCatch)
  * Multiple Class Declarations (Classes/MultipleDeclarations)
  * Multiple Classes In One File (Classes/MultipleClassesInFile)
  * Multiple Constant Definition (Constants/MultipleConstantDefinition)
  * Multiple Definition Of The Same Argument (Functions/MultipleSameArguments)
  * Multiple Exceptions Catch() (Exceptions/MultipleCatch)
  * Multiple Identical Trait Or Interface (Classes/MultipleTraitOrInterface)
  * Multiple Index Definition (Arrays/MultipleIdenticalKeys)
  * Multiple Return (Functions/MultipleReturn)
  * Multiples Identical Case (Structures/MultipleDefinedCase)
  * Multiply By One (Structures/MultiplyByOne)
  * Must Return Methods (Functions/MustReturn)
  * Namespaces (Namespaces/NamespaceUsage)
  * Namespaces Glossary (Namespaces/Namespacesnames)
  * Negative Power (Structures/NegativePow)
  * Nested Ifthen (Structures/NestedIfthen)
  * Nested Loops (Structures/NestedLoops)
  * Nested Ternary (Structures/NestedTernary)
  * Never Used Properties (Classes/PropertyNeverUsed)
  * New Functions In PHP 5.4 (Php/Php54NewFunctions)
  * New Functions In PHP 5.5 (Php/Php55NewFunctions)
  * New Functions In PHP 5.6 (Php/Php56NewFunctions)
  * New Functions In PHP 7.0 (Php/Php70NewFunctions)
  * New Functions In PHP 7.1 (Php/Php71NewFunctions)
  * No Choice (Structures/NoChoice)
  * No Count With 0 (Performances/NotCountNull)
  * No Direct Access (Structures/NoDirectAccess)
  * No Direct Call To Magic Method (Classes/DirectCallToMagicMethod)
  * No Direct Usage (Structures/NoDirectUsage)
  * No Global Modification (Wordpress/NoGlobalModification)
  * No Hardcoded Hash (Structures/NoHardcodedHash)
  * No Hardcoded Ip (Structures/NoHardcodedIp)
  * No Hardcoded Path (Structures/NoHardcodedPath)
  * No Hardcoded Port (Structures/NoHardcodedPort)
  * No Implied If (Structures/ImpliedIf)
  * No List With String (Php/NoListWithString)
  * No Parenthesis For Language Construct (Structures/NoParenthesisForLanguageConstruct)
  * No Plus One (Structures/PlusEgalOne)
  * No Public Access (Classes/NoPublicAccess)
  * No Real Comparison (Type/NoRealComparison)
  * No Self Referencing Constant (Classes/NoSelfReferencingConstant)
  * No String With Append (Php/NoStringWithAppend)
  * No Substr() One (Structures/NoSubstrOne)
  * No array_merge() In Loops (Performances/ArrayMergeInLoops)
  * Non Ascii Variables (Variables/VariableNonascii)
  * Non Static Methods Called In A Static (Classes/NonStaticMethodsCalledStatic)
  * Non-constant Index In Array (Arrays/NonConstantArray)
  * Non-lowercase Keywords (Php/UpperCaseKeyword)
  * Nonce Creation (Wordpress/NonceCreation)
  * Normal Methods (Classes/NormalMethods)
  * Normal Property (Classes/NormalProperty)
  * Not Definitions Only (Files/NotDefinitionsOnly)
  * Not Not (Structures/NotNot)
  * Not Same Name As File (Classes/NotSameNameAsFile)
  * Not Same Name As File (Classes/SameNameAsFile)
  * Nowdoc Delimiter Glossary (Type/Nowdoc)
  * Null Coalesce (Php/NullCoalesce)
  * Null On New (Classes/NullOnNew)
  * Objects Don't Need References (Structures/ObjectReferences)
  * Octal Glossary (Type/Octal)
  * Old Style Constructor (Classes/OldStyleConstructor)
  * Old Style __autoload() (Php/oldAutoloadUsage)
  * One Letter Functions (Functions/OneLetterFunctions)
  * One Object Operator Per Line (Classes/OneObjectOperatorPerLine)
  * One Variable String (Type/OneVariableStrings)
  * Only Static Methods (Classes/OnlyStaticMethods)
  * Only Variable Returned By Reference (Structures/OnlyVariableReturnedByReference)
  * Or Die (Structures/OrDie)
  * Overwriting Variable (Variables/Overwriting)
  * Overwritten Class Const (Classes/OverwrittenConst)
  * Overwritten Exceptions (Exceptions/OverwriteException)
  * Overwritten Literals (Variables/OverwrittenLiterals)
  * PHP 7.0 New Classes (Php/Php70NewClasses)
  * PHP 7.0 New Interfaces (Php/Php70NewInterfaces)
  * PHP 7.0 Removed Directives (Php/Php70RemovedDirective)
  * PHP 7.1 Removed Directives (Php/Php71RemovedDirective)
  * PHP 70 Removed Functions (Php/Php70RemovedFunctions)
  * PHP Arrays Index (Arrays/Phparrayindex)
  * PHP Bugfixes (Php/MiddleVersion)
  * PHP Constant Usage (Constants/PhpConstantUsage)
  * PHP Handlers Usage (Php/SetHandlers)
  * PHP Interfaces (Interfaces/Php)
  * PHP Keywords As Names (Php/ReservedNames)
  * PHP Sapi (Type/Sapi)
  * PHP Variables (Variables/VariablePhp)
  * PHP5 Indirect Variable Expression (Variables/Php5IndirectExpression)
  * PHP7 Dirname (Structures/PHP7Dirname)
  * Parent, Static Or Self Outside Class (Classes/PssWithoutClass)
  * Parenthesis As Parameter (Php/ParenthesisAsParameter)
  * Pear Usage (Php/PearUsage)
  * Perl Regex (Type/Pcre)
  * Php 7 Indirect Expression (Variables/Php7IndirectExpression)
  * Php 71 New Classes (Php/Php71NewClasses)
  * Php7 Relaxed Keyword (Php/Php7RelaxedKeyword)
  * Phpinfo (Structures/PhpinfoUsage)
  * Pre-increment (Performances/PrePostIncrement)
  * Preprocess Arrays (Arrays/ShouldPreprocess)
  * Preprocessable (Structures/ShouldPreprocess)
  * Print And Die (Structures/PrintAndDie)
  * Property Could Be Private (Classes/CouldBePrivate)
  * Property Is Modified (Classes/IsModified)
  * Property Is Read (Classes/IsRead)
  * Property Names (Classes/PropertyDefinition)
  * Property Used Above (Classes/PropertyUsedAbove)
  * Property Used Below (Classes/PropertyUsedBelow)
  * Property/Variable Confusion (Structures/PropertyVariableConfusion)
  * Queries In Loops (Structures/QueriesInLoop)
  * Random Without Try (Structures/RandomWithoutTry)
  * Real Functions (Functions/RealFunctions)
  * Real Glossary (Type/Real)
  * Real Variables (Variables/RealVariables)
  * Recursive Functions (Functions/Recursive)
  * Redeclared PHP Functions (Functions/RedeclaredPhpFunction)
  * Redefined Class Constants (Classes/RedefinedConstants)
  * Redefined Default (Classes/RedefinedDefault)
  * Redefined Methods (Classes/RedefinedMethods)
  * Redefined PHP Traits (Traits/Php)
  * Redefined Property (Classes/RedefinedProperty)
  * References (Variables/References)
  * Register Globals (Security/RegisterGlobals)
  * Relay Function (Functions/RelayFunction)
  * Repeated print() (Structures/RepeatedPrint)
  * Reserved Keywords In PHP 7 (Php/ReservedKeywords7)
  * Resources Usage (Structures/ResourcesUsage)
  * Results May Be Missing (Structures/ResultMayBeMissing)
  * Return ;  (Structures/ReturnVoid)
  * Return True False (Structures/ReturnTrueFalse)
  * Return Typehint Usage (Php/ReturnTypehintUsage)
  * Return With Parenthesis (Php/ReturnWithParenthesis)
  * Safe CurlOptions (Security/CurlOptions)
  * Same Conditions (Structures/SameConditions)
  * Scalar Typehint Usage (Php/ScalarTypehintUsage)
  * Sensitive Argument (Security/SensitiveArgument)
  * Sequences In For (Structures/SequenceInFor)
  * Setlocale() Uses Constants (Structures/SetlocaleNeedsConstants)
  * Several Instructions On The Same Line (Structures/OneLineTwoInstructions)
  * Shell Usage (Structures/ShellUsage)
  * Short Open Tags (Php/ShortOpenTagRequired)
  * Short Syntax For Arrays (Arrays/ArrayNSUsage)
  * Should Be Single Quote (Type/ShouldBeSingleQuote)
  * Should Chain Exception (Structures/ShouldChainException)
  * Should Make Alias (Namespaces/ShouldMakeAlias)
  * Should Typecast (Type/ShouldTypecast)
  * Should Use Coalesce (Php/ShouldUseCoalesce)
  * Should Use Constants (Functions/ShouldUseConstants)
  * Should Use Local Class (Classes/ShouldUseThis)
  * Should Use Prepared Statement (Security/ShouldUsePreparedStatement)
  * Silently Cast Integer (Type/SilentlyCastInteger)
  * Simple Global Variable (Php/GlobalWithoutSimpleVariable)
  * Simplify Regex (Structures/SimplePreg)
  * Slow Functions (Performances/SlowFunctions)
  * Special Integers (Type/SpecialIntegers)
  * Static Loop (Structures/StaticLoop)
  * Static Methods (Classes/StaticMethods)
  * Static Methods Called From Object (Classes/StaticMethodsCalledFromObject)
  * Static Methods Can't Contain $this (Classes/StaticContainsThis)
  * Static Names (Classes/StaticCpm)
  * Static Properties (Classes/StaticProperties)
  * Static Variables (Variables/StaticVariables)
  * Strict Comparison With Booleans (Structures/BooleanStrictComparison)
  * String May Hold A Variable (Type/StringHoldAVariable)
  * String glossary (Type/String)
  * Strpos Comparison (Structures/StrposCompare)
  * Super Global Usage (Php/SuperGlobalUsage)
  * Super Globals Contagion (Security/SuperGlobalContagion)
  * Switch To Switch (Structures/SwitchToSwitch)
  * Switch With Too Many Default (Structures/SwitchWithMultipleDefault)
  * Switch Without Default (Structures/SwitchWithoutDefault)
  * Ternary In Concat (Structures/TernaryInConcat)
  * Test Class (Classes/TestClass)
  * Throw (Php/ThrowUsage)
  * Throw Functioncall (Exceptions/ThrowFunctioncall)
  * Throw In Destruct (Classes/ThrowInDestruct)
  * Thrown Exceptions (Exceptions/ThrownExceptions)
  * Throws An Assignement (Structures/ThrowsAndAssign)
  * Timestamp Difference (Structures/TimestampDifference)
  * Too Many Children (Classes/TooManyChildren)
  * Trait Methods (Traits/TraitMethod)
  * Trait Names (Traits/Traitnames)
  * Traits (Traits/TraitUsage)
  * Trigger Errors (Php/TriggerErrorUsage)
  * True False Inconsistant Case (Constants/InconsistantCase)
  * Try With Finally (Structures/TryFinally)
  * Typehints (Functions/Typehints)
  * URL list (Type/Url)
  * Uncaught Exceptions (Exceptions/UncaughtExceptions)
  * Unchecked Resources (Structures/UncheckedResources)
  * Undefined Caught Exceptions (Exceptions/CaughtButNotThrown)
  * Undefined Class Constants (Classes/UndefinedConstants)
  * Undefined Classes (Classes/UndefinedClasses)
  * Undefined Classes (ZendF/UndefinedClasses)
  * Undefined Constants (Constants/UndefinedConstants)
  * Undefined Functions (Functions/UndefinedFunctions)
  * Undefined Interfaces (Interfaces/UndefinedInterfaces)
  * Undefined Parent (Classes/UndefinedParentMP)
  * Undefined Properties (Classes/UndefinedProperty)
  * Undefined Trait (Traits/UndefinedTrait)
  * Undefined Zend 1.10 (ZendF/UndefinedClass110)
  * Undefined Zend 1.11 (ZendF/UndefinedClass111)
  * Undefined Zend 1.12 (ZendF/UndefinedClass112)
  * Undefined Zend 1.8 (ZendF/UndefinedClass18)
  * Undefined Zend 1.9 (ZendF/UndefinedClass19)
  * Undefined static:: Or self:: (Classes/UndefinedStaticMP)
  * Unescaped Variables In Templates (Wordpress/UnescapedVariables)
  * Unicode Blocks (Type/UnicodeBlock)
  * Unicode Escape Partial (Php/UnicodeEscapePartial)
  * Unicode Escape Syntax (Php/UnicodeEscapeSyntax)
  * Unknown Directive Name (Php/DirectiveName)
  * Unkown Regex Options (Structures/UnknownPregOption)
  * Unpreprocessed Values (Structures/Unpreprocessed)
  * Unreachable Code (Structures/UnreachableCode)
  * Unresolved Catch (Classes/UnresolvedCatch)
  * Unresolved Classes (Classes/UnresolvedClasses)
  * Unresolved Instanceof (Classes/UnresolvedInstanceof)
  * Unresolved Use (Namespaces/UnresolvedUse)
  * Unserialize Second Arg (Security/UnserializeSecondArg)
  * Unset Arguments (Functions/UnsetOnArguments)
  * Unset In Foreach (Structures/UnsetInForeach)
  * Unthrown Exception (Exceptions/Unthrown)
  * Unused Arguments (Functions/UnusedArguments)
  * Unused Classes (Classes/UnusedClass)
  * Unused Constants (Constants/UnusedConstants)
  * Unused Functions (Functions/UnusedFunctions)
  * Unused Global (Structures/UnusedGlobal)
  * Unused Interfaces (Interfaces/UnusedInterfaces)
  * Unused Label (Structures/UnusedLabel)
  * Unused Methods (Classes/UnusedMethods)
  * Unused Protected Methods (Classes/UnusedProtectedMethods)
  * Unused Static Methods (Classes/UnusedPrivateMethod)
  * Unused Static Properties (Classes/UnusedPrivateProperty)
  * Unused Traits (Traits/UnusedTrait)
  * Unused Use (Namespaces/UnusedUse)
  * Unusual Case For PHP Functions (Php/UpperCaseFunction)
  * Unverified Nonce (Wordpress/UnverifiedNonce)
  * Usage Of class_alias() (Classes/ClassAliasUsage)
  * Use $wpdb Api (Wordpress/UseWpdbApi)
  * Use === null (Php/IsnullVsEqualNull)
  * Use Cli (Php/UseCli)
  * Use Const And Functions (Namespaces/UseFunctionsConstants)
  * Use Constant (Structures/UseConstant)
  * Use Constant As Arguments (Functions/UseConstantAsArguments)
  * Use Instanceof (Classes/UseInstanceof)
  * Use Lower Case For Parent, Static And Self (Php/CaseForPSS)
  * Use Nullable Type (Php/UseNullableType)
  * Use Object Api (Php/UseObjectApi)
  * Use Pathinfo (Php/UsePathinfo)
  * Use System Tmp (Structures/UseSystemTmp)
  * Use This (Classes/UseThis)
  * Use Web (Php/UseWeb)
  * Use With Fully Qualified Name (Namespaces/UseWithFullyQualifiedNS)
  * Use const (Constants/ConstRecommended)
  * Use password_hash() (Php/Password55)
  * Use random_int() (Php/BetterRand)
  * Used Classes (Classes/UsedClass)
  * Used Functions (Functions/UsedFunctions)
  * Used Interfaces (Interfaces/UsedInterfaces)
  * Used Methods (Classes/UsedMethods)
  * Used Once Variables (In Scope) (Variables/VariableUsedOnceByContext)
  * Used Once Variables (Variables/VariableUsedOnce)
  * Used Protected Method (Classes/UsedProtectedMethod)
  * Used Static Methods (Classes/UsedPrivateMethod)
  * Used Static Properties (Classes/UsedPrivateProperty)
  * Used Trait (Traits/UsedTrait)
  * Used Use (Namespaces/UsedUse)
  * Useless Abstract Class (Classes/UselessAbstract)
  * Useless Brackets (Structures/UselessBrackets)
  * Useless Constructor (Classes/UselessConstructor)
  * Useless Final (Classes/UselessFinal)
  * Useless Global (Structures/UselessGlobal)
  * Useless Instructions (Structures/UselessInstruction)
  * Useless Interfaces (Interfaces/UselessInterfaces)
  * Useless Parenthesis (Structures/UselessParenthesis)
  * Useless Return (Functions/UselessReturn)
  * Useless Switch (Structures/UselessSwitch)
  * Useless Unset (Structures/UselessUnset)
  * Uses Default Values (Functions/UsesDefaultArguments)
  * Uses Environnement (Php/UsesEnv)
  * Using $this Outside A Class (Classes/UsingThisOutsideAClass)
  * Using Short Tags (Structures/ShortTags)
  * Usort Sorting In PHP 7.0 (Php/UsortSorting)
  * Var (Classes/OldStyleVar)
  * Variable Constants (Constants/VariableConstant)
  * Variable Is Modified (Variables/IsModified)
  * Variable Is Read (Variables/IsRead)
  * Variables Names (Variables/Variablenames)
  * Variables Variables (Variables/VariableVariables)
  * Variables With Long Names (Variables/VariableLong)
  * Variables With One Letter Names (Variables/VariableOneLetter)
  * While(List() = Each()) (Structures/WhileListEach)
  * Wpdb Best Usage (Wordpress/WpdbBestUsage)
  * Written Only Variables (Variables/WrittenOnlyVariable)
  * Wrong Class Location (ZendF/NotInThatPath)
  * Wrong Number Of Arguments (Functions/WrongNumberOfArguments)
  * Wrong Number Of Arguments In Methods (Functions/WrongNumberOfArgumentsMethods)
  * Wrong Optional Parameter (Functions/WrongOptionalParameter)
  * Wrong Parameter Type (Php/InternalParameterType)
  * Yield From Usage (Php/YieldFromUsage)
  * Yield Usage (Php/YieldUsage)
  * Yoda Comparison (Structures/YodaComparison)
  * Zend Classes (ZendF/ZendClasses)
  * __debugInfo() usage (Php/debugInfoUsage)
  * __halt_compiler (Php/Haltcompiler)
  * __toString() Throws Exception (Structures/toStringThrowsException)
  * charger_fonction() (Spip/chargerFonction)
  * crypt() Without Salt (Structures/CryptWithoutSalt)
  * error_reporting() With Integers (Structures/ErrorReportingWithInteger)
  * eval() Without Try (Structures/EvalWithoutTry)
  * ext/0mq (Extensions/Extzmq)
  * ext/amqp (Extensions/Extamqp)
  * ext/apache (Extensions/Extapache)
  * ext/apc (Extensions/Extapc)
  * ext/apcu (Extensions/Extapcu)
  * ext/array (Extensions/Extarray)
  * ext/bcmath (Extensions/Extbcmath)
  * ext/bzip2 (Extensions/Extbzip2)
  * ext/cairo (Extensions/Extcairo)
  * ext/calendar (Extensions/Extcalendar)
  * ext/com (Extensions/Extcom)
  * ext/crypto (Extensions/Extcrypto)
  * ext/ctype (Extensions/Extctype)
  * ext/curl (Extensions/Extcurl)
  * ext/cyrus (Extensions/Extcyrus)
  * ext/date (Extensions/Extdate)
  * ext/dba (Extensions/Extdba)
  * ext/dio (Extensions/Extdio)
  * ext/dom (Extensions/Extdom)
  * ext/eaccelerator (Extensions/Exteaccelerator)
  * ext/enchant (Extensions/Extenchant)
  * ext/ereg (Extensions/Extereg)
  * ext/ev (Extensions/Extev)
  * ext/event (Extensions/Extevent)
  * ext/exif (Extensions/Extexif)
  * ext/expect (Extensions/Extexpect)
  * ext/fann (Extensions/Extfann)
  * ext/fdf (Extensions/Extfdf)
  * ext/ffmpeg (Extensions/Extffmpeg)
  * ext/file (Extensions/Extfile)
  * ext/fileinfo (Extensions/Extfileinfo)
  * ext/filter (Extensions/Extfilter)
  * ext/fpm (Extensions/Extfpm)
  * ext/ftp (Extensions/Extftp)
  * ext/gd (Extensions/Extgd)
  * ext/gearman (Extensions/Extgearman)
  * ext/gettext (Extensions/Extgettext)
  * ext/gmagick (Extensions/Extgmagick)
  * ext/gmp (Extensions/Extgmp)
  * ext/gnupgp (Extensions/Extgnupg)
  * ext/hash (Extensions/Exthash)
  * ext/ibase (Extensions/Extibase)
  * ext/iconv (Extensions/Exticonv)
  * ext/iis (Extensions/Extiis)
  * ext/imagick (Extensions/Extimagick)
  * ext/imap (Extensions/Extimap)
  * ext/info (Extensions/Extinfo)
  * ext/inotify (Extensions/Extinotify)
  * ext/intl (Extensions/Extintl)
  * ext/json (Extensions/Extjson)
  * ext/kdm5 (Extensions/Extkdm5)
  * ext/ldap (Extensions/Extldap)
  * ext/libevent (Extensions/Extlibevent)
  * ext/libxml (Extensions/Extlibxml)
  * ext/lua (Extensions/Extlua)
  * ext/mail (Extensions/Extmail)
  * ext/mailparse (Extensions/Extmailparse)
  * ext/math (Extensions/Extmath)
  * ext/mbstring (Extensions/Extmbstring)
  * ext/mcrypt (Extensions/Extmcrypt)
  * ext/memcache (Extensions/Extmemcache)
  * ext/memcached (Extensions/Extmemcached)
  * ext/ming (Extensions/Extming)
  * ext/mongo (Extensions/Extmongo)
  * ext/mssql (Extensions/Extmssql)
  * ext/mysql (Extensions/Extmysql)
  * ext/mysqli (Extensions/Extmysqli)
  * ext/ob (Extensions/Extob)
  * ext/oci8 (Extensions/Extoci8)
  * ext/odbc (Extensions/Extodbc)
  * ext/opcache (Extensions/Extopcache)
  * ext/openssl (Extensions/Extopenssl)
  * ext/parsekit (Extensions/Extparsekit)
  * ext/pcntl (Extensions/Extpcntl)
  * ext/pcre (Extensions/Extpcre)
  * ext/pdo (Extensions/Extpdo)
  * ext/pecl_http (Extensions/Exthttp)
  * ext/pgsql (Extensions/Extpgsql)
  * ext/phalcon (Extensions/Extphalcon)
  * ext/phar (Extensions/Extphar)
  * ext/php-ast (Extensions/Extast)
  * ext/posix (Extensions/Extposix)
  * ext/proctitle (Extensions/Extproctitle)
  * ext/pspell (Extensions/Extpspell)
  * ext/readline (Extensions/Extreadline)
  * ext/recode (Extensions/Extrecode)
  * ext/redis (Extensions/Extredis)
  * ext/reflexion (Extensions/Extreflection)
  * ext/runkit (Extensions/Extrunkit)
  * ext/sem (Extensions/Extsem)
  * ext/shmop (Extensions/Extshmop)
  * ext/simplexml (Extensions/Extsimplexml)
  * ext/snmp (Extensions/Extsnmp)
  * ext/soap (Extensions/Extsoap)
  * ext/sockets (Extensions/Extsession)
  * ext/sockets (Extensions/Extsockets)
  * ext/spl (Extensions/Extspl)
  * ext/sqlite (Extensions/Extsqlite)
  * ext/sqlite3 (Extensions/Extsqlite3)
  * ext/sqlsrv (Extensions/Extsqlsrv)
  * ext/ssh2 (Extensions/Extssh2)
  * ext/standard (Extensions/Extstandard)
  * ext/suhosin (Extensions/Extsuhosin)
  * ext/tidy (Extensions/Exttidy)
  * ext/tokenizer (Extensions/Exttokenizer)
  * ext/tokyotyrant (Extensions/Exttokyotyrant)
  * ext/trader (Extensions/Exttrader)
  * ext/v8js (Extensions/Extv8js)
  * ext/wddx (Extensions/Extwddx)
  * ext/wikidiff2 (Extensions/Extwikidiff2)
  * ext/wincache (Extensions/Extwincache)
  * ext/xcache (Extensions/Extxcache)
  * ext/xdebug (Extensions/Extxdebug)
  * ext/xdiff (Extensions/Extxdiff)
  * ext/xhprof (Extensions/Extxhprof)
  * ext/xml (Extensions/Extxml)
  * ext/xmlreader (Extensions/Extxmlreader)
  * ext/xmlrpc (Extensions/Extxmlrpc)
  * ext/xmlwriter (Extensions/Extxmlwriter)
  * ext/xsl (Extensions/Extxsl)
  * ext/yaml (Extensions/Extyaml)
  * ext/yis (Extensions/Extyis)
  * ext/zip (Extensions/Extzip)
  * ext/zlib (Extensions/Extzlib)
  * fopen() Mode (Php/FopenMode)
  * func_get_arg() Modified (Functions/funcGetArgModified)
  * include_once() Usage (Structures/OnceUsage)
  * list() May Omit Variables (Structures/ListOmissions)
  * mcrypt_create_iv() With Default Values (Structures/McryptcreateivWithoutOption)
  * parse_str() Warning (Security/parseUrlWithoutParameters)
  * preg_match_all() Flag (Php/PregMatchAllFlag)
  * preg_replace With Option e (Structures/pregOptionE)
  * set_exception_handler() Warning (Php/SetExceptionHandlerPHP7)
  * var_dump()... Usage (Structures/VardumpUsage)

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

