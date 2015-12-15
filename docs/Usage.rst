.. _Usage:

Exakat usage
************

A first test
------------

A simple run for the report : 

::

   php exakat.phar init -p sculpin -R https://github.com/sculpin/sculpin

This will init the project in the 'projects' folder, and clone the code with the provided repository. 

* `-p` : this is the name of the project. Use anything that may be a folder's name, as it will be located in 'projects/' folder. This option may be reused by later exakat commands.
* `-R` : this is the code repository. We recommend using Git. Other code repository, such as mercurial, svn or composer are still experimental.

Then, run : 
:: 

   php exakat.phar project -p sculpin


This will run the whole analysis.

Once it is finished, you may find the result in `projects/sculpin/report`. Simply open the 'index.html' file in a browser.

Note that Safari or Chrome have a security feature that will prevent them from loading directly the report. To avoid this, put the report on a webserver and open it again via http. 

Inline Help
-----------

::

   php exakat.phar help

It will display ::

   [Usage] :   php exakat.phar init -p <Project name> -R <Repository>
               php exakat.phar project -p <Project name>
               php exakat.phar doctor
               php exakat.phar version


Doctor
------

The 'doctor' command displays a list of configuration, and checks the current installation.
::

php exakat.phar doctor

It displays a list of configurations and information about the installation. This is useful to check if the installation is what `exakat` expects.
::

   php : 
       version              : 5.6.9
       curl                 : Yes
       sqlite3              : Yes
       tokenizer            : Yes
   
   java : 
       installed            : Yes
       type                 : Java(TM) SE Runtime Environment (build 1.8.0_60-b25)
       version              : 1.8.0_60
       $JAVA_HOME           : /Library/Java/JavaVirtualMachines/jdk1.8.0_60.jdk/Contents/Home
   
   neo4j : 
       version              : Neo4j 2.2.4
       port                 : 7474
       pid                  : 93022
       running              : Yes
       running here         : Yes
       gremlin              : Yes
       $NEO4J_HOME          : /usr/me/exakat/neo4j
   
   zip : 
       installed            : Yes
       version              : 3.0
   
   folders : 
       config-folder        : Yes
       config.ini           : Yes
       projects folder      : Yes
       test                 : No
       default              : No
       onepage              : Yes
   
   PHP 5.2 : 
       configured           : No
   
   PHP 5.3 : 
       configured           : Yes
       installed            : Yes
       version              : 5.3.29
       short_open_tags      : Off
       timezone             : Europe/Amsterdam
       tokenizer            : Yes
   
   PHP 5.4 : 
       configured           : Yes
       installed            : Yes
       version              : 5.4.41
       short_open_tags      : Off
       timezone             : Europe/Amsterdam
       tokenizer            : Yes
   
   PHP 5.5 : 
       configured           : Yes
       installed            : Yes
       version              : 5.5.25
       short_open_tags      : Off
       timezone             : Europe/Amsterdam
       tokenizer            : Yes
   
   PHP 5.6 : 
       configured           : /usr/bin/php56
       installed            : Yes
       version              : 5.6.9
       short_open_tags      : Off
       timezone             : Europe/Amsterdam
       tokenizer            : Yes
   
   PHP 7.0 : 
       configured           : Yes
       version              : 7.0.0-dev
       short_open_tags      : Off
       timezone             : Europe/Amsterdam
       tokenizer            : Yes
   
   hg : 
       installed            : Yes
       version              : 3.4
   
   svn : 
       installed            : Yes
       version              : 1.8.13
   
   composer : 
       installed            : Yes
       version              : 1.0.0-alpha10
   
   wget : 
       installed            : Yes
       version              : GNU Wget 1.16.3 built on darwin14.1.0.


Code update
-----------

You may update the code in the `projects/<name>/code` folder. After such an operation, you shall run the project again.

::

php exakat.phar project -p sculpin 


Project removal
---------------

To clean a repository, simply remove it from the `projects` folder.

::
   rm -rf projects/<name>
