.. Extensions:

Extensions
==========

Exakat support a system of extensions, that bring extra analysis, categories, data sources, configurations and reports to the exakat engine. Extensions focus on specific frameworks or platform. They are a simple way to package a predefined set of configurations and analysis. 

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
        rulesets             : mine2
        extra rulesets       : mine,
                               special,
                               MonologExtra
        tokenslimit          : 1 000 000 000
        extensions           : Cakephp.phar,
                               Laravel.phar,
                               Melis.phar,
                               Monolog.phar,
                               Slim.phar,
                               Wordpress.phar



List of extensions : there are 13 extensions

* :ref:`Cakephp <extension-cakephp>`
* :ref:`Codeigniter <extension-codeigniter>`
* :ref:`Drupal <extension-drupal>`
* :ref:`Laravel <extension-laravel>`
* :ref:`Melis <extension-melis>`
* :ref:`Monolog <extension-monolog>`
* :ref:`Prestashop <extension-prestashop>`
* :ref:`Shopware <extension-shopware>`
* :ref:`Slim <extension-slim>`
* :ref:`Symfony <extension-symfony>`
* :ref:`Twig <extension-twig>`
* :ref:`Wordpress <extension-wordpress>`
* :ref:`ZendF <extension-zendf>`




Extensions management
---------------------

The main command to manage the extensions is `extension`. It has 4 different actions : 

* `local`
* `list`
* `install`
* `update`
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

`update` : the update command
###############################

This command updates an installed extension. Check with `extension local` to know which are the locally installed extensions. 

::

    exakat extension update Wordpress



`uninstall` : the remove command
################################

This command uninstalls a previously installed extension. Check with `extension local` to know which are the locally installed extensions. 

::

    exakat extension uninstall Laravel


You may also remove the extension manually, by removing them from the extension folder.


Extensions usage
----------------

Exakat extensions bring several resources to enhance the Exakat engine : 

* Analysis
* Ruleset
* Reports

Analysis usage 
###############

Analysis are used individually by using their short name. They may be used with any command that accepts the -P option. 

::

    exakat analyze -p <project_name> -P Drupal/Drupal_8_6
    exakat dump    -p <project_name> -P Drupal/Drupal_8_6 -u
    exakat report -p <project_name> -P Drupal/Drupal_8_6 -format Text


Analysis may also be configured in the ``config/themes.ini`` file, by including them in any section. 

::

['specialDrupal']
analyzer[] = 'Drupal/Drupal_8_6';
analyzer[] = 'Drupal/Drupal_8_5';


['specialDrupal2']
analyzer[] = 'Drupal/Drupal_8_7';
analyzer[] = 'Drupal/Drupal_8_6';
analyzer[] = 'Drupal/Drupal_8_5';


Then, they may be used with any command that accept the -T option.

::

    exakat analyze -p <project_name> -T specialDrupal
    exakat dump    -p <project_name> -T specialDrupal -u
    exakat report -p <project_name> -T specialDrupal -format Text
    

Rulesets usage 
##############

Rulesets are predefined sets of analysis. Currently, an extension always provides one ruleset with the name of the extension : it includes all the analysis in this extension.

For example, the ``Drupal`` extension provides a ``Drupal``ruleset.

::

    exakat analyze -p <project_name> -T Drupal
    exakat dump    -p <project_name> -T Drupal -u
    exakat report -p <project_name>  -T Drupal -format Text

Reports usage 
##############

Reports are specific reports for the extension. 

When no specific report is provided by the extension, results are accessible with the universal reports, such as Text. 

::

    #Report of all Drupal issues, in Text format
    exakat dump    -p <project_name> -T Drupal -format Text

    #Specific report for melis framework
    exakat report  -p <project_name> -format Melis





Details about the extensions
----------------------------

.. _extension-cakephp:

Cakephp
#######

This is the CakePHP extension for Exakat. 

CakePHP makes building web applications simpler, faster, while requiring less code. A modern PHP 7 framework offering a flexible database access layer and a powerful scaffolding system that makes building both small and complex systems simpler, easier and, of course, tastier. Build fast, grow solid with CakePHP.

Exakat provides compatibility reports with classes, interfaces and traits from CakePHP 3.0 to 3.4.



* **Home page** : `https://cakephp.org/ <https://cakephp.org/>`_
* **Extension page** : `https://github.com/exakat/Exakat4CakePHP <https://github.com/exakat/Exakat4CakePHP>`_

Cakephp analysis
--------------------------------------------------

This extension includes 27 analyzers.

* CakePHP 2.5.0 Undefined Classes (Cakephp/Cakephp25)
* CakePHP 2.6.0 Undefined Classes (Cakephp/Cakephp26)
* CakePHP 2.7.0 Undefined Classes (Cakephp/Cakephp27)
* CakePHP 2.8.0 Undefined Classes (Cakephp/Cakephp28)
* CakePHP 2.9.0 Undefined Classes (Cakephp/Cakephp29)
* Cakephp 3.0 Compatibility (Cakephp/Cakephp_3_0)
* CakePHP 3.0 Deprecated Class (Cakephp/Cake30DeprecatedClass)
* CakePHP 3.0.0 Undefined Classes (Cakephp/Cakephp30)
* Cakephp 3.1 Compatibility (Cakephp/Cakephp_3_1)
* CakePHP 3.1.0 Undefined Classes (Cakephp/Cakephp31)
* Cakephp 3.2 Compatibility (Cakephp/Cakephp_3_2)
* CakePHP 3.2.0 Undefined Classes (Cakephp/Cakephp32)
* Cakephp 3.3 Compatibility (Cakephp/Cakephp_3_3)
* CakePHP 3.3 Deprecated Class (Cakephp/Cake33DeprecatedClass)
* CakePHP 3.3.0 Undefined Classes (Cakephp/Cakephp33)
* Cakephp 3.4 Compatibility (Cakephp/Cakephp_3_4)
* CakePHP 3.4.0 Undefined Classes (Cakephp/Cakephp34)
* Cakephp 3.5 Compatibility (Cakephp/Cakephp_3_5)
* Cakephp 3.6 Compatibility (Cakephp/Cakephp_3_6)
* Cakephp 3.7 Compatibility (Cakephp/Cakephp_3_7)
* CakePHP Unknown Classes (Cakephp/CakePHPMissing)
* Cakephp Usage (Cakephp/CakephpUsage)
* CakePHP Used (Cakephp/CakePHPUsed)
* Deprecated Methodcalls in Cake 3.2 (Cakephp/Cake32DeprecatedMethods)
* Deprecated Methodcalls in Cake 3.3 (Cakephp/Cake33DeprecatedMethods)
* Deprecated Static calls in Cake 3.3 (Cakephp/Cake33DeprecatedStaticmethodcall)
* Deprecated Trait in Cake 3.3 (Cakephp/Cake33DeprecatedTraits)


Cakephp rulesets
--------------------------------------------------

This extension includes one ruleset : Cakephp.


Cakephp reports
--------------------------------------------------

This extension includes no specific report. Use generic reports, like Text to access the results.



.. _extension-codeigniter:

Codeigniter
###########

This is the Code igniter extension for Exakat. 

Code igniter CodeIgniter is a powerful PHP framework with a very small footprint, built for developers who need a simple and elegant toolkit to create full-featured web applications.



* **Home page** : `https://codeigniter.com/ <https://codeigniter.com/>`_
* **Extension page** : `https://github.com/exakat/Exakat4Codeigniter <https://github.com/exakat/Exakat4Codeigniter>`_

Codeigniter analysis
--------------------------------------------------

This extension includes 6 analyzers.

* Codeigniter 2.0 Compatibility (Codeigniter/Codeigniter_2_0)
* Codeigniter 2.1 Compatibility (Codeigniter/Codeigniter_2_1)
* Codeigniter 2.2 Compatibility (Codeigniter/Codeigniter_2_2)
* Codeigniter 3.0 Compatibility (Codeigniter/Codeigniter_3_0)
* Codeigniter 3.1 Compatibility (Codeigniter/Codeigniter_3_1)
* Codeigniter Usage (Codeigniter/CodeigniterUsage)


Codeigniter rulesets
--------------------------------------------------

This extension includes one ruleset : Codeigniter.


Codeigniter reports
--------------------------------------------------

This extension includes no specific report. Use generic reports, like Text to access the results.



.. _extension-drupal:

Drupal
######

This is the Drupal extension for Exakat. 

`Drupal <http://www.drupal.org/>`_ is the "leading open-source CMS for ambitious digital experiences that reach your audience across multiple channels".



* **Home page** : `https://www.drupal.org/ <https://www.drupal.org/>`_
* **Extension page** : `https://github.com/exakat/Exakat4Drupal <https://github.com/exakat/Exakat4Drupal>`_

Drupal analysis
--------------------------------------------------

This extension includes 19 analyzers.

* Drupal 6.0 Compatibility (Drupal/Drupal_6_0)
* Drupal 6.10 Compatibility (Drupal/Drupal_6_10)
* Drupal 6.20 Compatibility (Drupal/Drupal_6_20)
* Drupal 6.38 Compatibility (Drupal/Drupal_6_38)
* Drupal 7.0 Compatibility (Drupal/Drupal_7_0)
* Drupal 7.10 Compatibility (Drupal/Drupal_7_10)
* Drupal 7.20 Compatibility (Drupal/Drupal_7_20)
* Drupal 7.30 Compatibility (Drupal/Drupal_7_30)
* Drupal 7.40 Compatibility (Drupal/Drupal_7_40)
* Drupal 7.50 Compatibility (Drupal/Drupal_7_50)
* Drupal 7.60 Compatibility (Drupal/Drupal_7_60)
* Drupal 8.0 Compatibility (Drupal/Drupal_8_0)
* Drupal 8.1 Compatibility (Drupal/Drupal_8_1)
* Drupal 8.2 Compatibility (Drupal/Drupal_8_2)
* Drupal 8.3 Compatibility (Drupal/Drupal_8_3)
* Drupal 8.4 Compatibility (Drupal/Drupal_8_4)
* Drupal 8.5 Compatibility (Drupal/Drupal_8_5)
* Drupal 8.6 Compatibility (Drupal/Drupal_8_6)
* Drupal Usage (Drupal/DrupalUsage)


Drupal rulesets
--------------------------------------------------

This extension includes one ruleset : Drupal.


Drupal reports
--------------------------------------------------

This extension includes no specific report. Use generic reports, like Text to access the results.



.. _extension-laravel:

Laravel
#######

This is the Laravel extension for Exakat. 

Laravel is the 'The PHP framework for web artisans.'

Exakat provides compatibility reports with classes, interfaces and traits from Laravel 5.0 to 5.7.



* **Home page** : `https://laravel.com/ <https://laravel.com/>`_
* **Extension page** : `https://github.com/exakat/Exakat4Laravel <https://github.com/exakat/Exakat4Laravel>`_

Laravel analysis
--------------------------------------------------

This extension includes 18 analyzers.

* Compatibility Laravel v5_0_0 (Laravel/Laravel_v5_0_0)
* Compatibility Laravel v5_1_0 (Laravel/Laravel_v5_1_0)
* Compatibility Laravel v5_2_0 (Laravel/Laravel_v5_2_0)
* Compatibility Laravel v5_3_0 (Laravel/Laravel_v5_3_0)
* Compatibility Laravel v5_4_0 (Laravel/Laravel_v5_4_0)
* Compatibility Laravel v5_5_0 (Laravel/Laravel_v5_5_0)
* Compatibility Laravel v5_6_0 (Laravel/Laravel_v5_6_0)
* Compatibility Laravel v5_7_0 (Laravel/Laravel_v5_7_0)
* Laravel 5.0 Compatibility (Laravel/Laravel_5_0)
* Laravel 5.1 Compatibility (Laravel/Laravel_5_1)
* Laravel 5.2 Compatibility (Laravel/Laravel_5_2)
* Laravel 5.3 Compatibility (Laravel/Laravel_5_3)
* Laravel 5.4 Compatibility (Laravel/Laravel_5_4)
* Laravel 5.5 Compatibility (Laravel/Laravel_5_5)
* Laravel 5.6 Compatibility (Laravel/Laravel_5_6)
* Laravel 5.7 Compatibility (Laravel/Laravel_5_7)
* Laravel 5.8 Compatibility (Laravel/Laravel_5_8)
* Laravel Usage (Laravel/LaravelUsage)


Laravel rulesets
--------------------------------------------------

This extension includes one ruleset : Laravel.


Laravel reports
--------------------------------------------------

This extension includes no specific report. Use generic reports, like Text to access the results.



.. _extension-melis:

Melis
#####

This is the Melis extension for Exakat. 

Melis is a new generation of Content Management System and eCommerce platform to achieve and manage websites from a single web interface easy to use while offering the best of open source technology.



* **Home page** : `https://www.melistechnology.com/ <https://www.melistechnology.com/>`_
* **Extension page** : `https://github.com/exakat/Exakat4Melis <https://github.com/exakat/Exakat4Melis>`_

Melis analysis
--------------------------------------------------

This extension includes 15 analyzers.

* Check Regex (Melis/CheckRegex)
* Make Type A String (Melis/MakeTypeAString)
* Melis 2.1 Compatibility (Melis/Melis_2_1)
* Melis 2.2 Compatibility (Melis/Melis_2_2)
* Melis 2.3 Compatibility (Melis/Melis_2_3)
* Melis 2.4 Compatibility (Melis/Melis_2_4)
* Melis 2.5 Compatibility (Melis/Melis_2_5)
* Melis 3.0 Compatibility (Melis/Melis_3_0)
* Melis Translation String (Melis/TranslationString)
* Melis Usage (Melis/MelisUsage)
* Melis/RouteConstraints (Melis/RouteConstraints)
* Missing Language (Melis/MissingLanguage)
* Missing Translation String (Melis/MissingTranslation)
* Undefined Configuration Type (Melis/UndefinedConfType)
* Undefined Configured Class (Melis/UndefinedConfiguredClass)


Melis rulesets
--------------------------------------------------

This extension includes one ruleset : Melis.


Melis reports
--------------------------------------------------

This extension includes one report : Melis.



.. _extension-monolog:

Monolog
#######

This is the Monolog extension for Exakat. 

Monolog is a popular logging component for PHP, written by ` <https://twitter.com/seldaek>`_. 


* **Home page** : `https://github.com/Seldaek/monolog <https://github.com/Seldaek/monolog>`_
* **Extension page** : `https://github.com/exakat/Exakat4Monolog <https://github.com/exakat/Exakat4Monolog>`_

Monolog analysis
--------------------------------------------------

This extension includes 52 analyzers.

* Monolog 1.0 Compatibility (Monolog/Monolog_1_0)
* Monolog 1.0.0 Compatibility (Monolog/Monolog_1_0_0)
* Monolog 1.1 Compatibility (Monolog/Monolog_1_1)
* Monolog 1.1.0 Compatibility (Monolog/Monolog_1_1_0)
* Monolog 1.10 Compatibility (Monolog/Monolog_1_10)
* Monolog 1.10.0 Compatibility (Monolog/Monolog_1_10_0)
* Monolog 1.11 Compatibility (Monolog/Monolog_1_11)
* Monolog 1.11.0 Compatibility (Monolog/Monolog_1_11_0)
* Monolog 1.12 Compatibility (Monolog/Monolog_1_12)
* Monolog 1.12.0 Compatibility (Monolog/Monolog_1_12_0)
* Monolog 1.13 Compatibility (Monolog/Monolog_1_13)
* Monolog 1.13.0 Compatibility (Monolog/Monolog_1_13_0)
* Monolog 1.14 Compatibility (Monolog/Monolog_1_14)
* Monolog 1.14.0 Compatibility (Monolog/Monolog_1_14_0)
* Monolog 1.15 Compatibility (Monolog/Monolog_1_15)
* Monolog 1.15.0 Compatibility (Monolog/Monolog_1_15_0)
* Monolog 1.16 Compatibility (Monolog/Monolog_1_16)
* Monolog 1.16.0 Compatibility (Monolog/Monolog_1_16_0)
* Monolog 1.17 Compatibility (Monolog/Monolog_1_17)
* Monolog 1.17.0 Compatibility (Monolog/Monolog_1_17_0)
* Monolog 1.18 Compatibility (Monolog/Monolog_1_18)
* Monolog 1.18.0 Compatibility (Monolog/Monolog_1_18_0)
* Monolog 1.19 Compatibility (Monolog/Monolog_1_19)
* Monolog 1.19.0 Compatibility (Monolog/Monolog_1_19_0)
* Monolog 1.2 Compatibility (Monolog/Monolog_1_2)
* Monolog 1.2.0 Compatibility (Monolog/Monolog_1_2_0)
* Monolog 1.20 Compatibility (Monolog/Monolog_1_20)
* Monolog 1.20.0 Compatibility (Monolog/Monolog_1_20_0)
* Monolog 1.21 Compatibility (Monolog/Monolog_1_21)
* Monolog 1.21.0 Compatibility (Monolog/Monolog_1_21_0)
* Monolog 1.22 Compatibility (Monolog/Monolog_1_22)
* Monolog 1.22.0 Compatibility (Monolog/Monolog_1_22_0)
* Monolog 1.23 Compatibility (Monolog/Monolog_1_23)
* Monolog 1.23.0 Compatibility (Monolog/Monolog_1_23_0)
* Monolog 1.24 Compatibility (Monolog/Monolog_1_24)
* Monolog 1.24.0 Compatibility (Monolog/Monolog_1_24_0)
* Monolog 1.3 Compatibility (Monolog/Monolog_1_3)
* Monolog 1.3.0 Compatibility (Monolog/Monolog_1_3_0)
* Monolog 1.4 Compatibility (Monolog/Monolog_1_4)
* Monolog 1.4.0 Compatibility (Monolog/Monolog_1_4_0)
* Monolog 1.5 Compatibility (Monolog/Monolog_1_5)
* Monolog 1.5.0 Compatibility (Monolog/Monolog_1_5_0)
* Monolog 1.6 Compatibility (Monolog/Monolog_1_6)
* Monolog 1.6.0 Compatibility (Monolog/Monolog_1_6_0)
* Monolog 1.7 Compatibility (Monolog/Monolog_1_7)
* Monolog 1.7.0 Compatibility (Monolog/Monolog_1_7_0)
* Monolog 1.8 Compatibility (Monolog/Monolog_1_8)
* Monolog 1.8.0 Compatibility (Monolog/Monolog_1_8_0)
* Monolog 1.9 Compatibility (Monolog/Monolog_1_9)
* Monolog 1.9.0 Compatibility (Monolog/Monolog_1_9_0)
* Monolog 2.0 Compatibility (Monolog/Monolog_2_0)
* Monolog Usage (Monolog/MonologUsage)


Monolog rulesets
--------------------------------------------------

This extension includes one ruleset : Monolog.


Monolog reports
--------------------------------------------------

This extension includes no specific report. Use generic reports, like Text to access the results.



.. _extension-prestashop:

Prestashop
##########

This is the Prestashop extension for Exakat. 

PrestaShop is an efficient and innovative e-commerce solution with all the features you need to create an online store and grow your business.


* **Home page** : `https://www.prestashop.com/ <https://www.prestashop.com/>`_
* **Extension page** : ` <>`_

Prestashop analysis
--------------------------------------------------

This extension includes 4 analyzers.

* Prestashop 1.5 Compatibility (Prestashop/Prestashop_1_5)
* Prestashop 1.6 Compatibility (Prestashop/Prestashop_1_6)
* Prestashop 1.7 Compatibility (Prestashop/Prestashop_1_7)
* Prestashop Usage (Prestashop/PrestashopUsage)


Prestashop rulesets
--------------------------------------------------

This extension includes one ruleset : Prestashop.


Prestashop reports
--------------------------------------------------

This extension includes no specific report. Use generic reports, like Text to access the results.



.. _extension-shopware:

Shopware
########

This is the Skeleton extension for Exakat. 
Shopware analysis
--------------------------------------------------

This extension includes 6 analyzers.

* Shopware 5.0 Compatibility (Shopware/Shopware_5_0)
* Shopware 5.1 Compatibility (Shopware/Shopware_5_1)
* Shopware 5.2 Compatibility (Shopware/Shopware_5_2)
* Shopware 5.3 Compatibility (Shopware/Shopware_5_3)
* Shopware 5.4 Compatibility (Shopware/Shopware_5_4)
* Shopware 5.5 Compatibility (Shopware/Shopware_5_5)


Shopware rulesets
--------------------------------------------------

This extension includes one ruleset : Shopware.


Shopware reports
--------------------------------------------------

This extension includes no specific report. Use generic reports, like Text to access the results.



.. _extension-slim:

Slim
####

This is the Slim extension for Exakat. 

Slim is a PHP micro framework that helps you quickly write simple yet powerful web applications and APIs.

Exakat provides compatibility reports with classes, interfaces and traits from Slim 1.0 to 3.8.



* **Home page** : `http://www.slimframework.com/ <http://www.slimframework.com/>`_
* **Extension page** : `https://github.com/exakat/Exakat4Slim <https://github.com/exakat/Exakat4Slim>`_

Slim analysis
--------------------------------------------------

This extension includes 40 analyzers.

* No Echo In Route Callable (Slim/NoEchoInRouteCallable)
* Slim 3.0 Compatibility (Slim/Slim_3_0)
* Slim 3.1 Compatibility (Slim/Slim_3_1)
* Slim 3.10 Compatibility (Slim/Slim_3_10)
* Slim 3.11 Compatibility (Slim/Slim_3_11)
* Slim 3.12 Compatibility (Slim/Slim_3_12)
* Slim 3.2 Compatibility (Slim/Slim_3_2)
* Slim 3.3 Compatibility (Slim/Slim_3_3)
* Slim 3.4 Compatibility (Slim/Slim_3_4)
* Slim 3.5 Compatibility (Slim/Slim_3_5)
* Slim 3.6 Compatibility (Slim/Slim_3_6)
* Slim 3.7 Compatibility (Slim/Slim_3_7)
* Slim 3.8 Compatibility (Slim/Slim_3_8)
* Slim 3.9 Compatibility (Slim/Slim_3_9)
* Slim Missing Classes (Slim/SlimMissing)
* Slim Usage (Slim/SlimUsage)
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


Slim rulesets
--------------------------------------------------

This extension includes one ruleset : Slim.


Slim reports
--------------------------------------------------

This extension includes one report : Slim.



.. _extension-symfony:

Symfony
#######

This is the Symfony extension for Exakat. 

Symfony is a new generation of Content Management System and eCommerce platform to achieve and manage websites from a single web interface easy to use while offering the best of open source technology.



* **Home page** : `https://symfony.com/ <https://symfony.com/>`_
* **Extension page** : `https://github.com/exakat/Exakat4Symfony <https://github.com/exakat/Exakat4Symfony>`_

Symfony analysis
--------------------------------------------------

This extension includes 10 analyzers.

* Symfony 3.0 Compatibility (Symfony/Symfony_3_0)
* Symfony 3.1 Compatibility (Symfony/Symfony_3_1)
* Symfony 3.2 Compatibility (Symfony/Symfony_3_2)
* Symfony 3.3 Compatibility (Symfony/Symfony_3_3)
* Symfony 3.4 Compatibility (Symfony/Symfony_3_4)
* Symfony 4.0 Compatibility (Symfony/Symfony_4_0)
* Symfony 4.1 Compatibility (Symfony/Symfony_4_1)
* Symfony 4.2 Compatibility (Symfony/Symfony_4_2)
* Symfony Missing (Symfony/SymfonyMissing)
* Symfony Usage (Symfony/SymfonyUsage)


Symfony rulesets
--------------------------------------------------

This extension includes one ruleset : Symfony.


Symfony reports
--------------------------------------------------

This extension includes no specific report. Use generic reports, like Text to access the results.



.. _extension-twig:

Twig
####

This is the Twig extension for Exakat. 

The flexible, fast, and secure template engine for PHP


* **Home page** : `https://twig.symfony.com/index.html <https://twig.symfony.com/index.html>`_
* **Extension page** : ` <>`_

Twig analysis
--------------------------------------------------

This extension includes 9 analyzers.

* Twig 2.0 Compatibility (Twig/Twig_2_0)
* Twig 2.1 Compatibility (Twig/Twig_2_1)
* Twig 2.2 Compatibility (Twig/Twig_2_2)
* Twig 2.3 Compatibility (Twig/Twig_2_3)
* Twig 2.4 Compatibility (Twig/Twig_2_4)
* Twig 2.5 Compatibility (Twig/Twig_2_5)
* Twig 2.6 Compatibility (Twig/Twig_2_6)
* Twig 2.7 Compatibility (Twig/Twig_2_7)
* Twig Usage (Twig/TwigUsage)


Twig rulesets
--------------------------------------------------

This extension includes one ruleset : Twig.


Twig reports
--------------------------------------------------

This extension includes no specific report. Use generic reports, like Text to access the results.



.. _extension-wordpress:

Wordpress
#########

This is the Wordpress extension for Exakat. 

WordPress is open source software you can use to create a beautiful website, blog, or app.

Exakat reports version compatibility with Worpdress 4.0 to 5.0. Exakat also includes extra code validation, inspired by the wordpress PHP guidelines.



* **Home page** : `https://wordpress.org/ <https://wordpress.org/>`_
* **Extension page** : `https://github.com/exakat/Exakat4Wordpress <https://github.com/exakat/Exakat4Wordpress>`_

Wordpress analysis
--------------------------------------------------

This extension includes 39 analyzers.

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
* Wordpress 3.9 Compatibility (Wordpress/Wordpress_3_9)
* Wordpress 4.0 Compatibility (Wordpress/Wordpress_4_0)
* Wordpress 4.0 Undefined Classes (Wordpress/Wordpress40Undefined)
* Wordpress 4.1 Compatibility (Wordpress/Wordpress_4_1)
* Wordpress 4.1 Undefined Classes (Wordpress/Wordpress41Undefined)
* Wordpress 4.2 Compatibility (Wordpress/Wordpress_4_2)
* Wordpress 4.2 Undefined Classes (Wordpress/Wordpress42Undefined)
* Wordpress 4.3 Compatibility (Wordpress/Wordpress_4_3)
* Wordpress 4.3 Undefined Classes (Wordpress/Wordpress43Undefined)
* Wordpress 4.4 Compatibility (Wordpress/Wordpress_4_4)
* Wordpress 4.4 Undefined Classes (Wordpress/Wordpress44Undefined)
* Wordpress 4.5 Compatibility (Wordpress/Wordpress_4_5)
* Wordpress 4.5 Undefined Classes (Wordpress/Wordpress45Undefined)
* Wordpress 4.6 Compatibility (Wordpress/Wordpress_4_6)
* Wordpress 4.6 Undefined Classes (Wordpress/Wordpress46Undefined)
* Wordpress 4.7 Compatibility (Wordpress/Wordpress_4_7)
* Wordpress 4.7 Undefined Classes (Wordpress/Wordpress47Undefined)
* Wordpress 4.8 Compatibility (Wordpress/Wordpress_4_8)
* Wordpress 4.8 Undefined Classes (Wordpress/Wordpress48Undefined)
* Wordpress 4.9 Compatibility (Wordpress/Wordpress_4_9)
* Wordpress 4.9 Undefined Classes (Wordpress/Wordpress49Undefined)
* Wordpress 5.0 Compatibility (Wordpress/Wordpress_5_0)
* Wordpress 5.1 Compatibility (Wordpress/Wordpress_5_1)
* Wordpress Usage (Wordpress/WordpressUsage)
* Wpdb Best Usage (Wordpress/WpdbBestUsage)
* Wpdb Prepare Or Not (Wordpress/WpdbPrepareOrNot)


Wordpress rulesets
--------------------------------------------------

This extension includes one ruleset : Wordpress.


Wordpress reports
--------------------------------------------------

This extension includes no specific report. Use generic reports, like Text to access the results.



.. _extension-zendf:

ZendF
#####

This is the Zend Framework extension for Exakat. 

Zend Framework is a collection of professional PHP packages with more than 345 million installations. It can be used to develop web applications and services using PHP 5.6+, and provides 100% object-oriented code using a broad spectrum of language features.

Exakat reports Zend framework compatibility for over 60 components, from versions 2.5 to 3.x. 


* **Home page** : `https://framework.zend.com/ <https://framework.zend.com/>`_
* **Extension page** : `https://github.com/exakat/Exakat4ZendF <https://github.com/exakat/Exakat4ZendF>`_

ZendF analysis
--------------------------------------------------

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


ZendF rulesets
--------------------------------------------------

This extension includes one ruleset : ZendF.


ZendF reports
--------------------------------------------------

This extension includes one report : ZendFramework.






