.. _Tutorial:

Exakat tutorial
***************

Installation
------------

Refer to the _Installation section to install quickly Exakat.


Initialization
--------------

A simple run for the report : 

::

    php exakat.phar init -p sculpin -R https://github.com/sculpin/sculpin

This will init the project in the 'projects' folder, with the name 'sculpin', then clone the code with the provided repository. 

More information on `command line usage <https://exakat.readthedocs.io/en/latest/Commands.html>`_.

Execution
---------

After initialization, run : 

:: 

    php exakat.phar project -p sculpin

This will run the whole analysis.

Once it is finished, the reports are in the folder `projects/sculpin/report` (HTML version) or `projects/sculpin/faceted` (Faceted version). Simply open the 'index.html' file in a browser.

Other reports
-------------

Once a report has been fully run, you may run the report command to access different report. Usually, 'Devoops' has the most complete report, but other focused reports are available. 

It is possible to access all report, even if another project is being processed. 

:: 

    php exakat.phar report -p sculpin -format Uml -file uml

This export the current project in UML format. The file is called 'uml.dot' : dot is added by exakat, as the report has to be opened by graphviz compatible software.
The full list of available reports are in the 'Command' section.

Once it is finished, the reports are in the folder `projects/sculpin/report` (HTML version) or `projects/sculpin/faceted` (Faceted version). Simply open the 'index.html' file in a browser.

New run
-------

After some modification in the code, commit them in the repository. Then, run : 

:: 

    php exakat.phar update -p sculpin
    php exakat.phar project -p sculpin

This update the repository to the last modification, then runs the whole analysis. If the code is not using a VCS repository, such as git, mercurial, SVN, etc. Then the update command has no impact on the code. You should update the code manually, by replacing it with a newer version.

Once it is finished, the report are in the same previous folders : `projects/sculpin/report` (HTML version) or `projects/sculpin/faceted` (Faceted version). Simply open the 'index.html' file in a browser.

The reports replace any previous report. If you want to keep a report of a previous version, move it away from the current location, and give it another name.


`Text report`_
--------------------

::

   #If you just ran that, you may skip the two following commands
   php exakat.phar init -p sculpin -R https://github.com/sculpin/sculpin.git
   php exakat.phar project -p sculpin 

   #report to file exakat.txt
   php exakat.phar report -p sculpin -format Text -T Analyze -f 

   #report to stdout
   php exakat.phar report -p sculpin -format Text -T Analyze -file stdout
   

`Json report`_
--------------------

::

   #If you just ran that, you may skip the two following commands
   php exakat.phar init -p sculpin -R https://github.com/sculpin/sculpin.git
   php exakat.phar project -p sculpin 

   #report to stdout
   php exakat.phar report -p sculpin -format Json -T Analyze -file stdout

   #report to file exakat.json
   php exakat.phar report -p sculpin -format Text -T Analyze 


`Inventories report`_
---------------------

The Inventories report is not a default report. It may be added to config.ini.
::

   #If you just ran that, you may skip the two following commands
   php exakat.phar init -p sculpin -R https://github.com/sculpin/sculpin.git
   php exakat.phar project -p sculpin 
   php exakat.phar analyze -p sculpin -T Inventories

   #report to inventories folder
   php exakat.phar report -p sculpin -format Inventories -T Inventories

`Getting One Analysis Results`_
-------------------------------

Analysis names are in the documentations. Check the ambassador report, or read `the docs online <http://exakat.readthedocs.io/en/latest/Rules.html>`_.

::

   #If you just ran that, you may skip the two following commands
   php exakat.phar init -p sculpin -R https://github.com/sculpin/sculpin.git
   php exakat.phar project -p sculpin 
   php exakat.phar analyze -p sculpin -P Structures/NoDanglingReference 

   #report in Text, for only one analysis
   php exakat.phar report -p sculpin -format Text -P Structures/NoDanglingReference 


