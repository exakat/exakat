.. _FAQ:

Frequently Asked Questions
==========================

Summary
-------

* `I need special command to get my code`_
* `The project is too big`_
* `Where can I find the report`_
* `Can I run exakat on local code?`_
* `Can I run exakat on Windows?`_


* `I need special command to get my code`_
------------------------------------------

If Exakat has no documented method to reach your code, you may use this process : 

::

    php exakat.phar init -p <your project name>
    cd ./projects/<your project name>
    mkdir code
    // here, do whatever it takes to put all your code in 'code' folder
    cd -
    php exakat.phar project -p <your project name>


Send a message on Github.com/exakat/exakat to mention your specific method.

`The project is too big`_
-------------------------

There is a soft limit in config/exakat.ini, called 'token_limit' that initially prevents analysis of projects over 1 millions tokens. That's roughly 125k LOC, more than most code source.

If you need to run exakat on larger sources, you may change this value to make it as large as possible. Then, the physical capacities of the machine, specially RAM, will be the actual limit. 

It may be interesting to 'ignore_dir[]', from projects/<>/config.ini. 


`Where can I find the report`_
------------------------------

Reports are available after running at least the following commands : 

::

    php exakat.phar init -p <your project name> -R <code source repo> 
    php exakat.phar project -p <your project name>


The default report is the HTML report, called 'Ambassador'. You'll find it in ./projects/<your project name>/report.

Other reports, build with 'report' command, will also be saved there, with different names. 

`Can I run exakat on local code?`_
----------------------------------

There are several ways to do that : use symbolic links, make a copy of the source.

::

    php exakat.phar init -p <your project name> -R <path/to/the/code> -symlink 
    php exakat.phar init -p <your project name> -R <path/to/the/code> -copy 
    php exakat.phar init -p <your project name> -R <path/to/the/code> -git 

Symlink will branch exakat directly into the code; -copy makes a copy of the code (this means the code will never be updated without manual intervention); git (or other vcs) may also be used with local repositories. 

Exakat do not modify any existing source code : it only access it for reading purpose, then works on a separated database. As a defensive security measure, we suggest that exakat should work on a read-only copy of the code. 

* use a symlink 

`Can I run exakat on Windows?`_
----------------------------------

Currently, Windows is not supported, though it might be some day. 
