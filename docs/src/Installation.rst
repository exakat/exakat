.. _Installation:

Installation
============

Summary
-------

* `Requirements`_
* `Quick installation with exakat.phar`_
* `Quick installation with OSX`_
* `Full installation with Debian/Ubuntu`_
* `Quick installation with Debian/Ubuntu`_
* `Installation guide with Composer`_
* `Installation guide with Docker`_
* `Installation guide as Github Action`_
* `Installation guide for optional tools`_

Requirements
------------

Exakat relies on several parts. Some are necessary and some are optional. 

Basic requirements : 

* exakat.phar, the main code.
* `Gremlin server <http://tinkerpop.apache.org/>`_ : exakat uses this graph database and the Gremlin 3 traversal language. Currently, only Gremlin Server is supported, with the tinkergraph and neo4j storage engine. Version 3.4.x is the recommended version, while version 3.3.x are still supported. Gremlin version 3.2.* are unsupported. 
* Java 8.x. Java 9.x/10.x will be supported later. Java 7.x was used, but is not actively supported.
* `PHP <https://www.php.net/>`_ 7.4 to run. PHP 7.4 is recommended, PHP 7.2 or later are possible. This version requires the PHP extensions curl, hash, phar, sqlite3, tokenizer, mbstring and json. 

Optional requirements : 

* PHP 5.2 to 8.0-dev for analysis purposes. Those versions only require the ext/tokenizer extension. 
* VCS (Version Control Software), such as Git, SVN, bazaar, Mercurial. They all are optional, though git is recommended. 
* Archives, such as zip, tgz, tbz2 may also be opened with optional helpers (See `Installation guide for optional tools`_).

OS requirements : 
Exakat has beed tested on OSX, Debian and Ubuntu (up to 20.04). Exakat should work on Linux distributions, may be with little work. Exakat hasn't been tested on Windows at all. 

For installation, curl or wget, and zip are needed.

Download Exakat
---------------

You can download exakat directly from `https://www.exakat.io/ <https://www.exakat.io/>`_. 

This server also provides older versions of Exakat. It is recommended to always download the last version, which is available with `https://www.exakat.io/versionss/index.php?file=latest <https://www.exakat.io/versions/index.php?file=latest>`_. 

For each version, MD5 and SHA256 signatures are available. The downloaded MD5 must match the one in the related .md5 file. The .md5 also has the version number, for extra check.

::

    curl -o exakat.phar 'https://www.exakat.io/versions/index.php?file=latest'
    
    curl -o exakat.phar.md5 'https://www.exakat.io/versions/index.php?file=latest.md5'
    //19485adb7d43b43f7c01b7153ae82881  exakat-2.0.0.phar
    md5sum exakat.phar.md5
    // Example : 
    //19485adb7d43b43f7c01b7153ae82881  exakat.phar
    
    curl -o exakat.phar.sha256 'https://www.exakat.io/versions/index.php?file=latest.sha256'
    //d838c9ec9291e15873137693da2a0038a67c2f15c2282b89f09f27f23d24d27f  exakat-2.0.0.phar
    sha256sum exakat.phar.md5
    // Example : 
    //d838c9ec9291e15873137693da2a0038a67c2f15c2282b89f09f27f23d24d27f  exakat.phar

    // Check with GPG signature
    curl -o exakat.sig 'https://www.exakat.io/versions/index.php?file=latest.sig'
    // Optional step : Download the Key
    gpg --recv-keys 5EDF7EA4
    // Check with GPG signature
    gpg --verify exakat.sig exakat.phar
    // Good result : 
    //gpg: Signature made Tue Nov  5 07:48:34 2019 CET using RSA key ID 5EDF7EA4
    //gpg: Good signature from "Seguy Damien <damien.seguy@gmail.com>" [ultimate]


Quick installation with exakat.phar
-----------------------------------

OSX installation with tinkergraph 3.4.8
***************************************

Exakat.phar includes its own installation script, as long as PHP is available. Exakat will then check different pre-requisites, and proceed to install some of the last elements.

Exakat checks for Java and Zip installations. Then, it downloads tinkergraph and the Neo4j plugin from exakat.io and runs the `doctor` command.

The script is based on the one displayed on the next section.

You can use the `install` command this way : 

::

    mkdir exakat
    cd exakat
    curl -o exakat.phar 'https://www.exakat.io/versions/index.php?file=latest'
    php exakat.phar install 


Quick installation with OSX
---------------------------

Paste the following commands in a terminal prompt. It downloads Exakat, and installs tinkerpop version 3.4.8. 
PHP 7.0 or more recent, curl, homebrew are required.

OSX installation with tinkergraph 3.4.8
***************************************

This is the installation script for Exakat and tinkergraph 3.4.8. 

::

    mkdir exakat
    cd exakat
    curl -o exakat.phar 'https://www.exakat.io/versions/index.php?file=latest'
    curl -o apache-tinkerpop-gremlin-server-3.4.8-bin.zip 'https://www.exakat.io/versions/apache-tinkerpop-gremlin-server-3.4.8-bin.zip'
    unzip apache-tinkerpop-gremlin-server-3.4.8-bin.zip 
    mv apache-tinkerpop-gremlin-server-3.4.8 tinkergraph
    rm -rf apache-tinkerpop-gremlin-server-3.4.8-bin.zip 
    
    # Optional : install neo4j engine.
    cd tinkergraph
    ./bin/gremlin-server.sh install org.apache.tinkerpop neo4j-gremlin 3.4.8
    cd ..
    
    php exakat.phar doctor

OSX installation troubleshooting
********************************

It has be reported that installation fails on OSX 10.11 and 10.12, with error similar to 'Error grabbing Grapes'. To fix this, use the following in command line : 

::

    rm -r ~/.groovy/grapes/
    rm -r ~/.m2/


They remove some files for grapes, that it will rebuild later. Then, try again the optional install instructions.


Full installation with Debian/Ubuntu
-------------------------------------

The following commands are an optional pre-requisite to the Quick installation guide, that just follows. If something is missing in the next section, check with this section that all has beed installed correctly.

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

Debian/Ubuntu installation with Tinkergraph 3.4.8
*************************************************

Paste the following commands in a terminal prompt. It installs Exakat most recent version with Tinkergraph 3.4.8. 
PHP 7.3 (7.0 or more recent), wget and unzip are expected.

::

    mkdir exakat
    cd exakat
    wget -O exakat.phar https://www.exakat.io/versions/index.php?file=latest
    wget -O apache-tinkerpop-gremlin-server-3.4.8-bin.zip 'https://www.exakat.io/versions/apache-tinkerpop-gremlin-server-3.4.8-bin.zip'
    unzip apache-tinkerpop-gremlin-server-3.4.8-bin.zip 
    mv apache-tinkerpop-gremlin-server-3.4.8 tinkergraph
    rm -rf apache-tinkerpop-gremlin-server-3.4.8-bin.zip 
    
    # Optional : install neo4j engine.
    cd tinkergraph
    ./bin/gremlin-server.sh install org.apache.tinkerpop neo4j-gremlin 3.4.8
    cd ..

    php exakat.phar doctor


Installation guide with Composer
--------------------------------

Composer installation first run
*******************************

To install Exakat with composer, you can use the following commands: 

::

    mkdir exakat
    cd exakat
    composer require exakat/exakat
    php vendor/bin/exakat install -v

The final command checks for the presence of Java and unZip utility. Then, it installs a local copy of a `Gremlin server <http://tinkerpop.apache.org/>`_. This is needed to run Exakat. 

To run your first audit, use the following commands: 

::

    php vendor/bin/exakat init -p sculpin -R 'https://github.com/sculpin/sculpin.git'
    php vendor/bin/exakat project -p sculpin
    


The final audit is now in the `projects/sculpin/report` directory.

Using multiple PHP versions
---------------------------

You need at least one version of PHP to run exakat. This version needs the `curl <http://www.php.net/curl>`_, `hash <http://www.php.net/hash>`_, `tokenizer <http://www.php.net/tokenizer>`_, `hash <http://www.php.net/hash>`_ and `sqlite3 <http://www.php.net/sqlite3>`_ extensions. They all are part of the core. 

Extra PHP-CLI versions allow more linting of the code. They only need to have the `tokenizer <http://www.php.net/tokenizer>`_ extension available.  

Exakat recommends PHP 7.4.4 (or newer version) to run Exakat. We also recommend the installation of PHP versions 5.6, 7.1, 7.2, 7.3, 7.4 and 8.0 (aka php-src master).

To install easily various versions of PHP, use the ondrej repository. Check `The main PPA for PHP (7.4, 7.3, 7.2, 7.1, 7.0, 5.6)  <https://launchpad.net/~ondrej/+archive/ubuntu/php>`_.
You may also check the dotdeb repository, at `dotdeb instruction <https://www.dotdeb.org/instructions/>`_ or compile PHP yourself. 

Installation guide with Docker
------------------------------

There are multiple ways to use exakat with docker. There is an image with a full exakat installation, which run with a traditional installation, or inside the audited code. Or, You may use Docker with a standard installation, to run useful part, such as a specific PHP version or the central database. 

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


For large code bases, it may be necessary to increase the allocated memory for the graph database. Do this by using the JAVA_OPTIONS environment variable when you start the docker command : this example gives 2Gb of RAM to the graphdb. That should cover medium size applications.

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


Installation guide as Github Action
-----------------------------------

Github Action
*************

`Github Action <https://docs.github.com/en/actions>`_ is a way to "Automate, customize, and execute your software development workflows right in your repository". Exakat may be run on Github platform.
 
 
Github Action for Exakat
************************

To add Exakat to your repository on Github, create a file `.github/workflows/test.yml`, at the root of your repository (`.github/workflows` might already exists).

In the file, use the following YAML code. It will create an automatic action, on push and pull_request actions, that runs Exakat and display the issues found in the workflow panel. It is also possible to run manually this action. 

:: 

    on: [push, pull_request]
    name: Test
    jobs:
      exakat:
        name: Exakat
        runs-on: ubuntu-latest
        steps:
        - uses: actions/checkout@v2
        - name: Exakat
          uses: docker://exakat/exakat-ga

Note : it is recommended to edit this file directly on github.com, as it cannot be pushed from a remote repository. 

Then, you can use the `Action` button, next to 'Pull requests'. 


Exakat Docker image for Github Action
*************************************

A Docker image is released with Exakat's version automatically, to be used with Github Action. It is available at `https://hub.docker.com/r/exakat/exakat-ga <https://hub.docker.com/r/exakat/exakat-ga>`_.

You can run it in any given directory like this:


:: 

    cd /path/to/code
    docker pull exakat/exakat-ga
    docker run --rm -it -v ${PWD}:/app exakat/exakat-ga:latest


Installation guide for optional tools
*************************************

Exakat is able to use a variety of tools to access PHP code to audit. Some external tools are necessary. You can check which tools are recognized locally with the `exakat doctor -v` command. 

+ `Bazaar <https://bazaar.canonical.com/en/>`_ : the `bzr` command must be available.
+ `composer <https://getcomposer.org/>`_ : the `composer` command must be available.
+ `CVS <https://www.nongnu.org/cvs/>`_ : the `cvs` command must be available
+ `Git <https://git-scm.com/>`_ : the `git` command must be available.
+ `mercurial <https://www.mercurial-scm.org/>`_ : the `hg` must be available
+ `Svn <https://subversion.apache.org/>`_ : the `svn` command must be available.
+ tgz : the `tar` and `gunzip` commands must be available
+ tbz : the `tar` and `bunzip2` commands must be available.
+ `rar <https://en.wikipedia.org/wiki/RAR_(file_format)>`_ : the `rar` commands must be available.
+ `zip <https://en.wikipedia.org/wiki/Zip_(file_format)>`_ : the `unzip` command must be available.
+ `7z <https://www.7-zip.org/7z.html>`_ : the `7z` command must be available

The binaries above are used with the `init` and `update` commands, to get the source code. They are optional.
