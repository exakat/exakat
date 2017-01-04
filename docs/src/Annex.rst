.. Annex:

Annex
=====

* Supported PHP Extensions
* Supported Frameworks
* Recognized Libraries

Supported PHP Extensions
------------------------

PHP extensions are used to check for defined structures (classes, interfaces, etc.), identify dependencies and directives. 

PHP extensions should be provided with the list of structures they define (functions, class, constants, traits, variables, interfaces, namespaces), and directives. 

{{EXTENSION_LIST}}

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

{{LIBRARY_LIST}}

New analyzers
-------------

List of analyzers, by version of introduction, newest to oldest. 

{{ANALYZER_INTRODUCTION}}

External services
-----------------

List of external services whose configuration files has been commited in the code.

{{EXTERNAL_SERVICES_LIST}}

