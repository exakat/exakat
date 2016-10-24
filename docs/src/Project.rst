.. Project:

Running an audit
================

Once installed, running an audit with Exakat is as simple as : 

    php exakat.phar project -p <project> -v 
    
Several operations are applied to the PHP code, so as to lead you to a useful report. 

* Counting files
* Removing libraries
* Removing uncompilable files
* Loading the tokens
* Running the analysis
* Exporting the reports

Here is an explanation of all the different steps involved in running exakat on your code.

Counting files
--------------

The first step of the analysis is finding the PHP files in the repository. Files are scanned in the root folder. Depending on configuration, some folders/files are excluded.

In the config.ini (projects/<project>/config.ini) ignore_dirs are ignored. Also, file_extensions are selected.


Removing libraries
------------------

This step removes a short list of classic libraries that are often included in repositories, but are not part of it. For example, PDF or ZIP libraries are often quite big, but shouldn't be audited. This is the step when they are spotted and removed.

The list of library include large and popular libraries. Smaller libraries or less popular ones may still be included in the final audit. 

Removing uncompilable files
---------------------------

Depending on the target PHP version configured in the config.ini, the files are then tested for compilation. Files that do not compile are noted and reported, but excluded from the analysis.

Loading the tokens
------------------

From the file list previously build, files are tokenized using PHP and the ext/tokenizer extension (part of core). 

Tokens are then cleaned of all no-effect tokens, such as comments, white space and docs. Also, most characters acting as delimiters, such as \", \', \(, \), \[, \], \{, \}, \`, ... are removed and stored as part of the AST.

The final tokens are then loaded in the database, and completed with extra indices, such as function definitions, no-delimiter version of the strings, or fully qualified name for classes, functions, constants... 

Running the analysis
--------------------

Once in the database, the tokens are ready for being analyzed. Analysis are grouped by recipes, for easier management, and each analysis may be run independantly.

A standard audit run the following recipes : 
+ Analysis : all purpose code review
+ Dead code : focus on unused or unreachable code
+ Security : special analysis for security of web applications
+ Performances : focus on speedy syntax and functions
+ Compatibitity* : There are compatibility recipes for minor and major versions of PHP : 5.4, 5.5, 5.6, 7.0 and 7.1. 

Exporting the reports
---------------------

At the end of a full audit, but actually at any time of the running, one may export the results to any available format. Various reports format are available. 

Note that reports may be generated during analysis (albeit, not complete), or at any later time, even when a new audit has been run. 

There are several reports formats, which are detailed in the report section.

* Text
* Json
* XML
* Ambassador
* Devoops
* Sqlite
* Clustergrammer
