.. Extensions:

Extensions
==========

Exakat support a system of extensions, that bring extra analysis, rules sets, data sources, configurations and reports to the exakat engine. Extensions focus on specific frameworks, platform, or aspect of coding. They are a simple way to package a predefined set of configurations and analysis. 

Extensions are bundled as PHP archives (`.phar` file), installed in the `ext` folder. Check the local extensions with `doctor`.

::

    exakat doctor
    
    
    
    exakat : 
        executable           : exakat
        version              : 1.7.5
        build                : 905
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

`local`  
########

This command lists the local and installed extensions. This command is the default command. 

::

    exakat extension local
    
This command may display something like this : 

:: 

    + Extension             Version Build
    ----------------------------------------
    + Drupal                    0.1   (5)
    + Pmb                       0.5   (8)
    + Prestashop                0.1   (5)
    + Symfony                   0.6  (12)
    + Wordpress                 0.5  (28)
    
    Total : 5 extensions


Each installed extension has a version number, and a build number. The build number increases with each build, while version are milestones.

`list`
######

This command lists the remote and installable extensions. It checks the www.exakat.io web server, and collects the most recent list of extensions.

::

    exakat extension list
    
This command may display something like this : 

:: 

    + Extension             Version Build
    ----------------------------------------
    + Cakephp                   0.5   (8)
    + Codeigniter               0.1   (5)
    + Drupal                    0.1   (7)
    + Laravel                   0.1   (6)
    + Melis                     0.5  (25)
    + Monolog                   0.1   (3)
    + Prestashop                0.1   (5)
    + Shopware                  0.1   (5)
    + Slim                      0.1  (22)
    + Symfony                   0.6  (15)
    + Twig                      0.1   (3)
    + Wordpress                 0.5  (28)
    + ZendF                     0.5   (5)
    
    Total : 13 extensions
 

`install` : the install command
###############################

This command installs a new extension. Check with `extension local` to know which are the locally installed extensions. 

::

    exakat extension install Laravel


You may also install the extensions manually, by downloading the .phar archive, and installing it in the `ext` folder.

`update`
########

This command updates an installed extension. Check with `extension local` to know which are the locally installed extensions. 

::

    exakat extension update Wordpress



`uninstall`
###########

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

For example, the ``Drupal`` extension provides a ``Drupal`` ruleset.

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

