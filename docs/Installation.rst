.. _Installation:

Installation
============

Summary
-------

* `Presentation`_
* `Installation guide with Docker`_
* `Installation guide with Vagrant and Ansible`_
* `Installation guide for Debian/Ubuntu`_
* `Optional installations`_

Presentation
------------

Exakat relies on several parts : 

* the exakat.phar, which is the main code. This is usually the one invoked.
* config folder : this is in the working directory, holding the general directive for running exakat.
* projects folder : this has all the data about the code, including the reports. This project keeps a sub-folder per project.
* Neo4j : exakat uses this graph database, with the Gremlin 3 plugin. 
* PHP 7.0 to run, and PHP 5.2 to 7.2 for analysis.

Exakat has beed tested on OSX, Debian and Ubuntu (not 16.04). Exakat should be ported on Linux distributions with little work. Exakat hasn't been tested on Windows at all. 

Installation guide with Docker
------------------------------

Installation with docker is easy, and convenient. It hides the dependency on the graph database, and keeps all files in the 'projects' folder, created in the working directory.

Currently, Docker installation only ships with one PHP version (7.0).

* Install `Docker <http://www.docker.com/>`_
* Start Docker
* Pull exkat : git pull exakat/exakat
* Run exakat : docker run -it -v $(pwd)/projects:/usr/src/exakat/projects --rm --name my-exakat exakat/exakat version
* Init a project : docker run -it -v $(pwd)/projects:/usr/src/exakat/projects --rm --name my-exakat exakat/exakat init -p <project name> -R <vcs_url>
* Run exakat : docker run -it -v $(pwd)/projects:/usr/src/exakat/projects --rm --name my-exakat exakat/exakat project -p <project name>

Installation guide with Vagrant and Ansible
-------------------------------------------

Installation list
#################

The exakat-vagrant repository contains an automated install for exakat. It installs everything in the working directory, or the system.
Vagrant install works with Debian and Ubuntu images (not yet 16.04, though). Other images may be usable, but not tested.

Pre-requisites
##############

You need the following tools : 

* `git <https://git-scm.com/>`_
* `ansible <http://docs.ansible.com/ansible/intro_installation.html>`_
* `vagrant <https://www.vagrantup.com/docs/installation/>`_

Most may easily be installed with the local package manager, or with a direct download from the editor's website. 

Install with Vagrant and Ansible
################################

:: 

    git clone https://github.com/exakat/exakat-vagrant
    cd exakat-vagrant
    // Review the Vagrant file to check the size of the virtualbox
    vagrant up --provision
    vagrant ssh 

You are now ready to run a project. 

Installation guide for Debian/Ubuntu
------------------------------------

These is the installation guide for a Debian server. This also serves as general installation guide. 

pre-requisite
#############

* Java 1.8
* Neo4j 2.3.*
* Gremlin 3.2 plugin
* PHP
* exakat.phar

Debian install
##############

apt-get
+++++++

This list of apt-get will install several needed libs for the installation. 

::

	apt-get install gcc make libc-dev libtool re2c autoconf automake git curl  libcurl3 libcurl3-dev  php5-curl
	apt-get update
	apt-get upgrade
	apt-get clean


Java install
############

Java 8 is needed. Java 7 might work but is not recommended. 

The following shell code install Java 8. Root privileges are needed.

::

	## You'll need to run this as root
	echo "deb http://ppa.launchpad.net/webupd8team/java/ubuntu trusty main" > /etc/apt/sources.list.d/webupd8team-java.list
	echo "deb-src http://ppa.launchpad.net/webupd8team/java/ubuntu trusty main" >> /etc/apt/sources.list.d/webupd8team-java.list
	apt-key adv --keyserver keyserver.ubuntu.com --recv-keys EEA14886
	apt-get update
	apt-get install oracle-java8-installer
	
	# Check
	java -version 

Neo4j
+++++++++++++++++++++++++++++
Download Neo4j 2.3.* version (currently, 2.3.7). Neo4j 2.2 are not supported. Neo4j 3.0 has no support for Gremlin at the moment (2016-12-01)

`Neo4j <http://neo4j.com/>`_

::

    wget http://dist.neo4j.org/neo4j-community-2.3.7-unix.tar.gz
    tar -xvf neo4j-community-2.3.7-unix.tar.gz 
    mv neo4j-community-2.3.7 neo4j

Gremlin plug-in
+++++++++++++++

Exakat uses `gremlin plug-in <https://github.com/thinkaurelius/neo4j-gremlin-plugin>`_ for Neo4j. Follow the install instructions there. 

Make the following changes in the following files : 

* pom.xml : change the version tag from 2.3.1 to 2.3.7
* tinkerpop2/pom.xml : change the version tag from 2.3.1 to 2.3.7
* tinkerpop3/pom.xml
    + change the version tag from 2.3.1 to 2.3.7
    + change the tinkerpop-version tag from 3.1.0-incubating to 3.2.2-incubating

Then, in command line : 

::

    git clone https://github.com/thinkaurelius/neo4j-gremlin-plugin gremlin
    cd gremlin
    mvn clean package -Dtp.version=3
    unzip target/neo4j-gremlin-plugin-tp3-2.3.7-server-plugin.zip -d ../neo4j/plugins/gremlin-plugin
    cd ../neo4j
    bin/neo4j restart


Various versions of PHP
+++++++++++++++++++++++++++++

You need one version of PHP (at least) to run exakat. This version needs the `curl <http://www.php.net/curl>`_, `hash <http://www.php.net/hash>`_, `Semaphore <http://php.net/manual/en/book.sem.php>`_ , `tokenizer <http://www.php.net/tokenizer>`_ and `sqlite3 <http://www.php.net/sqlite3>`_ extensions. They all are part of the core. 

Extra PHP-CLI versions allow more checks on the code. They only need to have the `tokenizer <http://www.php.net/tokenizer>`_ extension available.  

Exakat recommends PHP 7.1.0 (or latest version) to run Exakat. We also recommend the installation of PHP versions 5.2, 5.3, 5.4, 5.5, 5.6, 7.1 and 7.2 (aka php-src master).

To install easily various versions of PHP, use the dotdeb repository. Follow the `dotdeb instruction <https://www.dotdeb.org/instructions/>`_.

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
