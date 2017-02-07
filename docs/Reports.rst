.. Reports:

Obtaining a report 
==================

There are several reports that may be extracted from Exakat : 

* Text
* Json
* XML
* Ambassador
* Uml
* Clustergrammer
* Inventories
* PhpCompilation
* PhpConfiguration
* RadwellCode
* Devoops

   `php exakat.phar report -p <project> -format <format> -file <filename>`

Here is an explanation of all the different steps involved in running exakat on your code.

Common behavior
---------------

Default format is Text. Each report has a default filename, that may be configure with -file. 

Some formats may be output to stdout, such as Text or Json. Others, like Ambassador or Sqlite, may only be written to directories. 

Each report adds (or not) an file extension to the provided filename. 

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

Here is an extra of the XML :::

   <?xml version="1.0" encoding="UTF-8"?>
   <phpcs version="0.8.6">
   <file name="/src/NlpTools/Stemmers/PorterStemmer.php" errors="0" warnings="105" fixable="0">
    <warning line="55" column="0" source="Php/EllipsisUsage" severity="Major" fixable="0">... Usage</warning>
   ....
   
This report may be output to stdout.

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

.dot files are best seen with `graphviz <http://www.graphviz.org/>`_ : they are easily convert into PNG or PDF.

Clustergrammer
--------------

Clustergrammer is a visualisation tool that may be found online. After generation of this report, a TEXT file is available in the project directory. Upload it on `http://amp.pharm.mssm.edu/clustergrammer/ <http://amp.pharm.mssm.edu/clustergrammer/>`_ to visualize it. 

Inventories
--------------

Inventories collects straight values from the code. Straight values are literals : strings, integers, floats, heredoc; Structures names, : classes, interfaces, traits, variables, fonctions, constants, namespaces; and special values : etc) and special messages : error messages, compared literals. 
It provides the identified value, the file and line where it is present. 

Inventories are great to review spelling, check unusual names and frequencies. 

The result is a directory, containing CSV files. Empty results creates files containing only the headers.

PhpCompilation
----------------

PhpCompilation produces a list of php.ini compilation directives to compile a PHP binary tailored for the code. 

The result itself is a Text file.

PhpConfiguration
----------------

PhpConfiguration suggest a list of directive to check when setting up the hosting server, tailored for the code.

The result is a Text file.


RadwellCode
-----------

RadwellCodes is a report based on Oliver Radwell's `PHP Do And Don't <https://blog.radwell.codes/2016/11/php-dos-donts-aka-programmers-dont-like/>`_

This is a Text report, with the file name and line of issue, and the report's error. 

Note that all rules are not implemented, especially the 'coding conventions' ones, as this is beyond the scope of this tool.

Devoops
-------

Devoops report is retired. It is not updated anymore, and will soon be removed from Exakat.
Devoops is a standalone full HTML report, meant to be used from a browser. It is the first version of the HTML report, and is being replaced by the Ambassador report. During the migration, you may still use this report. 

Devoops includes : 

+ Full configuration for the audit
+ Full documentation of the analysis
+ All results, searchable and browsable by file and analysis
+ Extra reports for 
    + Minor versions compatibility
    + PHP Directive usage
    + List of processed files
    + List of dependant libraries
