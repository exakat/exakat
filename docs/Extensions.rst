.. Extensions:

Extensions
==========

Exakat support a system of extensions, that bring supplementary analysis, categories, data sources, configurations and reports to the current system. Extensions may focus on specific frameworks or platform, or be a simple way to package a predefined set of configuration. 

Extensions are PHP archives (`.phar` file), installed in the `ext` folder. Check the local extensions with `doctor`.

::

    exakat doctor
    
    
    
    exakat : 
        executable           : exakat
        version              : 1.5.7
        build                : 835
        exakat.ini           : ./config/exakat.ini,
                               config/remotes.json,
                               config/themes.ini
        graphdb              : gsneo4j
        reports              : Ambassador
        themes               : mine2
        extra themes         : mine,
                               special,
                               MonologExtra
        tokenslimit          : 1 000 000 000
        extensions           : Cakephp.phar,
                               Laravel.phar,
                               Melis.phar,
                               Monolog.phar,
                               Slim.phar,
                               Wordpress.phar



List of extensions : there are 7 extensions

* :ref:`Cakephp <extension-cakephp>`
* :ref:`Laravel <extension-laravel>`
* :ref:`Melis <extension-melis>`
* :ref:`Slim <extension-slim>`
* :ref:`Symfony <extension-symfony>`
* :ref:`Wordpress <extension-wordpress>`
* :ref:`ZendF <extension-zendf>`




Extension managements
---------------------

The main command to manage the extensions is `extension`. It has 4 different actions : 

* `local`
* `list`
* `install`
* `uninstall`

`local` : the local list of extensions
######################################

`list` : the remote list of extensions
######################################

`install` : the install command
###############################

This command installs a new extension. Check with `extension local` to know which are the locally installed extensions. 

::

    exakat extension install Laravel


You may also install the extensions manually, by downloading the .phar archive, and installing it in the `ext` folder.


`uninstall` : the remove command
################################

This command uninstalls a previously installed extension. Check with `extension local` to know which are the locally installed extensions. 

::

    exakat extension uninstall Laravel


You may also remove the extension manually, by removing them from the extension folder.



Details about the extension

.. _extension-cakephp:

Cakephp
#######

This is the CakePHP extension for Exakat. 

`CakePHP <http://www.cakephp.org>`_ is an open-source web, rapid development framework that makes building web applications simpler, faster and require less code.

This extension checks for classes, traits and interfaces, from version 3.0 to version 3.7. 

This extension includes 18 analyzers.

* CakePHP 2.5.0 Undefined Classes (Cakephp/Cakephp25)
* CakePHP 2.6.0 Undefined Classes (Cakephp/Cakephp26)
* CakePHP 2.7.0 Undefined Classes (Cakephp/Cakephp27)
* CakePHP 2.8.0 Undefined Classes (Cakephp/Cakephp28)
* CakePHP 2.9.0 Undefined Classes (Cakephp/Cakephp29)
* CakePHP 3.0 Deprecated Class (Cakephp/Cake30DeprecatedClass)
* CakePHP 3.0.0 Undefined Classes (Cakephp/Cakephp30)
* CakePHP 3.1.0 Undefined Classes (Cakephp/Cakephp31)
* CakePHP 3.2.0 Undefined Classes (Cakephp/Cakephp32)
* CakePHP 3.3 Deprecated Class (Cakephp/Cake33DeprecatedClass)
* CakePHP 3.3.0 Undefined Classes (Cakephp/Cakephp33)
* CakePHP 3.4.0 Undefined Classes (Cakephp/Cakephp34)
* CakePHP Unknown Classes (Cakephp/CakePHPMissing)
* CakePHP Used (Cakephp/CakePHPUsed)
* Deprecated Methodcalls in Cake 3.2 (Cakephp/Cake32DeprecatedMethods)
* Deprecated Methodcalls in Cake 3.3 (Cakephp/Cake33DeprecatedMethods)
* Deprecated Static calls in Cake 3.3 (Cakephp/Cake33DeprecatedStaticmethodcall)
* Deprecated Trait in Cake 3.3 (Cakephp/Cake33DeprecatedTraits)





.. _extension-laravel:

Laravel
#######

This is extension Laravel.

This extension includes 9 analyzers.

* Compatibility Laravel v5_0_0 (Laravel/Laravel_v5_0_0)
* Compatibility Laravel v5_1_0 (Laravel/Laravel_v5_1_0)
* Compatibility Laravel v5_2_0 (Laravel/Laravel_v5_2_0)
* Compatibility Laravel v5_3_0 (Laravel/Laravel_v5_3_0)
* Compatibility Laravel v5_4_0 (Laravel/Laravel_v5_4_0)
* Compatibility Laravel v5_5_0 (Laravel/Laravel_v5_5_0)
* Compatibility Laravel v5_6_0 (Laravel/Laravel_v5_6_0)
* Compatibility Laravel v5_7_0 (Laravel/Laravel_v5_7_0)
* {$this->name}Usage (Laravel/LaravelUsage)





.. _extension-melis:

Melis
#####

This is extension Melis.

This extension includes 8 analyzers.

* Check Regex (Melis/CheckRegex)
* Make Type A String (Melis/MakeTypeAString)
* Melis Translation String (Melis/TranslationString)
* Melis/RouteConstraints (Melis/RouteConstraints)
* Missing Language (Melis/MissingLanguage)
* Missing Translation String (Melis/MissingTranslation)
* Undefined Configuration Type (Melis/UndefinedConfType)
* Undefined Configured Class (Melis/UndefinedConfiguredClass)


This extension includes one report : Melis.



.. _extension-slim:

Slim
####

This is extension Slim.

This extension includes 26 analyzers.

* No Echo In Route Callable (Slim/NoEchoInRouteCallable)
* Slim Missing Classes (Slim/SlimMissing)
* SlimPHP 1.0.0 Undefined Classes (Slim/Slimphp10)
* SlimPHP 1.1.0 Undefined Classes (Slim/Slimphp11)
* SlimPHP 1.2.0 Undefined Classes (Slim/Slimphp12)
* SlimPHP 1.3.0 Undefined Classes (Slim/Slimphp13)
* SlimPHP 1.5.0 Undefined Classes (Slim/Slimphp15)
* SlimPHP 1.6.0 Undefined Classes (Slim/Slimphp16)
* SlimPHP 2.0.0 Undefined Classes (Slim/Slimphp20)
* SlimPHP 2.1.0 Undefined Classes (Slim/Slimphp21)
* SlimPHP 2.2.0 Undefined Classes (Slim/Slimphp22)
* SlimPHP 2.3.0 Undefined Classes (Slim/Slimphp23)
* SlimPHP 2.4.0 Undefined Classes (Slim/Slimphp24)
* SlimPHP 2.5.0 Undefined Classes (Slim/Slimphp25)
* SlimPHP 2.6.0 Undefined Classes (Slim/Slimphp26)
* SlimPHP 3.0.0 Undefined Classes (Slim/Slimphp30)
* SlimPHP 3.1.0 Undefined Classes (Slim/Slimphp31)
* SlimPHP 3.2.0 Undefined Classes (Slim/Slimphp32)
* SlimPHP 3.3.0 Undefined Classes (Slim/Slimphp33)
* SlimPHP 3.4.0 Undefined Classes (Slim/Slimphp34)
* SlimPHP 3.5.0 Undefined Classes (Slim/Slimphp35)
* SlimPHP 3.6.0 Undefined Classes (Slim/Slimphp36)
* SlimPHP 3.7.0 Undefined Classes (Slim/Slimphp37)
* SlimPHP 3.8.0 Undefined Classes (Slim/Slimphp38)
* Use Slim (Slim/UseSlim)
* Used Routes (Slim/UsedRoutes)


This extension includes one report : Slim.



.. _extension-symfony:

Symfony
#######

This is extension Symfony.

This extension includes 0 analyzers.

* 





.. _extension-wordpress:

Wordpress
#########

This is extension Wordpress.

This extension includes 26 analyzers.

* Avoid Double Prepare (Wordpress/DoublePrepare)
* Avoid Non Wordpress Globals (Wordpress/AvoidOtherGlobals)
* Missing in Wordpress (Wordpress/WordpressMissing)
* No Direct Input To Wpdb (Wordpress/NoDirectInputToWpdb)
* No Global Modification (Wordpress/NoGlobalModification)
* Nonce Creation (Wordpress/NonceCreation)
* Prepare Placeholder (Wordpress/PreparePlaceholder)
* Private Function Usage (Wordpress/PrivateFunctionUsage)
* Unescaped Variables In Templates (Wordpress/UnescapedVariables)
* Unverified Nonce (Wordpress/UnverifiedNonce)
* Use $wpdb Api (Wordpress/UseWpdbApi)
* Use Prepare With Variables (Wordpress/WpdbPrepareForVariables)
* Use Wordpress Functions (Wordpress/UseWpFunctions)
* Wordpress 4.0 Undefined Classes (Wordpress/Wordpress40Undefined)
* Wordpress 4.1 Undefined Classes (Wordpress/Wordpress41Undefined)
* Wordpress 4.2 Undefined Classes (Wordpress/Wordpress42Undefined)
* Wordpress 4.3 Undefined Classes (Wordpress/Wordpress43Undefined)
* Wordpress 4.4 Undefined Classes (Wordpress/Wordpress44Undefined)
* Wordpress 4.5 Undefined Classes (Wordpress/Wordpress45Undefined)
* Wordpress 4.6 Undefined Classes (Wordpress/Wordpress46Undefined)
* Wordpress 4.7 Undefined Classes (Wordpress/Wordpress47Undefined)
* Wordpress 4.8 Undefined Classes (Wordpress/Wordpress48Undefined)
* Wordpress 4.9 Undefined Classes (Wordpress/Wordpress49Undefined)
* Wordpress Usage (Wordpress/WordpressUsage)
* Wpdb Best Usage (Wordpress/WpdbBestUsage)
* Wpdb Prepare Or Not (Wordpress/WpdbPrepareOrNot)





.. _extension-zendf:

ZendF
#####

This is extension ZendF.

This extension includes 228 analyzers.

* Action Should Be In Controller (ZendF/ActionInController)
* Avoid PHP Superglobals (ZendF/DontUseGPC)
* Defined View Property (ZendF/DefinedViewProperty)
* Is Zend Framework 1 Controller (ZendF/IsController)
* Is Zend Framework 1 Helper (ZendF/IsHelper)
* Is Zend View File (ZendF/IsView)
* No Echo Outside View (ZendF/NoEchoOutsideView)
* Should Always Prepare (ZendF/Zf3DbAlwaysPrepare)
* Should Regenerate Session Id (ZendF/ShouldRegenerateSessionId)
* Thrown Exceptions (ZendF/ThrownExceptions)
* Undefined Class 2.0 (ZendF/UndefinedClass20)
* Undefined Class 2.1 (ZendF/UndefinedClass21)
* Undefined Class 2.2 (ZendF/UndefinedClass22)
* Undefined Class 2.3 (ZendF/UndefinedClass23)
* Undefined Class 2.4 (ZendF/UndefinedClass24)
* Undefined Class 2.5 (ZendF/UndefinedClass25)
* Undefined Class 3.0 (ZendF/UndefinedClass30)
* Undefined Classes (ZendF/UndefinedClasses)
* Undefined Zend 1.10 (ZendF/UndefinedClass110)
* Undefined Zend 1.11 (ZendF/UndefinedClass111)
* Undefined Zend 1.12 (ZendF/UndefinedClass112)
* Undefined Zend 1.8 (ZendF/UndefinedClass18)
* Undefined Zend 1.9 (ZendF/UndefinedClass19)
* Use Zend Session (ZendF/UseSession)
* Used View Property (ZendF/UsedViewProperty)
* Wrong Class Location (ZendF/NotInThatPath)
* Zend Classes (ZendF/ZendClasses)
* Zend Framework 3 Missing Classes (ZendF/Zf3ComponentMissing)
* Zend Interface (ZendF/ZendInterfaces)
* Zend Trait (ZendF/ZendTrait)
* Zend Typehinting (ZendF/ZendTypehinting)
* zend-authentication 2.5.0 Undefined Classes (ZendF/Zf3Authentication25)
* zend-authentication Usage (ZendF/Zf3Authentication)
* zend-barcode 2.5.0 Undefined Classes (ZendF/Zf3Barcode25)
* zend-barcode 2.6.0 Undefined Classes (ZendF/Zf3Barcode26)
* zend-barcode Usage (ZendF/Zf3Barcode)
* zend-cache 2.5.0 Undefined Classes (ZendF/Zf3Cache25)
* zend-cache 2.6.0 Undefined Classes (ZendF/Zf3Cache26)
* zend-cache 2.7.0 Undefined Classes (ZendF/Zf3Cache27)
* zend-cache Usage (ZendF/Zf3Cache)
* zend-captcha 2.5.0 Undefined Classes (ZendF/Zf3Captcha25)
* zend-captcha 2.6.0 Undefined Classes (ZendF/Zf3Captcha26)
* zend-captcha 2.7.0 Undefined Classes (ZendF/Zf3Captcha27)
* zend-captcha Usage (ZendF/Zf3Captcha)
* zend-code 2.5.0 Undefined Classes (ZendF/Zf3Code25)
* zend-code 2.6.0 Undefined Classes (ZendF/Zf3Code26)
* zend-code 3.0.0 Undefined Classes (ZendF/Zf3Code30)
* zend-code 3.1.0 Undefined Classes (ZendF/Zf3Code31)
* zend-code 3.2.0 Undefined Classes (ZendF/Zf3Code32)
* zend-code Usage (ZendF/Zf3Code)
* zend-config 2.5.x (ZendF/Zf3Config25)
* zend-config 2.6.x (ZendF/Zf3Config26)
* zend-config 3.0.x (ZendF/Zf3Config30)
* zend-config 3.1.x (ZendF/Zf3Config31)
* zend-console 2.5.0 Undefined Classes (ZendF/Zf3Console25)
* zend-console 2.6.0 Undefined Classes (ZendF/Zf3Console26)
* zend-console Usage (ZendF/Zf3Console)
* zend-crypt 2.5.0 Undefined Classes (ZendF/Zf3Crypt25)
* zend-crypt 2.6.0 Undefined Classes (ZendF/Zf3Crypt26)
* zend-crypt 3.0.0 Undefined Classes (ZendF/Zf3Crypt30)
* zend-crypt 3.1.0 Undefined Classes (ZendF/Zf3Crypt31)
* zend-crypt 3.2.0 Undefined Classes (ZendF/Zf3Crypt32)
* zend-crypt Usage (ZendF/Zf3Crypt)
* zend-db 2.5.0 Undefined Classes (ZendF/Zf3Db25)
* zend-db 2.6.0 Undefined Classes (ZendF/Zf3Db26)
* zend-db 2.7.0 Undefined Classes (ZendF/Zf3Db27)
* zend-db 2.8.0 Undefined Classes (ZendF/Zf3Db28)
* zend-db Usage (ZendF/Zf3Db)
* zend-debug 2.5.0 Undefined Classes (ZendF/Zf3Debug25)
* zend-debug Usage (ZendF/Zf3Debug)
* zend-di 2.5.0 Undefined Classes (ZendF/Zf3Di25)
* zend-di 2.6.0 Undefined Classes (ZendF/Zf3Di26)
* zend-di Usage (ZendF/Zf3Di)
* zend-dom 2.5.0 Undefined Classes (ZendF/Zf3Dom25)
* zend-dom 2.6.0 Undefined Classes (ZendF/Zf3Dom26)
* zend-dom Usage (ZendF/Zf3Dom)
* zend-escaper 2.5.0 Undefined Classes (ZendF/Zf3Escaper25)
* zend-escaper Usage (ZendF/Zf3Escaper)
* zend-eventmanager 2.5.0 Undefined Classes (ZendF/Zf3Eventmanager25)
* zend-eventmanager 2.6.0 Undefined Classes (ZendF/Zf3Eventmanager26)
* zend-eventmanager 3.0.0 Undefined Classes (ZendF/Zf3Eventmanager30)
* zend-eventmanager 3.1.0 Undefined Classes (ZendF/Zf3Eventmanager31)
* zend-eventmanager 3.2.0 Undefined Classes (ZendF/Zf3Eventmanager32)
* zend-eventmanager Usage (ZendF/Zf3Eventmanager)
* zend-feed 2.5.0 Undefined Classes (ZendF/Zf3Feed25)
* zend-feed 2.6.0 Undefined Classes (ZendF/Zf3Feed26)
* zend-feed 2.7.0 Undefined Classes (ZendF/Zf3Feed27)
* zend-feed 2.8.0 Undefined Classes (ZendF/Zf3Feed28)
* zend-feed Usage (ZendF/Zf3Feed)
* zend-file 2.5.0 Undefined Classes (ZendF/Zf3File25)
* zend-file 2.6.0 Undefined Classes (ZendF/Zf3File26)
* zend-file 2.7.0 Undefined Classes (ZendF/Zf3File27)
* zend-file Usage (ZendF/Zf3File)
* zend-filter 2.5.0 Undefined Classes (ZendF/Zf3Filter25)
* zend-filter 2.6.0 Undefined Classes (ZendF/Zf3Filter26)
* zend-filter 2.7.0 Undefined Classes (ZendF/Zf3Filter27)
* zend-filter Usage (ZendF/Zf3Filter)
* zend-form 2.5.0 Undefined Classes (ZendF/Zf3Form25)
* zend-form 2.6.0 Undefined Classes (ZendF/Zf3Form26)
* zend-form 2.7.0 Undefined Classes (ZendF/Zf3Form27)
* zend-form 2.8.0 Undefined Classes (ZendF/Zf3Form28)
* zend-form 2.9.0 Undefined Classes (ZendF/Zf3Form29)
* zend-form Usage (ZendF/Zf3Form)
* zend-http 2.5.0 Undefined Classes (ZendF/Zf3Http25)
* zend-http 2.6.0 Undefined Classes (ZendF/Zf3Http26)
* zend-http 2.7.0 Undefined Classes (ZendF/Zf3Http27)
* zend-http Usage (ZendF/Zf3Http)
* zend-i18n 2.5.0 Undefined Classes (ZendF/Zf3I18n25)
* zend-i18n 2.6.0 Undefined Classes (ZendF/Zf3I18n26)
* zend-i18n 2.7.0 Undefined Classes (ZendF/Zf3I18n27)
* zend-i18n resources Usage (ZendF/Zf3I18n_resources)
* zend-i18n Usage (ZendF/Zf3I18n)
* zend-i18n-resources 2.5.x (ZendF/Zf3I18n_resources25)
* zend-inputfilter 2.5.0 Undefined Classes (ZendF/Zf3Inputfilter25)
* zend-inputfilter 2.6.0 Undefined Classes (ZendF/Zf3Inputfilter26)
* zend-inputfilter 2.7.0 Undefined Classes (ZendF/Zf3Inputfilter27)
* zend-inputfilter Usage (ZendF/Zf3Inputfilter)
* zend-json 2.5.0 Undefined Classes (ZendF/Zf3Json25)
* zend-json 2.6.0 Undefined Classes (ZendF/Zf3Json26)
* zend-json 3.0.0 Undefined Classes (ZendF/Zf3Json30)
* zend-json Usage (ZendF/Zf3Json)
* zend-loader 2.5.0 Undefined Classes (ZendF/Zf3Loader25)
* zend-loader Usage (ZendF/Zf3Loader)
* zend-log 2.5.0 Undefined Classes (ZendF/Zf3Log25)
* zend-log 2.6.0 Undefined Classes (ZendF/Zf3Log26)
* zend-log 2.7.0 Undefined Classes (ZendF/Zf3Log27)
* zend-log 2.8.0 Undefined Classes (ZendF/Zf3Log28)
* zend-log 2.9.0 Undefined Classes (ZendF/Zf3Log29)
* zend-log Usage (ZendF/Zf3Log)
* zend-mail 2.5.0 Undefined Classes (ZendF/Zf3Mail25)
* zend-mail 2.6.0 Undefined Classes (ZendF/Zf3Mail26)
* zend-mail 2.7.0 Undefined Classes (ZendF/Zf3Mail27)
* zend-mail 2.8.0 Undefined Classes (ZendF/Zf3Mail28)
* zend-mail Usage (ZendF/Zf3Mail)
* zend-math 2.5.0 Undefined Classes (ZendF/Zf3Math25)
* zend-math 2.6.0 Undefined Classes (ZendF/Zf3Math26)
* zend-math 2.7.0 Undefined Classes (ZendF/Zf3Math27)
* zend-math 3.0.0 Undefined Classes (ZendF/Zf3Math30)
* zend-math Usage (ZendF/Zf3Math)
* zend-memory 2.5.0 Undefined Classes (ZendF/Zf3Memory25)
* zend-memory Usage (ZendF/Zf3Memory)
* zend-mime 2.5.0 Undefined Classes (ZendF/Zf3Mime25)
* zend-mime 2.6.0 Undefined Classes (ZendF/Zf3Mime26)
* zend-mime Usage (ZendF/Zf3Mime)
* zend-modulemanager 2.5.0 Undefined Classes (ZendF/Zf3Modulemanager25)
* zend-modulemanager 2.6.0 Undefined Classes (ZendF/Zf3Modulemanager26)
* zend-modulemanager 2.7.0 Undefined Classes (ZendF/Zf3Modulemanager27)
* zend-modulemanager 2.8.0 Undefined Classes (ZendF/Zf3Modulemanager28)
* zend-modulemanager Usage (ZendF/Zf3Modulemanager)
* zend-mvc 2.5.x (ZendF/Zf3Mvc25)
* zend-mvc 2.6.x (ZendF/Zf3Mvc26)
* zend-mvc 2.7.x (ZendF/Zf3Mvc27)
* zend-mvc 3.0.x (ZendF/Zf3Mvc30)
* zend-mvc 3.1.0 Undefined Classes (ZendF/Zf3Mvc31)
* zend-mvc Usage (ZendF/Zf3Mvc)
* zend-navigation 2.5.0 Undefined Classes (ZendF/Zf3Navigation25)
* zend-navigation 2.6.0 Undefined Classes (ZendF/Zf3Navigation26)
* zend-navigation 2.7.0 Undefined Classes (ZendF/Zf3Navigation27)
* zend-navigation 2.8.0 Undefined Classes (ZendF/Zf3Navigation28)
* zend-navigation Usage (ZendF/Zf3Navigation)
* zend-paginator 2.5.0 Undefined Classes (ZendF/Zf3Paginator25)
* zend-paginator 2.6.0 Undefined Classes (ZendF/Zf3Paginator26)
* zend-paginator 2.7.0 Undefined Classes (ZendF/Zf3Paginator27)
* zend-paginator Usage (ZendF/Zf3Paginator)
* zend-progressbar 2.5.0 Undefined Classes (ZendF/Zf3Progressbar25)
* zend-progressbar Usage (ZendF/Zf3Progressbar)
* zend-serializer 2.5.0 Undefined Classes (ZendF/Zf3Serializer25)
* zend-serializer 2.6.0 Undefined Classes (ZendF/Zf3Serializer26)
* zend-serializer 2.7.0 Undefined Classes (ZendF/Zf3Serializer27)
* zend-serializer 2.8.0 Undefined Classes (ZendF/Zf3Serializer28)
* zend-serializer Usage (ZendF/Zf3Serializer)
* zend-server 2.5.0 Undefined Classes (ZendF/Zf3Server25)
* zend-server 2.6.0 Undefined Classes (ZendF/Zf3Server26)
* zend-server 2.7.0 Undefined Classes (ZendF/Zf3Server27)
* zend-server Usage (ZendF/Zf3Server)
* zend-servicemanager 2.5.0 Undefined Classes (ZendF/Zf3Servicemanager25)
* zend-servicemanager 2.6.0 Undefined Classes (ZendF/Zf3Servicemanager26)
* zend-servicemanager 2.7.0 Undefined Classes (ZendF/Zf3Servicemanager27)
* zend-servicemanager 3.0.0 Undefined Classes (ZendF/Zf3Servicemanager30)
* zend-servicemanager 3.1.0 Undefined Classes (ZendF/Zf3Servicemanager31)
* zend-servicemanager 3.2.0 Undefined Classes (ZendF/Zf3Servicemanager32)
* zend-servicemanager 3.3.0 Undefined Classes (ZendF/Zf3Servicemanager33)
* zend-servicemanager Usage (ZendF/Zf3Servicemanager)
* zend-session 2.5.0 Undefined Classes (ZendF/Zf3Session25)
* zend-session 2.6.0 Undefined Classes (ZendF/Zf3Session26)
* zend-session 2.7.0 Undefined Classes (ZendF/Zf3Session27)
* zend-session 2.8.0 Undefined Classes (ZendF/Zf3Session28)
* zend-session Usage (ZendF/Zf3Session)
* zend-soap 2.5.0 Undefined Classes (ZendF/Zf3Soap25)
* zend-soap 2.6.0 Undefined Classes (ZendF/Zf3Soap26)
* zend-soap Usage (ZendF/Zf3Soap)
* zend-stdlib 2.5.0 Undefined Classes (ZendF/Zf3Stdlib25)
* zend-stdlib 2.6.0 Undefined Classes (ZendF/Zf3Stdlib26)
* zend-stdlib 2.7.0 Undefined Classes (ZendF/Zf3Stdlib27)
* zend-stdlib 3.0.0 Undefined Classes (ZendF/Zf3Stdlib30)
* zend-stdlib 3.1.0 Undefined Classes (ZendF/Zf3Stdlib31)
* zend-stdlib Usage (ZendF/Zf3Stdlib)
* zend-tag 2.5.0 Undefined Classes (ZendF/Zf3Tag25)
* zend-tag 2.6.0 Undefined Classes (ZendF/Zf3Tag26)
* zend-tag Usage (ZendF/Zf3Tag)
* zend-test 2.5.0 Undefined Classes (ZendF/Zf3Test25)
* zend-test 2.6.0 Undefined Classes (ZendF/Zf3Test26)
* zend-test 3.0.0 Undefined Classes (ZendF/Zf3Test30)
* zend-test 3.1.0 Undefined Classes (ZendF/Zf3Test31)
* zend-test Usage (ZendF/Zf3Test)
* zend-text 2.5.0 Undefined Classes (ZendF/Zf3Text25)
* zend-text 2.6.0 Undefined Classes (ZendF/Zf3Text26)
* zend-text Usage (ZendF/Zf3Text)
* zend-uri (ZendF/Zf3Uri)
* zend-uri 2.5.x (ZendF/Zf3Uri25)
* zend-validator 2.6.x (ZendF/Zf3Validator26)
* zend-validator 2.7.x (ZendF/Zf3Validator27)
* zend-validator 2.8.x (ZendF/Zf3Validator28)
* zend-validator 2.9.0 Undefined Classes (ZendF/Zf3Validator29)
* zend-validator Usage (ZendF/Zf3Validator)
* zend-view 2.5.0 Undefined Classes (ZendF/Zf3View25)
* zend-view 2.6.0 Undefined Classes (ZendF/Zf3View26)
* zend-view 2.7.0 Undefined Classes (ZendF/Zf3View27)
* zend-view 2.8.0 Undefined Classes (ZendF/Zf3View28)
* zend-view 2.9.0 Undefined Classes (ZendF/Zf3View29)
* zend-view Usage (ZendF/Zf3View)
* zend-xmlrpc 2.5.0 Undefined Classes (ZendF/Zf3Xmlrpc25)
* zend-xmlrpc 2.6.0 Undefined Classes (ZendF/Zf3Xmlrpc26)
* zend-xmlrpc Usage (ZendF/Zf3Xmlrpc)
* Zend\Config (ZendF/Zf3Config)
* ZF3 Component (ZendF/Zf3Component)
* ZF3 Usage Of Deprecated (ZendF/Zf3DeprecatedUsage)


This extension includes one report : ZendFramework.






