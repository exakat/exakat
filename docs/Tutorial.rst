.. _Tutorial:

Tutorials
*********

Here are four tutorials to run Exakat on your code. You may install exakat with the projects folder, and centralize your audits in one place, or run exakat in-code, right from the source code. You may also run exakat with a bare-metal installation, or as a docker container.

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

Refer to the _Installation section to install Exakat.


Initialization
______________

First, fetch the code to be audited. This has to be done once.

::

    php exakat.phar init -p sculpin -R https://github.com/sculpin/sculpin

This command inits the project in the 'projects' folder, with the name 'sculpin', then clone the code with the provided repository. 

Exakat requires a copy of the code. When accessing via VCS, such as git, mercurial, svn, etc., read-only access is sufficient and recommended. Exakat doesn't write anything in the code.

More information on _Commands.

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

    php exakat.phar update  -p sculpin
    php exakat.phar project -p sculpin

This update the repository to the last modification, then runs the whole analysis. If the code is not using a VCS repository, such as git, mercurial, SVN, etc. Then the update command has no impact on the code. You should update the code manually, by replacing it with a newer version.

Once it is finished, the report are in the same previous folders : `projects/sculpin/report` (HTML version).

The reports replace any previous report. To keep a report of a previous version, move it away from the current location, and give it another name.

Bare metal install, within the code
-----------------------------------

This tutorial runs exakat from the source code repository.

Installation
____________

Refer to the _Installation section to install Exakat.


Initialization
______________

Go to the directory that contains the source code.

Create a configuration file called `.exakat.yml`, with the following content : 

:: 

    project: "name"

This is the minimum configuration for that file. You may read more about _Configuration in the dedicated section.

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

    project: "name"
    project_reports: 
      - Uml
      - Plantuml
      - Ambassador

Then, run the audit as explained in the previous section. 

This configuration produces 3 reports : "Ambassador", which is the default report, "Uml", available in the 'uml.dot' file, and "Plantuml", that may be opened with `plantuml <http://plantuml.com/>`_.

The full list of available reports are in the 'Command' section.

New run
_______

After some modification in the code, run again exakat with the same command than the first time. Since the audit is run within the code source, no update operation is needed.

Check the `config.ini` file before running the audit, to check if all the reports you want are configureds.

:: 

    docker run -it --rm -w /src -v $(pwd):/src --entrypoint "/usr/src/exakat/exakat.phar" exakat/exakat:latest project


Docker container, within the code folder
-----------------------------------------

This tutorial runs exakat audits from the source code repository, with a docker container.

Installation
____________

Refer to the _Installation section to install Exakat on docker.


Initialization
______________

Go to the directory that contains the source code.

Create a configuration file called `.exakat.yml`, with the following content : 

:: 

    project: "name"

This is the minimum configuration for that file. You may read more about _Configuration in the dedicated section.

Execution
_________

After creating the configuration file, an audit may be run from the same directory: 

:: 

    docker run -it --rm -v $(`pwd`):/src exakat/exakat:latest exakat project

This command runs the whole cycle : code loading, code audits and report building. It works without initial configuration. 

Once it is finished, the report is displayed on the standard output (aka, the screen).

More reports
____________

When running exakat inside code, reports must be configured before the run of the audit : they will be build immediately. 

Edit the .exakat.yml file, and add the following lines : 

:: 

    project: "name"
    project_reports: 
      - Uml
      - Plantuml
      - Ambassador


Then, run the audit as explained in the previous section. 

This configuration produces 3 reports : "Ambassador", which is the default report, "Uml", available in the 'uml.dot' file, and "Plantuml", that may be opened with `plantuml <http://plantuml.com/>`_.

The full list of available reports are in the _Reports section.

New run
_______

After adding some modifications to the code, run again exakat with the same command than the first time. Since the audit is run within the code source, no explicit update operation is needed.

Check the `.exakat.yml` file before running the audit, to check if all the reports you want are configured.

:: 

    docker run -it --rm -w /src -v $(pwd):/src --entrypoint "/usr/src/exakat/exakat.phar" exakat/exakat:latest project


Docker container, with projects folder
----------------------------------------

This tutorial runs exakat audits, when source code are organized in the `projects` folder. Any folder will do, since exakat is now hosted in the docker image.

Initialization
______________

Go to the directory that contains the 'projects' folder. 

Init the project with the following command : 

::

  docker run -it --rm -v /Users/famille/Desktop/analyzeG3/projects:/usr/src/exakat/projects exakat/exakat:latest exakat init -p sculpin -R https://github.com/sculpin/sculpin -git

This will create a 'projects/sculpin' folder, with various documents and folder. The most important folder being 'code', where the code of the project is fetched, an cached. See _Commands for more details about the `init` command.

Execution
_________

After creating the project, an audit may be run from the same directory: 

:: 

    docker run -it --rm -v /Users/famille/Desktop/analyzeG3/projects:/usr/src/exakat/projects exakat/exakat:dev exakat project -p sculpin 

This command runs the whole cycle : code loading, code audits and report building. 

Once it is finished, the report is available in the `projects/sculpin/report/` folder. Open `projects/sculpin/report/index.htmll` with a browser.

More reports
____________

When running exakat with the projects folder, reports may be configured before the run of the audit, in the config.ini file, or in command line, or extracted after the run.

After a first audit, use the `report` command. Here is an example with the `Uml` report. 

:: 

    docker run -it --rm -v /Users/famille/Desktop/analyzeG3/projects:/usr/src/exakat/projects exakat/exakat:dev exakat report -p sculpin -format Uml 
    
Reports may only be build if the analysis they depend on, were already processed.

In command line, use the `-format` option, multiple times if necessary.

:: 

    docker run -it --rm -v /Users/famille/Desktop/analyzeG3/projects:/usr/src/exakat/projects exakat/exakat:dev exakat project -p sculpin -format Uml 

In config.ini, edit the `projects/sculpin/report/config.ini` file, and add the following lines : 

:: 

    project_reports[] = 'Uml';
    project_reports[] = 'Plantuml';
    project_reports[] = 'Ambassador';


Then, run the audit as explained in the previous section. 

The full list of available reports are in the _Reports section.

New run
_______

After adding some modifications to the code and committing them, you need to update the code before running it again : otherwise, it will run on the previous version of the code. 

:: 

    docker run -it --rm -v /Users/famille/Desktop/analyzeG3/projects:/usr/src/exakat/projects exakat/exakat:dev exakat update -p sculpin 
    docker run -it --rm -v /Users/famille/Desktop/analyzeG3/projects:/usr/src/exakat/projects exakat/exakat:dev exakat project -p sculpin
