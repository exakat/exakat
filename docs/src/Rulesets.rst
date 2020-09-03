.. _Rulesets:

Rulesets
********

Presentation
############

Analysis are grouped in different rulesets, that may be run independantly. Each ruleset has a focus target, 

Rulesets runs all its analysis and any needed dependency.

Rulesets are configured with the -T option, when running exakat in command line. For example : 

::

   php exakat.phar analyze -p <project> -T <Security>



List of rulesets
################

Here is the list of the current rulesets supported by Exakat Engine.

+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|Name                                           | Description                                                                                          |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Analyze`                                 | Check for common best practices.                                                                     |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CI-checks`                               | Quick check for common best practices.                                                               |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Dead code <dead-code>`                   | Check the unused code or unreachable code.                                                           |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Suggestions`                             | List of possible modernisation of the PHP code.                                                      |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP74`                      | List features that are incompatible with PHP 7.4. It is known as php-src, work in progress.          |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP73`                      | List features that are incompatible with PHP 7.3.                                                    |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP72`                      | List features that are incompatible with PHP 7.2.                                                    |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP71`                      | List features that are incompatible with PHP 7.1.                                                    |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP80`                      | Work in progress. The first rules are in, but far from finished                                      |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Performances`                            | Check the code for slow code.                                                                        |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Security`                                | Check the code for common security bad practices, especially in the Web environnement.               |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Top10`                                   | The most common issues found in the code                                                             |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Classreview`                             | A set of rules dedicate to class hygiene                                                             |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`LintButWontExec`                         | Check the code for common errors that will lead to a Fatal error on production, but lint fine.       |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP70`                      | List features that are incompatible with PHP 7.0.                                                    |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP56`                      | List features that are incompatible with PHP 5.6.                                                    |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP55`                      | List features that are incompatible with PHP 5.5.                                                    |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP54`                      | List features that are incompatible with PHP 5.4.                                                    |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`CompatibilityPHP53`                      | List features that are incompatible with PHP 5.3.                                                    |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Coding Conventions <coding-conventions>` | List coding conventions violations.                                                                  |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Semantics`                               | Checks the meanings found the names of the code.                                                     |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Typechecks`                              | Checks related to types.                                                                             |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`Rector`                                  | Suggests configuration to apply changes with Rector                                                  |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+
|:ref:`php-cs-fixable`                          | Suggests configuration to apply changes with PHP-CS-FIXER                                            |
+-----------------------------------------------+------------------------------------------------------------------------------------------------------+

Note : in command line, don't forget to add quotes to rulesets' names that include white space.

Rulesets details
################

.. comment: The rest of the document is automatically generated. Don't modify it manually. 
.. comment: Rulesets details
.. comment: Generation date : Mon, 10 Oct 2016 10:17:00 +0000
.. comment: Generation hash : d4a634700b94af15c6612b44000d8e148260503b

