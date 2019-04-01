.. _Configuration:

Configuration
*************

Summary
-------

* `Common Behavior`_
* `Engine configuration`_
* `Project Configuration`_
* `Configuring analysis to be run`_
* `Specific analyser configurations`_

Common Behavior
---------------

General Philosophy
##################
Exakat tries to avoid configuration as much as possible, so as to focus on working out of the box, rather than spend time on pre-requisite.

As such, it will probably do more work, but that may be dismissed later, at the report reading time.

More configuration options will appear with the evolution of the engine.

Precedence
##########

The exakat engine read directives from three places :

1. The command line options
2. The .exakat.ini file at the root of the code
3. The config.ini file in the project directory
4. The exakat.ini file in the config directory
5. The default values in the code


The precedence of the directives is the same as the list above : command line options always have highest priority, config.ini files are in second, when command line are not available, and finally, the default values are read in the code.

Some of the directives are only available in the config.ini files.

Common Options
###############
 
All options are the same, whatever the command provided to exakat. -f always means files, and -q always means quick. 

Any option that a command doesn't understand is ignored. 

Any option that is not recognized is ignored and reported (with visibility).

Engine configuration
--------------------

Engine configuration is were the exakat engine general configuration are stored. For example, the php binaries or the neo4j folder are there. Engine configurations affect all projects.

Configuration File
##################

The Exakat engine is configured in the 'config/exakat.ini' file. 

This file is created with the 'doctor' command, or simply by copying another such file from another installation.

::

   php exakat.phar doctor

When the doctor can't find the 'config/config.ini' file, it attempts to create one, with reasonable values. It is recommended to use this to create the exakat.ini skeleton, and later, modify it.

Available Options
#################

Here are the currently available options in Exakat's configuration file : config/config.ini

+--------------------+-------------------------------------------------------------------------------------------+
| Option             | Description                                                                               |
+====================+===========================================================================================+
| graphdb            | The graph database to use.                                                                |
|                    | Currently, it may be gsneo4j, or tinkergraph.                                             |
+--------------------+-------------------------------------------------------------------------------------------+
| gsneo4j_host       | The host to connect to reach the graph database, when using gsneo4j driver.               |
|                    | The default value is 'localhost'                                                          |
+--------------------+-------------------------------------------------------------------------------------------+
| gsneo4j_host       | The port to use on the host to reach the graph database, when using gsneo4j driver..      |
|                    | The default value is '8182'                                                               |
+--------------------+-------------------------------------------------------------------------------------------+
| gsneo4j_folder     | The folder where the code for the graph database resides, when using gsneo4j driver.      |
|                    | The default value is 'tinkergraph', and is located near exakat.phar                       |
+--------------------+-------------------------------------------------------------------------------------------+
| tinkergraph_host   | The host to connect to reach the graph database, when using tinkergraph driver.           |
|                    | The default value is 'localhost'                                                          |
+--------------------+-------------------------------------------------------------------------------------------+
| tinkergraph_port   | The port to use on the host to reach the graph database, when using tinkergraph driver.   |
|                    | The default value is '8182'                                                               |
+--------------------+-------------------------------------------------------------------------------------------+
| tinkergraph_folder | The folder where the code for the graph database resides, when using tinkergraph driver.  |
|                    | The default value is 'tinkergraph', and is located near exakat.phar                       |
+--------------------+-------------------------------------------------------------------------------------------+
| project_themes     | List of analysis rulesets to be run. The list may include extra rulesets that are not     |
|                    | used by the default reports : you can then summon them manually.                          |
|                    | project_themes[] = 'Theme', one per line.                                                 |
+--------------------+-------------------------------------------------------------------------------------------+
| project_reports    | The list of reports that can be produced when running 'project' command.                  |
|                    | This list may automatically add extra rulesets if a report requires them. For example,    |
|                    | the 'Ambassador' report requires 'Security' ruleset, while 'Text' has no pre-requisite.   |
|                    | project_reports is 'Ambassador', by default.                                              |
|                    | project_reports[] = 'Report', one per line.                                               |
+--------------------+-------------------------------------------------------------------------------------------+
| token_limit        | Maximum size of the analyzed project, in number of PHP tokens (excluding whitespace).     |
|                    | Use this to avoid running a really long analyze without knowing it.                       |
|                    | Default is 1 million.                                                                     |
+--------------------+-------------------------------------------------------------------------------------------+
| php                | Link to the PHP binary. This binary is the one that runs Exakat. It is recommended to use |
|                    | PHP 7.0, or 5.6. The same binary may be used with the following options.                  |
+--------------------+-------------------------------------------------------------------------------------------+
| php73              | Link to the PHP 7.3.x binary. This binary is needed to test the compilation with the 7.3  |
|                    | series or if the analyze should be run with this version (see project's config.ini).      |
|                    | Comment it out if you don't want this version tested. It is not recommended to use this   |
|                    | version for the analyze                                                                   |
+--------------------+-------------------------------------------------------------------------------------------+
| php72              | Link to the PHP 7.2.x binary. This binary is needed to test the compilation with the 7.2  |
|                    | series or if the analyze should be run with this version (see project's config.ini).      |
|                    | Comment it out if you don't want this version tested. It is not recommended to use this   |
|                    | version for the analyze                                                                   |
+--------------------+-------------------------------------------------------------------------------------------+
| php71              | Link to the PHP 7.1.x binary. This binary is needed to test the compilation with the 7.1  |
|                    | series or if the analyze should be run with this version (see project's config.ini).      |
|                    | Comment it out if you don't want this version tested. It is not recommended to use this   |
|                    | version for the analyze                                                                   |
+--------------------+-------------------------------------------------------------------------------------------+
| php70              | Link to the PHP 7.0.x binary. This binary is needed to test the compilation with the 7.0  |
|                    | series or if the analyze should be run with this version (see project's config.ini).      |
|                    | Comment it out if you don't want this version tested. It is not recommended to use this   |
|                    | version for the analyze                                                                   |
+--------------------+-------------------------------------------------------------------------------------------+
| php56              | Link to the PHP 5.6.x binary. This binary is needed to test the compilation with the 5.6  |
|                    | series or if the analyze should be run with this version (see project's config.ini).      |
|                    | Comment it out if you don't want this version tested. It is not recommended to use this   |
|                    | version for the analyze                                                                   |
+--------------------+-------------------------------------------------------------------------------------------+
| php55              | Link to the PHP 5.5.x binary. This binary is needed to test the compilation with the 5.5  |
|                    | series or if the analyze should be run with this version (see project's config.ini).      |
|                    | Comment it out if you don't want this version tested. It is not recommended to use this   |
|                    | version for the analyze                                                                   |
+--------------------+-------------------------------------------------------------------------------------------+
| php54              | Link to the PHP 5.4.x binary. This binary is needed to test the compilation with the 5.4  |
|                    | series or if the analyze should be run with this version (see project's config.ini).      |
|                    | Comment it out if you don't want this version tested. It is not recommended to use this   |
|                    | version for the analyze                                                                   |
+--------------------+-------------------------------------------------------------------------------------------+
| php53              | Link to the PHP 5.3.x binary. This binary is needed to test the compilation with the 5.3  |
|                    | series or if the analyze should be run with this version (see project's config.ini).      |
|                    | Comment it out if you don't want this version tested. It is not recommended to use this   |
|                    | version for the analyze                                                                   |
+--------------------+-------------------------------------------------------------------------------------------+
| php52              | Link to the PHP 5.2.x binary. This binary is needed to test the compilation with the 5.2  |
|                    | series or if the analyze should be run with this version (see project's config.ini).      |
|                    | Comment it out if you don't want this version tested. It is not recommended to use this   |
|                    | version for the analyze                                                                   |
+--------------------+-------------------------------------------------------------------------------------------+

Custom rulesets
###############

Create custom rulesets by creating a 'config/themes.ini' directive files. 

This file is a .INI file, build with several sections. Each section is the name of a ruleset : for example, 'mine' is the name for the ruleset below. 

There may be several sections, as long as the names are distinct. 

It is recommended to use all low-case names for custom rulesets. Exakat uses names with a first capital letter, which prevents conflicts. Behavior is undefined if a custom ruleset has the same name as a default ruleset.

:: 

    ['mine']
    analyzer[] = 'Structures/AddZero';
    analyzer[] = 'Performances/ArrayMergeInLoops';


The list of analyzer in the ruleset is based on the 'analyzer' array. The analyzer is identified by its 'shortname'. Analyzer shortname may be found in the documentation (:ref:`Rules` or within the Ambassador report). Analyzers names have a 'A/B' structure.

The list of available rulesets, including the custom ones, is listed with the `doctor` command.

Project Configuration
---------------------

Project configuration are were the project specific configuration are stored. For example, the project name, the ignored directories or its external libraries are kept. Configurations only affect one project and not the others.

Project configuration file are called 'config.ini'. They are located, one per project, in the 'projects/&lt;project name&gt;/config.ini' file. 

Available Options
#################

Here are the currently available options in Exakat's project configuration file : projects/&lt;project name&gt;/config.ini

+-----------------------+-------------------------------------------------------------------------------------------+
| Option                | Description                                                                               |
+=======================+===========================================================================================+
| phpversion            | Version with which to run the analyze.                                                    |
|                       | It may be one of : 7.3, 7.2, 7.1, 7.0, 5.6, 5.5, 5.4, 5.3, 5.2.                           |
|                       | Default is 7.2 or the CLI version used to init the project.                               |
|                       | 5.* versions are available, but are less tested.                                          |
|                       | 7.3 is actually the current dev version.                                                  |
+-----------------------+-------------------------------------------------------------------------------------------+
| include_dirs[]        | This is the list of files and dir to include in the project's directory. It is chrooted   |
|                       | in the project's folder. Values provided with a starting / are used as a path prefix.     |
|                       | Values without / are used as a substring, anywhere in the path.                           |
|                       | include_dirs are added AFTER ignore_dirs, so as to partially ignore a folder, such as     |
|                       | the vendor folder from composer.                                                          |
+-----------------------+-------------------------------------------------------------------------------------------+
| ignore_dirs[]         | This is the list of files and dir to ignore in the project's directory. It is chrooted in |
|                       | the project's folder. Values provided with a starting / are used as a path prefix. Values |
|                       | without / are used as a substring, anywhere in the path.                                  |
+-----------------------+-------------------------------------------------------------------------------------------+
| file_extensions       | This is the list of file extensions that is considered as PHP scripts. All others will be |
|                       | ignored. All files bearing those extensions are subject to check, though they will be     |
|                       | scanned first for PHP tags before being analyzed. The extensions are comma separated,     |
|                       | without dot.                                                                              |
|                       | The default are : php, php3, inc, tpl, phtml, tmpl, phps, ctp                             |
+-----------------------+-------------------------------------------------------------------------------------------+
| project_name          | This is the project name, as it appears at the top left in the Ambassador report.         |
+-----------------------+-------------------------------------------------------------------------------------------+
| project_url           | This is the repository URL for the project. It is used to get the source for the project. |
+-----------------------+-------------------------------------------------------------------------------------------+
| project_vcs           | This is the VCS used to fetch the project source.                                         |
+-----------------------+-------------------------------------------------------------------------------------------+
| project_description   | This is the description of the project.                                                   |
+-----------------------+-------------------------------------------------------------------------------------------+
| project_packagist     | This is the packagist name for the code, when the code is fetched with composer.          |
+-----------------------+-------------------------------------------------------------------------------------------+

Adding/Excluding files
----------------------

ignore_dirs and include_dirs are the option used to select files within a folder. Here are some tips to choose 

* From the full list of files, ignore_dirs[] is applied, then include_dirs is applied. The remaining list is processed.
* ignore one file : 
  `ignore_dirs[] = "/path/to/file.php"`

* ignore one dir : 
  `ignore_dirs[] = "/path/to/dir/"`

* ignore siblings but include one dir : 
  `ignore_dirs[] = "/path/to/parent/";`
  `include_dirs[] = "/path/to/parent/dir/"`

* ignore every name containing 'test' : 
  `ignore_dirs[] = "test";`

* only include one dir (and exclude the rest): 
  `include_dirs[] = "/path/to/dir/";`

* omitting include_dirs defaults to `"include_dirs[] = ""`
* omitting ignore_dirs defaults to `"ignore_dirs[] = ""`
* including or ignoring files multiple times only has effect once


Specific analyser configurations
--------------------------------

Some analyzer may be configured individually. Those parameters are then specific to one analyzer, and it only affects their behavior. 

Analyzers may be configured in the `project/*/config.ini`; they may also be configured globally in the `config/exakat.ini` file.

{{PARAMETERED_ANALYSIS}}


Configuring analysis to be run
------------------------------

Exakat builds a list of analysis to run, based on two directives : `project_reports` and `projects_themes`. Both are list of rulesets. Unknown rulesets are omitted. 

project_reports makes sure you can extract those reports, while `projects_themes` allow you to build reports a la carte later, and avoid running the whole audit again.

Required rulesets
#################
First, analysis are very numerous, and it is very tedious to sort them by hand. Exakat only handles 'themes' which are groups of analysis. There are several list of rulesets available by default, and it is possible to customize those lists. 

When using the `projects_themes` directive, you can configure which rulesets must be processed by exakat, each time a 'project' command is run. Those rulesets will always be run. 

Report-needed rulesets
######################

Reports are build based on results found during the auditing phase. Some reports, like 'Ambassador' or 'Drillinstructor' needs the results of specific rulesets. Others, like 'Text' or 'Json' build reports at the last moment. 

As such, exakat uses the project_reports directive to collect the list of necessary rulesets, and add them to the `projects_themes` results. 

Late reports
############

It is possible de extract a report, even if the configuration has not been explicitly set for it. 

For example, it is possible to build the Owasp report after telling exakat to build a 'Ambassador' report, as Ambassador includes all the analysis needed for Owasp. On the other hand, the contrary is not true : one can't get the Ambassador report after running exakat for the Owasp report, as Owasp only covers the security rulesets, and Ambassador requires other rulesets. 

Recommendations
###############

* The 'Ambassador' report has all the classic rulesets, it's the most comprehensive choice. 
* To collect everything possible, use the ruleset 'All'. It's also the longest-running ruleset of all. 
* To get one report, simply configure project_report with that report. 
* You may configure several rulesets, like 'Security', 'Suggestions', 'CompatibilityPHP73', and later extract independant results with the 'Text' or 'Json' format.
* If you just want one compulsory report and two optional reports (total of three), simply configure all of them with project_report. It's better to produce extra reports, than run again a whole audit to collect missing informations. 
* It is possible to configure customized rulesets, and use them in project_rulesets
* Excluding one analyzer is not supported. Use custom rulesets to build a new one instead. 

Example
#######

::

    project_reports[] = 'Drillinstructor';
    project_reports[] = 'Owasp';

    project_themes[] = 'Security';
    project_themes[] = 'Suggestions';
    

With that configuration, the Drillinstructor and the Owasp report are created automatically when running 'project'. Use the following command to get the specific rulesets ; 

::

    php exakat.phar report -p <project> -format Text -T Security -v 
    

Check Install
-------------

Once the prerequisite are installed, it is advised to run to check if all is found : 

`php exakat.phar doctor`

After this run, you may edit 'config/config.ini' to change some of the default values. Most of the time, the default values will be OK for a quick start.
