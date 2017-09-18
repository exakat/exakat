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
* CompatibilityPHP73
* Custom
* Dead code
* DefensiveProgrammingTM
* Dismell
* Internal
* Inventory
* Level 1
* Newfeatures
* OneFile
* PHP recommendations
* Performances
* Portability
* Preferences
* RadwellCodes
* Security
* Simple
* Slim
* Suggestions
* Unassigned
* Wordpress
* ZendFramework

Supported Reports
-----------------

Exakat produces various reports. Some are general, covering various aspects in a reference way; others focus on one aspect. 

  * Ambassador
  * AmbassadorNoMenu
  * Devoops
  * Text
  * Xml
  * Uml
  * PlantUml
  * None
  * PhpConfiguration
  * PhpCompilation
  * Inventories
  * Clustergrammer
  * FileDependencies
  * FileDependenciesHtml
  * ZendFramework
  * RadwellCode
  * CodeSniffer
  * Slim
  * FacetedJson
  * Json
  * OnepageJson
  * Marmelab
  * Simpletable
  * Codeflower
  * Dependencywheel
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
* ext/ds
* ext/eaccelerator
* ext/enchant
* ext/ereg
* ext/ev
* ext/event
* ext/exif
* ext/expect
* ext/fam
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
* ext/gender
* Ext/geoip
* ext/gettext
* ext/gmagick
* ext/gmp
* ext/gnupgp
* ext/grpc
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
* ext/judy
* ext/kdm5
* ext/lapack
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
* ext/parle
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
* ext/rdkafka
* ext/readline
* ext/recode
* ext/redis
* ext/reflection
* ext/runkit
* ext/sem
* ext/session
* ext/shmop
* ext/simplexml
* ext/snmp
* ext/soap
* ext/sockets
* ext/sphinx
* ext/spl
* ext/sqlite
* ext/sqlite3
* ext/sqlsrv
* ext/ssh2
* ext/standard
* ext/stats
* String
* ext/suhosin
* ext/swoole
* ext/tidy
* ext/tokenizer
* ext/tokyotyrant
* ext/trader
* ext/v8js
* ext/wddx
* ext/wikidiff2
* ext/wincache
* ext/xattr
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


* 0.12.12

  * Use pathinfo() Arguments (Php/UsePathinfoArgs ; Performances)
  * ext/parle (Extensions/Extparle)

* 0.12.11

  * Could Be Protected Class Constant (Classes/CouldBeProtectedConstant ; Analyze)
  * Could Be Protected Method (Classes/CouldBeProtectedMethod ; Analyze)
  * Method Used Below (Classes/MethodUsedBelow ; Analyze)
  * Pathinfo() Returns May Vary (Php/PathinfoReturns ; Analyze)
  * Property Could Be Private Method (Classes/CouldBePrivateMethod)

* 0.12.10

  * Constant Used Below (Classes/ConstantUsedBelow)
  * Could Be Private Class Constant (Classes/CouldBePrivateConstante ; Analyze)

* 0.12.9

  * Shell Favorite (Php/ShellFavorite)

* 0.12.8

  * ext/fam (Extensions/Extfam)
  * ext/rdkafka (Extensions/Extrdkafka ; Appinfo)

* 0.12.7

  * Should Use Foreach (Structures/ShouldUseForeach)

* 0.12.5

  * Logical To in_array (Performances/LogicalToInArray)
  * No Substr Minus One (Php/NoSubstrMinusOne ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP70)

* 0.12.4

  * Assign With And (Php/AssignAnd ; Analyze)
  * Avoid Concat In Loop (Performances/NoConcatInLoop ; Performances)
  * Child Class Remove Typehint (Classes/ChildRemoveTypehint)
  * Isset Multiple Arguments (Php/IssetMultipleArgs ; Suggestions)
  * Logical Operators Favorite (Php/LetterCharsLogicalFavorite ; Preferences)
  * No Magic With Array (Classes/NoMagicWithArray ; Analyze)
  * Optional Parameter (Functions/OptionalParameter ; DefensiveProgrammingTM)
  * PHP 7.2 Object Keyword (Php/Php72ObjectKeyword ; CompatibilityPHP72, CompatibilityPHP73)
  * PHP 72 Removed Classes (Php/Php72RemovedClasses ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP70, CompatibilityPHP71)
  * PHP 72 Removed Interfaces (Php/Php72RemovedInterfaces ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP70, CompatibilityPHP71)
  * ext/xattr (Extensions/Extxattr ; Appinfo)

* 0.12.3

  * Group Use Trailing Comma (Php/GroupUseTrailingComma ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP70, CompatibilityPHP71)
  * Mismatched Default Arguments (Functions/MismatchedDefaultArguments ; Analyze)
  * Mismatched Typehint (Functions/MismatchedTypehint ; Analyze)
  * Scalar Or Object Property (Classes/ScalarOrObjectProperty)

* 0.12.2

  * Mkdir Default (Security/MkdirDefault ; Security)
  * ext/lapack (Extensions/Extlapack)
  * strict_types Preference (Php/DeclareStrict ; Appinfo, Preferences)

* 0.12.1

  * Const Or Define (Structures/ConstDefineFavorite ; Appinfo)
  * Declare strict_types Usage (Php/DeclareStrictType ; Appinfo, Preferences)
  * Encoding Usage (Php/DeclareEncoding)
  * Mismatched Ternary Alternatives (Structures/MismatchedTernary ; Analyze)
  * No Return Or Throw In Finally (Structures/NoReturnInFinally ; Security)
  * Ticks Usage (Php/DeclareTicks ; Preferences)

* 0.12.0

  * Avoid Optional Properties (Classes/AvoidOptionalProperties)
  * Heredoc Delimiter (Structures/HeredocDelimiterFavorite ; Coding Conventions)
  * Multiple Functions Declarations (Functions/MultipleDeclarations ; Appinfo)
  * Non Breakable Space In Names (Structures/NonBreakableSpaceInNames ; Appinfo, Appcontent)
  * ext/swoole (Extensions/Extswoole ; Appinfo)

* 0.11.8

  * Cant Inherit Abstract Method (Classes/CantInheritAbstractMethod)
  * Codeigniter usage (Vendors/Codeigniter ; Appinfo)
  * Ez cms usage (Vendors/Ez ; Appinfo)
  * Joomla usage (Vendors/Joomla ; Appinfo, Appcontent)
  * Laravel usage (Vendors/Laravel ; Appinfo, Appcontent)
  * Symfony usage (Vendors/Symfony ; Appinfo)
  * Use session_start() Options (Php/UseSessionStartOptions ; Suggestions)
  * Wordpress usage (Vendors/Wordpress ; Appinfo)
  * Yii usage (Vendors/Yii ; Appinfo, Appcontent)

* 0.11.7

  * Forgotten Interface (Interfaces/CouldUseInterface ; Analyze)
  * Order Of Declaration (Classes/OrderOfDeclaration)

* 0.11.6

  * Concatenation Interpolation Consistence (Structures/ConcatenationInterpolationFavorite ; Preferences)
  * Could Make A Function (Functions/CouldCentralize ; Analyze, Suggestions)
  * Courrier Anti-Pattern (Patterns/CourrierAntiPattern ; Appinfo, Appcontent, Dismell)
  * DI Cyclic Dependencies (Classes/TypehintCyclicDependencies ; Dismell)
  * Dependency Injection (Patterns/DependencyInjection ; Appinfo)
  * PSR-13 Usage (Psr/Psr13Usage ; Appinfo)
  * PSR-16 Usage (Psr/Psr16Usage ; Appinfo)
  * PSR-3 Usage (Psr/Psr3Usage ; Appinfo)
  * PSR-6 Usage (Psr/Psr6Usage ; Appinfo)
  * PSR-7 Usage (Psr/Psr7Usage ; Appinfo)
  * Too Many Injections (Classes/TooManyInjections)
  * ext/gender (Extensions/Extgender ; Appinfo)
  * ext/judy (Extensions/Extjudy ; Appinfo)

* 0.11.5

  * Could Typehint (Functions/CouldTypehint ; Analyze)
  * Implemented Methods Are Public (Classes/ImplementedMethodsArePublic)
  * Mixed Concat And Interpolation (Structures/MixedConcatInterpolation ; Analyze, Coding Conventions)
  * No Reference On Left Side (Structures/NoReferenceOnLeft ; Analyze)
  * PSR-11 Usage (Psr/Psr11Usage ; Appinfo)
  * ext/stats (Extensions/Extstats ; Appinfo)

* 0.11.4

  * No Class As Typehint (Functions/NoClassAsTypehint)
  * Use Browscap (Php/UseBrowscap ; Appinfo)
  * Use Debug (Structures/UseDebug ; Appinfo)

* 0.11.3

  * No Return Used (Functions/NoReturnUsed ; Analyze)
  * Only Variable Passed By Reference (Functions/OnlyVariablePassedByReference ; Analyze)
  * Try With Multiple Catch (Php/TryMultipleCatch ; Appinfo)
  * ext/grpc (Extensions/Extgrpc)
  * ext/sphinx (Extensions/Extsphinx ; Appinfo)

* 0.11.2

  * Alternative Syntax Consistence (Structures/AlternativeConsistenceByFile ; Analyze)
  * Randomly Sorted Arrays (Arrays/RandomlySortedLiterals)

* 0.11.1

  * Difference Consistence (Structures/DifferencePreference)
  * No Empty Regex (Structures/NoEmptyRegex ; Analyze)

* 0.11.0

  * Could Use str_repeat() (Structures/CouldUseStrrepeat ; Analyze, Level 1)
  * Crc32() Might Be Negative (Php/Crc32MightBeNegative ; Analyze, PHP recommendations)
  * Empty Final Element (Arrays/EmptyFinal)
  * Strings With Strange Space (Type/StringWithStrangeSpace ; Analyze)
  * Suspicious Comparison (Structures/SuspiciousComparison ; Analyze)

* 0.10.9

  * Displays Text (Php/Prints ; Internal)
  * Method Is Overwritten (Classes/MethodIsOverwritten)
  * No Class In Global (Php/NoClassInGlobal ; Analyze)
  * Repeated Regex (Structures/RepeatedRegex ; Analyze, Level 1)
  * Thrown Exceptions (ZendF/ThrownExceptions ; ZendFramework)
  * zend-log 2.5.0 Undefined Classes (ZendF/Zf3Log25 ; ZendFramework)
  * zend-log 2.6.0 Undefined Classes (ZendF/Zf3Log26 ; ZendFramework)
  * zend-log 2.7.0 Undefined Classes (ZendF/Zf3Log27 ; ZendFramework)
  * zend-log 2.8.0 Undefined Classes (ZendF/Zf3Log28 ; ZendFramework)
  * zend-log 2.9.0 Undefined Classes (ZendF/Zf3Log29 ; ZendFramework)
  * zend-log Usage (ZendF/Zf3Log ; ZendFramework)
  * zend-mail 2.5.0 Undefined Classes (ZendF/Zf3Mail25 ; ZendFramework)
  * zend-mail 2.6.0 Undefined Classes (ZendF/Zf3Mail26 ; ZendFramework)
  * zend-mail 2.7.0 Undefined Classes (ZendF/Zf3Mail27 ; ZendFramework)
  * zend-mail Usage (ZendF/Zf3Mail ; ZendFramework)
  * zend-math 2.5.0 Undefined Classes (ZendF/Zf3Math25 ; ZendFramework)
  * zend-math 2.6.0 Undefined Classes (ZendF/Zf3Math26 ; ZendFramework)
  * zend-math 2.7.0 Undefined Classes (ZendF/Zf3Math27 ; ZendFramework)
  * zend-math 3.0.0 Undefined Classes (ZendF/Zf3Math30 ; ZendFramework)
  * zend-math Usage (ZendF/Zf3Math ; ZendFramework)
  * zend-memory 2.5.0 Undefined Classes (ZendF/Zf3Memory25 ; ZendFramework)
  * zend-memory Usage (ZendF/Zf3Memory ; ZendFramework)
  * zend-mime 2.5.0 Undefined Classes (ZendF/Zf3Mime25 ; ZendFramework)
  * zend-mime 2.6.0 Undefined Classes (ZendF/Zf3Mime26 ; ZendFramework)
  * zend-mime Usage (ZendF/Zf3Mime ; ZendFramework)
  * zend-modulemanager 2.5.0 Undefined Classes (ZendF/Zf3Modulemanager25 ; ZendFramework)
  * zend-modulemanager 2.6.0 Undefined Classes (ZendF/Zf3Modulemanager26 ; ZendFramework)
  * zend-modulemanager 2.7.0 Undefined Classes (ZendF/Zf3Modulemanager27 ; ZendFramework)
  * zend-modulemanager Usage (ZendF/Zf3Modulemanager ; ZendFramework)
  * zend-navigation 2.5.0 Undefined Classes (ZendF/Zf3Navigation25 ; ZendFramework)
  * zend-navigation 2.6.0 Undefined Classes (ZendF/Zf3Navigation26 ; ZendFramework)
  * zend-navigation 2.7.0 Undefined Classes (ZendF/Zf3Navigation27 ; ZendFramework)
  * zend-navigation 2.8.0 Undefined Classes (ZendF/Zf3Navigation28 ; ZendFramework)
  * zend-navigation Usage (ZendF/Zf3Navigation ; ZendFramework)
  * zend-paginator 2.5.0 Undefined Classes (ZendF/Zf3Paginator25 ; ZendFramework)
  * zend-paginator 2.6.0 Undefined Classes (ZendF/Zf3Paginator26 ; ZendFramework)
  * zend-paginator 2.7.0 Undefined Classes (ZendF/Zf3Paginator27 ; ZendFramework)
  * zend-paginator Usage (ZendF/Zf3Paginator ; ZendFramework)
  * zend-progressbar 2.5.0 Undefined Classes (ZendF/Zf3Progressbar25 ; ZendFramework)
  * zend-progressbar Usage (ZendF/Zf3Progressbar ; ZendFramework)
  * zend-serializer 2.5.0 Undefined Classes (ZendF/Zf3Serializer25 ; ZendFramework)
  * zend-serializer 2.6.0 Undefined Classes (ZendF/Zf3Serializer26 ; ZendFramework)
  * zend-serializer 2.7.0 Undefined Classes (ZendF/Zf3Serializer27 ; ZendFramework)
  * zend-serializer 2.8.0 Undefined Classes (ZendF/Zf3Serializer28 ; ZendFramework)
  * zend-serializer Usage (ZendF/Zf3Serializer ; ZendFramework)
  * zend-server 2.5.0 Undefined Classes (ZendF/Zf3Server25 ; ZendFramework)
  * zend-server 2.6.0 Undefined Classes (ZendF/Zf3Server26 ; ZendFramework)
  * zend-server 2.7.0 Undefined Classes (ZendF/Zf3Server27 ; ZendFramework)
  * zend-server Usage (ZendF/Zf3Server ; ZendFramework)
  * zend-servicemanager 2.5.0 Undefined Classes (ZendF/Zf3Servicemanager25 ; ZendFramework)
  * zend-servicemanager 2.6.0 Undefined Classes (ZendF/Zf3Servicemanager26 ; ZendFramework)
  * zend-servicemanager 2.7.0 Undefined Classes (ZendF/Zf3Servicemanager27 ; ZendFramework)
  * zend-servicemanager 3.0.0 Undefined Classes (ZendF/Zf3Servicemanager30 ; ZendFramework)
  * zend-servicemanager 3.1.0 Undefined Classes (ZendF/Zf3Servicemanager31 ; ZendFramework)
  * zend-servicemanager 3.2.0 Undefined Classes (ZendF/Zf3Servicemanager32 ; ZendFramework)
  * zend-servicemanager 3.3.0 Undefined Classes (ZendF/Zf3Servicemanager33 ; ZendFramework)
  * zend-servicemanager Usage (ZendF/Zf3Servicemanager ; ZendFramework)
  * zend-soap 2.5.0 Undefined Classes (ZendF/Zf3Soap25 ; ZendFramework)
  * zend-soap 2.6.0 Undefined Classes (ZendF/Zf3Soap26 ; ZendFramework)
  * zend-soap Usage (ZendF/Zf3Soap ; ZendFramework)
  * zend-stdlib 2.5.0 Undefined Classes (ZendF/Zf3Stdlib25 ; ZendFramework)
  * zend-stdlib 2.6.0 Undefined Classes (ZendF/Zf3Stdlib26 ; ZendFramework)
  * zend-stdlib 2.7.0 Undefined Classes (ZendF/Zf3Stdlib27 ; ZendFramework)
  * zend-stdlib 3.0.0 Undefined Classes (ZendF/Zf3Stdlib30 ; ZendFramework)
  * zend-stdlib 3.1.0 Undefined Classes (ZendF/Zf3Stdlib31 ; ZendFramework)
  * zend-stdlib Usage (ZendF/Zf3Stdlib ; ZendFramework)
  * zend-tag 2.5.0 Undefined Classes (ZendF/Zf3Tag25 ; ZendFramework)
  * zend-tag 2.6.0 Undefined Classes (ZendF/Zf3Tag26 ; ZendFramework)
  * zend-tag Usage (ZendF/Zf3Tag ; ZendFramework)
  * zend-test 2.5.0 Undefined Classes (ZendF/Zf3Test25 ; ZendFramework, ZendFramework)
  * zend-test 2.6.0 Undefined Classes (ZendF/Zf3Test26 ; ZendFramework, ZendFramework)
  * zend-test 3.0.0 Undefined Classes (ZendF/Zf3Test30 ; ZendFramework, ZendFramework)
  * zend-test Usage (ZendF/Zf3Test ; ZendFramework, ZendFramework)
  * zend-xmlrpc 2.5.0 Undefined Classes (ZendF/Zf3Xmlrpc25 ; ZendFramework)
  * zend-xmlrpc 2.6.0 Undefined Classes (ZendF/Zf3Xmlrpc26 ; ZendFramework)
  * zend-xmlrpc Usage (ZendF/Zf3Xmlrpc ; ZendFramework)

* 0.10.8

  * zend-i18n-resources 2.5.x (ZendF/Zf3I18n_resources25)

* 0.10.7

  * Group Use Declaration (Php/GroupUseDeclaration)
  * Missing Cases In Switch (Structures/MissingCases ; Analyze)
  * New Constants In PHP 7.2 (Php/Php72NewConstants ; CompatibilityPHP72, CompatibilityPHP73)
  * New Functions In PHP 7.2 (Php/Php72NewFunctions ; CompatibilityPHP72, CompatibilityPHP73)
  * New Functions In PHP 7.3 (Php/Php73NewFunctions ; CompatibilityPHP73)
  * No Echo In Route Callable (Slim/NoEchoInRouteCallable ; Slim)
  * Slim Missing Classes (Slim/SlimMissing ; Internal)
  * SlimPHP 1.0.0 Undefined Classes (Slim/Slimphp10 ; Slim)
  * SlimPHP 1.1.0 Undefined Classes (Slim/Slimphp11 ; Slim)
  * SlimPHP 1.2.0 Undefined Classes (Slim/Slimphp12 ; Slim)
  * SlimPHP 1.3.0 Undefined Classes (Slim/Slimphp13 ; Slim)
  * SlimPHP 1.5.0 Undefined Classes (Slim/Slimphp15 ; Slim)
  * SlimPHP 1.6.0 Undefined Classes (Slim/Slimphp16 ; Slim)
  * SlimPHP 2.0.0 Undefined Classes (Slim/Slimphp20 ; Slim)
  * SlimPHP 2.1.0 Undefined Classes (Slim/Slimphp21 ; Slim)
  * SlimPHP 2.2.0 Undefined Classes (Slim/Slimphp22 ; Slim)
  * SlimPHP 2.3.0 Undefined Classes (Slim/Slimphp23 ; Slim)
  * SlimPHP 2.4.0 Undefined Classes (Slim/Slimphp24 ; Slim)
  * SlimPHP 2.5.0 Undefined Classes (Slim/Slimphp25 ; Slim)
  * SlimPHP 2.6.0 Undefined Classes (Slim/Slimphp26 ; Slim)
  * SlimPHP 3.0.0 Undefined Classes (Slim/Slimphp30 ; Slim)
  * SlimPHP 3.1.0 Undefined Classes (Slim/Slimphp31 ; Slim)
  * SlimPHP 3.2.0 Undefined Classes (Slim/Slimphp32 ; Slim)
  * SlimPHP 3.3.0 Undefined Classes (Slim/Slimphp33 ; Slim)
  * SlimPHP 3.4.0 Undefined Classes (Slim/Slimphp34 ; Slim)
  * SlimPHP 3.5.0 Undefined Classes (Slim/Slimphp35 ; Slim)
  * SlimPHP 3.6.0 Undefined Classes (Slim/Slimphp36 ; Slim)
  * SlimPHP 3.7.0 Undefined Classes (Slim/Slimphp37 ; Slim)
  * SlimPHP 3.8.0 Undefined Classes (Slim/Slimphp38 ; Slim)
  * Use Slim (Slim/UseSlim ; Appinfo, Slim)
  * Used Routes (Slim/UsedRoutes ; Slim)
  * ZendF/DontUseGPC (ZendF/DontUseGPC ; ZendFramework)
  * zend-authentication 2.5.0 Undefined Classes (ZendF/Zf3Authentication25 ; ZendFramework)
  * zend-authentication Usage (ZendF/Zf3Authentication ; ZendFramework)
  * zend-barcode 2.5.0 Undefined Classes (ZendF/Zf3Barcode25 ; ZendFramework)
  * zend-barcode 2.6.0 Undefined Classes (ZendF/Zf3Barcode26 ; ZendFramework)
  * zend-barcode Usage (ZendF/Zf3Barcode ; ZendFramework)
  * zend-captcha 2.5.0 Undefined Classes (ZendF/Zf3Captcha25 ; ZendFramework)
  * zend-captcha 2.6.0 Undefined Classes (ZendF/Zf3Captcha26 ; ZendFramework)
  * zend-captcha 2.7.0 Undefined Classes (ZendF/Zf3Captcha27 ; ZendFramework)
  * zend-captcha Usage (ZendF/Zf3Captcha ; ZendFramework)
  * zend-code 2.5.0 Undefined Classes (ZendF/Zf3Code25 ; ZendFramework)
  * zend-code 2.6.0 Undefined Classes (ZendF/Zf3Code26 ; ZendFramework)
  * zend-code 3.0.0 Undefined Classes (ZendF/Zf3Code30 ; ZendFramework)
  * zend-code 3.1.0 Undefined Classes (ZendF/Zf3Code31 ; ZendFramework)
  * zend-code Usage (ZendF/Zf3Code ; ZendFramework)
  * zend-console 2.5.0 Undefined Classes (ZendF/Zf3Console25 ; ZendFramework)
  * zend-console 2.6.0 Undefined Classes (ZendF/Zf3Console26 ; ZendFramework)
  * zend-console Usage (ZendF/Zf3Console ; ZendFramework)
  * zend-crypt 2.5.0 Undefined Classes (ZendF/Zf3Crypt25 ; ZendFramework)
  * zend-crypt 2.6.0 Undefined Classes (ZendF/Zf3Crypt26 ; ZendFramework)
  * zend-crypt 3.0.0 Undefined Classes (ZendF/Zf3Crypt30 ; ZendFramework)
  * zend-crypt 3.1.0 Undefined Classes (ZendF/Zf3Crypt31 ; ZendFramework)
  * zend-crypt 3.2.0 Undefined Classes (ZendF/Zf3Crypt32 ; ZendFramework)
  * zend-crypt Usage (ZendF/Zf3Crypt ; ZendFramework)
  * zend-db 2.5.0 Undefined Classes (ZendF/Zf3Db25 ; ZendFramework)
  * zend-db 2.6.0 Undefined Classes (ZendF/Zf3Db26 ; ZendFramework)
  * zend-db 2.7.0 Undefined Classes (ZendF/Zf3Db27 ; ZendFramework)
  * zend-db 2.8.0 Undefined Classes (ZendF/Zf3Db28 ; ZendFramework)
  * zend-db Usage (ZendF/Zf3Db ; ZendFramework)
  * zend-debug 2.5.0 Undefined Classes (ZendF/Zf3Debug25 ; ZendFramework)
  * zend-debug Usage (ZendF/Zf3Debug ; ZendFramework)
  * zend-di 2.5.0 Undefined Classes (ZendF/Zf3Di25 ; ZendFramework)
  * zend-di 2.6.0 Undefined Classes (ZendF/Zf3Di26 ; ZendFramework)
  * zend-di Usage (ZendF/Zf3Di ; ZendFramework)
  * zend-dom 2.5.0 Undefined Classes (ZendF/Zf3Dom25 ; ZendFramework)
  * zend-dom 2.6.0 Undefined Classes (ZendF/Zf3Dom26 ; ZendFramework)
  * zend-dom Usage (ZendF/Zf3Dom ; ZendFramework)
  * zend-escaper 2.5.0 Undefined Classes (ZendF/Zf3Escaper25 ; ZendFramework)
  * zend-escaper Usage (ZendF/Zf3Escaper ; ZendFramework)
  * zend-eventmanager 2.5.0 Undefined Classes (ZendF/Zf3Eventmanager25 ; ZendFramework, ZendFramework)
  * zend-eventmanager 2.6.0 Undefined Classes (ZendF/Zf3Eventmanager26 ; ZendFramework, ZendFramework)
  * zend-eventmanager 3.0.0 Undefined Classes (ZendF/Zf3Eventmanager30 ; ZendFramework, ZendFramework)
  * zend-eventmanager 3.1.0 Undefined Classes (ZendF/Zf3Eventmanager31 ; ZendFramework, ZendFramework)
  * zend-eventmanager Usage (ZendF/Zf3Eventmanager ; ZendFramework, ZendFramework)
  * zend-feed 2.5.0 Undefined Classes (ZendF/Zf3Feed25 ; ZendFramework)
  * zend-feed 2.6.0 Undefined Classes (ZendF/Zf3Feed26 ; ZendFramework)
  * zend-feed 2.7.0 Undefined Classes (ZendF/Zf3Feed27 ; ZendFramework)
  * zend-feed Usage (ZendF/Zf3Feed ; ZendFramework)
  * zend-file 2.5.0 Undefined Classes (ZendF/Zf3File25 ; ZendFramework)
  * zend-file 2.6.0 Undefined Classes (ZendF/Zf3File26 ; ZendFramework)
  * zend-file 2.7.0 Undefined Classes (ZendF/Zf3File27 ; ZendFramework)
  * zend-file Usage (ZendF/Zf3File ; ZendFramework)
  * zend-filter 2.5.0 Undefined Classes (ZendF/Zf3Filter25 ; ZendFramework)
  * zend-filter 2.6.0 Undefined Classes (ZendF/Zf3Filter26 ; ZendFramework)
  * zend-filter 2.7.0 Undefined Classes (ZendF/Zf3Filter27 ; ZendFramework)
  * zend-filter Usage (ZendF/Zf3Filter ; ZendFramework)
  * zend-form 2.5.0 Undefined Classes (ZendF/Zf3Form25 ; ZendFramework)
  * zend-form 2.6.0 Undefined Classes (ZendF/Zf3Form26 ; ZendFramework)
  * zend-form 2.7.0 Undefined Classes (ZendF/Zf3Form27 ; ZendFramework)
  * zend-form 2.8.0 Undefined Classes (ZendF/Zf3Form28 ; ZendFramework)
  * zend-form 2.9.0 Undefined Classes (ZendF/Zf3Form29 ; ZendFramework)
  * zend-form Usage (ZendF/Zf3Form ; ZendFramework)
  * zend-http 2.5.0 Undefined Classes (ZendF/Zf3Http25 ; ZendFramework)
  * zend-http 2.6.0 Undefined Classes (ZendF/Zf3Http26 ; ZendFramework)
  * zend-http Usage (ZendF/Zf3Http ; ZendFramework)
  * zend-i18n 2.5.0 Undefined Classes (ZendF/Zf3I18n25 ; ZendFramework)
  * zend-i18n 2.6.0 Undefined Classes (ZendF/Zf3I18n26 ; ZendFramework)
  * zend-i18n 2.7.0 Undefined Classes (ZendF/Zf3I18n27 ; ZendFramework)
  * zend-i18n Usage (ZendF/Zf3I18n ; ZendFramework)
  * zend-i18n resources Usage (ZendF/Zf3I18n_resources ; ZendFramework)
  * zend-i18n-resources 2.5.0 Undefined Classes (ZendF/Zf3I18n-resources25 ; )
  * zend-i18n-resources Usage (ZendF/Zf3I18n-resources ; )
  * zend-inputfilter 2.5.0 Undefined Classes (ZendF/Zf3Inputfilter25 ; ZendFramework)
  * zend-inputfilter 2.6.0 Undefined Classes (ZendF/Zf3Inputfilter26 ; ZendFramework)
  * zend-inputfilter 2.7.0 Undefined Classes (ZendF/Zf3Inputfilter27 ; ZendFramework)
  * zend-inputfilter Usage (ZendF/Zf3Inputfilter ; ZendFramework)
  * zend-json 2.5.0 Undefined Classes (ZendF/Zf3Json25 ; ZendFramework)
  * zend-json 2.6.0 Undefined Classes (ZendF/Zf3Json26 ; ZendFramework)
  * zend-json 3.0.0 Undefined Classes (ZendF/Zf3Json30 ; ZendFramework)
  * zend-json Usage (ZendF/Zf3Json ; ZendFramework)
  * zend-loader 2.5.0 Undefined Classes (ZendF/Zf3Loader25 ; ZendFramework)
  * zend-loader Usage (ZendF/Zf3Loader ; ZendFramework)
  * zend-session 2.5.0 Undefined Classes (ZendF/Zf3Session25 ; ZendFramework)
  * zend-session 2.6.0 Undefined Classes (ZendF/Zf3Session26 ; ZendFramework)
  * zend-session 2.7.0 Undefined Classes (ZendF/Zf3Session27 ; ZendFramework)
  * zend-session Usage (ZendF/Zf3Session ; ZendFramework)
  * zend-text 2.5.0 Undefined Classes (ZendF/Zf3Text25 ; ZendFramework)
  * zend-text 2.6.0 Undefined Classes (ZendF/Zf3Text26 ; ZendFramework)
  * zend-text Usage (ZendF/Zf3Text ; ZendFramework)

* 0.10.6

  * CakePHP 2.5.0 Undefined Classes (Cakephp/Cakephp25 ; Cakephp)
  * CakePHP 2.6.0 Undefined Classes (Cakephp/Cakephp26 ; Cakephp)
  * CakePHP 2.7.0 Undefined Classes (Cakephp/Cakephp27 ; Cakephp)
  * CakePHP 2.8.0 Undefined Classes (Cakephp/Cakephp28 ; Cakephp)
  * CakePHP 2.9.0 Undefined Classes (Cakephp/Cakephp29 ; Cakephp)
  * CakePHP 3.0.0 Undefined Classes (Cakephp/Cakephp30 ; Cakephp)
  * CakePHP 3.1.0 Undefined Classes (Cakephp/Cakephp31 ; Cakephp)
  * CakePHP 3.2.0 Undefined Classes (Cakephp/Cakephp32 ; Cakephp)
  * CakePHP 3.3.0 Undefined Classes (Cakephp/Cakephp33 ; Cakephp)
  * CakePHP 3.4.0 Undefined Classes (Cakephp/Cakephp34 ; Cakephp)
  * CakePHP Unknown Classes (Cakephp/CakePHPMissing)
  * CakePHP Used (Cakephp/CakePHPUsed ; Appinfo, Cakephp)
  * Check All Types (Structures/CheckAllTypes ; Analyze)
  * Do Not Cast To Int (Php/NoCastToInt ; )
  * Manipulates INF (Php/IsINF ; Appinfo)
  * Manipulates NaN (Php/IsNAN ; Appinfo)
  * Set Cookie Safe Arguments (Security/SetCookieArgs ; Security)
  * Should Use SetCookie() (Php/UseSetCookie ; Analyze)
  * Use Cookies (Php/UseCookies ; Appinfo, Appcontent)
  * ZF3 Usage Of Deprecated (ZendF/Zf3DeprecatedUsage ; ZendFramework)
  * zend-cache Usage (ZendF/Zf3Cache ; ZendFramework, ZendFramework)
  * zend-view 2.5.0 Undefined Classes (ZendF/Zf3View25 ; ZendFramework)
  * zend-view 2.6.0 Undefined Classes (ZendF/Zf3View26 ; ZendFramework)
  * zend-view 2.7.0 Undefined Classes (ZendF/Zf3View27 ; ZendFramework)
  * zend-view 2.8.0 Undefined Classes (ZendF/Zf3View28 ; ZendFramework)
  * zend-view 2.9.0 Undefined Classes (ZendF/Zf3View29 ; ZendFramework)
  * zend-view Usage (ZendF/Zf3View ; ZendFramework)

* 0.10.5

  * Could Be Typehinted Callable (Functions/CouldBeCallable ; Analyze)
  * Encoded Simple Letters (Security/EncodedLetters ; Security)
  * Regex Delimiter (Structures/RegexDelimiter ; Preferences)
  * Strange Name For Constants (Constants/StrangeName ; Analyze)
  * Strange Name For Variables (Variables/StrangeName ; Analyze)
  * Too Many Finds (Classes/TooManyFinds)
  * ZF3 Component (ZendF/Zf3Component ; Internal)
  * Zend Framework 3 Missing Classes (ZendF/Zf3ComponentMissing ; Internal)
  * Zend\Config (ZendF/Zf3Config ; ZendFramework)
  * zend-cache 2.5.0 Undefined Classes (ZendF/Zf3Cache25 ; ZendFramework)
  * zend-cache 2.6.0 Undefined Classes (ZendF/Zf3Cache26 ; ZendFramework)
  * zend-cache 2.7.0 Undefined Classes (ZendF/Zf3Cache27 ; ZendFramework)
  * zend-config 2.5.x (ZendF/Zf3Config25 ; ZendFramework)
  * zend-config 2.6.x (ZendF/Zf3Config26 ; ZendFramework)
  * zend-config 3.0.x (ZendF/Zf3Config30 ; ZendFramework)
  * zend-config 3.1.x (ZendF/Zf3Config31 ; ZendFramework)
  * zend-mvc (ZendF/Zf3Mvc ; ZendFramework)
  * zend-mvc 2.5.x (ZendF/Zf3Mvc25 ; ZendFramework)
  * zend-mvc 2.6.x (ZendF/Zf3Mvc26 ; ZendFramework)
  * zend-mvc 2.7.x (ZendF/Zf3Mvc27 ; ZendFramework)
  * zend-mvc 3.0.x (ZendF/Zf3Mvc30 ; ZendFramework)
  * zend-uri (ZendF/Zf3Uri ; ZendFramework)
  * zend-uri 2.5.x (ZendF/Zf3Uri25 ; ZendFramework)
  * zend-validator (ZendF/Zf3Validator ; ZendFramework)
  * zend-validator 2.6.x (ZendF/Zf3Validator25 ; ZendFramework)
  * zend-validator 2.6.x (ZendF/Zf3Validator26 ; ZendFramework)
  * zend-validator 2.7.x (ZendF/Zf3Validator27 ; ZendFramework)
  * zend-validator 2.8.x (ZendF/Zf3Validator28 ; ZendFramework)

* 0.10.4

  * No Need For Else (Structures/NoNeedForElse ; Analyze)
  * Should Regenerate Session Id (ZendF/ShouldRegenerateSessionId ; ZendFramework)
  * Should Use session_regenerateid() (Security/ShouldUseSessionRegenerateId ; Security)
  * Use Zend Session (ZendF/UseSession ; Internal)
  * ext/ds (Extensions/Extds)

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
  * ext/libsodium (Extensions/Extlibsodium ; Appinfo, Appcontent)

* 0.10.1

  * All strings (Type/CharString ; Inventory)
  * Avoid Non Wordpress Globals (Wordpress/AvoidOtherGlobals ; Wordpress)
  * SQL queries (Type/Sql ; Inventory)
  * Strange Names For Methods (Classes/StrangeName)

* 0.10.0

  * Error_Log() Usage (Php/ErrorLogUsage ; Appinfo)
  * No Boolean As Default (Functions/NoBooleanAsDefault ; Analyze)
  * Raised Access Level (Classes/RaisedAccessLevel)
  * Use Prepare With Variables (Wordpress/WpdbPrepareForVariables ; )

* 0.9.9

  * PHP 7.2 Deprecations (Php/Php72Deprecation)
  * PHP 7.2 Removed Functions (Php/Php72RemovedFunctions ; CompatibilityPHP72, CompatibilityPHP73)

* 0.9.8

  * Assigned Twice (Variables/AssignedTwiceOrMore ; Analyze, Codacy)
  * New Line Style (Structures/NewLineStyle ; Preferences)
  * New On Functioncall Or Identifier (Classes/NewOnFunctioncallOrIdentifier)

* 0.9.7

  * Avoid Large Array Assignation (Structures/NoAssignationInFunction ; Performances)
  * Could Be Protected Property (Classes/CouldBeProtectedProperty)
  * Long Arguments (Structures/LongArguments ; Analyze)
  * ZendF/ZendTypehinting (ZendF/ZendTypehinting ; ZendFramework)

* 0.9.6

  * Avoid glob() Usage (Performances/NoGlob ; Performances)
  * Fetch One Row Format (Performances/FetchOneRowFormat)

* 0.9.5

  * Ext/mongodb (Extensions/Extmongodb)
  * One Expression Brackets Consistency (Structures/OneExpressionBracketsConsistency ; Preferences)
  * Should Use Function Use (Php/ShouldUseFunction ; Performances)
  * ext/zbarcode (Extensions/Extzbarcode ; Appinfo)

* 0.9.4

  * Class Should Be Final By Ocramius (Classes/FinalByOcramius)
  * String (Extensions/Extstring ; Appinfo, Appcontent)
  * ext/mhash (Extensions/Extmhash ; Appinfo, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, Appcontent, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)

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

  * Avoid Using stdClass (Php/UseStdclass ; Analyze, OneFile, Codacy, Simple)
  * Avoid array_push() (Performances/AvoidArrayPush ; Performances, PHP recommendations)
  * Could Return Void (Functions/CouldReturnVoid)
  * Invalid Octal In String (Type/OctalInString ; Inventory, CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
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
  * Bail Out Early (Structures/BailOutEarly ; Analyze, OneFile, Codacy, Simple)
  * Die Exit Consistence (Structures/DieExitConsistance ; Preferences)
  * Dont Change The Blind Var (Structures/DontChangeBlindKey ; Analyze, Codacy)
  * More Than One Level Of Indentation (Structures/OneLevelOfIndentation ; Calisthenics)
  * One Dot Or Object Operator Per Line (Structures/OneDotOrObjectOperatorPerLine ; Calisthenics)
  * PHP 7.1 Microseconds (Php/Php71microseconds ; CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
  * Unitialized Properties (Classes/UnitializedProperties ; Analyze, OneFile, Codacy, Simple)
  * Use Wordpress Functions (Wordpress/UseWpFunctions ; Wordpress)
  * Useless Check (Structures/UselessCheck ; Analyze, OneFile, Codacy, Simple, Level 1)

* 0.8.7

  * Dont Echo Error (Security/DontEchoError ; Analyze, Security, Codacy, Simple, Level 1)
  * No Isset With Empty (Structures/NoIssetWithEmpty ; Analyze, PHP recommendations, OneFile, RadwellCodes, Codacy, Simple)
  * Performances/timeVsstrtotime (Performances/timeVsstrtotime ; Performances, OneFile, RadwellCodes)
  * Use Class Operator (Classes/UseClassOperator)
  * Useless Casting (Structures/UselessCasting ; Analyze, PHP recommendations, OneFile, RadwellCodes, Codacy, Simple)
  * ext/rar (Extensions/Extrar ; Appinfo)

* 0.8.6

  * Boolean Value (Type/BooleanValue ; Appinfo)
  * Drop Else After Return (Structures/DropElseAfterReturn)
  * Modernize Empty With Expression (Structures/ModernEmpty ; Analyze, OneFile, Codacy, Simple)
  * Null Value (Type/NullValue ; Appinfo)
  * Use Positive Condition (Structures/UsePositiveCondition ; Analyze, OneFile, Codacy, Simple)

* 0.8.5

  * Is Zend Framework 1 Controller (ZendF/IsController ; ZendFramework)
  * Is Zend Framework 1 Helper (ZendF/IsHelper ; ZendFramework)
  * Should Make Ternary (Structures/ShouldMakeTernary ; Analyze, OneFile, Codacy, Simple)
  * Unused Returned Value (Functions/UnusedReturnedValue)

* 0.8.4

  * $HTTP_RAW_POST_DATA (Php/RawPostDataUsage ; Appinfo, CompatibilityPHP56, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, Codacy, CompatibilityPHP73)
  * $this Belongs To Classes Or Traits (Classes/ThisIsForClasses ; Analyze, Codacy, Simple)
  * $this Is Not An Array (Classes/ThisIsNotAnArray ; Analyze, Codacy)
  * $this Is Not For Static Methods (Classes/ThisIsNotForStatic ; Analyze, Codacy)
  * ** For Exponent (Php/NewExponent ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP53)
  * ::class (Php/StaticclassUsage ; CompatibilityPHP54, CompatibilityPHP53)
  * <?= Usage (Php/EchoTagUsage ; Analyze, Appinfo, Codacy, Simple)
  * @ Operator (Structures/Noscream ; Analyze, Appinfo, ClearPHP)
  * Abstract Class Usage (Classes/Abstractclass ; Appinfo, Appcontent)
  * Abstract Methods Usage (Classes/Abstractmethods ; Appinfo, Appcontent)
  * Abstract Static Methods (Classes/AbstractStatic ; Analyze, Codacy, Simple)
  * Access Protected Structures (Classes/AccessProtected ; Analyze, Codacy, Simple)
  * Accessing Private (Classes/AccessPrivate ; Analyze, Codacy, Simple)
  * Action Should Be In Controller (ZendF/ActionInController ; ZendFramework)
  * Adding Zero (Structures/AddZero ; Analyze, OneFile, ClearPHP, Codacy, Simple, Level 1)
  * Aliases (Namespaces/Alias ; Appinfo)
  * Aliases Usage (Functions/AliasesUsage ; Analyze, OneFile, ClearPHP, Codacy, Simple, Level 1)
  * All Uppercase Variables (Variables/VariableUppercase ; Coding Conventions)
  * Already Parents Interface (Interfaces/AlreadyParentsInterface ; Analyze, Codacy)
  * Altering Foreach Without Reference (Structures/AlteringForeachWithoutReference ; Analyze, ClearPHP, Codacy, Simple, Level 1)
  * Alternative Syntax (Php/AlternativeSyntax ; Appinfo)
  * Always Positive Comparison (Structures/NeverNegative ; Analyze, Codacy, Simple)
  * Ambiguous Array Index (Arrays/AmbiguousKeys)
  * Anonymous Classes (Classes/Anonymous ; Appinfo, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53)
  * Argument Should Be Typehinted (Functions/ShouldBeTypehinted ; ClearPHP, Suggestions)
  * Arguments (Variables/Arguments ; )
  * Array Index (Arrays/Arrayindex ; Appinfo)
  * Arrays Is Modified (Arrays/IsModified ; Internal)
  * Arrays Is Read (Arrays/IsRead ; Internal)
  * Assertions (Php/AssertionUsage ; Appinfo)
  * Assign Default To Properties (Classes/MakeDefault ; Analyze, ClearPHP, Codacy, Simple)
  * Autoloading (Php/AutoloadUsage ; Appinfo)
  * Avoid Parenthesis (Structures/PrintWithoutParenthesis ; Analyze, Codacy, Simple)
  * Avoid Those Hash Functions (Security/AvoidThoseCrypto ; Security)
  * Avoid array_unique() (Structures/NoArrayUnique ; Performances)
  * Avoid get_class() (Structures/UseInstanceof ; Analyze, Codacy, Simple)
  * Avoid sleep()/usleep() (Security/NoSleep ; Security)
  * Bad Constants Names (Constants/BadConstantnames ; PHP recommendations)
  * Binary Glossary (Type/Binary ; Inventory, Appinfo, CompatibilityPHP53)
  * Blind Variables (Variables/Blind ; )
  * Bracketless Blocks (Structures/Bracketless ; Coding Conventions)
  * Break Outside Loop (Structures/BreakOutsideLoop ; Analyze, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, Codacy, CompatibilityPHP73)
  * Break With 0 (Structures/Break0 ; CompatibilityPHP53, OneFile, Codacy)
  * Break With Non Integer (Structures/BreakNonInteger ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, OneFile, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, Codacy, CompatibilityPHP73)
  * Buried Assignation (Structures/BuriedAssignation ; Analyze, Codacy)
  * CakePHP 3.0 Deprecated Class (Cakephp/Cake30DeprecatedClass ; Cakephp)
  * CakePHP 3.3 Deprecated Class (Cakephp/Cake33DeprecatedClass ; Cakephp)
  * Calltime Pass By Reference (Structures/CalltimePassByReference ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, Codacy, CompatibilityPHP73)
  * Can't Disable Function (Security/CantDisableFunction ; Appinfo, Appcontent)
  * Can't Extend Final (Classes/CantExtendFinal ; Analyze, Dead code, Codacy, Simple)
  * Cant Use Return Value In Write Context (Php/CantUseReturnValueInWriteContext ; CompatibilityPHP54, CompatibilityPHP53)
  * Cast To Boolean (Structures/CastToBoolean ; Analyze, OneFile, Codacy, Simple, Level 1)
  * Cast Usage (Php/CastingUsage ; Appinfo)
  * Catch Overwrite Variable (Structures/CatchShadowsVariable ; Analyze, ClearPHP, Codacy, Simple)
  * Caught Exceptions (Exceptions/CaughtExceptions ; )
  * Caught Expressions (Php/TryCatchUsage ; Appinfo)
  * Class Const With Array (Php/ClassConstWithArray ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP53)
  * Class Has Fluent Interface (Classes/HasFluentInterface ; )
  * Class Name Case Difference (Classes/WrongCase ; Analyze, Coding Conventions, RadwellCodes, Codacy, Simple)
  * Class Usage (Classes/ClassUsage ; )
  * Class, Interface Or Trait With Identical Names (Classes/CitSameName ; Analyze, Codacy)
  * Classes Mutually Extending Each Other (Classes/MutualExtension ; Analyze, Codacy)
  * Classes Names (Classes/Classnames ; Appinfo)
  * Clone Usage (Classes/CloningUsage ; Appinfo)
  * Close Tags (Php/CloseTags ; Coding Conventions)
  * Closure May Use $this (Php/ClosureThisSupport ; CompatibilityPHP53, Codacy)
  * Closures Glossary (Functions/Closures ; Appinfo)
  * Coalesce (Php/Coalesce ; Appinfo, Appcontent)
  * Common Alternatives (Structures/CommonAlternatives ; Analyze, Codacy, Simple)
  * Compare Hash (Security/CompareHash ; Security, ClearPHP)
  * Compared Comparison (Structures/ComparedComparison ; Analyze, Codacy)
  * Composer Namespace (Composer/IsComposerNsname ; Appinfo, Internal)
  * Composer Usage (Composer/UseComposer ; Appinfo)
  * Composer's autoload (Composer/Autoload ; Appinfo)
  * Concrete Visibility (Interfaces/ConcreteVisibility ; Analyze, Codacy, Simple)
  * Conditional Structures (Structures/ConditionalStructures ; )
  * Conditioned Constants (Constants/ConditionedConstants ; Appinfo, Internal)
  * Conditioned Function (Functions/ConditionedFunctions ; Appinfo, Internal)
  * Confusing Names (Variables/CloseNaming ; Analyze, Codacy, Simple)
  * Const With Array (Php/ConstWithArray ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP53)
  * Constant Class (Classes/ConstantClass ; Analyze, Codacy, Simple)
  * Constant Comparison (Structures/ConstantComparisonConsistance ; Coding Conventions, Preferences)
  * Constant Conditions (Structures/ConstantConditions ; )
  * Constant Definition (Classes/ConstantDefinition ; Appinfo)
  * Constant Scalar Expression (Php/ConstantScalarExpression ; )
  * Constant Scalar Expressions (Structures/ConstantScalarExpression ; Appinfo, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP53)
  * Constants (Constants/Constantnames ; )
  * Constants Created Outside Its Namespace (Constants/CreatedOutsideItsNamespace ; Analyze, Codacy)
  * Constants Usage (Constants/ConstantUsage ; Appinfo)
  * Constants With Strange Names (Constants/ConstantStrangeNames ; Analyze, Codacy, Simple)
  * Constructors (Classes/Constructor ; Internal)
  * Continents (Type/Continents ; Inventory)
  * Could Be Class Constant (Classes/CouldBeClassConstant ; Analyze, Codacy)
  * Could Be Static (Structures/CouldBeStatic ; Analyze, OneFile, Codacy)
  * Could Use Alias (Namespaces/CouldUseAlias ; Analyze, OneFile, Codacy)
  * Could Use Short Assignation (Structures/CouldUseShortAssignation ; Analyze, Performances, OneFile, Codacy, Simple)
  * Could Use __DIR__ (Structures/CouldUseDir ; Analyze, Codacy, Simple, Suggestions)
  * Could Use self (Classes/ShouldUseSelf ; Analyze, Codacy, Simple)
  * Curly Arrays (Arrays/CurlyArrays ; Coding Conventions)
  * Custom Class Usage (Classes/AvoidUsing ; Custom)
  * Custom Constant Usage (Constants/CustomConstantUsage ; )
  * Dangling Array References (Structures/DanglingArrayReferences ; Analyze, PHP recommendations, ClearPHP, Codacy, Simple, Level 1)
  * Deep Definitions (Functions/DeepDefinitions ; Analyze, Appinfo, Codacy, Simple)
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
  * Double Instructions (Structures/DoubleInstruction ; Analyze, Codacy, Simple)
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
  * Echo With Concat (Structures/EchoWithConcat ; Analyze, Performances, Codacy, Simple)
  * Ellipsis Usage (Php/EllipsisUsage ; Appinfo, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP53)
  * Else If Versus Elseif (Structures/ElseIfElseif ; Analyze, Codacy, Simple)
  * Else Usage (Structures/ElseUsage ; Appinfo, Appcontent, Calisthenics)
  * Email Addresses (Type/Email ; Inventory)
  * Empty Blocks (Structures/EmptyBlocks ; Analyze, Codacy, Simple)
  * Empty Classes (Classes/EmptyClass ; Analyze, Codacy, Simple)
  * Empty Function (Functions/EmptyFunction ; Analyze, Codacy, Simple)
  * Empty Instructions (Structures/EmptyLines ; Analyze, Dead code, Codacy, Simple)
  * Empty Interfaces (Interfaces/EmptyInterface ; Analyze, Codacy, Simple)
  * Empty List (Php/EmptyList ; Analyze, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, Codacy, CompatibilityPHP73)
  * Empty Namespace (Namespaces/EmptyNamespace ; Analyze, Dead code, OneFile, Codacy, Simple)
  * Empty Slots In Arrays (Arrays/EmptySlots ; Coding Conventions)
  * Empty Traits (Traits/EmptyTrait ; Analyze, Codacy, Simple)
  * Empty Try Catch (Structures/EmptyTryCatch ; Analyze, Codacy)
  * Empty With Expression (Structures/EmptyWithExpression ; CompatibilityPHP55, CompatibilityPHP56, OneFile, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
  * Error Messages (Structures/ErrorMessages ; Appinfo, ZendFramework)
  * Eval() Usage (Structures/EvalUsage ; Analyze, Appinfo, Performances, OneFile, ClearPHP, Codacy, Simple)
  * Exception Order (Exceptions/AlreadyCaught ; Dead code)
  * Exit() Usage (Structures/ExitUsage ; Analyze, Appinfo, OneFile, ClearPHP, ZendFramework, Codacy)
  * Exit-like Methods (Functions/KillsApp ; Internal)
  * Exponent Usage (Php/ExponentUsage ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP53)
  * Ext/geoip (Extensions/Extgeoip ; Appinfo)
  * External Config Files (Files/Services ; Internal)
  * Failed Substr Comparison (Structures/FailingSubstrComparison ; Analyze, Codacy, Simple)
  * File Is Component (Files/IsComponent ; Internal)
  * File Uploads (Structures/FileUploadUsage ; Appinfo)
  * File Usage (Structures/FileUsage ; Appinfo)
  * Final Class Usage (Classes/Finalclass ; Appinfo)
  * Final Methods Usage (Classes/Finalmethod ; Appinfo)
  * Fopen Binary Mode (Portability/FopenMode ; Portability)
  * For Using Functioncall (Structures/ForWithFunctioncall ; Analyze, Performances, ClearPHP, Codacy, Simple, Level 1)
  * Foreach Don't Change Pointer (Php/ForeachDontChangePointer ; CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
  * Foreach Needs Reference Array (Structures/ForeachNeedReferencedSource ; Analyze, Codacy)
  * Foreach Reference Is Not Modified (Structures/ForeachReferenceIsNotModified ; Analyze, Codacy, Simple)
  * Foreach With list() (Structures/ForeachWithList ; CompatibilityPHP54, CompatibilityPHP53)
  * Forgotten Visibility (Classes/NonPpp ; Analyze, ClearPHP, Codacy, Simple, Level 1)
  * Forgotten Whitespace (Structures/ForgottenWhiteSpace ; Analyze, Codacy)
  * Fully Qualified Constants (Namespaces/ConstantFullyQualified ; Analyze, Codacy)
  * Function Called With Other Case Than Defined (Functions/FunctionCalledWithOtherCase ; )
  * Function Subscripting (Structures/FunctionSubscripting ; Appinfo, CompatibilityPHP53)
  * Function Subscripting, Old Style (Structures/FunctionPreSubscripting ; Analyze, Codacy)
  * Functioncall Is Global (Functions/IsGlobal ; Internal)
  * Functions Glossary (Functions/Functionnames ; Appinfo)
  * Functions In Loop Calls (Functions/LoopCalling ; Unassigned)
  * Functions Removed In PHP 5.4 (Php/Php54RemovedFunctions ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, Codacy, CompatibilityPHP73)
  * Functions Removed In PHP 5.5 (Php/Php55RemovedFunctions ; CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
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
  * Hardcoded Passwords (Functions/HardcodedPasswords ; Analyze, Security, OneFile, Codacy, Simple)
  * Has Magic Property (Classes/HasMagicProperty ; Internal)
  * Has Variable Arguments (Functions/VariableArguments ; Appinfo, Internal)
  * Hash Algorithms (Php/HashAlgos ; Analyze, Codacy)
  * Hash Algorithms Incompatible With PHP 5.3 (Php/HashAlgos53 ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
  * Hash Algorithms Incompatible With PHP 5.4/5 (Php/HashAlgos54 ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
  * Heredoc Delimiter Glossary (Type/Heredoc ; Appinfo)
  * Hexadecimal Glossary (Type/Hexadecimal ; Appinfo)
  * Hexadecimal In String (Type/HexadecimalString ; Inventory, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
  * Hidden Use Expression (Namespaces/HiddenUse ; Analyze, OneFile, Codacy, Simple)
  * Htmlentities Calls (Structures/Htmlentitiescall ; Analyze, Codacy, Simple)
  * Http Headers (Type/HttpHeader ; Inventory)
  * Identical Conditions (Structures/IdenticalConditions ; Analyze, Codacy, Simple)
  * If With Same Conditions (Structures/IfWithSameConditions ; Analyze, Codacy, Simple)
  * Iffectations (Structures/Iffectation ; Analyze, Codacy)
  * Implement Is For Interface (Classes/ImplementIsForInterface ; Analyze, Codacy, Simple)
  * Implicit Global (Structures/ImplicitGlobal ; Analyze, Codacy)
  * Inclusions (Structures/IncludeUsage ; Appinfo)
  * Incompilable Files (Php/Incompilable ; Analyze, Appinfo, ClearPHP, Simple)
  * Inconsistent Concatenation (Structures/InconsistentConcatenation ; )
  * Indices Are Int Or String (Structures/IndicesAreIntOrString ; Analyze, OneFile, Codacy, Simple)
  * Indirect Injection (Security/IndirectInjection ; Security)
  * Instantiating Abstract Class (Classes/InstantiatingAbstractClass ; Analyze, Codacy, Simple)
  * Integer Glossary (Type/Integer ; Appinfo)
  * Interface Arguments (Variables/InterfaceArguments ; )
  * Interface Methods (Interfaces/InterfaceMethod ; )
  * Interfaces Glossary (Interfaces/Interfacenames ; Appinfo)
  * Interfaces Usage (Interfaces/InterfaceUsage ; )
  * Internally Used Properties (Classes/PropertyUsedInternally ; )
  * Internet Ports (Type/Ports ; Inventory)
  * Interpolation (Type/StringInterpolation ; Coding Conventions)
  * Invalid Constant Name (Constants/InvalidName ; Analyze, Codacy, Simple)
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
  * List Short Syntax (Php/ListShortSyntax ; Appinfo, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, Internal, CompatibilityPHP53, CompatibilityPHP70)
  * List With Appends (Php/ListWithAppends ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP70, CompatibilityPHP71)
  * List With Keys (Php/ListWithKeys ; Appinfo, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, Appcontent, CompatibilityPHP53, CompatibilityPHP70)
  * Locally Unused Property (Classes/LocallyUnusedProperty ; Analyze, Dead code, Codacy, Simple)
  * Locally Used Property (Classes/LocallyUsedProperty ; Internal)
  * Logical Mistakes (Structures/LogicalMistakes ; Analyze, Codacy, Simple, Level 1)
  * Logical Should Use Symbolic Operators (Php/LogicalInLetters ; Analyze, OneFile, ClearPHP, Codacy, Simple)
  * Lone Blocks (Structures/LoneBlock ; Analyze, Codacy, Simple)
  * Lost References (Variables/LostReferences ; Analyze, Codacy, Simple)
  * Magic Constant Usage (Constants/MagicConstantUsage ; Appinfo)
  * Magic Methods (Classes/MagicMethod ; Appinfo)
  * Magic Visibility (Classes/toStringPss ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, Codacy, Simple)
  * Mail Usage (Structures/MailUsage ; Appinfo)
  * Make Global A Property (Classes/MakeGlobalAProperty ; Analyze, Codacy, Simple)
  * Make One Call With Array (Performances/MakeOneCall ; Performances)
  * Malformed Octal (Type/MalformedOctal ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53)
  * Mark Callable (Functions/MarkCallable ; Internal)
  * Md5 Strings (Type/Md5String ; Inventory)
  * Method Has Fluent Interface (Functions/HasFluentInterface ; )
  * Method Has No Fluent Interface (Functions/HasNotFluentInterface ; )
  * Methodcall On New (Php/MethodCallOnNew ; CompatibilityPHP53)
  * Methods Without Return (Functions/WithoutReturn ; )
  * Mime Types (Type/MimeType ; Inventory)
  * Mixed Keys Arrays (Arrays/MixedKeys ; CompatibilityPHP54, CompatibilityPHP53)
  * Multidimensional Arrays (Arrays/Multidimensional ; Appinfo)
  * Multiple Alias Definitions (Namespaces/MultipleAliasDefinitions ; Analyze, Codacy, Simple)
  * Multiple Catch (Structures/MultipleCatch ; Appinfo, Internal)
  * Multiple Class Declarations (Classes/MultipleDeclarations ; Analyze, Codacy, Simple)
  * Multiple Classes In One File (Classes/MultipleClassesInFile ; Appinfo, Coding Conventions)
  * Multiple Constant Definition (Constants/MultipleConstantDefinition ; Analyze, Codacy, Simple)
  * Multiple Definition Of The Same Argument (Functions/MultipleSameArguments ; OneFile, CompatibilityPHP70, ClearPHP, CompatibilityPHP71, CompatibilityPHP72, Simple, CompatibilityPHP73)
  * Multiple Exceptions Catch() (Exceptions/MultipleCatch ; Appinfo, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP70)
  * Multiple Identical Trait Or Interface (Classes/MultipleTraitOrInterface ; Analyze, OneFile, Codacy, Simple)
  * Multiple Index Definition (Arrays/MultipleIdenticalKeys ; Analyze, OneFile, Codacy, Simple)
  * Multiple Returns (Functions/MultipleReturn ; )
  * Multiples Identical Case (Structures/MultipleDefinedCase ; Analyze, OneFile, ClearPHP, Codacy, Simple, Level 1)
  * Multiply By One (Structures/MultiplyByOne ; Analyze, OneFile, ClearPHP, Codacy, Simple, Level 1)
  * Must Return Methods (Functions/MustReturn ; Analyze, Codacy, Simple)
  * Namespaces (Namespaces/NamespaceUsage ; Appinfo)
  * Namespaces Glossary (Namespaces/Namespacesnames ; Appinfo)
  * Negative Power (Structures/NegativePow ; Analyze, OneFile, Codacy, Simple)
  * Nested Ifthen (Structures/NestedIfthen ; Analyze, RadwellCodes, Codacy)
  * Nested Loops (Structures/NestedLoops ; Appinfo)
  * Nested Ternary (Structures/NestedTernary ; Analyze, ClearPHP, Codacy, Simple, Level 1)
  * Never Used Properties (Classes/PropertyNeverUsed ; Analyze, Codacy, Simple)
  * New Functions In PHP 5.4 (Php/Php54NewFunctions ; CompatibilityPHP53, CompatibilityPHP71)
  * New Functions In PHP 5.5 (Php/Php55NewFunctions ; CompatibilityPHP54, CompatibilityPHP53, CompatibilityPHP71)
  * New Functions In PHP 5.6 (Php/Php56NewFunctions ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP53)
  * New Functions In PHP 7.0 (Php/Php70NewFunctions ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP71)
  * New Functions In PHP 7.1 (Php/Php71NewFunctions ; CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
  * No Choice (Structures/NoChoice ; Analyze, Codacy, Simple)
  * No Count With 0 (Performances/NotCountNull ; Performances)
  * No Direct Access (Structures/NoDirectAccess ; Appinfo)
  * No Direct Call To Magic Method (Classes/DirectCallToMagicMethod ; Analyze, Codacy)
  * No Direct Usage (Structures/NoDirectUsage ; Analyze, Codacy, Simple)
  * No Global Modification (Wordpress/NoGlobalModification ; Wordpress)
  * No Hardcoded Hash (Structures/NoHardcodedHash ; Analyze, Security, Codacy, Simple)
  * No Hardcoded Ip (Structures/NoHardcodedIp ; Analyze, Security, ClearPHP, Codacy, Simple)
  * No Hardcoded Path (Structures/NoHardcodedPath ; Analyze, ClearPHP, Codacy, Simple)
  * No Hardcoded Port (Structures/NoHardcodedPort ; Analyze, Security, ClearPHP, Codacy, Simple)
  * No Implied If (Structures/ImpliedIf ; Analyze, ClearPHP, Codacy, Simple)
  * No List With String (Php/NoListWithString ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53)
  * No Parenthesis For Language Construct (Structures/NoParenthesisForLanguageConstruct ; Analyze, ClearPHP, RadwellCodes, Codacy, Simple)
  * No Plus One (Structures/PlusEgalOne ; Coding Conventions, OneFile)
  * No Public Access (Classes/NoPublicAccess ; Analyze, Codacy)
  * No Real Comparison (Type/NoRealComparison ; Analyze, Codacy, Simple)
  * No Self Referencing Constant (Classes/NoSelfReferencingConstant ; Analyze, Codacy, Simple)
  * No String With Append (Php/NoStringWithAppend ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53)
  * No Substr() One (Structures/NoSubstrOne ; Analyze, Performances, CompatibilityPHP71, CompatibilityPHP72, Codacy, Simple, CompatibilityPHP73)
  * No array_merge() In Loops (Performances/ArrayMergeInLoops ; Analyze, Performances, ClearPHP, Codacy, Simple)
  * Non Ascii Variables (Variables/VariableNonascii ; Analyze, Codacy)
  * Non Static Methods Called In A Static (Classes/NonStaticMethodsCalledStatic ; Analyze, CompatibilityPHP56, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, Codacy, Simple, CompatibilityPHP73)
  * Non-constant Index In Array (Arrays/NonConstantArray ; Analyze, Codacy, Simple)
  * Non-lowercase Keywords (Php/UpperCaseKeyword ; Coding Conventions, RadwellCodes)
  * Nonce Creation (Wordpress/NonceCreation ; Wordpress)
  * Normal Methods (Classes/NormalMethods ; Appcontent)
  * Normal Property (Classes/NormalProperty ; Appcontent)
  * Not Definitions Only (Files/NotDefinitionsOnly ; Analyze, Codacy)
  * Not Not (Structures/NotNot ; Analyze, OneFile, Codacy, Simple)
  * Not Same Name As File (Classes/NotSameNameAsFile ; )
  * Not Same Name As File (Classes/SameNameAsFile ; Internal)
  * Nowdoc Delimiter Glossary (Type/Nowdoc ; Appinfo)
  * Null Coalesce (Php/NullCoalesce ; Appinfo)
  * Null On New (Classes/NullOnNew ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, OneFile, Simple)
  * Objects Don't Need References (Structures/ObjectReferences ; Analyze, OneFile, ClearPHP, Codacy, Simple)
  * Octal Glossary (Type/Octal ; Appinfo)
  * Old Style Constructor (Classes/OldStyleConstructor ; Analyze, Appinfo, OneFile, ClearPHP, Codacy, Simple)
  * Old Style __autoload() (Php/oldAutoloadUsage ; Analyze, OneFile, ClearPHP, Codacy, Simple)
  * One Letter Functions (Functions/OneLetterFunctions ; Analyze, Codacy)
  * One Object Operator Per Line (Classes/OneObjectOperatorPerLine ; Calisthenics)
  * One Variable String (Type/OneVariableStrings ; Analyze, RadwellCodes, Codacy, Simple)
  * Only Static Methods (Classes/OnlyStaticMethods ; Internal)
  * Only Variable Returned By Reference (Structures/OnlyVariableReturnedByReference ; Analyze, Codacy, Simple)
  * Or Die (Structures/OrDie ; Analyze, OneFile, ClearPHP, Codacy, Simple)
  * Overwriting Variable (Variables/Overwriting ; Analyze, Codacy)
  * Overwritten Class Const (Classes/OverwrittenConst ; Appinfo)
  * Overwritten Exceptions (Exceptions/OverwriteException ; Analyze, Codacy, Simple)
  * Overwritten Literals (Variables/OverwrittenLiterals ; Analyze, Codacy)
  * PHP 7.0 New Classes (Php/Php70NewClasses ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP71)
  * PHP 7.0 New Interfaces (Php/Php70NewInterfaces ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP71)
  * PHP 7.0 Removed Directives (Php/Php70RemovedDirective ; CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
  * PHP 7.1 Removed Directives (Php/Php71RemovedDirective ; CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
  * PHP 70 Removed Functions (Php/Php70RemovedFunctions ; CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
  * PHP Arrays Index (Arrays/Phparrayindex ; Appinfo)
  * PHP Bugfixes (Php/MiddleVersion ; Appinfo, Appcontent)
  * PHP Constant Usage (Constants/PhpConstantUsage ; Appinfo)
  * PHP Handlers Usage (Php/SetHandlers ; )
  * PHP Interfaces (Interfaces/Php ; )
  * PHP Keywords As Names (Php/ReservedNames ; Analyze, Codacy, Simple)
  * PHP Sapi (Type/Sapi ; Internal)
  * PHP Variables (Variables/VariablePhp ; )
  * PHP5 Indirect Variable Expression (Variables/Php5IndirectExpression ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53)
  * PHP7 Dirname (Structures/PHP7Dirname ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, Suggestions)
  * Parent, Static Or Self Outside Class (Classes/PssWithoutClass ; Analyze, Codacy, Simple)
  * Parenthesis As Parameter (Php/ParenthesisAsParameter ; CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
  * Pear Usage (Php/PearUsage ; Appinfo, Appcontent)
  * Perl Regex (Type/Pcre ; Inventory)
  * Php 7 Indirect Expression (Variables/Php7IndirectExpression ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP70)
  * Php 71 New Classes (Php/Php71NewClasses ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP73)
  * Php7 Relaxed Keyword (Php/Php7RelaxedKeyword ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53)
  * Phpinfo (Structures/PhpinfoUsage ; Analyze, OneFile, Codacy, Simple)
  * Pre-increment (Performances/PrePostIncrement ; Analyze, Performances, Codacy, Simple)
  * Preprocess Arrays (Arrays/ShouldPreprocess ; Analyze, Codacy, Simple)
  * Preprocessable (Structures/ShouldPreprocess ; Analyze, Codacy)
  * Print And Die (Structures/PrintAndDie ; Analyze, Codacy, Simple)
  * Property Could Be Private Property (Classes/CouldBePrivate ; Analyze, Codacy)
  * Property Is Modified (Classes/IsModified ; Internal)
  * Property Is Read (Classes/IsRead ; Internal)
  * Property Names (Classes/PropertyDefinition ; Appinfo)
  * Property Used Above (Classes/PropertyUsedAbove ; Internal)
  * Property Used Below (Classes/PropertyUsedBelow ; Internal)
  * Property Variable Confusion (Structures/PropertyVariableConfusion ; Analyze, Codacy, Simple)
  * Queries In Loops (Structures/QueriesInLoop ; Analyze, OneFile, Codacy, Simple, Level 1)
  * Random Without Try (Structures/RandomWithoutTry ; Security)
  * Real Functions (Functions/RealFunctions ; Appcontent)
  * Real Glossary (Type/Real ; Appinfo)
  * Real Variables (Variables/RealVariables ; Appcontent)
  * Recursive Functions (Functions/Recursive ; Appinfo)
  * Redeclared PHP Functions (Functions/RedeclaredPhpFunction ; Analyze, Appinfo, Codacy, Simple)
  * Redefined Class Constants (Classes/RedefinedConstants ; Analyze, Codacy, Simple)
  * Redefined Default (Classes/RedefinedDefault ; Analyze, Codacy, Simple)
  * Redefined Methods (Classes/RedefinedMethods ; Appinfo)
  * Redefined PHP Traits (Traits/Php ; Appinfo)
  * Redefined Property (Classes/RedefinedProperty ; Appinfo)
  * References (Variables/References ; Appinfo)
  * Register Globals (Security/RegisterGlobals ; Security)
  * Relay Function (Functions/RelayFunction ; Analyze, Codacy)
  * Repeated print() (Structures/RepeatedPrint ; Analyze, Codacy, Simple)
  * Reserved Keywords In PHP 7 (Php/ReservedKeywords7 ; CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
  * Resources Usage (Structures/ResourcesUsage ; Appinfo)
  * Results May Be Missing (Structures/ResultMayBeMissing ; Analyze, Codacy, Simple)
  * Return ;  (Structures/ReturnVoid ; )
  * Return True False (Structures/ReturnTrueFalse ; Analyze, Codacy, Simple, Level 1)
  * Return Typehint Usage (Php/ReturnTypehintUsage ; Appinfo, Internal)
  * Return With Parenthesis (Php/ReturnWithParenthesis ; Coding Conventions, PHP recommendations)
  * Safe Curl Options (Security/CurlOptions ; Security)
  * Same Conditions (Structures/SameConditions ; Analyze, Codacy, Simple)
  * Scalar Typehint Usage (Php/ScalarTypehintUsage ; Appinfo, CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53)
  * Sensitive Argument (Security/SensitiveArgument ; Internal)
  * Sequences In For (Structures/SequenceInFor ; Analyze, Codacy)
  * Setlocale() Uses Constants (Structures/SetlocaleNeedsConstants ; CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
  * Several Instructions On The Same Line (Structures/OneLineTwoInstructions ; Analyze, Codacy)
  * Shell Usage (Structures/ShellUsage ; Appinfo)
  * Short Open Tags (Php/ShortOpenTagRequired ; Analyze, Codacy, Simple)
  * Short Syntax For Arrays (Arrays/ArrayNSUsage ; Appinfo, CompatibilityPHP53)
  * Should Be Single Quote (Type/ShouldBeSingleQuote ; Coding Conventions, ClearPHP)
  * Should Chain Exception (Structures/ShouldChainException ; Analyze, Codacy, Simple)
  * Should Make Alias (Namespaces/ShouldMakeAlias ; Analyze, OneFile, ZendFramework, Codacy, Simple)
  * Should Typecast (Type/ShouldTypecast ; Analyze, OneFile, Codacy, Simple)
  * Should Use Coalesce (Php/ShouldUseCoalesce ; Analyze, Codacy, Simple, Suggestions)
  * Should Use Constants (Functions/ShouldUseConstants ; Analyze, Codacy, Simple)
  * Should Use Local Class (Classes/ShouldUseThis ; Analyze, ClearPHP, Codacy, Simple)
  * Should Use Prepared Statement (Security/ShouldUsePreparedStatement ; Analyze, Security, Codacy, Simple)
  * Silently Cast Integer (Type/SilentlyCastInteger ; Analyze, Codacy, Simple)
  * Simple Global Variable (Php/GlobalWithoutSimpleVariable ; CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
  * Simplify Regex (Structures/SimplePreg ; Performances)
  * Slow Functions (Performances/SlowFunctions ; Performances, OneFile)
  * Special Integers (Type/SpecialIntegers ; Inventory)
  * Static Loop (Structures/StaticLoop ; Analyze, Codacy, Simple)
  * Static Methods (Classes/StaticMethods ; Appinfo)
  * Static Methods Called From Object (Classes/StaticMethodsCalledFromObject ; Analyze, Codacy, Simple)
  * Static Methods Can't Contain $this (Classes/StaticContainsThis ; Analyze, ClearPHP, Codacy, Simple, Level 1)
  * Static Names (Classes/StaticCpm ; )
  * Static Properties (Classes/StaticProperties ; Appinfo)
  * Static Variables (Variables/StaticVariables ; Appinfo)
  * Strict Comparison With Booleans (Structures/BooleanStrictComparison ; Analyze, Codacy, Simple)
  * String May Hold A Variable (Type/StringHoldAVariable ; Analyze, Codacy, Simple)
  * String glossary (Type/String ; )
  * Strpos Comparison (Structures/StrposCompare ; Analyze, PHP recommendations, ClearPHP, Codacy, Simple)
  * Super Global Usage (Php/SuperGlobalUsage ; Appinfo)
  * Super Globals Contagion (Security/SuperGlobalContagion ; Internal)
  * Switch To Switch (Structures/SwitchToSwitch ; Analyze, RadwellCodes, Codacy, Simple)
  * Switch With Too Many Default (Structures/SwitchWithMultipleDefault ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, ClearPHP, Codacy, Simple)
  * Switch Without Default (Structures/SwitchWithoutDefault ; Analyze, ClearPHP, Codacy, Simple)
  * Ternary In Concat (Structures/TernaryInConcat ; Analyze, Codacy, Simple)
  * Test Class (Classes/TestClass ; Appinfo)
  * Throw (Php/ThrowUsage ; Appinfo)
  * Throw Functioncall (Exceptions/ThrowFunctioncall ; Analyze, Codacy, Simple, Level 1)
  * Throw In Destruct (Classes/ThrowInDestruct ; Analyze, Codacy, Simple)
  * Thrown Exceptions (Exceptions/ThrownExceptions ; Appinfo)
  * Throws An Assignement (Structures/ThrowsAndAssign ; Analyze, Codacy, Simple)
  * Timestamp Difference (Structures/TimestampDifference ; Analyze, Codacy, Simple)
  * Too Many Children (Classes/TooManyChildren ; )
  * Trait Methods (Traits/TraitMethod ; )
  * Trait Names (Traits/Traitnames ; Appinfo)
  * Traits Usage (Traits/TraitUsage ; Appinfo)
  * Trigger Errors (Php/TriggerErrorUsage ; Appinfo)
  * True False Inconsistant Case (Constants/InconsistantCase ; Preferences)
  * Try With Finally (Structures/TryFinally ; Appinfo, Internal)
  * Typehints (Functions/Typehints ; Appinfo)
  * URL List (Type/Url ; Inventory)
  * Uncaught Exceptions (Exceptions/UncaughtExceptions ; Analyze, Codacy)
  * Unchecked Resources (Structures/UncheckedResources ; Analyze, ClearPHP, Codacy, Simple)
  * Undefined Caught Exceptions (Exceptions/CaughtButNotThrown ; Dead code)
  * Undefined Class Constants (Classes/UndefinedConstants ; Analyze, Codacy)
  * Undefined Classes (Classes/UndefinedClasses ; Analyze, Codacy)
  * Undefined Classes (ZendF/UndefinedClasses ; )
  * Undefined Constants (Constants/UndefinedConstants ; Analyze, Codacy, Simple)
  * Undefined Functions (Functions/UndefinedFunctions ; Analyze, Codacy)
  * Undefined Interfaces (Interfaces/UndefinedInterfaces ; Analyze, Codacy)
  * Undefined Parent (Classes/UndefinedParentMP ; Analyze, Codacy, Simple)
  * Undefined Properties (Classes/UndefinedProperty ; Analyze, ClearPHP, Codacy, Simple)
  * Undefined Trait (Traits/UndefinedTrait ; Analyze, Codacy)
  * Undefined Zend 1.10 (ZendF/UndefinedClass110 ; ZendFramework)
  * Undefined Zend 1.11 (ZendF/UndefinedClass111 ; ZendFramework)
  * Undefined Zend 1.12 (ZendF/UndefinedClass112 ; ZendFramework)
  * Undefined Zend 1.8 (ZendF/UndefinedClass18 ; ZendFramework)
  * Undefined Zend 1.9 (ZendF/UndefinedClass19 ; ZendFramework)
  * Undefined static:: Or self:: (Classes/UndefinedStaticMP ; Analyze, Codacy, Simple)
  * Unescaped Variables In Templates (Wordpress/UnescapedVariables ; Wordpress)
  * Unicode Blocks (Type/UnicodeBlock ; Inventory)
  * Unicode Escape Partial (Php/UnicodeEscapePartial ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53)
  * Unicode Escape Syntax (Php/UnicodeEscapeSyntax ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53)
  * Unknown Directive Name (Php/DirectiveName ; Analyze, Codacy)
  * Unkown Regex Options (Structures/UnknownPregOption ; Analyze, Codacy, Simple)
  * Unpreprocessed Values (Structures/Unpreprocessed ; Analyze, OneFile, ClearPHP, Codacy, Simple)
  * Unreachable Code (Structures/UnreachableCode ; Analyze, Dead code, OneFile, ClearPHP, Codacy, Simple)
  * Unresolved Catch (Classes/UnresolvedCatch ; Dead code, ClearPHP)
  * Unresolved Classes (Classes/UnresolvedClasses ; Analyze, Codacy)
  * Unresolved Instanceof (Classes/UnresolvedInstanceof ; Analyze, Dead code, ClearPHP, Codacy, Simple)
  * Unresolved Use (Namespaces/UnresolvedUse ; Analyze, ClearPHP, Codacy, Simple)
  * Unserialize Second Arg (Security/UnserializeSecondArg ; Security)
  * Unset Arguments (Functions/UnsetOnArguments ; OneFile)
  * Unset In Foreach (Structures/UnsetInForeach ; Analyze, Dead code, OneFile, Codacy, Simple)
  * Unthrown Exception (Exceptions/Unthrown ; Analyze, Dead code, ClearPHP, Codacy, Simple)
  * Unused Arguments (Functions/UnusedArguments ; Analyze, Codacy, Simple)
  * Unused Classes (Classes/UnusedClass ; Analyze, Dead code, Codacy, Simple)
  * Unused Constants (Constants/UnusedConstants ; Analyze, Dead code, Codacy, Simple)
  * Unused Functions (Functions/UnusedFunctions ; Analyze, Dead code, Codacy, Simple)
  * Unused Global (Structures/UnusedGlobal ; Analyze, Codacy, Simple)
  * Unused Interfaces (Interfaces/UnusedInterfaces ; Analyze, Dead code, Codacy, Simple)
  * Unused Label (Structures/UnusedLabel ; Analyze, Dead code, Codacy, Simple)
  * Unused Methods (Classes/UnusedMethods ; Analyze, Dead code, Codacy, Simple)
  * Unused Private Properties (Classes/UnusedPrivateProperty ; Analyze, Dead code, OneFile, Codacy, Simple)
  * Unused Protected Methods (Classes/UnusedProtectedMethods ; Dead code)
  * Unused Static Methods (Classes/UnusedPrivateMethod ; Analyze, Dead code, OneFile, Codacy, Simple)
  * Unused Traits (Traits/UnusedTrait ; Analyze, Codacy, Simple)
  * Unused Use (Namespaces/UnusedUse ; Analyze, Dead code, ClearPHP, Codacy, Simple)
  * Unusual Case For PHP Functions (Php/UpperCaseFunction ; Coding Conventions)
  * Unverified Nonce (Wordpress/UnverifiedNonce ; Wordpress)
  * Usage Of class_alias() (Classes/ClassAliasUsage ; Appinfo)
  * Use $wpdb Api (Wordpress/UseWpdbApi ; Wordpress)
  * Use === null (Php/IsnullVsEqualNull ; Analyze, OneFile, RadwellCodes, Codacy, Simple)
  * Use Cli (Php/UseCli ; Appinfo)
  * Use Const And Functions (Namespaces/UseFunctionsConstants ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP53)
  * Use Constant (Structures/UseConstant ; PHP recommendations)
  * Use Constant As Arguments (Functions/UseConstantAsArguments ; Analyze, Codacy, Simple)
  * Use Instanceof (Classes/UseInstanceof ; Analyze, Codacy, Simple)
  * Use Lower Case For Parent, Static And Self (Php/CaseForPSS ; CompatibilityPHP54, CompatibilityPHP53, Codacy)
  * Use Nullable Type (Php/UseNullableType ; Appinfo, CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
  * Use Object Api (Php/UseObjectApi ; Analyze, ClearPHP, Codacy, Simple)
  * Use Pathinfo (Php/UsePathinfo ; Analyze, Codacy, Simple)
  * Use System Tmp (Structures/UseSystemTmp ; Analyze, Codacy, Simple)
  * Use This (Classes/UseThis ; Internal)
  * Use Web (Php/UseWeb ; Appinfo)
  * Use With Fully Qualified Name (Namespaces/UseWithFullyQualifiedNS ; Analyze, Coding Conventions, PHP recommendations, Codacy, Simple)
  * Use const (Constants/ConstRecommended ; Analyze, Coding Conventions, Codacy)
  * Use password_hash() (Php/Password55 ; CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
  * Use random_int() (Php/BetterRand ; Analyze, Security, CompatibilityPHP71, CompatibilityPHP72, Codacy, Simple, CompatibilityPHP73)
  * Used Classes (Classes/UsedClass ; Internal)
  * Used Functions (Functions/UsedFunctions ; Internal)
  * Used Interfaces (Interfaces/UsedInterfaces ; Internal)
  * Used Methods (Classes/UsedMethods ; Internal)
  * Used Once Variables (In Scope) (Variables/VariableUsedOnceByContext ; Analyze, OneFile, ClearPHP, Codacy, Simple)
  * Used Once Variables (Variables/VariableUsedOnce ; Analyze, OneFile, Codacy, Simple)
  * Used Protected Method (Classes/UsedProtectedMethod ; Dead code)
  * Used Static Methods (Classes/UsedPrivateMethod ; Internal)
  * Used Static Properties (Classes/UsedPrivateProperty ; Internal)
  * Used Trait (Traits/UsedTrait ; Internal)
  * Used Use (Namespaces/UsedUse ; )
  * Useless Abstract Class (Classes/UselessAbstract ; Analyze, Codacy, Simple)
  * Useless Brackets (Structures/UselessBrackets ; Analyze, RadwellCodes, Codacy, Simple)
  * Useless Constructor (Classes/UselessConstructor ; Analyze, Codacy, Simple)
  * Useless Final (Classes/UselessFinal ; Analyze, OneFile, ClearPHP, Codacy, Simple)
  * Useless Global (Structures/UselessGlobal ; Analyze, OneFile, Codacy, Simple)
  * Useless Instructions (Structures/UselessInstruction ; Analyze, OneFile, ClearPHP, Codacy, Simple, Level 1)
  * Useless Interfaces (Interfaces/UselessInterfaces ; Analyze, ClearPHP, Codacy, Simple)
  * Useless Parenthesis (Structures/UselessParenthesis ; Analyze, Codacy, Simple)
  * Useless Return (Functions/UselessReturn ; Analyze, OneFile, Codacy, Simple)
  * Useless Switch (Structures/UselessSwitch ; Analyze, Codacy, Simple)
  * Useless Unset (Structures/UselessUnset ; Analyze, OneFile, ClearPHP, Codacy, Simple)
  * Uses Default Values (Functions/UsesDefaultArguments ; Analyze, Codacy, Simple)
  * Uses Environnement (Php/UsesEnv ; Appinfo, Appcontent)
  * Using $this Outside A Class (Classes/UsingThisOutsideAClass ; Analyze, CompatibilityPHP71, CompatibilityPHP72, Codacy, Simple, CompatibilityPHP73)
  * Using Short Tags (Structures/ShortTags ; Appinfo)
  * Usort Sorting In PHP 7.0 (Php/UsortSorting ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP53, CompatibilityPHP70, CompatibilityPHP71)
  * Var (Classes/OldStyleVar ; Analyze, OneFile, ClearPHP, Codacy, Simple, Level 1)
  * Variable Constants (Constants/VariableConstant ; Appinfo)
  * Variable Is Modified (Variables/IsModified ; Internal)
  * Variable Is Read (Variables/IsRead ; Internal)
  * Variables Names (Variables/Variablenames ; )
  * Variables Variables (Variables/VariableVariables ; Appinfo)
  * Variables With Long Names (Variables/VariableLong ; )
  * Variables With One Letter Names (Variables/VariableOneLetter ; )
  * While(List() = Each()) (Structures/WhileListEach ; Analyze, Performances, OneFile, Codacy, Simple, Suggestions)
  * Wpdb Best Usage (Wordpress/WpdbBestUsage ; Wordpress)
  * Written Only Variables (Variables/WrittenOnlyVariable ; Analyze, OneFile, Codacy, Simple)
  * Wrong Class Location (ZendF/NotInThatPath ; ZendFramework)
  * Wrong Number Of Arguments (Functions/WrongNumberOfArguments ; Analyze, OneFile, Codacy, Simple)
  * Wrong Number Of Arguments In Methods (Functions/WrongNumberOfArgumentsMethods ; OneFile)
  * Wrong Optional Parameter (Functions/WrongOptionalParameter ; Analyze, Codacy, Simple, Level 1)
  * Wrong Parameter Type (Php/InternalParameterType ; Analyze, OneFile, Codacy, Simple)
  * Wrong fopen() Mode (Php/FopenMode ; Analyze, Codacy)
  * Yield From Usage (Php/YieldFromUsage ; Appinfo, Appcontent)
  * Yield Usage (Php/YieldUsage ; Appinfo, Appcontent)
  * Yoda Comparison (Structures/YodaComparison ; Coding Conventions)
  * Zend Classes (ZendF/ZendClasses ; Appinfo, ZendFramework)
  * __debugInfo() usage (Php/debugInfoUsage ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP53)
  * __halt_compiler (Php/Haltcompiler ; Appinfo)
  * __toString() Throws Exception (Structures/toStringThrowsException ; Analyze, OneFile, Codacy, Simple)
  * charger_fonction() (Spip/chargerFonction ; )
  * crypt() Without Salt (Structures/CryptWithoutSalt ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, Codacy, CompatibilityPHP73)
  * error_reporting() With Integers (Structures/ErrorReportingWithInteger ; Analyze, Codacy, Simple)
  * eval() Without Try (Structures/EvalWithoutTry ; Analyze, Codacy, Simple)
  * ext/0mq (Extensions/Extzmq ; Appinfo)
  * ext/amqp (Extensions/Extamqp ; Appinfo)
  * ext/apache (Extensions/Extapache ; Appinfo)
  * ext/apc (Extensions/Extapc ; Appinfo, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, Codacy, CompatibilityPHP73)
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
  * ext/ereg (Extensions/Extereg ; Appinfo, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
  * ext/ev (Extensions/Extev ; Appinfo)
  * ext/event (Extensions/Extevent ; Appinfo)
  * ext/exif (Extensions/Extexif ; Appinfo)
  * ext/expect (Extensions/Extexpect ; Appinfo)
  * ext/fann (Extensions/Extfann ; Appinfo, Codacy)
  * ext/fdf (Extensions/Extfdf ; Appinfo, CompatibilityPHP53, Codacy)
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
  * ext/mcrypt (Extensions/Extmcrypt ; Appinfo, CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
  * ext/memcache (Extensions/Extmemcache ; Appinfo)
  * ext/memcached (Extensions/Extmemcached ; Appinfo)
  * ext/ming (Extensions/Extming ; Appinfo, CompatibilityPHP53)
  * ext/mongo (Extensions/Extmongo ; Appinfo)
  * ext/mssql (Extensions/Extmssql ; Appinfo)
  * ext/mysql (Extensions/Extmysql ; Appinfo, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, Codacy, CompatibilityPHP73)
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
  * ext/recode (Extensions/Extrecode ; Appinfo, Portability)
  * ext/redis (Extensions/Extredis ; Appinfo)
  * ext/reflection (Extensions/Extreflection ; Appinfo)
  * ext/runkit (Extensions/Extrunkit ; Appinfo)
  * ext/sem (Extensions/Extsem ; Appinfo)
  * ext/session (Extensions/Extsession ; Appinfo)
  * ext/shmop (Extensions/Extshmop ; Appinfo)
  * ext/simplexml (Extensions/Extsimplexml ; Appinfo)
  * ext/snmp (Extensions/Extsnmp ; Appinfo)
  * ext/soap (Extensions/Extsoap ; Appinfo)
  * ext/sockets (Extensions/Extsockets ; Appinfo)
  * ext/spl (Extensions/Extspl ; Appinfo)
  * ext/sqlite (Extensions/Extsqlite ; Appinfo, Codacy)
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
  * func_get_arg() Modified (Functions/funcGetArgModified ; Analyze, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, Codacy, Simple, CompatibilityPHP73)
  * include_once() Usage (Structures/OnceUsage ; Analyze, Appinfo, Codacy)
  * list() May Omit Variables (Structures/ListOmissions ; Analyze, Codacy, Simple)
  * mcrypt_create_iv() With Default Values (Structures/McryptcreateivWithoutOption ; CompatibilityPHP54, CompatibilityPHP55, CompatibilityPHP56, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, Codacy, CompatibilityPHP73)
  * parse_str() Warning (Security/parseUrlWithoutParameters ; Security)
  * preg_match_all() Flag (Php/PregMatchAllFlag ; Analyze, Codacy, Simple)
  * preg_replace With Option e (Structures/pregOptionE ; Analyze, Security, CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, Codacy, Simple, CompatibilityPHP73)
  * set_exception_handler() Warning (Php/SetExceptionHandlerPHP7 ; CompatibilityPHP70, CompatibilityPHP71, CompatibilityPHP72, CompatibilityPHP73)
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

* ` <http://mnapoli.fr/using-non-breakable-spaces-in-test-method-names/>`_
* ` <https://en.wikipedia.org/wiki/Secure_Hash_Algorithms>`_
* ` <https://wiki.php.net/rfc/list-syntax-trailing-commas>`_
* `1003.1-2008 - IEEE Standard for Information Technology - Portable Operating System Interface (POSIX(R)) <https://standards.ieee.org/findstds/standard/1003.1-2008.html>`_
* `[blog] array_column() <https://benramsey.com/projects/array-column/>`_
* `__toString() <http://php.net/manual/en/language.oop5.magic.php#object.tostring>`_
* `A PHP extension for Redis <https://github.com/phpredis/phpredis/>`_
* `Alternative PHP Cache <http://php.net/apc>`_
* `Alternative syntax <http://php.net/manual/en/control-structures.alternative-syntax.php>`_
* `ansible <http://docs.ansible.com/ansible/intro_installation.html>`_
* `Apache <http://php.net/manual/en/book.apache.php>`_
* `APCU <http://www.php.net/manual/en/book.apcu.php>`_
* `Aronduby Dump <https://github.com/aronduby/dump>`_
* `Array <http://php.net/manual/en/function.types.array.php>`_
* `Array <http://php.net/manual/en/language.types.array.php>`_
* `array <http://php.net/manual/en/language.types.array.php>`_
* `Arrays <http://php.net/manual/en/book.array.php>`_
* `Autoloading Classe <http://php.net/manual/en/language.oop5.autoload.php>`_
* `Avoid optional services as much as possible <http://bestpractices.thecodingmachine.com/php/design_beautiful_classes_and_methods.html#avoid-optional-services-as-much-as-possible>`_
* `Backward incompatible changes PHP 7.0 <http://php.net/manual/en/migration70.incompatible.php>`_
* `bazaar <http://bazaar.canonical.com/en/>`_
* `BC Math Functions <http://www.php.net/bcmath>`_
* `browscap <http://browscap.org/>`_
* `Bzip2 Functions <http://nl1.php.net/manual/en/ref.bzip2.php>`_
* `Cairo Graphics Library <https://cairographics.org/>`_
* `Cake 3.0 migration guide <http://book.cakephp.org/3.0/en/appendices/3-0-migration-guide.html>`_
* `Cake 3.2 migration guide <http://book.cakephp.org/3.0/en/appendices/3-2-migration-guide.html>`_
* `Cake 3.3 migration guide <http://book.cakephp.org/3.0/en/appendices/3-3-migration-guide.html>`_
* `CakePHP <https://www.cakephp.org/>`_
* `Callback / callable <http://php.net/manual/en/language.types.callable.php>`_
* `Changes to variable handling <http://php.net/manual/en/migration70.incompatible.php>`_
* `Class Reference/wpdb <https://codex.wordpress.org/Class_Reference/wpdb>`_
* `Classes abstraction <http://php.net/abstract>`_
* `Codeigniter <https://codeigniter.com/>`_
* `COM and .Net (Windows) <http://php.net/manual/en/book.com.php>`_
* `Comparison Operators <http://php.net/manual/en/language.operators.comparison.php>`_
* `composer <https://getcomposer.org/>`_
* `Cookies <http://php.net/manual/en/features.cookies.php>`_
* `Courrier Anti-pattern <https://r.je/oop-courier-anti-pattern.html>`_
* `crc32() <http://php.net/crc32>`_
* `Ctype funtions <http://php.net/manual/en/ref.ctype.php>`_
* `curl <http://www.php.net/curl>`_
* `Curl for PHP <http://php.net/manual/en/book.curl.php>`_
* `Cyrus <http://php.net/manual/en/book.cyrus.php>`_
* `Data filtering <http://php.net/manual/en/book.filter.php>`_
* `Data structures <http://docs.php.net/manual/en/book.ds.php>`_
* `Database (dbm-style) Abstraction Layer <http://php.net/manual/en/book.dba.php>`_
* `Date and Time <http://php.net/manual/en/book.datetime.php>`_
* `DCDFLIB <https://people.sc.fsu.edu/~jburkardt/c_src/cdflib/cdflib.html>`_
* `declare <http://php.net/manual/en/control-structures.declare.php>`_
* `Dependency Injection Smells <http://seregazhuk.github.io/2017/05/04/di-smells/>`_
* `DIO <http://php.net/manual/en/refs.fileprocess.file.php>`_
* `Docker <http://www.docker.com/>`_
* `Docker image <https://hub.docker.com/r/exakat/exakat/>`_
* `docker PHP container <https://hub.docker.com/_/php/>`_
* `Document Object Model <http://php.net/manual/en/book.dom.php>`_
* `dotdeb instruction <https://www.dotdeb.org/instructions/>`_
* `download <https://www.exakat.io/download-exakat/>`_
* `Eaccelerator <http://eaccelerator.net/>`_
* `Empty interfaces are bad practice <https://r.je/empty-interfaces-bad-practice.html>`_
* `Enchant spelling library <http://php.net/manual/en/book.enchant.php>`_
* `Ereg <http://php.net/manual/en/function.ereg.php>`_
* `Ev <http://php.net/manual/en/book.ev.php>`_
* `Exakat <http://www.exakat.io/>`_
* `Exakat cloud <https://www.exakat.io/exakat-cloud/>`_
* `Exakat SAS <https://www.exakat.io/get-php-expertise/>`_
* `exakat.phar` archive from `exakat.io <http://www.exakat.io/>`_
* `Exceptions <http://php.net/manual/en/language.exceptions.php>`_
* `exif <http://php.net/manual/en/book.exif.php>`_
* `expect <http://php.net/manual/en/book.expect.php>`_
* `ext-http <https://github.com/m6w6/ext-http>`_
* `ext/ast <https://pecl.php.net/package/ast>`_
* `ext/gender <http://php.net/manual/en/book.gender.php>`_
* `ext/inotify <http://php.net/manual/en/book.inotify.php>`_
* `ext/lua <http://php.net/manual/en/book.lua.php>`_
* `ext/mcrypt <http://www.php.net/manual/en/book.mcrypt.php>`_
* `ext/memcached <http://php.net/manual/en/book.memcached.php>`_
* `ext/OpenSSL <http://php.net/manual/en/book.openssl.php>`_
* `ext/readline <http://php.net/manual/en/book.readline.php>`_
* `ext/recode <http://www.php.net/manual/en/book.recode.php>`_
* `ext/sqlite <http://php.net/manual/en/book.sqlite.php>`_
* `ext/sqlite3 <http://php.net/manual/en/book.sqlite3.php>`_
* `extension FANN <http://php.net/manual/en/book.fann.php>`_
* `Ez <https://ez.no/>`_
* `FAM <http://oss.sgi.com/projects/fam/>`_
* `FastCGI Process Manager <http://php.net/fpm>`_
* `FDF <http://www.adobe.com/devnet/acrobat/fdftoolkit.html>`_
* `ffmpeg-php <http://ffmpeg-php.sourceforge.net/>`_
* `filesystem <http://www.php.net/manual/en/book.filesystem.php>`_
* `Filinfo <http://php.net/manual/en/book.fileinfo.php>`_
* `Final Keyword <http://php.net/manual/en/language.oop5.final.php>`_
* `Final keyword <http://php.net/manual/en/language.oop5.final.php>`_
* `Floats <http://php.net/manual/en/language.types.float.php>`_
* `Gearman on PHP <http://php.net/manual/en/book.gearman.php>`_
* `Generalize support of negative string offsets <https://wiki.php.net/rfc/negative-string-offsets>`_
* `GeoIP <http://php.net/manual/en/book.geoip.php>`_
* `Gettext <https://www.gnu.org/software/gettext/manual/gettext.html>`_
* `git <https://git-scm.com/>`_
* `Github <https://github.com/exakat/exakat>`_
* `Global Variables <https://codex.wordpress.org/Global_Variables>`_
* `GMP <http://php.net/manual/en/book.gmp.php>`_
* `Gnupg Function for PHP <http://www.php.net/manual/en/book.gnupg.php>`_
* `Goto <http://php.net/manual/en/control-structures.goto.php>`_
* `gremlin plug-in <https://github.com/thinkaurelius/neo4j-gremlin-plugin>`_
* `Group Use Declaration RFC <https://wiki.php.net/rfc/group_use_declarations>`_
* `GRPC <http://www.grpc.io/>`_
* `hash <http://www.php.net/hash>`_
* `HASH Message Digest Framework <http://www.php.net/manual/en/book.hash.php>`_
* `hg <https://www.mercurial-scm.org/>`_
* `How to fix Headers already sent error in PHP <http://stackoverflow.com/questions/8028957/how-to-fix-headers-already-sent-error-in-php>`_
* `Iconv <http://php.net/iconv>`_
* `ICU <http://site.icu-project.org/>`_
* `IIS Administration <http://www.php.net/manual/en/book.iisfunc.php>`_
* `Image Processing and GD <http://php.net/manual/en/book.image.php>`_
* `Imagick for PHP <http://php.net/manual/en/book.imagick.php>`_
* `IMAP <http://www.php.net/imap>`_
* `in_array() <http://php.net/in_array>`_
* `Integers <http://php.net/manual/en/language.types.integer.php>`_
* `Interfaces <http://php.net/manual/en/language.oop5.interfaces.php>`_
* `Internal Constructor Behavior <https://wiki.php.net/rfc/internal_constructor_behaviour>`_
* `isset <http://www.php.net/isset>`_
* `Isset Ternary <https://wiki.php.net/rfc/isset_ternary>`_
* `Joomla <http://www.joomla.org/>`_
* `Judy C library <http://judy.sourceforge.net/>`_
* `Kafka client for PHP <https://github.com/arnaud-lb/php-rdkafka>`_
* `Kerberos V <http://php.net/manual/en/book.kadm5.php>`_
* `Lapack <http://php.net/manual/en/book.lapack.php>`_
* `Laravel <http://www.lavarel.com/>`_
* `libevent <http://www.libevent.org/>`_
* `libmongoc <https://github.com/mongodb/mongo-c-driver>`_
* `libxml <http://www.php.net/manual/en/book.libxml.php>`_
* `list <http://php.net/manual/en/function.list.php>`_
* `List of function aliases <http://php.net/manual/en/aliases.php>`_
* `List of HTTP header fields <https://en.wikipedia.org/wiki/List_of_HTTP_header_fields>`_
* `List of Keywords <http://php.net/manual/en/reserved.keywords.php>`_
* `List of other reserved words <http://php.net/manual/en/reserved.other-reserved-words.php>`_
* `Logical Expressions in C/C++. Mistakes Made by Professionals <http://www.viva64.com/en/b/0390/>`_
* `Logical Operators <http://php.net/manual/en/language.operators.logical.php>`_
* `Magic Hashes <https://blog.whitehatsec.com/magic-hashes/>`_
* `Magic methods <http://php.net/manual/en/language.oop5.magic.php>`_
* `Mail related functions <http://www.php.net/manual/en/book.mail.php>`_
* `Marco Pivetta tweet <https://twitter.com/Ocramius/status/811504929357660160>`_
* `Math predefined constants <http://php.net/manual/en/math.constants.php>`_
* `Mathematical Functions <http://php.net/manual/en/book.math.php>`_
* `Mbstring <http://www.php.net/manual/en/book.mbstring.php>`_
* `Memcache on PHP <http://www.php.net/manual/en/book.memcache.php>`_
* `mhash <http://mhash.sourceforge.net/>`_
* `Microsoft SQL Server <http://www.php.net/manual/en/book.mssql.php>`_
* `Microsoft SQL Server Driver <http://php.net/sqlsrv>`_
* `Ming (flash) <http://www.libming.org/>`_
* `mongodb Driver <ext/mongo>`_
* `MySQL Improved Extension <http://php.net/manual/en/book.mysqli.php>`_
* `mysqli <http://php.net/manual/en/book.mysqli.php>`_
* `Ncurses Terminal Screen Control <http://php.net/manual/en/book.ncurses.php>`_
* `Neo4j <http://neo4j.com/>`_
* `Net SNMP <http://www.net-snmp.org/>`_
* `New features <http://php.net/manual/en/migration56.new-features.php>`_
* `No Dangling Reference <https://github.com/dseguy/clearPHP/blob/master/rules/no-dangling-reference.md>`_
* `Null Object Pattern <https://en.wikipedia.org/wiki/Null_Object_pattern#PHP>`_
* `Object Calisthenics <http://williamdurand.fr/2013/06/03/object-calisthenics/>`_
* `Object cloning <http://php.net/manual/en/language.oop5.cloning.php>`_
* `ODBC (Unified) <http://www.php.net/manual/en/book.uodbc.php>`_
* `OPcache functions <http://www.php.net/manual/en/book.opcache.php>`_
* `Operator precedence <http://php.net/manual/en/language.operators.precedence.php>`_
* `Oracle OCI8 <http://php.net/manual/en/book.oci8.php>`_
* `Original MySQL API <http://www.php.net/manual/en/book.mysql.php>`_
* `Output Buffering Control <http://php.net/manual/en/book.outcontrol.php>`_
* `Overload <http://php.net/manual/en/language.oop5.overloading.php#object.get>`_
* `Packagist <https://packagist.org/>`_
* `Parsekit <http://www.php.net/manual/en/book.parsekit.php>`_
* `Parsing and Lexing <http://php.net/manual/en/book.parle.php>`_
* `Passing by reference <http://php.net/manual/en/language.references.pass.php>`_
* `PCRE <http://php.net/pcre>`_
* `PEAR <http://pear.php.net/>`_
* `pecl crypto <https://pecl.php.net/package/crypto>`_
* `phar <http://www.php.net/manual/en/book.phar.php>`_
* `PHP 7.0 Backward incompatible changes <http://php.net/manual/en/migration70.incompatible.php>`_
* `PHP AMQP Binding Library <https://github.com/pdezwart/php-amqp>`_
* `PHP class name constant case sensitivity and PSR-11 <https://gist.github.com/bcremer/9e8d6903ae38a25784fb1985967c6056>`_
* `PHP Data Object <http://php.net/manual/en/book.pdo.php>`_
* `PHP extension for libsodium <https://github.com/jedisct1/libsodium-php>`_
* `PHP gmagick <http://www.php.net/manual/en/book.gmagick.php>`_
* `PHP Options/Info Functions <http://php.net/manual/en/ref.info.php>`_
* `PHP RFC: Allow abstract function override <https://wiki.php.net/rfc/allow-abstract-function-override>`_
* `PHP RFC: Deprecate and Remove Bareword (Unquoted) Strings <https://wiki.php.net/rfc/deprecate-bareword-strings>`_
* `PHP RFC: Scalar Type Hints <https://wiki.php.net/rfc/scalar_type_hints>`_
* `PHP Tags <http://php.net/manual/en/language.basic-syntax.phptags.php>`_
* `php-zbarcode <https://github.com/mkoppanen/php-zbarcode>`_
* `PostgreSQL <http://php.net/manual/en/book.pgsql.php>`_
* `Predefined Variables <http://php.net/manual/en/reserved.variables.php>`_
* `Prepare for PHP 7 error messages (part 3) <https://www.exakat.io/prepare-for-php-7-error-messages-part-3/>`_
* `Process Control <http://php.net/manual/en/book.pcntl.php>`_
* `proctitle <http://php.net/manual/en/book.proctitle.php>`_
* `Pspell <http://php.net/manual/en/book.pspell.php>`_
* `PSR-11 : Dependency injection container <https://github.com/container-interop/fig-standards/blob/master/proposed/container.md>`_
* `PSR-13 : Link definition interface <http://www.php-fig.org/psr/psr-13/>`_
* `PSR-16 : Common Interface for Caching Libraries <http://www.php-fig.org/psr/psr-16/>`_
* `PSR-3 : Logger Interface <http://www.php-fig.org/psr/psr-3/>`_
* `PSR-6 : Caching <http://www.php-fig.org/psr/psr-6/>`_
* `PSR7 <http://www.php-fig.org/psr/psr-7/>`_
* `Putting glob to the test <https://www.phparch.com/2010/04/putting-glob-to-the-test/>`_
* `Quick Start <https://github.com/zendframework/zend-mvc/blob/master/doc/book/quick-start.md>`_
* `References <http://php.net/references>`_
* `Reflection <http://php.net/manual/en/book.reflection.php>`_
* `Regular Expressions (Perl-Compatible) <http://php.net/manual/en/book.pcre.php>`_
* `Return Inside Finally Block <https://www.owasp.org/index.php/Return_Inside_Finally_Block>`_
* `RFC 7159 <http://www.faqs.org/rfcs/rfc7159>`_
* `RFC 7230 <https://tools.ietf.org/html/rfc7230>`_
* `RFC 822 (MIME) <http://www.faqs.org/rfcs/rfc822.html>`_
* `RFC 959 <http://www.faqs.org/rfcs/rfc959>`_
* `Routing <https://www.slimframework.com/docs/objects/router.html>`_
* `runkit <http://php.net/manual/en/book.runkit.php>`_
* `Scope Resolution Operator (::) <http://php.net/manual/en/language.oop5.paamayim-nekudotayim.php>`_
* `Semaphore <http://php.net/manual/en/book.sem.php>`_
* `Semaphore, Shared Memory and IPC <http://php.net/manual/en/book.sem.php>`_
* `Session <http://php.net/manual/en/book.session.php>`_
* `session_regenerateid() <http://php.net/session_regenerate_id>`_
* `Set-Cookie <https://developer.mozilla.org/en-US/docs/Web/HTTP/Headers/Set-Cookie>`_
* `Simplexml <http://php.net/manual/en/book.simplexml.php>`_
* `Single Function Exit Point <http://wiki.c2.com/?SingleFunctionExitPoint>`_
* `Slim <https://www.slimframework.com/>`_
* `SlimPHP <https://www.slimframework.com/>`_
* `SOAP <http://php.net/manual/en/book.soap.php>`_
* `Sockets <http://php.net/manual/en/book.sockets.php>`_
* `Specification pattern <https://en.wikipedia.org/wiki/Specification_pattern>`_
* `Sphinx Client <http://php.net/manual/en/book.sphinx.php>`_
* `sqlite3 <http://www.php.net/sqlite3>`_
* `SSH2 functions <http://php.net/manual/en/book.ssh2.php>`_
* `Standard PHP Library (SPL) <http://www.php.net/manual/en/book.spl.php>`_
* `static keyword <http://php.net/manual/en/language.oop5.static.php>`_
* `String functions <http://php.net/manual/en/ref.strings.php>`_
* `Suhosin.org <https://suhosin.org/>`_
* `svn <https://subversion.apache.org/>`_
* `Swoole <https://github.com/swoole/swoole-src>`_
* `Symfony <http://www.symfony.com/>`_
* `the docs online <http://exakat.readthedocs.io/en/latest/Rules.html>`_
* `The Linux NIS(YP)/NYS/NIS+ HOWTO <http://www.tldp.org/HOWTO/NIS-HOWTO/index.html>`_
* `The main PPA for PHP (5.6, 7.0, 7.1)  <https://launchpad.net/~ondrej/+archive/ubuntu/php>`_
* `tokenizer <http://www.php.net/tokenizer>`_
* `tokyo_tyrant <http://php.net/manual/en/book.tokyo-tyrant.php>`_
* `trader <https://pecl.php.net/package/trader>`_
* `Traits <http://php.net/manual/en/language.oop5.traits.php>`_
* `Traits <http://php.net/trait>`_
* `Tutorial 1: Let’s learn by example <https://docs.phalconphp.com/en/latest/reference/tutorial.html>`_
* `Type hinting for interfaces <http://phpenthusiast.com/object-oriented-php-tutorials/type-hinting-for-interfaces>`_
* `Understanding Dependency Injection <http://php-di.org/doc/understanding-di.html>`_
* `Unicode spaces <https://www.cs.tut.fi/~jkorpela/chars/spaces.html>`_
* `V8 Javascript Engine <https://bugs.chromium.org/p/v8/issues/list>`_
* `vagrant <https://www.vagrantup.com/docs/installation/>`_
* `Vagrant file <https://github.com/exakat/exakat-vagrant>`_
* `Variable Scope <http://php.net/manual/en/language.variables.scope.php>`_
* `Variables <http://php.net/manual/en/language.variables.basics.php>`_
* `Wddx on PHP <http://php.net/manual/en/intro.wddx.php>`_
* `When to declare classes final <http://ocramius.github.io/blog/when-to-declare-classes-final/>`_
* `Why 777 Folder Permissions are a Security Risk <https://www.spiralscripts.co.uk/Blog/why-777-folder-permissions-are-a-security-risk.html>`_
* `Why does PHP 5.2+ disallow abstract static class methods? <https://stackoverflow.com/questions/999066/why-does-php-5-2-disallow-abstract-static-class-methods>`_
* `wikidiff2 <https://www.mediawiki.org/wiki/Extension:Wikidiff2>`_
* `Wincache extension for PHP <http://www.php.net/wincache>`_
* `Wordpress <https://www.wordpress.org/>`_
* `Wordpress Functions <https://codex.wordpress.org/Function_Reference>`_
* `Wordpress Nonce <https://codex.wordpress.org/WordPress_Nonces>`_
* `xattr <http://php.net/manual/en/book.xattr.php>`_
* `xcache <https://xcache.lighttpd.net/>`_
* `Xdebug <https://xdebug.org/>`_
* `xdiff <http://php.net/manual/en/book.xdiff.php>`_
* `XML Parser <http://www.php.net/manual/en/book.xml.php>`_
* `XML-RPC <http://www.php.net/manual/en/book.xmlrpc.php>`_
* `xmlreader <http://www.php.net/manual/en/book.xmlreader.php>`_
* `XMLWriter <http://php.net/manual/en/book.xmlwriter.php>`_
* `XSL extension <http://php.net/manual/en/intro.xsl.php>`_
* `YAML Ain't Markup Language <http://www.yaml.org/>`_
* `Yii <http://www.yiiframework.com/>`_
* `Zend Framework 1.10 <https://framework.zend.com/manual/1.10/en/manual.html>`_
* `Zend Framework 1.11 <https://framework.zend.com/manual/1.11/en/manual.html>`_
* `Zend Framework 1.12 <https://framework.zend.com/manual/1.12/en/manual.html>`_
* `Zend Framework 1.8 <https://framework.zend.com/manual/1.8/en/index.html>`_
* `Zend Framework 1.9 <https://framework.zend.com/manual/1.9/en/index.html>`_
* `Zend Framework 2.0 <https://framework.zend.com/manual/2.0/en/index.html>`_
* `Zend Framework 2.1 <https://framework.zend.com/manual/2.1/en/index.html>`_
* `Zend Framework 2.2 <https://framework.zend.com/manual/2.2/en/index.html>`_
* `Zend Framework 2.3 <https://framework.zend.com/manual/2.3/en/index.html>`_
* `Zend Framework 2.4 <https://framework.zend.com/manual/2.4/en/index.html>`_
* `Zend Framework <http://framework.zend.com/>`_
* `Zend Framework Components <https://framework.zend.com/learn>`_
* `Zend Session <https://docs.zendframework.com/zend-session/manager/>`_
* `zend-authentication <https://github.com/zendframework/zend-authentication>`_
* `zend-barcode <https://github.com/zendframework/zend-barcode>`_
* `zend-captcha <https://github.com/zendframework/zend-captcha>`_
* `zend-code <https://github.com/zendframework/zend-code>`_
* `Zend-config <https://docs.zendframework.com/zend-config/>`_
* `zend-console <https://github.com/zendframework/zend-console>`_
* `zend-crypt <https://github.com/zendframework/zend-crypt>`_
* `zend-db <https://github.com/zendframework/zend-db>`_
* `zend-debug <https://github.com/zendframework/zend-debug>`_
* `zend-di <https://github.com/zendframework/zend-di>`_
* `zend-dom <https://github.com/zendframework/zend-dom>`_
* `zend-escaper <https://github.com/zendframework/zend-escaper>`_
* `zend-eventmanager <https://github.com/zendframework/zend-eventmanager>`_
* `zend-feed <https://github.com/zendframework/zend-feed>`_
* `zend-file <https://github.com/zendframework/zend-file>`_
* `zend-filter <https://github.com/zendframework/zend-filter>`_
* `zend-form <https://github.com/zendframework/zend-form>`_
* `zend-http <https://github.com/zendframework/zend-http>`_
* `zend-i18n <https://github.com/zendframework/zend-i18n>`_
* `zend-i18n-resources <https://github.com/zendframework/zend-i18n-resources>`_
* `zend-inputfilter <https://github.com/zendframework/zend-inputfilter>`_
* `zend-json <https://github.com/zendframework/zend-json>`_
* `zend-loader <https://github.com/zendframework/zend-loader>`_
* `zend-log <https://github.com/zendframework/zend-log>`_
* `zend-mail <https://github.com/zendframework/zend-mail>`_
* `zend-math <https://github.com/zendframework/zend-math>`_
* `zend-memory <https://github.com/zendframework/zend-memory>`_
* `zend-mime <https://github.com/zendframework/zend-mime>`_
* `zend-modulemanager <https://github.com/zendframework/zend-modulemanager>`_
* `zend-navigation <https://github.com/zendframework/zend-navigation>`_
* `zend-paginator <https://github.com/zendframework/zend-paginator>`_
* `zend-progressbar <https://github.com/zendframework/zend-progressbar>`_
* `zend-serializer <https://github.com/zendframework/zend-serializer>`_
* `zend-server <https://github.com/zendframework/zend-server>`_
* `zend-servicemanager <https://github.com/zendframework/zend-servicemanager>`_
* `zend-session <https://github.com/zendframework/zend-session>`_
* `zend-soap <https://github.com/zendframework/zend-soap>`_
* `zend-stdlib <https://github.com/zendframework/zend-stdlib>`_
* `zend-tag <https://github.com/zendframework/zend-tag>`_
* `zend-test <https://github.com/zendframework/zend-test>`_
* `zend-text <https://github.com/zendframework/zend-text>`_
* `zend-xmlrpc <https://github.com/zendframework/zend-xmlrpc>`_
* `ZeroMQ <http://zeromq.org/>`_
* `ZHprof Documentation <http://web.archive.org/web/20110514095512/http://mirror.facebook.net/facebook/xhprof/doc.html>`_
* `Zip <http://php.net/manual/en/book.zip.php>`_
* `Zlib <http://php.net/manual/en/book.zlib.php>`_


