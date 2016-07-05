.. _Commands:

Exakat commands
===============

List of commands :
------------------

* doctor
* help
* init
* project
* report
* remove
* update
* upgrade

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


help
----

Displays the help section. 

::

    php exakat.phar help

results
#######

This displays : 
::

    [Usage] :   php exakat.phar init -p <Project name> -R <Repository>
                php exakat.phar project -p <Project name>
                php exakat.phar doctor
                php exakat.phar version

init
----

Initialize a new project. 

Command
#######
::

    exakat init -p <project> [-R vcs_url] [-git|-svn|-bzr|-hg|-composer] [-v]

Options
#######

+-----------+-----+-----------------------------------------------------------------------------+
| Option    | Req | Description                                                                 |
+-----------+-----+-----------------------------------------------------------------------------+
| -p        | Yes | Project name. Should be filesystem compatible (avoid / or \)                |
+-----------+-----+-----------------------------------------------------------------------------+
| -R        | No  | URL to the VCS repository. Anything compatible with the expected VCS.       |
+-----------+-----+-----------------------------------------------------------------------------+
| -git      | No  | Force VCS to be git (also, default value if no clue is given in the VCS URL)|
+-----------+-----+-----------------------------------------------------------------------------+
| -svn      | No  | Force VCS to be SVN                                                         |
+-----------+-----+-----------------------------------------------------------------------------+
| -bzr      | No  | Force VCS to be Baazar                                                      |
+-----------+-----+-----------------------------------------------------------------------------+
| -hg       | No  | Force VCS to be Mercurial                                                   |
+-----------+-----+-----------------------------------------------------------------------------+
| -composer | No  | Force VCS to be Composer                                                    |
+-----------+-----+-----------------------------------------------------------------------------+
| -v        | No  | Verbose mode                                                                |
+-----------+-----+-----------------------------------------------------------------------------+

Tips
####

* `-R` is not compulsory : you may omit it, then, provide PHP files in the `projects/<name>/code` folder by the way you want.


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
| -p        | Yes | Project name. Should be filesystem compatible (avoid / or \)                |
+-----------+-----+-----------------------------------------------------------------------------+
| -v        | No  | Verbose mode                                                                |
+-----------+-----+-----------------------------------------------------------------------------+


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
| -p        | Yes | Project name. Should be filesystem compatible (avoid / or \)                |
+-----------+-----+-----------------------------------------------------------------------------+
| -v        | No  | Verbose mode                                                                |
+-----------+-----+-----------------------------------------------------------------------------+

report
------

Produce a report for a project. 

Reports may be produced as soon as exakat has reach the phase of 'analysis'. If the analysis phase hasn't finished, then some results may be unavailable. Run report again later to get the full report. 
For example, the 'Uml' report may be run fully as soon as exakat is in analysis phase. 

It is possible to extract a report even after the graph database has been cleaned. This allows running several projects one after each other, yet have access to several reports. 

Command
#######
::

    exakat report -p <project> -format <Format> -file <file> [-v]

Options
#######

+-----------+-----+-----------------------------------------------------------------------------+
| Option    | Req | Description                                                                 |
+-----------+-----+-----------------------------------------------------------------------------+
| -p        | Yes | Project name. Should be filesystem compatible (avoid / or \)                |
+-----------+-----+-----------------------------------------------------------------------------+
| -v        | No  | Verbose mode                                                                |
+-----------+-----+-----------------------------------------------------------------------------+
| -format   | No  | Which format to extract.                                                    |
|           |     | Available formats : Devoops, Faceted, FacetedJson, Json, OnepageJson, Text, |
|           |     |                     Uml, Xml                                                |
|           |     | Default is 'Text'                                                           |
+-----------+-----+-----------------------------------------------------------------------------+
| -file     | No  | File or directory name for the report. Adapted file extension is added.     |
|           |     | Report is located in the projects/<project>/ folder                         |
|           |     | Default is 'stdout', but varies with format.                                |
+-----------+-----+-----------------------------------------------------------------------------+

Report formats
##############

+-------------+-----------------------------------------------------------------------------+
| Report      | Description                                                                 |
+-------------+-----------------------------------------------------------------------------+
| Devoops     | HTML format, with all available reports in one compact format.              |
+-------------+-----------------------------------------------------------------------------+
| Faceted     | HTML format, with faceted filters                                           |
+-------------+-----------------------------------------------------------------------------+
| FacetedJson | JSON format, used by Faceted.                                               |
+-------------+-----------------------------------------------------------------------------+
| Json        | JSON format.                                                                |
+-------------+-----------------------------------------------------------------------------+
| Text        | Text format. One issue per line, with description, file, line.              |
+-------------+-----------------------------------------------------------------------------+
| Text        | Text format. One issue per line, with description, file, line.              |
+-------------+-----------------------------------------------------------------------------+
| Uml         | Dot format. All classes/interfaces/traits hierarchies, and grouped by name- |
|             | spaces.                                                                     |
+-------------+-----------------------------------------------------------------------------+
| Xml         | XML format.                                                                 |
+-------------+-----------------------------------------------------------------------------+

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
| -p        | Yes | Project name. Should be filesystem compatible (avoid / or \)                |
+-----------+-----+-----------------------------------------------------------------------------+
| -v        | No  | Verbose mode                                                                |
+-----------+-----+-----------------------------------------------------------------------------+


upgrade
-------

Upgrade exakat itself. By default, this is a dry run : only the availability of a new version is reported. 

Use -u option to actually replace the current phar archive.

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
