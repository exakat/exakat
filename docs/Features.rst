.. Features:

Exakat features
===============

Features list
-------------

* 344 analyzers
* Compatible with PHP 5.3 to 7.1-dev
* Migration analyzers from 5.2 to 5.3 to 7.2 to dev
* List bug fixes for your code
* appinfo(): the list of PHP features
* List PHP directives that impact your code
* Class Hierarchy Diagram

344 analyzers
-----------------------------

There are currently 344 different analyzers that check the PHP code to report code smells. Analyzers are inspired by PHP manual, migration documents, community good practices, computer science or simple logic. 

Some of them track rare occurrences, and some are frequent. Some track careless mistakes and some are highly complex situations. In any case, exakat has your back, and will warn you. 

Compatible with PHP 5.3 to 7.2-dev
----------------------------------

The Exakat engine runs on PHP 7.1, 7.0, 5.6. It is checked against newer version by itself, and it probably runs on older versions. 
It may examine code that range from PHP 5.2 to PHP 7.1-dev. 

Migration guide from 5.2 to 5.3 to 7.2 to dev
---------------------------------------------

Every middle version of PHP comes with its migration guide from the manual, and from community's feedback. Incompatibilities are included as analyzers in Exakat, and report everything they can find that may prevent you from moving to the newer version. 

Although they won't catch it all, they do reduce the amount of unexpected surprises by a lot.

List bug fixes for your code
----------------------------

Every minor version of PHP comes with bug fixes and modifications at the function level. Some special situations are better handled, and that may have impact in your code. Every modified function, class, trait or interface that is also found in your code is reported here, giving a good overview of the impact of every minor version.

Safe bet : keep up to date! 

appinfo(): the list of PHP features
-----------------------------------

Do you know the PHP features that your application rely upon ? Recursivité, reflexion, backticks or anonymous classes ? 
Exakat collect all those features, and sum them up in one nice table, so you know all of it.

List PHP directives that impact your code
-----------------------------------------

exakat recommends which PHP directives to check while preparing your code for production. If 'memory_limit' is an ever green, may be 'post_max_size' (linked to file_upload), or assertions shouldn't be forgotten.
Based on feature and extension usage, it also list the most important directives, and leads you to the full manual list, in case you want to fine tune it to the max. Use it as a reminder. 

Class Hierarchy Diagram
-----------------------

Exakat provides a full class diagramm, based on inheritance (classes), usage (traits) and implementations (interfaces), grouped by namespaces. 

