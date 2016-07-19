.. _Installation:

Installation
============

Summary
-------

* `Installation guide with Vagrant and Ansible`_
* `Installation guide for Debian`_
* `Installation guide for OSX`_
* `Generic installation guide`_
* `Optional installation`_

Installation guide with Vagrant and Ansible
-------------------------------------------

Installation list
#################

The exakat-vagrant repository contains an automated install for exakat with the last version. It installs : 

* PHP 5.4, 5.5, 5.6, 7.0, 7.1 and 7.2 (a.k.a, dev)
* Neo4j 2.3.5
* Gremlin 3.2 
* Java 8
* Exakat > 0.7.4

Pre-requisites
##############

You need the following tools : 

* [git](https://git-scm.com/)
* [ansible](http://docs.ansible.com/ansible/intro_installation.html)
* [vagrant](https://www.vagrantup.com/docs/installation/)

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


Installation guide for Debian
-----------------------------

This is a specific installation guide for a Debian server.

pre-requisite
#############

* Java 1.8
* Neo4j 2.3.*
* Gremlin 3.0 plugin
* PHP (at least one version)
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
Download Neo4j 2.3.* version (currently, 2.3.5). Neo4j 2.2 are not supported. Neo4j 3.0 has no support for Gremlin at the moment (2016-07-01)

[Neo4j](http://neo4j.com/)

::

    wget http://dist.neo4j.org/neo4j-community-2.3.5-unix.tar.gz
    tar -xvf neo4j-community-2.3.5-unix.tar.gz 
    mv neo4j-community-2.3.5 neo4j

Gremlin plug-in
+++++++++++++++

Exakat uses [gremlin plug-in](https://github.com/thinkaurelius/neo4j-gremlin-plugin) for Neo4j. Follow the install instructions there. 

Make the following changes in the following files : 

* pom.xml : change the version tag from 2.3.1 to 2.3.5
* tinkerpop2/pom.xml : change the version tag from 2.3.1 to 2.3.5
* tinkerpop3/pom.xml
    + change the version tag from 2.3.1 to 2.3.5
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
+++++++++++++++++++++++++++++

You need one version of PHP (at least) to run exakat. This version needs the [`curl`](http://www.php.net/curl), [`hash`](http://www.php.net/hash), [`tokenizer`](http://www.php.net/tokenizer) and [`sqlite3`](http://www.php.net/sqlite3) extensions. They all are part of the core. 

Extra PHP-CLI versions allow more checks on the code. They only need to have the [`tokenizer`](http://www.php.net/tokenizer) extension available.  

Exakat recommends PHP 7.0.8 (or latest version) to run Exakat. We also recommend the installation of PHP versions 5.2, 5.3, 5.4, 5.5, 5.6, 7.1-alpha and 7.2-dev.

To install easily various versions of PHP, use the dotdeb repository. Follow the [dotdeb instruction](https://www.dotdeb.org/instructions/).

Exakat 
######
Download the `exakat.phar` archive from [exakat.io](http://www.exakat.io/) and place it in the `exakat` folder.

Test
####

From the commandline, run `php exakat.phar doctor`.
This will check if all of the above has be correctly run and will report some diagnostic. 



Installation guide for Osx
--------------------------

pre-requisite
#############
* Xcode
* homebrew
* git
* Java 1.8
* Neo4j 2.3.*
* Gremlin plugin
* zip
* PHP version
* exakat

OSX install
############

You need to use the Terminal, which is always installed with OSX.

You need [xcode](https://developer.apple.com/xcode/) installed, with the command line tools. Xcode is available for free in the App store. 

Create a folder for exakat. It will contain four elements : `neo4j` folder, the `exakat.phar` and the projects folder `projects`. Other folders will be created along the way.

homebrew
########

[Homebrew](http://brew.sh/) is a package manager for OSX. It will speed up the installation if you install it now. You may do also without it, or using [Fink](http://www.finkproject.org/) or [macport](https://www.macports.org/).

::

    ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"

If brew is installed, it is a good moment to check the updates and then the doctor. 
:: 

    brew update; brew upgrade
    brew doctor

git
###

Git should be available as soon as Homebrew is installed.

Java install
############

Install Java(TM) JDK 1.8. Neo4j recommends using Java 1.7, but is currently reported to work correctly with Java 1.8. 

* Go to [Java Se Download] (http://www.oracle.com/technetwork/java/javase/downloads/index.html) and follow the instructions
* Check with `java -version`
* `echo $JAVA_HOME` (Should be set to the path of Java 1.8)
* `export JAVA_HOME='/Library/Java/JavaVirtualMachines/jdk1.8.0_92.jdk/Contents/Home'` (Note that 1.8.0_92 may differ on your installation. Check the path)

Neo4j
#####

Go to [Neo4j Releases](http://neo4j.com/download/other-releases/) and download the Community edition for Linux/Mac.
As of today (july 2016), version 2.3.5 have been tested successfully. 

Neo4j 3.0.0 won't work yet (The gremlin plug-in hasn't been tested successfully). 

::

    curl -O http://neo4j.com/artifact.php?name=neo4j-community-2.3.5-unix.tar.gz 
    tar -xf artifact.php\?name=neo4j-community-2.3.5-unix.tar.gz
    mv neo4j-community-2.3.5 neo4j
    cd neo4j
    ./bin/neo4j start
    ./bin/neo4j stop
    cd ..
    
    //This will set the environnement variable
    
    export NEO4J_HOME=\`pwd\`


Register the Gremlin plugin in the `$NEO4J_HOME/conf/neo4j-server.properties` file. To do so, add this line:

::

    org.neo4j.server.thirdparty_jaxrs_classes=com.thinkaurelius.neo4j.plugins=/tp

Gremlin plug-in
+++++++++++++++

This install [gremlin plug-in](https://github.com/thinkaurelius/neo4j-gremlin-plugin) for Neo4j.
  
First, in command line : 

::

    git clone https://github.com/thinkaurelius/neo4j-gremlin-plugin.git gremlin-plugin
    cd gremlin-plugin

Make the following changes in the following files : 

* pom.xml : change the version tag from 2.3.1 to 2.3.5
* tinkerpop2/pom.xml : change the version tag from 2.3.1 to 2.3.5
* tinkerpop3/pom.xml
    + change the version tag from 2.3.1 to 2.3.5
    + change the tinkerpop-version tag from 3.1.0-incubating to 3.2.0-incubating

::

    git clone https://github.com/thinkaurelius/neo4j-gremlin-plugin.git gremlin-plugin
    cd gremlin-plugin


Then, finish the compilation : 
::

    brew install maven // If you haven't installed maven yet
    mvn clean package  -Dtp.version=3


`$NEO4J_HOME`  is the home of the neo4j server. It was installed just before. Use the path or set the variable.

::

    mkdir $NEO4J_HOME/plugins/gremlin-plugin
    unzip target/neo4j-gremlin-plugin-tp3-2.3.5-server-plugin.zip -d $NEO4J_HOME/plugins/gremlin-plugin
    cd $NEO4J_HOME
    bin/neo4j start

You may call check that the server has GremlinPlugin available with 

::

    curl -s -G http://localhost:7474/tp/gremlin/execute

Result should be : 

::

    {
       "success": true
    }

You may now removed the git repository for gremlin-plugin.

Various versions of PHP
#######################

You need one version of PHP (at least) to run exakat. This version needs the [`curl`](http://www.php.net/curl), [`hash`](http://www.php.net/hash), [`tokenizer`](http://www.php.net/tokenizer) and [`sqlite3`](http://www.php.net/sqlite3) extensions. They all are part of the core. 

Extra PHP-CLI versions allow more checks on the code. They only need to have the [`tokenizer`](http://www.php.net/tokenizer) extension available.  

You may reduce the load of those binaries by disabling all other extensions.

::

    brew install php70 php70-curl php70-sqlite3

PHP versions 5.3 to 5.6
#######################

::

    brew tap homebrew/dupes
    brew tap homebrew/versions
    brew tap homebrew/homebrew-php
    brew install php53
    brew install php54
    brew install php55
    brew install php56
    brew install php70

::

    brew install libzip
    zip -help

Exakat 
######

Download the `exakat.phar` archive and place it in the `exakat` folder.

Generic installation guide
--------------------------

This is a simplified installation guide for a non-descript OS. Installation was tested on Osx and Debian, both with specific instructions. 
If you have succeeded in installing exakat on another system, please report any tips.

pre-requisite
#############
* Java 1.8 (needed for Neo4j)
* Neo4j 2.3.*
* Gremlin plugin
* PHP (at least one version)
* exakat.phar

Java install
############
You need a recent version of Java : the recommended version is Java 8. 

[Java Se Download] (http://www.oracle.com/technetwork/java/javase/downloads/index.html) 

Neo4j
#####

Download Neo4j 2.3.* version (currently, 2.3.5). 

[Neo4j](http://neo4j.com/)

Register the Gremlin plugin in the `$NEO4J_HOME/conf/neo4j-server.properties` file. To do so, add this line:

`org.neo4j.server.thirdparty_jaxrs_classes=com.thinkaurelius.neo4j.plugins=/tp`

Gremlin plug-in
+++++++++++++++++++++++++++++

There is a [gremlin plug-in](https://github.com/thinkaurelius/neo4j-gremlin-plugin) for Neo4j. Follow the install instructions there, but also before running the maven compile, update the pom.xml files : 

* pom.xml : change the version tag from 2.3.1 to 2.3.5
* tinkerpop2/pom.xml : change the version tag from 2.3.1 to 2.3.5
* tinkerpop3/pom.xml
    + change the version tag from 2.3.1 to 2.3.5
    + change the tinkerpop-version tag from 3.1.0-incubating to 3.2.0-incubating


Various versions of PHP
+++++++++++++++++++++++++++++
You need one version of PHP (at least) to run exakat. This version needs the [`curl`](http://www.php.net/curl), [`hash`](http://www.php.net/hash), [`tokenizer`](http://www.php.net/tokenizer) and [`sqlite3`](http://www.php.net/sqlite3) extensions. They all are part of the core. 

Extra PHP-CLI versions allow more checks on the code. They only need to have the [`tokenizer`](http://www.php.net/tokenizer) extension available.  

We recommend running PHP 7.0.8 (or latest version) to run Exakat. We also recommend the installation of PHP versions 5.2, 5.3, 5.4, 5.5, 5.6, 7.0, 7.1-alpha and 7.2-dev, as they may be used with exakat.

Exakat 
++++++
Download the `exakat.phar` archive from [exakat.io](http://www.exakat.io/) and place it in the `exakat` folder.

Test
####

From the commandline, run `php exakat.phar doctor`.
This will check if all of the above has be correctly run and will report some diagnostic. 

Optional installation
---------------------

By default, exakat works with Git repository for downloading code. You may also use 

* [composer](https://getcomposer.org/)
* [svn](https://subversion.apache.org/)
* [hg](https://www.mercurial-scm.org/)
* [bazaar](http://bazaar.canonical.com/en/)
* zip
* local copy of a code folder
* local symlink of a code folder (only used for reading, no writing)

The binary above are used with the `init` and `update` commands, to get the source code. They are optional.
