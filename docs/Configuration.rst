.. _Configuration:

Configuration
*************

Summary
-------

* `Common Behavior`_
* `Engine configuration`_
* `Project Configuration`_


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

* The command line options
* The config.ini files
* The default values in the code

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
| tinkergraph_host   | The port to use on the host to reach the graph database, when using tinkergraph driver.   |
|                    | The default value is '8182'                                                               |
+--------------------+-------------------------------------------------------------------------------------------+
| tinkergraph_folder | The folder where the code for the graph database resides, when using tinkergraph driver.  |
|                    | The default value is 'tinkergraph', and is located near exakat.phar                       |
+--------------------+-------------------------------------------------------------------------------------------+
| project_themes     | List of analysis themes to be run. The list may include extra themes that are not used    |
|                    | by the default reports : you can then summon them manually.                               |
|                    | project_themes[] = 'Theme', one per line.                                                 |
+--------------------+-------------------------------------------------------------------------------------------+
| project_reports    | The list of reports that can be produced when running 'project' command.                  |
|                    | This list may automatically add extra themes if a report requires them. For example,      |
|                    | the 'Ambassador' report requires 'Security' theme, while 'Text' has no pre-requisite.     |
|                    | project_reports is 'Ambassador', by default.                                              |
|                    | project_reports[] = 'Report', one per line.                                               |
+--------------------+-------------------------------------------------------------------------------------------+
| token_limit        | Maximum size of the analyzed project, in number of PHP tokens, and excluding whitespace.  |
|                    | Use this to avoid running a really long analyze without knowing it. Default is 1 million. |
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
| phpversion            | Version with which to run the analyze. It may be one of : 7.0, 5.6, 5.5, 5.4, 5.3, 5.2.   |
|                       | Default is 7.0. 7.0 5.6 and 5.5 have been extensively tested and used in developpement.   |
|                       | 5.4, 5.3 and 5.2 are available, but are less tested.                                      |
|                       | 7.1 will appear with the next PHP version                                                 |
+-----------------------+-------------------------------------------------------------------------------------------+
| ignore_dirs[]         | This is the list of files and dir to ignore in the project's directory. It is chrooted in |
|                       | the project's folder. Values provided with a starting / are used as a path prefix. Values |
|                       | without / are used as a substring, anywhere in the path.                                  |
+-----------------------+-------------------------------------------------------------------------------------------+
| file_extensions       | This is the list of file extensions that is considered as PHP scripts. All others will be |
|                       | ignored. All files bearing those extensions are subject to check, though they will be     |
|                       | scanned first for PHP tags before being analyzed. The extensions are comma separated,     |
|                       | without dot. The default are : php, php3, inc, tpl, phtml, tmpl, phps, ctp                |
+-----------------------+-------------------------------------------------------------------------------------------+
| project_name          | This is the project name, as it appears at the top left in the report.                    |
+-----------------------+-------------------------------------------------------------------------------------------+
| project_url           | This is the repository URL for the project. It is used to get the source for the project. |
+-----------------------+-------------------------------------------------------------------------------------------+
| FindExternalLibraries | This is a generated value, that appears after exakat's first run on the project. You may  |
|                       | remove this line entirely if you want Exakat to check again for libraries.                |
|                       | Otherwise, just let it there                                                              |
+-----------------------+-------------------------------------------------------------------------------------------+

Check Install
-------------

Once the prerequisite are installed, it is advised to run to check if all is found : 

`php exakat.phar doctor`

After this run, you may edit 'config/config.ini' to change some of the default values. Most of the time, the default values will be OK for a quick start.
