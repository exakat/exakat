.. _FAQ:

Frequently Asked Questions
==========================

Summary
-------

* `I need special command to get my code`_
* `Can I checkout that branch?`_
* `The project is too big`_
* `Where can I find the report`_
* `Can I run exakat on local code?`_
* `I get the error 'The executable 'ansible-playbook' Vagrant is trying to run was not found'`_
* `Can I run exakat on Windows?`_
* `Does exakat send my code to a central server?`_


`I need special command to get my code`_
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

`Can I checkout that branch?`_
------------------------------

Currently (Version 0.9.9), there is no way to request a tag or a branche or a revision when cloning the code. 

The best way is to reach the 'code' folder, and make the change there. Unless with 'init' or 'update', exakat doesn't make any change to the code. 

::

    php exakat.phar init -p myProject
    cd ./projects/myProject/code
    git branch notMasterBranch
    cd -
    php exakat.phar project -p myProject


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

`I get the error 'The executable 'ansible-playbook' Vagrant is trying to run was not found'`_
---------------------------------------------------------------------------------------------

This error is displayed when the host machine doesn't have Ansible installed. Install ansible, and try again to provision. 

`Can I run exakat on Windows?`_
-------------------------------

Currently, Windows is not supported, though it might be some day. 

Until then, you may run Exakat with Vagrant, or with Docker. 

`Does exakat send my code to a central server?`_
-------------------------------------------------

When run from the sources, Exakat has everything it needs to fulfill its mission. There is no central server that does the job, and requires the transmission of the code.

When running an audit on the Saas service of Exakat, the code is processed on our servers. 