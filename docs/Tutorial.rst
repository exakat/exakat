.. _Tutorial:

Exakat tutorials
**************** 

Here are four tutorials to run Exakat on your code quickly. You may install exakat with the projects folder, and centralize your audits in one place, or run exakat in-code, folder by folder. You may also run exakat with a bare-metal installation, or as a docker container.

+ Bare metal install
 + with projects folder
 + within the code
+ Docker container
 + with projects folder
 + within the code
 
All four tutorials offer the same steps : 
+ Project initialisation
+ Audit run
+ Reports access

Bare metal install, with projects folder
----------------------------------------

Installation
____________

Refer to the _Installation section to install quickly Exakat.


Initialization
______________

Start by obtaining the code for the audit. This has to be done once.

::

    php exakat.phar init -p sculpin -R https://github.com/sculpin/sculpin

This command inits the project in the 'projects' folder, with the name 'sculpin', then clone the code with the provided repository. 

Exakat requires a copy of the code. When accessing via VCS, such as git, mercurial, svn, etc., read-only access is sufficient and recommended. Exakat doesn't write anything in the code.

More information on `command line usage <https://exakat.readthedocs.io/en/latest/Commands.html>`_.

Execution
_________

After initialization, an audit may be run : 

:: 

    php exakat.phar project -p sculpin

This command runs the whole cycle : code loading, code audits and report building. It works without initial configuration. 

Once it is finished, the reports are in the folder `projects/sculpin/report` (HTML version). Simply open the 'projects/sculpin/report/index.html' file in a browser.

More reports
____________

Once the 'project' command has been fully run, you may run the 'report' command to access different report. Usually, 'Ambassador' has the most complete report, but other focused reports are available. 

It is possible to access all report, even if another project is being processed. 

:: 

    php exakat.phar report -p sculpin -format Uml -file uml

This export the current project in UML format. The file is called 'uml.dot' : dot is added by exakat, as the report has to be opened by graphviz compatible software.

The full list of available reports are in the 'Command' section.

Once it is finished, the reports are in the folder `projects/sculpin/*`.

New run
_______

After some modification in the code, commit them in the repository. Then, run : 

:: 

    php exakat.phar update -p sculpin
    php exakat.phar project -p sculpin

This update the repository to the last modification, then runs the whole analysis. If the code is not using a VCS repository, such as git, mercurial, SVN, etc. Then the update command has no impact on the code. You should update the code manually, by replacing it with a newer version.

Once it is finished, the report are in the same previous folders : `projects/sculpin/report` (HTML version).

The reports replace any previous report. To keep a report of a previous version, move it away from the current location, and give it another name.


`Text report`_
______________

::

   #If you just ran that, you may skip the two following commands
   php exakat.phar init -p sculpin -R https://github.com/sculpin/sculpin.git
   php exakat.phar project -p sculpin 

   #report to file exakat.txt
   php exakat.phar report -p sculpin -format Text -T Analyze -f 

   #report to stdout
   php exakat.phar report -p sculpin -format Text -T Analyze -file stdout
   

`Json report`_
______________

::

   #If you just ran that, you may skip the two following commands
   php exakat.phar init -p sculpin -R https://github.com/sculpin/sculpin.git
   php exakat.phar project -p sculpin 

   #report to stdout
   php exakat.phar report -p sculpin -format Json -T Analyze -file stdout

   #report to file exakat.json
   php exakat.phar report -p sculpin -format Text -T Analyze 


`Inventories report`_
_____________________

The Inventories report is not a default report. It may be added to config.ini.
::

   #If you just ran that, you may skip the two following commands
   php exakat.phar init -p sculpin -R https://github.com/sculpin/sculpin.git
   php exakat.phar project -p sculpin 
   php exakat.phar analyze -p sculpin -T Inventories

   #report to inventories folder
   php exakat.phar report -p sculpin -format Inventories -T Inventories


Bare metal install, within the code
-----------------------------------

This tutorial installs exakat on the system, and run it from the source code repository.

Installation
____________

Refer to the _Installation section to install quickly Exakat.


Initialization
______________

Go to the directory that contains the source code you plan to update. 

Create a .exakat.yml file at the root of the source code, with, at minimum, the `project: "name"` entry. `name` is a string, and used for identification purposes. 

Execution
_________

After creating the configuration file above, an audit may be run : 

:: 

docker run -it --rm -w /src -v $(pwd):/src --entrypoint "/usr/src/exakat/exakat.phar" exakat/exakat:latest project

This command runs the whole cycle : code loading, code audits and report building. It works without initial configuration. 

Once it is finished, the reports are in the current folder. Simply open the 'report/index.html' file in a browser.

More reports
____________

When running exakat inside code, audits must be configured before the run of the audit. 

Edit the .exakat.yml file, and add the following lines : 

:: 

project_reports = { "Uml",
                    "Plantuml",
                    "Ambassador"}


Then, run the audit as explained in the previous section. 

This configuration produces 3 reports : "Ambassador", which is the default report, "Uml", available in the 'uml.dot' file, and "Plantuml", that may be opened with `plantuml <http://plantuml.com/>`_.

The full list of available reports are in the 'Command' section.

New run
_______

After some modification in the code, run again exakat with the same command than the first time. Since the audit is run within the code source, no update operation is needed.

Check the `config.ini` file before running the audit, to check if all the reports you want are configureds.

:: 

docker run -it --rm -w /src -v $(pwd):/src --entrypoint "/usr/src/exakat/exakat.phar" exakat/exakat:latest project


Docker container, with projects folder
--------------------------------------

This tutorial runs exakat audits from the source code repository.

Installation
____________

Refer to the _Installation section to install quickly Exakat with docker.


Initialization
______________

Go to the directory that contains the source code you plan to update. 

Create a .exakat.yml file at the root of the source code, with, at minimum, the `project: "name"` entry. `name` is a string, and used for identification purposes. 

Execution
_________

After creating the configuration file above, an audit may be run : 

:: 

docker run -it --rm -w /src -v $(pwd):/src --entrypoint "/usr/src/exakat/exakat.phar" exakat/exakat:latest project

This command runs the whole cycle : code loading, code audits and report building. It works without initial configuration. 

Once it is finished, the reports are in the current folder. Simply open the 'report/index.html' file in a browser.

More reports
____________

When running exakat inside code, audits must be configured before the run of the audit. 

Edit the .exakat.yml file, and add the following lines : 

:: 

project_reports = { "Uml",
                    "Plantuml",
                    "Ambassador"}


Then, run the audit as explained in the previous section. 

This configuration produces 3 reports : "Ambassador", which is the default report, "Uml", available in the 'uml.dot' file, and "Plantuml", that may be opened with `plantuml <http://plantuml.com/>`_.

The full list of available reports are in the 'Command' section.

New run
_______

After some modification in the code, run again exakat with the same command than the first time. Since the audit is run within the code source, no update operation is needed.

Check the `config.ini` file before running the audit, to check if all the reports you want are configureds.

:: 

docker run -it --rm -w /src -v $(pwd):/src --entrypoint "/usr/src/exakat/exakat.phar" exakat/exakat:latest project


Docker container, with projects folder
----------------------------------------

This tutorial runs exakat audits from the source code repository.

Installation
____________

Refer to the _Installation section to install quickly Exakat with docker.


Initialization
______________

Go to the directory that contains the source code you plan to update. 

Create a .exakat.yml file at the root of the source code, with, at minimum, the `project: "name"` entry. `name` is a string, and used for identification purposes. 

Execution
_________

After creating the configuration file above, an audit may be run : 

:: 

docker run -it --rm -w /src -v $(pwd):/src --entrypoint "/usr/src/exakat/exakat.phar" exakat/exakat:latest project

This command runs the whole cycle : code loading, code audits and report building. It works without initial configuration. 

Once it is finished, the reports are in the current folder. Simply open the 'report/index.html' file in a browser.

More reports
____________

When running exakat inside code, audits must be configured before the run of the audit. 

Edit the .exakat.yml file, and add the following lines : 

:: 

project_reports = { "Uml",
                    "Plantuml",
                    "Ambassador"}


Then, run the audit as explained in the previous section. 

This configuration produces 3 reports : "Ambassador", which is the default report, "Uml", available in the 'uml.dot' file, and "Plantuml", that may be opened with `plantuml <http://plantuml.com/>`_.

The full list of available reports are in the 'Command' section.

New run
_______

After some modification in the code, run again exakat with the same command than the first time. Since the audit is run within the code source, no update operation is needed.

Check the `config.ini` file before running the audit, to check if all the reports you want are configureds.

:: 

docker run -it --rm -w /src -v $(pwd):/src --entrypoint "/usr/src/exakat/exakat.phar" exakat/exakat:latest project
