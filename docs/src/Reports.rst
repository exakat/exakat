.. Reports:

Obtaining a report 
==================

There are several reports that may be extracted from Exakat : 

* Text
* Json
* XML
* CodeSniffer
* Codeflower
* Owasp
* Ambassador
* Uml
* Plantuml
* Simpletable
* Marmelab
* Drillinstructor
* Clustergrammer
* Composer
* Inventories
* PhpCompilation
* PhpConfiguration
* RadwellCode

   `php exakat.phar report -p <project> -format <format> -file <filename>`

Here is an explanation of all the different steps involved in running exakat on your code.

Common behavior
---------------

Default format is Text. Each report has a default filename, that may be configured with the -file option. Each report adds a file extension to the provided filename. 

A special value for -file is 'stdout'. Some formats may be output to stdout, such as Text or Json. Not all format are accepting that value : some format, like Ambassador or Sqlite, may only be written to directories. 

Each report is stored in its <project> folder, under the requested name.

Reports may be generated at any time, during execution of the analysis (partial results) or later, even if another audit is running. 

Text
----

Very simple text format, one result per line, including : 

   /path/from/project/root/to/file:line[space]name of analysis

Example : 

   /src/NlpTools/Documents/RawDocument.php:10 Class, Interface Or Trait With Identical Names

It may be output to stdout.

Json
----

Simple Json format. It is a structured array with all results, described as object.::

    Filename => [
                    errors   => count,
                    warning  => count,
                    fixable  => count,
                    filename => string,
                    message  => [
                        line => [
                            type,
                            source,
                            severity,
                            fixable,
                            message
                        ]
                    ]
                ]

Example :::

    {  
       "\/src\/Path\/To\/File.php":{  
          "errors":0,
          "warnings":105,
          "fixable":0,
          "filename":"\/src\/Path\/To\/File.php",
          "messages":{  
             "55":[  
                [  
                   {  
                      "type":"warning",
                      "source":"Php/EllipsisUsage",
                      "severity":"Major",
                      "fixable":"fixable",
                      "message":"... Usage"
                   }
                ]
             ],
             }
        }
    }
   
It may be output to stdout.
   
XML
---

XML version of the reports. It uses the same format than PHP Code Sniffer to output the results. 

Here is an extract of the resulting XML 

:::

   <?xml version="1.0" encoding="UTF-8"?>
   <phpcs version="0.8.6">
   <file name="/src/NlpTools/Stemmers/PorterStemmer.php" errors="0" warnings="105" fixable="0">
    <warning line="55" column="0" source="Php/EllipsisUsage" severity="Major" fixable="0">... Usage</warning>
   ....
   
This report may be output to stdout.

CodeSniffer
-----------

This format reports analysis using the Codesniffer's result format. 

Here is an example of the resulting format : 

:::

    FILE : /Path/To/View/The/File.php
    --------------------------------------------------------------------------------
    FOUND 3 ISSUES AFFECTING 3 LINES
    --------------------------------------------------------------------------------
     32 | MINOR | Could Use Alias
     41 | MINOR | Could Make A Function
     43 | MINOR | Could Make A Function
    --------------------------------------------------------------------------------
   ....
   
See also [Code Sniffer Report](https://github.com/squizlabs/PHP_CodeSniffer/wiki/Reporting).

Codeflower
----------

Codeflower is a javascript visualization of the code. It is based on Francois Zaninotto's [CodeFlower Source code visualization](http://www.redotheweb.com/CodeFlower/).

Owasp
-----

The OWASP report is a security report, that focuses on the [OWASP top 10](https://www.owasp.org/index.php/Category:OWASP_Top_Ten_Project). It reports all the security analysis, distributed across the 10 categories of vulnerabilities. 

Ambassador
----------

Ambassador is a standalone full HTML report, meant to be used from a browser. 

Ambassador includes : 

+ Full configuration for the audit
+ Full documentation of the analysis
+ All results, searchable and browsable by file and analysis
+ Extra reports for 
    + Minor versions compatibility
    + PHP Directive usage
    + PHP compilation recommendations
    + Error messages list
    + List of processed files

Uml
---

This report produces a dot file with a representation of the classes used in the repository. 

.dot files are best seen with [graphviz](http://www.graphviz.org/) : they are easily convert into PNG or PDF.

PlanUml
-------

This report produces a .puml file, compatible with [PlantUML](http://plantuml.com/).

PlantUML is an Open Source component that creates class diagrams. 

Simpletable
-----------

Simpletable is a simple table presentation, inspired from the Clang report. The result is a HTML file, with Javascript and CSS. 

This format doesn't support stdout output.

Marmelab
--------

Marmelab is a report format to build GraphQL server with exakat's results. Export the results of the audit in this JSON file, then use the [json-graphql-server](https://github.com/marmelab/json-graphql-server) to have a GraphQL server with all the results.
You may also learn more about GraphQL at [Introducing Json GraphQL Server](https://marmelab.com/blog/2017/07/12/json-graphql-server.html)

:::
    php exakat.phar report -p -format Marmelab -file marmelab
    cp projects/myproject/marmelab.json path/to/marmelab
    json-graphql-server db.json


Clustergrammer
--------------

Clustergrammer is a visualisation tool that may be found online. After generation of this report, a TEXT file is available in the project directory. Upload it on [http://amp.pharm.mssm.edu/clustergrammer/](http://amp.pharm.mssm.edu/clustergrammer/) to visualize it. 

Composer
--------

Composer is a report that enhances your composer.json with all the extensions requirement that your code has. If you don't have a composer.json, exakat produces a simple composer.json with those contraints. 

The composer.json itself is not updated : review all suggestions before actually adopting them.

Inventories
--------------

The inventories report collects literals values from the code. It provides the value, the file and line where it is present. 

The following values and names are inventoried : 

+ Constants
+ Functions
+ Classes
+ Interfaces
+ Traitnames
+ Namespaces
+ Exceptions
+ Variables
+ Incoming Variables
+ Session Variables
+ Global Variables
+ Date formats
+ Regex
+ Integer
+ Real
+ Literal Arrays
+ Strings

Literal values are hardcoded values : strings, integers, floats, heredoc; Structures names, : classes, interfaces, traits, variables, fonctions, constants, namespaces; and special values : etc) and special messages : error messages, compared literals. 

Inventories are great to review spelling, check unusual names and frequencies. 

The result is a directory, containing CSV files. Empty results creates files containing only the headers.

PhpCompilation
---------------

PhpCompilation produces a list of php.ini compilation directives to compile a PHP binary tailored for the code. 

The result itself is a Text file.

PhpConfiguration
----------------

PhpConfiguration suggest a list of directive to check when setting up the hosting server, tailored for the code.

The result is a Text file.


RadwellCode
-----------

RadwellCodes is a report based on Oliver Radwell's [PHP Do And Don't](https://blog.radwell.codes/2016/11/php-dos-donts-aka-programmers-dont-like/)

This is a Text report, with the file name and line of issue, and the report's error. 

Note that all rules are not implemented, especially the 'coding conventions' ones, as this is beyond the scope of this tool.

