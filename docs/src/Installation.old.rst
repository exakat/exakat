.. _Installation_old:

Installation
============

Summary
-------

* `Presentation`_
* `Requirements`_
* `Quick installation with OSX`_
* `Quick installation with Debian/Ubuntu`_
* `Installation guide with Docker`_
* `Installation guide with Docker : all on the container`_
* `Installation with Gremlin server on Docker`_
* `Installation with PHP on Docker`_
* `Installation guide with Composer`_
* `Installation guide with Vagrant and Ansible`_
* `Installation guide for Debian/Ubuntu`_
* `Optional installations`_

Presentation
------------

Exakat is a PHP static analyzer. It relies on PHP to lint and tokenize the target code; a graph database to process the AST and the tokens; a SQLITE database to store the results and produce the various reports.

Exakat itself runs on PHP 7.0 and more recent, with a short selection of extensions. 

.. image:: exakat.architecture.png
    :alt: exakat architecture
    
Source code is imported into exakat using VCS client, like git, SVN, mercurial, tar, zip, bz2 or even symlink. Only reading access is required.

At least one version of PHP have to be used, and it may be the same running Exakat. Extra versions are used to provide linting reports. Only one version is used for analysis. 

The gremlin server is used to query the source code. Once analyzes are all finished, the results are dumped into a SQLITE database and the graph may be removed. Reports are build from this database.
    
Requirements
------------

Exakat relies on several parts. Some are necessary and some are optional. 

Basic requirements : 

* exakat.phar, the main code.
* Neo4j and gremlin : exakat uses this graph database, with the Gremlin 3 plugin. 
* PHP 7.0 or later to run. This version requires curl, hash, phar, sqlite3, tokenizer, mbstring and json. 

Optional requirements : 
* PHP 5.2 to 7.2 for analysis. Those versions only require the ext/tokenizer extension. 
* VCS (Version Control Software), such as Git, SVN, bazaar, Mercurial. They all are optional, though git is recommended. 
* Archives, such as zip, tgz, tbz2 may also be opened with optional helpers.

OS requirements : 
Exakat has beed tested on OSX, Debian and Ubuntu (up to 14.04). Exakat should work on Linux distributions, may be with little work. Exakat hasn't been tested on Windows at all. 

Quick installation with OSX
---------------------------

Paste the following commands in a terminal prompt : the first script download the exakat.phar, and the second sets up Gremlin 3 on Neo4j 2.3.
PHP 7.0 or more recent, curl, homebrew are required.

::

    mkdir exakat
    cd exakat
    curl -o exakat.phar http://dist.exakat.io/index.php?file=latest
    curl -sL https://raw.githubusercontent.com/exakat/gremlin3neo4j2/master/install.osx.sh | sh
    php exakat.phar doctor


Quick installation with Debian/Ubuntu
-------------------------------------

Paste the following commands in a terminal prompt : the first script download the exakat.phar, and the second sets up Gremlin 3 on Neo4j 2.3.
PHP 7.0 or more recent, wget are expected.

::

    mkdir exakat
    cd exakat
    wget -O exakat.phar http://dist.exakat.io/index.php?file=latest
    wget -qO- https://raw.githubusercontent.com/exakat/gremlin3neo4j2/master/install.debian.sh | sh
    php exakat.phar doctor

Installation guide with Docker
------------------------------

There are several ways to install Exakat with docker : 

* No docker at all. Then, check a bare-bone install with OSX, Debian or Vagrant.
* With the various versions of PHP on a container : convenient to have several versions of PHP without installing them. 
* With Neo4j on a container. Convenient to mask the Gremlin server.
* All exakat on a container. All packaged in one place.

Installation guide with Docker : all on the container
-----------------------------------------------------

Installation with docker is easy, and convenient. It hides the dependency on the graph database, and keeps all files in the 'projects' folder, created in the working directory.

Currently, Docker installation only ships with one PHP version (7.0).

* Install `Docker <http://www.docker.com/>`_
* Start Docker
* Pull exkat : 

::

    docker pull exakat/exakat

* Run exakat : 

::

    docker run -it -v $(pwd)/projects:/usr/src/exakat/projects --rm --name my-exakat exakat/exakat version

* Init a project : 

::

    docker run -it -v $(pwd)/projects:/usr/src/exakat/projects --rm --name my-exakat exakat/exakat init -p <project name> -R <vcs_url>

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

Installation with Gremlin server on Docker
------------------------------------------

It is possible to install Exakat as a phar or source code, and the Gremlin server as a docker image.

This installation script presume that docker is installed and running. 

::

    mkdir exakat
    cd exakat
    mkdir -p neo4j/scripts
    curl -o exakat.phar http://dist.exakat.io/index.php?file=latest
    chmod u+x exakat.phar
    php exakat doctor

    sed -i.bak -e "s/neo4j_host     = '127.0.0.1';/neo4j_ip = '`docker-machine ip`';/" config/exakat.ini
    sed -i.bak -e "s/neo4j_port     = '7474';/neo4j_port     = '7777';/" config/exakat.ini
    sed -i.bak -e "s/;loader = CypherG3/loader = CypherG3/" config/exakat.ini
    sed -i.bak -e "s/loader = Neo4jImport/;loader = Neo4jImport/" config/exakat.ini
    rm config/exakat.ini.bak

    docker pull exakat/gremlin4neo4j 
    docker run --publish=7777:7777 \
            -v $(pwd)/projects/.exakat:$(pwd)/projects/.exakat \
            -v $(pwd)/neo4j/scripts:/usr/src/gremlin/neo4j/scripts \
            -d exakat/gremlin4neo4j 

    You may now run an exakat project. Restart the docker image to run another project. 

Installation With PHP On Docker
-------------------------------

It is possible to install various PHP versions, provided as docker images. Check the `docker PHP container <https://hub.docker.com/_/php/>`_ on the docker web site to find the available containers.

In the config/exakat.ini file, mention the PHP version with this format : 

::

    ; config/exakat.ini 
    php56 = php:5.6
    php71 = php:7.1


Installation guide with Composer
--------------------------------

Exakat is available on packagist. After the composer installation, it initially requires the installation of the graph database. Once gremlin installed, it is rarely updated.

The documentation is written with OSX as target. 

::

    mkdir exakat
    cd exakat
    composer require exakat/exakat
    php vendor/bin/exakat doctor
    curl -sL https://raw.githubusercontent.com/exakat/gremlin3neo4j2/master/install.osx.sh | sh
    php vendor/bin/exakat init -p x 


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
Download Neo4j 2.3.* version (currently, 2.3.9). Neo4j 2.2 is not supported anymore. Neo4j 3.0 has no support for Gremlin at the moment (2017-03-01)

`Neo4j <http://neo4j.com/>`_

::

    wget http://dist.neo4j.org/neo4j-community-2.3.9-unix.tar.gz
    tar -xvf neo4j-community-2.3.9-unix.tar.gz 
    mv neo4j-community-2.3.9 neo4j

In the neo4j folder, update the server configuration. The configuration is in the neo4j_home/conf/neo4j-server.properties : 

Activate the gremlin plugin.
::

    #org.neo4j.server.thirdparty_jaxrs_classes=org.neo4j.examples.server.unmanaged=/examples/unmanaged
    # add this line below the above one
    org.neo4j.server.thirdparty_jaxrs_classes=com.thinkaurelius.neo4j.plugins=/tp


You may also disable authentication. If not, do not forget to update the config/exakat.ini file, with the right credential. 
::

    #dbms.security.auth_enabled=true
    dbms.security.auth_enabled=false



Gremlin plug-in
+++++++++++++++

Exakat uses `gremlin plug-in <https://github.com/thinkaurelius/neo4j-gremlin-plugin>`_ for Neo4j. Follow the install instructions there. 

Make the following changes in the following files : 

* tinkerpop3/pom.xml
    + change the tinkerpop-version tag from 3.1.0-incubating to 3.2.0-incubating

Then, in command line : 

::

    git clone https://github.com/thinkaurelius/neo4j-gremlin-plugin gremlin
    cd gremlin
    mvn clean package -Dtp.version=3
    unzip target/neo4j-gremlin-plugin-tp3-2.3.1-server-plugin.zip -d ../neo4j/plugins/gremlin-plugin
    cd ../neo4j
    bin/neo4j restart


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
