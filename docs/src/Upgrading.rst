.. _Upgrading:

Upgrading
=========

Upgrading
---------

Upgrade exakat with the `upgrade` command. 

`php exakat.phar upgrade`

Exakat returns the current status : 

`This needs some updating (Current : 0.9.7c, Latest: 1.2.6)`

To make exakat update itself, runs the same command, with the `-u` option. Exakat will then download the file, check the sums, and replace itself. 

Upgrading manually
------------------

Exakat is a PHP phar archive. Download the latest version from `dist.exakat.io <http://dist.exakat.io/>`_ and replace it. 


Upgrading gremlin-server
------------------------

Exakat installs the last version of gremlin at installation time. Usually, there is no need to upgrade the database when upgrading : changing the phar file is sufficient.

However, to enjoy the new features, or keep up to date, it is recommended to upgrade the gremlin server.

To upgrade gremlin-server, remove the old 'tinkergraph' folder from your installation. If exakat was installed following the installation instruction, this folder is located next to `exakat.phar`.

Then, run again the installation instruction, only for gremlin. 