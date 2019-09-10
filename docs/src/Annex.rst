.. Annex:

Annex
=====

* Supported Rulesets
* Supported Reports
* Supported PHP Extensions
* Supported Frameworks
* Applications
* Recognized Libraries
* New analyzers
* External services
* PHP Error messages

Supported Rulesets
------------------

Exakat groups analysis by rulesets. This way, analyzing 'Security' runs all possible analysis related to security. One analysis may belong to multiple rulesets.

{{RULESETS_LIST}}

Supported Reports
-----------------

Exakat produces various reports. Some are general, covering various aspects in a reference way; others focus on one aspect. 

{{REPORTS_LIST}}

Supported PHP Extensions
------------------------

PHP extensions are used to check for structures usage (classes, interfaces, etc.), to identify dependencies and directives. 

PHP extensions are described with the list of structures they define : functions, classes, constants, traits, variables, interfaces, namespaces, and directives. 

{{EXTENSION_LIST}}

Supported Frameworks
--------------------

Frameworks, components and libraries are supported via Exakat extensions.

{{EXAKAT_EXTENSION_LIST}}


Applications
------------

A number of applications were scanned in order to find real life examples of patterns. They are listed here : 

{{APPLICATIONS}}

Recognized Libraries
--------------------

Libraries that are popular, large and often included in repositories are identified early in the analysis process, and ignored. This prevents Exakat to analysis some code foreign to the current repository : it prevents false positives from this code, and make the analysis much lighter. The whole process is entirely automatic. 

Those libraries, or even some of the, may be included again in the analysis by commenting the ignored_dir[] line, in the projects/<project>/config.ini file. 

{{LIBRARY_LIST}}

New analyzers
-------------

List of analyzers, by version of introduction, newest to oldest. In parenthesis, the first element is the analyzer name, used with 'analyze -P' command, and the seconds, if any, are the ruleset, used with the -T option. Rulesets are separated by commas, as the same analysis may be used in several rulesets.

{{ANALYZER_INTRODUCTION}}

PHP Error messages
------------------

Exakat helps reduce the amount of error and warning that code is producing by reporting pattern that are likely to emit errors.

{{PHP_ERROR_MESSAGES}}


External services
-----------------

List of external services whose configuration files has been commited in the code.

{{EXTERNAL_SERVICES_LIST}}

External links
--------------

List of external links mentionned in this documentation.

{{URL_LIST}}

Ruleset configurations
----------------------

INI configuration for built-in rulesets. Copy them in config/themes.ini, and make your owns.

{{INI_RULESETS}}

