.. Extensions:

Extensions
==========

Exakat support a system of extensions, that bring extra analysis, categories, data sources, configurations and reports to the exakat engine. Extensions focus on specific frameworks or platform. They are a simple way to package a predefined set of configurations and analysis. 

Extensions are PHP archives (`.phar` file), installed in the `ext` folder. Check the local extensions with `doctor`.

::

    exakat doctor
    
    
    
    exakat : 
        executable           : exakat
        version              : 1.5.7
        build                : 835
        exakat.ini           : ./config/exakat.ini,
                               config/remotes.json,
                               config/themes.ini
        graphdb              : gsneo4j
        reports              : Ambassador
        rulesets             : mine2
        extra rulesets       : mine,
                               special,
                               MonologExtra
        tokenslimit          : 1 000 000 000
        extensions           : Cakephp.phar,
                               Laravel.phar,
                               Melis.phar,
                               Monolog.phar,
                               Slim.phar,
                               Wordpress.phar



{{EXAKAT_EXTENSION_LIST}}

Extensions management
---------------------

The main command to manage the extensions is `extension`. It has 4 different actions : 

* `local`
* `list`
* `install`
* `update`
* `uninstall`

`local` : the local list of extensions
######################################

`list` : the remote list of extensions
######################################

`install` : the install command
###############################

This command installs a new extension. Check with `extension local` to know which are the locally installed extensions. 

::

    exakat extension install Laravel


You may also install the extensions manually, by downloading the .phar archive, and installing it in the `ext` folder.

`update` : the update command
###############################

This command updates an installed extension. Check with `extension local` to know which are the locally installed extensions. 

::

    exakat extension update Wordpress



`uninstall` : the remove command
################################

This command uninstalls a previously installed extension. Check with `extension local` to know which are the locally installed extensions. 

::

    exakat extension uninstall Laravel


You may also remove the extension manually, by removing them from the extension folder.


Extensions usage
----------------

Exakat extensions bring several resources to enhance the Exakat engine : 

* Analysis
* Ruleset
* Reports

Analysis usage 
###############

Analysis are used individually by using their short name. They may be used with any command that accepts the -P option. 

::

    exakat analyze -p <project_name> -P Drupal/Drupal_8_6
    exakat dump    -p <project_name> -P Drupal/Drupal_8_6 -u
    exakat report -p <project_name> -P Drupal/Drupal_8_6 -format Text


Analysis may also be configured in the ``config/themes.ini`` file, by including them in any section. 

::

['specialDrupal']
analyzer[] = 'Drupal/Drupal_8_6';
analyzer[] = 'Drupal/Drupal_8_5';


['specialDrupal2']
analyzer[] = 'Drupal/Drupal_8_7';
analyzer[] = 'Drupal/Drupal_8_6';
analyzer[] = 'Drupal/Drupal_8_5';


Then, they may be used with any command that accept the -T option.

::

    exakat analyze -p <project_name> -T specialDrupal
    exakat dump    -p <project_name> -T specialDrupal -u
    exakat report -p <project_name> -T specialDrupal -format Text
    

Rulesets usage 
##############

Rulesets are predefined sets of analysis. Currently, an extension always provides one ruleset with the name of the extension : it includes all the analysis in this extension.

For example, the ``Drupal`` extension provides a ``Drupal``ruleset.

::

    exakat analyze -p <project_name> -T Drupal
    exakat dump    -p <project_name> -T Drupal -u
    exakat report -p <project_name>  -T Drupal -format Text

Reports usage 
##############

Reports are specific reports for the extension. 

When no specific report is provided by the extension, results are accessible with the universal reports, such as Text. 

::

    #Report of all Drupal issues, in Text format
    exakat dump    -p <project_name> -T Drupal -format Text

    #Specific report for melis framework
    exakat report  -p <project_name> -format Melis





{{EXTENSION_DETAILS}}

