.. _Installation:

Installation
============

Summary
-------

* `Requirements`_
* `Quick installation with OSX`_
* `Full installation with Debian/Ubuntu`_
* `Quick installation with Debian/Ubuntu`_
* `Installation guide with Composer`_
* `Installation guide with Docker`_
* `Installation guide with Vagrant and Ansible`_
* `Optional installations`_

Requirements
------------

Exakat relies on several parts. Some are necessary and some are optional. 

Basic requirements : 

* exakat.phar, the main code.
* Gremlin server : exakat uses this graph database and the Gremlin 3 traversal language. Currently, only Gremlin Server is supported, with the tinkergraph and neo4j storage engine. Version 3.3.x is the recommended version. 
* Java 8.x. Java 9.x/10.x will be supported later. Java 7.x was used, but is not actively supported.
* PHP 7.0 or later to run. This version requires curl, hash, phar, sqlite3, tokenizer, mbstring and json. 

Optional requirements : 

* PHP 5.2 to 7.3 for analysis. Those versions only require the ext/tokenizer extension. 
* VCS (Version Control Software), such as Git, SVN, bazaar, Mercurial. They all are optional, though git is recommended. 
* Archives, such as zip, tgz, tbz2 may also be opened with optional helpers.

OS requirements : 
Exakat has been tested on OSX, Debian and Ubuntu (up to 14.04). Exakat should work on Linux distributions, maybe with little work. Exakat hasn't been tested on Windows at all. 

For installation, curl or wget, and zip are needed.

Download Exakat
---------------

You can download exakat directly from `http://dist.exakat.io/ <http://dist.exakat.io/>`_. 

This server also provides older versions of Exakat. It is recommended to always download the latest version, which is available with `http://dist.exakat.io/index.php?file=latest <http://dist.exakat.io/index.php?file=latest>`_. 

For each version, MD5 and SHA256 signatures are available. The downloaded MD5 must match the one in the related .md5 file. The .md5 also has the version number, for extra check.

::

    curl -o exakat.phar http://dist.exakat.io/index.php?file=latest
    
    curl -o exakat.phar.md5 http://dist.exakat.io/index.php?file=latest.md5
    //22110fe2fa1b412f5d2f4b716947760d  exakat-1.2.0.phar
    md5sum exakat.phar.md5
    // Example : 
    //22110fe2fa1b412f5d2f4b716947760d  exakat.phar
    
    curl -o exakat.phar.sha256 http://dist.exakat.io/index.php?file=latest.sha256
    //a2a2b9c41ae94c6446d43e370c6ba4cdd970d232d817bf30207e58a61c5adfd9  exakat-1.2.0.phar
    sha256sum exakat.phar.md5
    // Example : 
    //a2a2b9c41ae94c6446d43e370c6ba4cdd970d232d817bf30207e58a61c5adfd9  exakat.phar

    // Check with GPG signature
    curl -o exakat.sig http://dist.exakat.io/index.php?file=latest.sig
    // Optional step : Download the Key
    gpg --recv-keys 5EDF7EA4
    // Check with GPG signature
    gpg --verify exakat.sig exakat.phar
    // Good result : 
    //gpg: Signature made Tue Apr  3 08:28:52 2018 CEST using RSA key ID 5EDF7EA4
    //gpg: Good signature from "Seguy Damien <damien.seguy@gmail.com>" [ultimate]


Quick installation with OSX
---------------------------

Paste the following commands in a terminal prompt. It downloads Exakat and installs tinkerpop version 3.3.7. 
PHP 7.0 or more recent, curl, homebrew are required.

OSX installation with tinkergraph 3.3.7
***************************************

This is the installation script for Exakat and tinkergraph 3.3.7. 

::

    mkdir exakat
    cd exakat
    curl -o exakat.phar http://dist.exakat.io/index.php?file=latest
    curl -o apache-tinkerpop-gremlin-server-3.3.7-bin.zip http://dist.exakat.io/apache-tinkerpop-gremlin-server-3.3.7-bin.zip
    unzip apache-tinkerpop-gremlin-server-3.3.7-bin.zip 
    mv apache-tinkerpop-gremlin-server-3.3.7 tinkergraph
    rm -rf apache-tinkerpop-gremlin-server-3.3.7-bin.zip 
    
    # Optional : install neo4j engine.
    cd tinkergraph
    ./bin/gremlin-server.sh install org.apache.tinkerpop neo4j-gremlin 3.3.7
    cd ..
    
    php exakat.phar doctor

OSX installation troubleshooting
********************************

It has be reported that installation fails on OSX 10.11 and 10.12, with an error similar to 'Error grabbing Grapes'. To fix this, use the following in command line:

::

    rm -r ~/.groovy/grapes/
    rm -r ~/.m2/


They remove some files for grapes, that it will rebuild later. Then, try again the optional install instructions.


Full installation with Debian/Ubuntu
-------------------------------------

The following commands are an optional pre-requisite to the Quick installation guide, that just follows. If something is missing in the next section, check with this section that all has been installed correctly.

::

    //// Installing PHP from sury.org 
    apt update
    apt install apt-transport-https lsb-release ca-certificates
    
    wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
    sh -c 'echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list'
    apt update
    
    apt-get install php7.2 php7.2-common php7.2-cli php7.2-curl php7.2-json php7.2-mbstring php7.2-sqlite3 
    
    //// Installing Java JDK
    echo "deb http://ppa.launchpad.net/webupd8team/java/ubuntu trusty main" | tee /etc/apt/sources.list.d/webupd8team-java.list  
    echo "deb-src http://ppa.launchpad.net/webupd8team/java/ubuntu trusty main" | tee -a /etc/apt/sources.list.d/webupd8team-java.list  
    apt-get update  
    
    echo debconf shared/accepted-oracle-license-v1-1 select true | debconf-set-selections  
    echo debconf shared/accepted-oracle-license-v1-1 seen true | debconf-set-selections  
    DEBIAN_FRONTEND=noninteractive  apt-get install -y --force-yes oracle-java8-installer oracle-java8-set-default  
    
    //// Installing other tools 
    apt-get update && apt-get install -y --no-install-recommends git subversion mercurial lsof unzip 



Quick installation with Debian/Ubuntu
-------------------------------------

Debian/Ubuntu installation with Tinkergraph 3.3.7
*************************************************

Paste the following commands in a terminal prompt. It installs Exakat most recent version with Tinkergraph 3.3.7. 
PHP 7.2 (7.0 or more recent), wget and unzip are expected.

::

    mkdir exakat
    cd exakat
    wget -O exakat.phar http://dist.exakat.io/index.php?file=latest
    wget -O apache-tinkerpop-gremlin-server-3.3.7-bin.zip http://dist.exakat.io/apache-tinkerpop-gremlin-server-3.3.7-bin.zip
    unzip apache-tinkerpop-gremlin-server-3.3.7-bin.zip 
    mv apache-tinkerpop-gremlin-server-3.3.7 tinkergraph
    rm -rf apache-tinkerpop-gremlin-server-3.3.7-bin.zip 
    
    # Optional : install neo4j engine.
    cd tinkergraph
    ./bin/gremlin-server.sh -i org.apache.tinkerpop neo4j-gremlin 3.3.7
    cd ..

    php exakat.phar doctor


Installation guide with Composer
--------------------------------

Composer installation first run
*******************************

When running exakat in composer mode, 

::

    php vendor/bin/exakat init -p sculpin -R https://github.com/sculpin/sculpin.git
    php vendor/bin/exakat project -p sculpin
    
The final audit is now in the projects/sculpin/report directory.

Using multiple PHP versions
---------------------------

You need at least one version of PHP to run exakat. This version needs the `curl <http://www.php.net/curl>`_, `hash <http://www.php.net/hash>`_, `tokenizer <http://www.php.net/tokenizer>`_, `hash <http://www.php.net/hash>`_ and `sqlite3 <http://www.php.net/sqlite3>`_ extensions. They all are part of the core. 

Extra PHP-CLI versions allow more linting of the code. They only need to have the `tokenizer <http://www.php.net/tokenizer>`_ extension available.  

Exakat recommends PHP 7.3.4 (or newer version) to run Exakat. We also recommend the installation of PHP versions 5.6, 7.1, 7.2, 7.3, 7.4 and 8.0 (aka php-src master).

To install easily various versions of PHP, use the ondrej repository. Check `The main PPA for PHP (7.3, 7.2, 7.1, 7.0, 5.6)  <https://launchpad.net/~ondrej/+archive/ubuntu/php>`_.
You may also check the dotdeb repository, at `dotdeb instruction <https://www.dotdeb.org/instructions/>`_ or compile PHP yourself. 

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

There are multiple ways to use exakat with docker. There is an image with a full exakat installation, which runs with a traditional installation, or inside the audited code. Or, you may use Docker with a standard installation, to run useful part, such as a specific PHP version or the central database. 

image:: images/exakat-and-docker.png

Docker image for Exakat with projects folder
********************************************

Installation with Docker is easy, and convenient. It hides the dependency of the graph database, and keeps all files in the 'projects' folder, created in the working directory. 

Currently, Docker installation only ships with one PHP version (7.3), and with support for bazaar, composer, git, mercurial, svn, and zip.

* Install `Docker <http://www.docker.com/>`_
* Start Docker
* Pull exakat. The official docker page is `exakat/exakat <https://hub.docker.com/r/exakat/exakat/>`_.

::

    docker pull exakat/exakat

* Check-run exakat : 

::

    docker run -it -v $(pwd)/projects:/usr/src/exakat/projects --rm --name my-exakat exakat/exakat exakat version
    docker run -it -v $(pwd)/projects:/usr/src/exakat/projects --rm --name my-exakat exakat/exakat exakat doctor

* Init a project : 

::

    docker run -it -v $(pwd)/projects:/usr/src/exakat/projects --rm --name my-exakat exakat/exakat exakat init -p <project name> -R <vcs_url>

* Run exakat : 

::

    docker run -it -v $(pwd)/projects:/usr/src/exakat/projects --rm --name my-exakat exakat/exakat exakat project -p <project name>

* Run exakat directly in the code base. For that, the code needs to have the .exakat.yml or .exakat.ini file available at the root. Then, you may call exakat with the 'project' command, without other options. 

::

    docker run -it -v $(pwd)/projects:/usr/src/exakat/projects --rm --name my-exakat exakat/exakat exakat project


For large code bases, it may be necessary to increase the allocated memory for the graph database. Do this by using the JAVA_OPTIONS environnement variable when you start the docker command : this example gives 2Gb of RAM to the graphdb. That should cover medium size applications. 

::

    docker run -it -e JAVA_OPTIONS="-Xms32m -Xmx2g" -v $(pwd)/projects:/usr/src/exakat/projects --rm --name my-exakat exakat/exakat exakat


You may run any exakat command by prefixing it with the following command : 

::

    docker run -it -v $(pwd)/projects:/usr/src/exakat/projects --rm --name my-exakat exakat/exakat exakat


You may also create a handy shortcut, by creating an exakat.sh script and put it in your PATH : 

::

    cat 'docker run -it -v $(pwd)/projects:/usr/src/exakat/projects --rm --name my-exakat exakat/exakat exakat $1' > /etc/local/sbin/exakat.sh
    chmod u+x  /etc/local/sbin/exakat.sh
    ./exakat.sh version

Docker image for Exakat with projects folder
********************************************

To run exakat inside the audited code, you must configure the `.exakat.ini` or `.exakat.yaml` file. See `Add Exakat To Your CI Pipeline <https://www.exakat.io/add-exakat-to-your-ci-pipeline/>`_.

Then, you can run the following command, with docker : 

::

  docker run -it --rm -v `$pwd`:/src exakat/exakat:latest exakat project -v 


Docker PHP image with Exakat
****************************

Exakat recognizes docker images configured as PHP binaries. Instead of configuring exakat with local binaries, such as `/usr/bin/php`, you may configure a specific PHP version with a docker image. 

Open the `config/exakat.ini` file, at the root of the exakat installation, and use the following value : 

::

    // configuration with the 'tetraweb/php:5.5' image. 
    ;php55 = tetraweb/php:5.5
    php56 = tetraweb/php:5.6
    # classic configuration with local binary
    php73 = /usr/bin/php


The image may be any docker image that provides a PHP binary. We suggest using `tetraweb/php <https://hub.docker.com/r/tetraweb/php/>`_, which supports PHP 5.5 to 7.1. There are other images available, and you may also roll out your own.

Docker Gremlin image with Exakat
********************************

Exakat is able to use only the central database, Gremlin, as a docker image. This is convenient, as the database is only a temporary database, and those data are not necessary for producing the final reports. 

This image is under construction, and will be soon available. 


Installation guide with Vagrant and Ansible
-------------------------------------------

Installation list
*****************

The exakat-vagrant repository contains an automated install for exakat. It installs everything in the working directory, or the system.
Vagrant install works with Debian 8 and Ubuntu 15.10 images. Other images may be usable, but not tested.

Pre-requisites
**************

You need the following tools : 

* `git <https://git-scm.com/>`_
* `ansible <http://docs.ansible.com/ansible/intro_installation.html>`_
* `vagrant installation <https://www.vagrantup.com/docs/installation/>`_

Most may easily be installed with the local package manager, or with a direct download from the editor's website. 

Install with Vagrant and Ansible
********************************

:: 

    git clone https://github.com/exakat/exakat-vagrant
    cd exakat-vagrant
    // Review the Vagrant file to check the size of the virtualbox
    vagrant up --provision
    vagrant ssh 

You are now ready to run a project.
