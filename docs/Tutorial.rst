.. _Tutorial:

Exakat tutorial
***************

Installation
------------

Refer to the _Installation section to install quickly Exakat.


Initialization
--------------

Start by obtaining the code for the audit. This has to be done once.

::

    php exakat.phar init -p sculpin -R https://github.com/sculpin/sculpin

This command inits the project in the 'projects' folder, with the name 'sculpin', then clone the code with the provided repository. 

Exakat requires a copy of the code. When accessing via VCS, such as git, mercurial, svn, etc., read-only access is sufficient and recommended. Exakat doesn't write anything in the code.

More information on `command line usage <https://exakat.readthedocs.io/en/latest/Commands.html>`_.

Execution
---------

After initialization, an audit may be run : 

:: 

    php exakat.phar project -p sculpin

This command runs the whole cycle : code loading, code audits and report building. It works without initial configuration. 

Once it is finished, the reports are in the folder `projects/sculpin/report` (HTML version). Simply open the 'projects/sculpin/report/index.html' file in a browser.

More reports
------------

Once the 'project' command has been fully run, you may run the 'report' command to access different report. Usually, 'Ambassador' has the most complete report, but other focused reports are available. 

It is possible to access all report, even if another project is being processed. 

:: 

    php exakat.phar report -p sculpin -format Uml -file uml

This export the current project in UML format. The file is called 'uml.dot' : dot is added by exakat, as the report has to be opened by graphviz compatible software.

The full list of available reports are in the 'Command' section.

Once it is finished, the reports are in the folder `projects/sculpin/*`.

New run
-------

After some modification in the code, commit them in the repository. Then, run : 

:: 

    php exakat.phar update -p sculpin
    php exakat.phar project -p sculpin

This update the repository to the last modification, then runs the whole analysis. If the code is not using a VCS repository, such as git, mercurial, SVN, etc. Then the update command has no impact on the code. You should update the code manually, by replacing it with a newer version.

Once it is finished, the report are in the same previous folders : `projects/sculpin/report` (HTML version).

The reports replace any previous report. To keep a report of a previous version, move it away from the current location, and give it another name.


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
