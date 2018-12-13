.. Extensions:

Extensions
==========

Exakat support a system of extensions, that bring supplementary analysis, categories, data sources, configurations and reports to the current system. Extensions may focus on specific frameworks or platform, or be a simple way to package a predefined set of configuration. 

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
        themes               : mine2
        extra themes         : mine,
                               special,
                               MonologExtra
        tokenslimit          : 1 000 000 000
        extensions           : Cakephp.phar,
                               Laravel.phar,
                               Melis.phar,
                               Monolog.phar,
                               Slim.phar,
                               Wordpress.phar



List of extensions : there are 6 extensions

* Cakephp
* Laravel
* Melis
* Slim
* Wordpress
* ZendF





Extension managements
---------------------

The main command to manage the extensions is `extension`. It has 4 different actions : 

* `local`
* `list`
* `install`
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


`uninstall` : the remove command
################################

This command uninstalls a previously installed extension. Check with `extension local` to know which are the locally installed extensions. 

::

    exakat extension uninstall Laravel


You may also remove the extension manually, by removing them from the extension folder.



Details about the extension

Cakephp
#######
This is the extension for Cakephp

Laravel
#######
This is the extension for Laravel

Melis
#####
This is the extension for Melis

Slim
####
This is the extension for Slim

Wordpress
#########
This is the extension for Wordpress

ZendF
#####
This is the extension for ZendF





