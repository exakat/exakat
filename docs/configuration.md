#Exkat Configuration

##Common Behavior

### General Philosophy
At the moment, the exakat engine has the lowest overhead for configuration. It tries to avoid configuration as much as possible, so as to focus on working out of the box, rather than spend time on pre-requisite.

As such, it will probably do more work, but that may be dismissed later, at the report reading time.

More configuration options will appear with the evolution of the engine.

### Precedence
The exakat engine read directives from three places :

* The command line options
* The config.ini files
* The default values in the code

The precedence of the directives is the same as the list above : command line options always have highest priority, config.ini files are in second, when command line are not available, and finally, the default values are read in the code.

Some of the directives are only available in the config.ini files.

### Common Options
All options are the same, whatever the command provided to exakat. -f always means files, and -q always means quick. 

Any option that a command doesn't understand is ignored. 

Any option that is not recognized is ignored and reported (with visibility).

##Engine configuration

## Configuration File
The Exakat engine is configured in the 'config/config.ini' file. 

This file is created manually, or, with the 'doctor' command.

```shell
php exakat.phar doctor
```

When the doctor can't find the 'config/config.ini' file, it attempts to create one, with reasonable values. It is recommended to use this to create the config.ini skeleton, and later, modify it.

## Available Options

Here are the currently available options in Exakat's configuration file : config/config.ini

|Option|Description|
|---|---|
| token_limit | Maximum size of the analyzed project, in number of PHP tokens, and excluding whitespace. Use this to avoid running a really long analyze without knowing it. Default is 1 million. |
|neo4j_host| The IP on which to reach Neo4j Database. It is recommended to use the default value, until exakat has loosened the link between it and the location of Neo4j. Default : 127.0.0.1|
|neo4j_port     |Port to use to reach Neo4j. Default :7474|
|neo4j_folder   | Folder in which neo4j reside. Default : './neo4j'|
|neo4j_login    | Neo4j User when connecting to the neo4j server. Leave this blank (empty string) if the authentication is not set. Default : 'neo4j'|
|neo4j_password | Neo4j password when connecting to the neo4j server. Leave this blank (empty string) if the authentication is not set. Default : 'oui';|
|php| Link to the PHP binary. This binary is the one that runs Exakat. It is recommended to use PHP 5.6 or 7.0 for this. |
|php52| Link to the PHP 5.2.x binary. This binary is needed to test the compilation with the 5.2 series or if the analyze should be run with this version (see project's config.ini). Comment it out if you don't want this version tested. It is not recommended to use this version for the analyze.|
|php53| Link to the PHP 5.3.x binary. This binary is needed to test the compilation with the 5.3 series or if the analyze should be run with this version (see project's config.ini). Comment it out if you don't want this version tested. It is not recommended to use this version for the analyze.|
|php54| Link to the PHP 5.4.x binary. This binary is needed to test the compilation with the 5.4 series or if the analyze should be run with this version (see project's config.ini). Comment it out if you don't want this version tested. Analyze with this versions should work correctly, but is not extensively tested.|
|php55| Link to the PHP 5.5.x binary. This binary is needed to test the compilation with the 5.5 series or if the analyze should be run with this version (see project's config.ini). Comment it out if you don't want this version tested.|
|php56| Link to the PHP 5.6.x binary. This binary is needed to test the compilation with the 5.6 series or if the analyze should be run with this version (see project's config.ini). Comment it out if you don't want this version tested.|
|php70| Link to the PHP 7.0.x binary. This binary is needed to test the compilation with the 7.0 series or if the analyze should be run with this version (see project's config.ini). Comment it out if you don't want this version tested.|
|php71| Link to the PHP 7.1.x binary. This is reserved for Future Use, and is not available yet|



## Project Configuration

Project configuration file are called 'config.ini'. They are located, one per project, in the 'projects/&lt;project name&gt;/config.ini' file. 

## Available Options

Here are the currently available options in Exakat's project configuration file : projects/&lt;project name&gt;/config.ini

|Option|Description|
|---|---|
| phpversion | Version with which to run the analyze. It may be one of : 7.0, 5.6, 5.5, 5.4, 5.3, 5.2. Default is 5.6, and will soon be 7.0. 7.0 5.6 and 5.5 have been extensively tested and used in developpement. 5.4, 5.3 and 5.2 are available, but are less tested. 7.1 will come when the next version appears|
|ignore_dirs[]|This is the list of files and dir to ignore in the projects/&lt;project name&gt;/code/ directory. It is chrooted in this folder. Values provided with a starting / are used as a path prefix. Values without / are used as a substring, anywhere in the path.|
|file_extensions|This is the list of file extensions that is considered as PHP scripts. All others will be ignored. All files bearing those extensions are subject to check, though they will be scanned first for PHP tags before being analyzed. The extensions are comma separated, without dot. The default are : php, php3, inc, tpl, phtml, tmpl, phps, ctp|
|project_name|This is the project name, as it appears at the top left in the report. |
|project_url|This is the repository URL for the project. It is used to get the source for the project. |
|FindExternalLibraries| This is a generated value, that appears after exakat's first run on the project. You may remove this line entirely if you want Exakat to check again for libraries. Otherwise, just let it there. |


