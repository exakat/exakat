.. _Commands:

Exakat commands
===============

List of commands :
------------------

* `anonymize`_
* `baseline`_
* `catalog`_
* `clean`_
* `cleandb`_
* `doctor`_
* `help`_
* `init`_
* `project`_
* `report`_
* `remove`_
* `show`_
* `update`_
* `upgrade`_
* `install`_

anonymize
---------

Read files, directory or projects, and produce a anonymized version of the code. 
Consistence between variables and names is preserved ($a is always replaced with the same name). 
PHP language structures, such as eval, isset or unset are preserved, though other native functions are not.

File structure is not preserved : all files are renamed, and the hiearchy is flattented in one folder.
As such, code is probably un-runnable if it relies on inclusions. 

Non-PHP files, non-lintable or files that produces one PHP token are ignored.

Command
#######
::

    exakat anonymize -p <project> 
    exakat anonymize -d <directory> 
    exakat anonymize -file <filename> 

Options
#######

+-----------+-----+-----------------------------------------------------------------------------+
| Option    | Req | Description                                                                 |
+-----------+-----+-----------------------------------------------------------------------------+
| -p        | No  | Project name. Should be filesystem compatible (avoid /, : or \)             |
|           |     | This takes into account <project> configuration                             |
+-----------+-----+-----------------------------------------------------------------------------+
| -d        | No  | Directory to anonymize. Results aree in <directory>.anon                    |
+-----------+-----+-----------------------------------------------------------------------------+
| -file     | No  | File to anonymize. Results are in <file>.anon                               |
+-----------+-----+-----------------------------------------------------------------------------+
| -v        | No  | Verbose mode                                                                |
+-----------+-----+-----------------------------------------------------------------------------+

Tips
####

* `-R` is not compulsory : you may omit it, then, provide PHP files in the `projects/<name>/code` folder by the mean you want.

:: _baseline:

baseline
--------

Baseline manage previous audits that may be used as a baseline for new audits. 

A Baseline is a previous audit, that has already reviewed the code. It has identified issues and code. Later, after some code modification, a new audit is run. When we want to know the new issues, or the removed ones, it has to be compared to a baseline.

This is a help command, to help find the available values for various options.

Commands
########

+-----------+-----------------------------------------------------------------------------+
| Command   | Description                                                                 |
+-----------+-----------------------------------------------------------------------------+
| list      | List all available baselines. Default action                                |
+-----------+-----------------------------------------------------------------------------+
| remove    | Removes a baseline, using its name or its auto-id                           |
+-----------+-----------------------------------------------------------------------------+
| save      | Save the current audit, when it exists, as the last base, with the provided |
|           | name.                                                                       |
+-----------+-----------------------------------------------------------------------------+

:: _catalog:

catalog
-------

Catalog list all available rulesets and reports with the current exakat.

This is a help command, to help find the available values for various options.

Options
#######

+-----------+-----+-----------------------------------------------------------------------------+
| Option    | Req | Description                                                                 |
+-----------+-----+-----------------------------------------------------------------------------+
| -json     | No  | Returns the catalog as JSON, for further processing.                        |
+-----------+-----+-----------------------------------------------------------------------------+

:: _clean:

clean
-----

Cleans the provided project of everything except the config.ini and the code. 

This is a maintenance command, that removes all produced files and folder, and restores a project to its initial state.

Options
#######

+-----------+-----+-----------------------------------------------------------------------------+
| Option    | Req | Description                                                                 |
+-----------+-----+-----------------------------------------------------------------------------+
| -p        | Yes | Project name. Should be an existing project.                                |
+-----------+-----+-----------------------------------------------------------------------------+
| -v        | No  | Verbose mode                                                                |
+-----------+-----+-----------------------------------------------------------------------------+

:: _cleandb:

cleandb
-------

Cleans the graph database. 

This is a maintenance command, that removes all produced data and scripts, and restores the exakat database to its empty state. 

By default, the database is cleaned with graph commands, letting the server do the cleaning.

The -Q option makes the same cleaning with a full restart of the server. This is cleaner, and faster if the database was big or in some instable state.

Options
#######

+-----------+-----+-----------------------------------------------------------------------------+
| Option    | Req | Description                                                                 |
+-----------+-----+-----------------------------------------------------------------------------+
| -Q        | No  | Cleans the database by restarting it, and removing files.                   |
+-----------+-----+-----------------------------------------------------------------------------+
| -stop     | No  | Stops gremlin server                                                        |
+-----------+-----+-----------------------------------------------------------------------------+
| -start    | No  | Starts gremlin server, without removing files.                              |
+-----------+-----+-----------------------------------------------------------------------------+
| -restart  | No  | Restarts gremlin server, without removing files.                            |
+-----------+-----+-----------------------------------------------------------------------------+
| -v        | No  | Verbose mode                                                                |
+-----------+-----+-----------------------------------------------------------------------------+

:: _doctor:

doctor
------

Check the current installation of Exakat.

Command
#######
::

    exakat doctor

Results
#######

::

    PHP : 
        version              : 7.0.1
        curl                 : Yes
        sqlite3              : Yes
        tokenizer            : Yes

    java : 
        installed            : Yes
        type                 : Java(TM) SE Runtime Environment (build 1.8.0_40-b25)
        version              : 1.8.0_40
        $JAVA_HOME           : /Library/Java/JavaVirtualMachines/jdk1.8.0_40.jdk/Contents/Home

    neo4j : 
        version              : Neo4j 2.2.6
        port                 : 7474
        authentication       : Not enabled (Please, enable it)
        gremlinPlugin        : Configured.
        gremlinJar           : neo4j/plugins/gremlin-plugin/gremlin-java-2.7.0-SNAPSHOT.jar
        scriptFolder         : Yes
        pid                  : 20895
        running              : Yes
        running here         : Yes
        gremlin              : Yes
        $NEO4J_HOME          : /Users/famille/Desktop/analyze/neo4j

    folders : 
        config-folder        : Yes
        config.ini           : Yes
        projects folder      : Yes
        progress             : Yes
        in                   : Yes
        out                  : Yes
        projects/test        : Yes
        projects/default     : Yes
        projects/onepage     : Yes

    PHP 5.2 : 
        configured           : No

    PHP 5.3 : 
        configured           : Yes
        installed            : Yes
        version              : 5.3.29
        short_open_tags      : Off
        timezone             : Europe/Amsterdam
        tokenizer            : Yes
        memory_limit         : -1

    PHP 5.4 : 
        configured           : Yes
        installed            : Yes
        version              : 5.4.45
        short_open_tags      : Off
        timezone             : Europe/Amsterdam
        tokenizer            : Yes
        memory_limit         : 384M

    PHP 5.5 : 
        configured           : Yes
        installed            : Yes
        version              : 5.5.30
        short_open_tags      : Off
        timezone             : Europe/Amsterdam
        tokenizer            : Yes
        memory_limit         : -1

    PHP 5.6 : 
        configured           : /usr/local/sbin/php56
        installed            : Yes
        version              : 5.6.16
        short_open_tags      : Off
        timezone             : Europe/Amsterdam
        tokenizer            : Yes
        memory_limit         : -1

    PHP 7.0 : 
        configured           : Yes
        version              : 7.0.1
        short_open_tags      : Off
        timezone             : 
        tokenizer            : Yes
        memory_limit         : -1

    PHP 7.1 : 
        configured           : Yes
        version              : 7.1.0-dev
        short_open_tags      : Off
        timezone             : 
        tokenizer            : Yes
        memory_limit         : 128M

    git : 
        installed            : Yes
        version              : 2.7.0

    hg : 
        installed            : Yes
        version              : 3.6.3

    svn : 
        installed            : Yes
        version              : 1.9.3

    bzr : 
        installed            : No
        optional             : Yes

    composer : 
        installed            : Yes
        version              : 1.0.0-alpha11

    wget : 
        installed            : Yes
        version              : GNU Wget 1.17.1 built on darwin15.2.0.

    zip : 
        installed            : Yes
        version              : 3.0

# Tips

* The `PHP` section is the PHP binary used to run Exakat. 
* The `PHP x.y` sections are the PHP binaries used to check the code. 
* Optional installations (such as svn, zip, etc.) are not necessarily reported if they are not installed.

Options
#######

+-----------+-----+-----------------------------------------------------------------------------+
| Option    | Req | Description                                                                 |
+-----------+-----+-----------------------------------------------------------------------------+
| -p        | No  | Displays the project-specific configuration.                                |
|           |     | Otherwise, only displays general configuration.                             |
+-----------+-----+-----------------------------------------------------------------------------+
| -json     | No  | Displays the project-specific configuration in json format, to stdout       |
+-----------+-----+-----------------------------------------------------------------------------+
| -v        | No  | Verbose mode : include helpers configurations                               |
+-----------+-----+-----------------------------------------------------------------------------+
| -q        | No  | Quiet mode : runs doctor, and install checks, but displays nothing.         |
|           |     | This is useful to automate installation finalization                        |
+-----------+-----+-----------------------------------------------------------------------------+


:: _help:

help
----

Displays the help section. 

::

    php exakat.phar help

Results
#######

This displays : 
::

    [Usage] :   php exakat.phar init -p <Project name> -R <Repository>
                php exakat.phar project -p <Project name>
                php exakat.phar doctor
                php exakat.phar version

:: _init:

init
----

Initialize a new project. 

Command
#######
::

    exakat init -p <project> [-R vcs_url] [-git|-svn|-bzr|-hg|-composer|-symlink|-copy|-tgz|-7z|-zip] [-v] [-D]

Options
#######

+-----------+-----+-----------------------------------------------------------------------------+
| Option    | Req | Description                                                                 |
+-----------+-----+-----------------------------------------------------------------------------+
| -p        | Yes | Project name. Should be filesystem compatible (avoid /, : or \)             |
+-----------+-----+-----------------------------------------------------------------------------+
| -R        | No  | URL to the VCS repository. Anything compatible with the expected VCS.       |
+-----------+-----+-----------------------------------------------------------------------------+
| -git      | No  | Use git client      (also, default value if no clue is given in the VCS URL)|
+-----------+-----+-----------------------------------------------------------------------------+
| -svn      | No  | Use SVN client                                                              |
+-----------+-----+-----------------------------------------------------------------------------+
| -bzr      | No  | Use Bazar client                                                            |
+-----------+-----+-----------------------------------------------------------------------------+
| -hg       | No  | Use Mercurial (hg) client                                                   |
+-----------+-----+-----------------------------------------------------------------------------+
| -composer | No  | Use Composer client                                                         |
+-----------+-----+-----------------------------------------------------------------------------+
| -symlink  | No  | -R path is symlinked. Directory is never accessed for writing.              |
+-----------+-----+-----------------------------------------------------------------------------+
| -copy     | No  | -R path is recursively copied.                                              |
+-----------+-----+-----------------------------------------------------------------------------+
| -zip      | No  | -R is a ZIP archive, local or remote                                        |
+-----------+-----+-----------------------------------------------------------------------------+
| -tgz      | No  | -R is a .tar.gzip archive, local or remote                                  |
+-----------+-----+-----------------------------------------------------------------------------+
| -tbz      | No  | -R is a .tar.bz2 archive, local or remote                                   |
+-----------+-----+-----------------------------------------------------------------------------+
| -rar      | No  | -R is a .rar archive, local or remote                                       |
+-----------+-----+-----------------------------------------------------------------------------+
| -7z       | No  | -R is a .7z archive, local or remote                                        |
+-----------+-----+-----------------------------------------------------------------------------+
| -v        | No  | Verbose mode                                                                |
+-----------+-----+-----------------------------------------------------------------------------+
| -D        | No  | First erase any pre-existing project with the same name                     |
+-----------+-----+-----------------------------------------------------------------------------+

Tips
####

* `-R` is not compulsory : you may omit it, then, provide PHP files in the `projects/<name>/code` folder by the mean you want.
* Default VCS used is git. 
* `-D` removes any previous project before doing the init.
* Archives (zip, tar.gz, tar.bz, 7z, rar, etc.) depends on external tools to unpack them. They depends on PHP to reach the file, locally or remotely.

Examples
########
::

    # Clone Exakat with Git
    php exakat.phar init -p exakat -R https://github.com/exakat/exakat.git 

    # Download Spip with Zip
    php exakat init -p spip2 -zip -R http://files.spip.org/spip/stable/spip-3.1.zip

    # Download PHPMyadmin, 
    php exakat.phar init -p pma2 -tgz -R https://files.phpmyadmin.net/phpMyAdmin/4.6.4/phpMyAdmin-4.6.4-all-languages.tar.gz

    # Make a local copy of PHPMyadmin, 
    php exakat.phar init -p copyProject -copy -R projects/phpmyadmin/code/

    # Make a local symlink with the local webserver, 
    php exakat.phar init -p symlinkProject -symlink -R /var/www/public_html


:: _project:

project
-------

Runs a new analyze on a project. 

The results of the analysis are available in the `projects/<name>/` folder. `report` and `faceted` are two HTML reports.

Command
#######
::

    exakat project -p <project> [-v]

Options
#######

+-----------+-----+-----------------------------------------------------------------------------+
| Option    | Req | Description                                                                 |
+-----------+-----+-----------------------------------------------------------------------------+
| -p        | Yes | Project name. Should be filesystem compatible (avoid /, : or \)             |
+-----------+-----+-----------------------------------------------------------------------------+
| -v        | No  | Verbose mode                                                                |
+-----------+-----+-----------------------------------------------------------------------------+

:: _remove:

remove
------

Destroy a project. All code source, configuration and any results from exakat are destroyed. 

Command
#######
::

    exakat remove -p <project> [-v]

Options
#######

+-----------+-----+-----------------------------------------------------------------------------+
| Option    | Req | Description                                                                 |
+-----------+-----+-----------------------------------------------------------------------------+
| -p        | Yes | Project name. Should be filesystem compatible (avoid /, : or \)             |
+-----------+-----+-----------------------------------------------------------------------------+
| -v        | No  | Verbose mode                                                                |
+-----------+-----+-----------------------------------------------------------------------------+

:: _remove:

show
----

Displays the the full command line to create an exakat project. 

Command
#######
::

    exakat show -p <project>

Options
#######

+-----------+-----+-----------------------------------------------------------------------------+
| Option    | Req | Description                                                                 |
+-----------+-----+-----------------------------------------------------------------------------+
| -p        | Yes | Project name. Should be filesystem compatible (avoid /, : or \)             |
+-----------+-----+-----------------------------------------------------------------------------+


:: _report:

report
------

Produce a report for a project. 

Reports may be produced as soon as exakat has reach the phase of 'analysis'. If the analysis phase hasn't finished, then some results may be unavailable. Run report again later to get the full report. 
For example, the 'Uml' report may be run fully as soon as exakat is in analysis phase. 

It is possible to extract a report even after the graph database has been cleaned. This allows running several projects one after each other, yet have access to several reports. 

Command
#######
::

    exakat report -p <project> -format <Format> [-file <file>] [-v]

Options
#######

+-----------+-----+-----------------------------------------------------------------------------+
| Option    | Req | Description                                                                 |
+-----------+-----+-----------------------------------------------------------------------------+
| -p        | Yes | Project name. Should be filesystem compatible (avoid /, : or \)             |
+-----------+-----+-----------------------------------------------------------------------------+
| -v        | No  | Verbose mode                                                                |
+-----------+-----+-----------------------------------------------------------------------------+
| -format   | No  | Which format to extract.                                                    |
|           |     | Available formats : Devoops, Faceted, FacetedJson, Json, OnepageJson, Text, |
|           |     | Uml, Xml                                                                    |
|           |     | Default is 'Text'                                                           |
+-----------+-----+-----------------------------------------------------------------------------+
| -file     | No  | File or directory name for the report. Adapted file extension is added.     |
|           |     | Report is located in the projects/<project>/ folder                         |
|           |     | Default is 'stdout', but varies with format.                                |
+-----------+-----+-----------------------------------------------------------------------------+
| -T        | No  | Ruleset's results. All the analyses in this ruleset are reported.           |
|           |     | Note that the report format may override this configuration : for example   |
|           |     | Ambassador manage its own list of analyses.                                 |
|           |     | Uses this with Text format.                                                 |
|           |     | Has priority over the -P option                                             |
+-----------+-----+-----------------------------------------------------------------------------+
| -P        | No  | Analyzer's results. Only one analysis's is reported.                        |
|           |     | Note that the report format may override this configuration : for example   |
|           |     | Ambassador manage its own list of analyses.                                 |
|           |     | Uses this with Text format.                                                 |
|           |     | Has lower priority than the -T option                                       |
+-----------+-----+-----------------------------------------------------------------------------+

Report formats
##############

All reports are detailed in the ref:`Reports <reports>` section.

+-------------+-----------------------------------------------------------------------------+
| Report      | Description                                                                 |
+-------------+-----------------------------------------------------------------------------+
| Amabassador | HTML format, with all available reports in one compact format.              |
+-------------+-----------------------------------------------------------------------------+
| Devoops     | HTML format, deprecated.                                                    |
+-------------+-----------------------------------------------------------------------------+
| Json        | JSON format.                                                                |
+-------------+-----------------------------------------------------------------------------+
| Text        | Text format. One issue per line, with description, file, line.              |
+-------------+-----------------------------------------------------------------------------+
| Codesniffer | Text format, similar to Codesniffer report style.                           |
+-------------+-----------------------------------------------------------------------------+
| Uml         | Dot format. All classes/interfaces/traits hierarchies, and grouped by name- |
|             | spaces.                                                                     |
+-------------+-----------------------------------------------------------------------------+
| Xml         | XML format.                                                                 |
+-------------+-----------------------------------------------------------------------------+
| All         | All availble format, using default naming                                   |
+-------------+-----------------------------------------------------------------------------+

:: _update:

update
------

Update the code base of a project. 

Command
#######
::

    exakat update -p <project> [-v]

Options
#######

+-----------+-----+-----------------------------------------------------------------------------+
| Option    | Req | Description                                                                 |
+-----------+-----+-----------------------------------------------------------------------------+
| -p        | Yes | Project name. Should be filesystem compatible (avoid /, : or \)             |
+-----------+-----+-----------------------------------------------------------------------------+
| -v        | No  | Verbose mode                                                                |
+-----------+-----+-----------------------------------------------------------------------------+

:: _upgrade:


upgrade
-------

Upgrade exakat itself. By default, this command only checks for the availability of a new version : it doesn't upgrade immediately. 

Use -u option to actually replace the current phar archive.

Use -version option to downgrade or upgrade to a specific version. 

In case the upgrade command file, you may also download manually the `.phar` from the exakat.io website : `www.exakat.io <http://www.exakat.io/versions/>`_. Then replace the current version with the new one.

Command
#######
::

    exakat upgrade 

Options
#######

+-----------+-----+-----------------------------------------------------------------------------+
| Option    | Req | Description                                                                 |
+-----------+-----+-----------------------------------------------------------------------------+
| -u        | Yes | Actually upgrades exakat. Without it, it is a dry run.                      |
+-----------+-----+-----------------------------------------------------------------------------+
| -version  | No  | Select a specific Exakat version and update to it. By default, it upgrades  |
|           |     | to the latest version, as published on the https://www.exakat.io/ site.     |
|           |     | Example value : 1.8.8                                                       |
+-----------+-----+-----------------------------------------------------------------------------+

Install
-------

Install exakat's graph dependency. This command is an integrated installation script, and it is only accessible once the .phar is downloaded locally.

Command
#######
::

    mkdir exakat
    cd exakat
    
    // Download exakat.phar, like this, or any other valid means
    curl -o exakat.phar https://www.exakat.io/versions/index.php?file=latest
    exakat.phar upgrade 

Options
#######

+-----------+-----+-----------------------------------------------------------------------------+
| Option    | Req | Description                                                                 |
+-----------+-----+-----------------------------------------------------------------------------+
| -u        | Yes | Actually upgrades exakat. Without it, it is a dry run.                      |
+-----------+-----+-----------------------------------------------------------------------------+
| -version  | No  | Select a specific Exakat version and update to it. By default, it upgrades  |
|           |     | to the latest version, as published on the https://www.exakat.io/ site.     |
|           |     | Example value : 1.8.8                                                       |
+-----------+-----+-----------------------------------------------------------------------------+
