.. _Installation:

Installation
============

Summary
-------

* `Presentation`_
* `Requirements`_
* `Quick installation with OSX`_
* `Quick installation with Debian/Ubuntu`_
* `Installation guide with Docker`_
* `Installation guide with Vagrant and Ansible`_
* `Optional installations`_

Presentation
------------

Exakat is a PHP static analyzer. It relies on PHP to lint and tokenize the target code; a graph database to process the AST and the tokens; a SQLITE 3 database to store the results and produce the various reports.

Exakat itself runs on PHP 7.2, with a short selection of extensions. It is tested with PHP 7.0 and more recent.

.. image:: exakat.architecture.png
    :alt: exakat architecture
    
Source code is imported into exakat using VCS client, like git, SVN, mercurial, tar, zip, bz2 or even symlink. Only reading access is actually required : the code is never modified in any way. 

At least one version of PHP have to be used, and it may be the same running Exakat. Only one version is used for analysis and it may be different from the running PHP version. For example, exakat may run with PHP 7.2 but audit code with PHP 5.6. Extra versions of PHP are used to provide compilations reports. PHP middle versions may be configured separately. Minor versions are not important, except for edge cases. 

The gremlin server is used to query the source code. Once analyzes are all finished, the results are dumped into a SQLITE database and the graph may be removed. Reports are build from the SQLITE database.

The older version of the installation chapter, including some sections that are not yet upgraded, is available in the `Installation_old`_.

Requirements
------------

Exakat relies on several parts. Some are necessary and some are optional. 

Basic requirements : 

* exakat.phar, the main code.
* Gremlin server : exakat uses this graph database and the Gremlin 3 traversal language. Currently, only Gremlin Server is supported, with the tinkergraph and neo4j storage engine. Version 3.2.x are supported, 3.3.x not yet.
* Java 8.x. Java 9.x will be supported later. Java 7.x 
* PHP 7.0 or later to run. This version requires curl, hash, phar, sqlite3, tokenizer, mbstring and json. 

Optional requirements : 

* PHP 5.2 to 7.3 for analysis. Those versions only require the ext/tokenizer extension. 
* VCS (Version Control Software), such as Git, SVN, bazaar, Mercurial. They all are optional, though git is recommended. 
* Archives, such as zip, tgz, tbz2 may also be opened with optional helpers.

OS requirements : 
Exakat has beed tested on OSX, Debian and Ubuntu (up to 14.04). Exakat should work on Linux distributions, may be with little work. Exakat hasn't been tested on Windows at all. 

For installation, curl or wget, and zip are needed.

Quick installation with OSX
---------------------------

Paste the following commands in a terminal prompt : the first script download the exakat.phar, and the second sets up Gremlin 3 on Neo4j 2.3.
PHP 7.0 or more recent, curl, homebrew are required.

::

    mkdir exakat
    cd exakat
    curl -o exakat.phar http://dist.exakat.io/index.php?file=latest
    curl -o apache-tinkerpop-gremlin-server-3.2.6-bin.zip http://ftp.tudelft.nl/apache/tinkerpop/3.2.6/apache-tinkerpop-gremlin-server-3.2.6-bin.zip
    unzip apache-tinkerpop-gremlin-server-3.2.6-bin.zip 
    mv apache-tinkerpop-gremlin-server-3.2.6 tinkergraph
    rm -rf apache-tinkerpop-gremlin-server-3.2.6-bin.zip 
    
    # Optional : install neo4j engine.
    cd tinkergraph
    bin/gremlin-server.sh -i org.apache.tinkerpop neo4j-gremlin 3.2.6
    cd ..
    
    php exakat.phar doctor


Quick installation with Debian/Ubuntu
-------------------------------------

Paste the following commands in a terminal prompt : the first script download the exakat.phar, and the second sets up Gremlin 3.*, with tinkergrpah and Neo4j.
PHP 7.2 (7.0 or more recent), wget and unzip are expected.

::

    mkdir exakat
    cd exakat
    wget -O exakat.phar http://dist.exakat.io/index.php?file=latest
    wget -O apache-tinkerpop-gremlin-server-3.2.6-bin.zip http://ftp.tudelft.nl/apache/tinkerpop/3.2.6/apache-tinkerpop-gremlin-server-3.2.6-bin.zip
    unzip apache-tinkerpop-gremlin-server-3.2.6-bin.zip 
    mv apache-tinkerpop-gremlin-server-3.2.6 tinkergraph
    rm -rf apache-tinkerpop-gremlin-server-3.2.6-bin.zip 
    
    # Optional : install neo4j engine.
    cd tinkergraph
    bin/gremlin-server.sh -i org.apache.tinkerpop neo4j-gremlin 3.2.6
    cd ..

    php exakat.phar doctor

Various versions of PHP
+++++++++++++++++++++++

You need one version of PHP (at least) to run exakat. This version needs the `curl <http://www.php.net/curl>`_, `hash <http://www.php.net/hash>`_, `Semaphore <http://php.net/manual/en/book.sem.php>`_ , `tokenizer <http://www.php.net/tokenizer>`_ and `sqlite3 <http://www.php.net/sqlite3>`_ extensions. They all are part of the core. 

Extra PHP-CLI versions allow more checks on the code. They only need to have the `tokenizer <http://www.php.net/tokenizer>`_ extension available.  

Exakat recommends PHP 7.1.0 (or latest version) to run Exakat. We also recommend the installation of PHP versions 5.2, 5.3, 5.4, 5.5, 5.6, 7.1 and 7.2 (aka php-src master).

To install easily various versions of PHP, use the ondrej repository. Check `The main PPA for PHP (5.6, 7.0, 7.1)  <https://launchpad.net/~ondrej/+archive/ubuntu/php>`_.
You may also check the dotdeb repository, at `dotdeb instruction <https://www.dotdeb.org/instructions/>`_. 

Exakat 
######
Download the `exakat.phar` archive from `exakat.io <http://www.exakat.io/>`_ and place it in the `exakat` folder.

Test
####

From the commandline, run `php exakat.phar doctor`.
This will check if all of the above has be correctly run and will report some diagnostic. 

Optional installations
----------------------

By default, exakat works with Git repository for downloading code. You may also use 

* `composer <https://getcomposer.org/>`_
* `svn <https://subversion.apache.org/>`_
* `hg <https://www.mercurial-scm.org/>`_
* `bazaar <http://bazaar.canonical.com/en/>`_
* zip

The binaries above are used with the `init` and `update` commands, to get the source code. They are optional.

Installation guide with Docker
------------------------------

Installation with docker is easy, and convenient. It hides the dependency on the graph database, and keeps all files in the 'projects' folder, created in the working directory.

Currently, Docker installation only ships with one PHP version (7.1), and with support for git, svn and mercurial.

* Install `Docker <http://www.docker.com/>`_
* Start Docker
* Pull exakat. The official docker page is `exakat/exakat <https://hub.docker.com/r/exakat/exakat/>`_.

::

    docker pull exakat/exakat

* Run exakat : 

::

    docker run -it -v $(pwd)/projects:/usr/src/exakat/projects --rm --name my-exakat exakat/exakat version

* Init a project : 

::

    docker run -it -v $(pwd)/projects:/usr/src/exakat/projects --rm --name my-exakat exakat/exakat init -p <project name> -R <vcs_url>

**Please note**: The init command usually makes a local clone of your repository. In case you want to analyse a private repository for which you need an SSH key, init will fail silently on the cloning process. Before running the `project` command below, run:

::

    cd projects/<project name>
    git clone <git_url> code

If you don't use git, or don't want to use version control, make sure that your project code ends up in `projects/<project name>/code` for Exakat to work correctly.

* Run exakat : 

::

    docker run -it -v $(pwd)/projects:/usr/src/exakat/projects --rm --name my-exakat exakat/exakat project -p <project name>

You may simply run any exakat command by prefixing it with the following command : 

::

    docker run -it -v $(pwd)/projects:/usr/src/exakat/projects --rm --name my-exakat exakat/exakat 


You may also create a handy shortcut, by creating an exakat.sh script and put it in your PATH : 

::

    cat 'docker run -it -v $(pwd)/projects:/usr/src/exakat/projects --rm --name my-exakat exakat/exakat $1' > /etc/local/sbin/exakat.sh
    chmod u+x  /etc/local/sbin/exakat.sh
    ./exakat.sh version
    
Installation guide with Vagrant and Ansible
-------------------------------------------

Installation list
+++++++++++++++++

The exakat-vagrant repository contains an automated install for exakat. It installs everything in the working directory, or the system.
Vagrant install works with Debian 8 and Ubuntu 15.10 images. Other images may be usable, but not tested.

Pre-requisites
++++++++++++++

You need the following tools : 

* `git <https://git-scm.com/>`_
* `ansible <http://docs.ansible.com/ansible/intro_installation.html>`_
* `vagrant <https://www.vagrantup.com/docs/installation/>`_

Most may easily be installed with the local package manager, or with a direct download from the editor's website. 

Install with Vagrant and Ansible
++++++++++++++++++++++++++++++++

:: 

    git clone https://github.com/exakat/exakat-vagrant
    cd exakat-vagrant
    // Review the Vagrant file to check the size of the virtualbox
    vagrant up --provision
    vagrant ssh 

You are now ready to run a project.