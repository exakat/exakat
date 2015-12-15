.. _Installation:

Installation
============

Summary
-------

* `Installation guide for Debian`_
* `Installation guide for Osx`_
* `Generic installation guide`_

Installation guide for Debian
-----------------------------

This is a specific installation guide for a debian server.

pre-requisite
#############

* Java 1.8
* Neo4j 2.2.*
* Gremlin plugin
* PHP (at least one version)
* exakat.phar

Debian install
##############

apt-get
+++++++

This list of apt-get will install several needed libs for the installation. 

`apt-get install php5-cli zip wkhtmltopdf maven vim python-software-properties php5-mysqlnd sqlite gcc make libxml2-dev autoconf re2c bison screen php5-curl php5-sqlite libssl-dev libcurl4-openssl-dev pkg-config libbz2-dev libjpeg-dev libpng-dev libXpm-dev libfreetype6-dev libt1-dev libgmp3-dev libldap2-dev libmcrypt-dev libmhash-dev freetds-dev libz-dev ncurses-dev libpcre3-dev unixODBC-dev libsqlite-dev libaspell-dev libreadline6-dev librecode-dev 
apt-get update
apt-get upgrade
apt-get clean`


### Java install
You need a Java 8. Java 7 might work.

`$ su root
# echo "deb http://ppa.launchpad.net/webupd8team/java/ubuntu trusty main" > /etc/apt/sources.list.d/webupd8team-java.list
# echo "deb-src http://ppa.launchpad.net/webupd8team/java/ubuntu trusty main" >> /etc/apt/sources.list.d/webupd8team-java.list
# apt-key adv --keyserver keyserver.ubuntu.com --recv-keys EEA14886
# apt-get update
# apt-get install oracle-java8-installer
# java -version
# exit`

Neo4j
+++++++++++++++++++++++++++++
Download Neo4j 2.2.* version (currently, 2.2.7).

[Neo4j](http://neo4j.com/)
::

   wget http://dist.neo4j.org/neo4j-community-2.2.4-unix.tar.gz
   tar -xvf neo4j-community-2.2.4-unix.tar.gz 
   mv neo4j-community-2.2.4 neo4j

Gremlin plug-in
+++++++++++++++++++++++++++++
There is a [gremlin plug-in](https://github.com/thinkaurelius/neo4j-gremlin-plugin) for Neo4j. Follow the install instructions there. 

Check the pom.xml file, and make sure that Maven finds the Gremlin-2.7-SNAPSHOT. Until Gremlin 2.7 hits the repositories, you can use this (add it in the pom.xml, below contributors.) : 
:::

    <repositories>
       <repository>
         <id>snapshots-repo</id>
         <url>https://oss.sonatype.org/content/repositories/snapshots</url>
         <releases><enabled>false</enabled></releases>
         <snapshots><enabled>true</enabled></snapshots>
       </repository>
     </repositories>

  
  Then, in command line : 

::
   git clone https://github.com/neo4j-contrib/gremlin-plugin.git gremlin
   cd gremlin
   mvn clean package
   unzip target/neo4j-gremlin-plugin-2.1-SNAPSHOT-server-plugin.zip -d ../neo4j/plugins/gremlin-plugin
   cd ../neo4j
   bin/neo4j restart


Various versions of PHP
+++++++++++++++++++++++++++++

You need one version of PHP (at least) to run exakat. This version needs the `curl` and `sqlite3` extensions.  

Extra PHP-CLI versions will bring your more checks on the code. 

We recommend running PHP 5.6.9 (or latest version) to run Exakat. We also recommend the installation of PHP versions 5.2, 5.3, 5.4, 5.5, 5.6 and 7.0-dev, as they may be used with exakat.

To install easily various versions of PHP, use the dotdeb repository. Follow the instruction [here](https://www.dotdeb.org/instructions/).

Zip
###
Install the command zip utility.

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
* Java 1.8 (needed for Neo4j)
* Neo4j 2.2.*
* Gremlin plugin
* zip
* PHP version (at least one)
* exakat
* composer (Optional)

OSX install
############

You need to use the Terminal, which is always installed with OSX.

You need [xcode](https://developer.apple.com/xcode/) installed, with the command line tools. Xcode is available for free in the App store. 

Create a folder for exakat. It will contain four elements : `neo4j` folder, the `exakat.phar` and the projects folder `projects`. Other folders will be created along the way.

homebrew
########

Homebew is a package manager for OSX. It will speed up the installation if you install it now. You may do also without it (or using Fink or macport) : we are just confortable with brew.

* `ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"`

If brew is installed, it is a good moment to check the updates and then the doctor. 
* `brew update; brew upgrade`
* `brew doctor`

git
###

Git should be available as soon as you have installed Homebrew.

Java install
############

Install Java(TM) JDK 1.8. Neo4j recommends using Java 1.7, but is currently reported to work correctly with Java 1.8. 

* Go to [Java Se Download] (http://www.oracle.com/technetwork/java/javase/downloads/index.html) and follow the instructions
* Check with `java -version`
* `echo $JAVA_HOME` (Should be set to the path of Java 1.8)
* `export JAVA_HOME='/Library/Java/JavaVirtualMachines/jdk1.8.0_60.jdk/Contents/Home'` (Note that 1.8.0_60 may differ on your installation. Check the path)

Neo4j
#####

Go to [Neo4j Releases](http://neo4j.com/download/other-releases/) and download the Community edition for Linux/Mac.
As of today (december 2015), version 2.2.7 have been tested successfully. 
Versions 2.1.\* might work, though they are not supported. 
Neo4j 2.3.\* or 3.0.0 won't work yet (The gremlin plug-in hasn't been tested successfully). 

::

   curl -O http://neo4j.com/artifact.php?name=neo4j-community-2.2.6-unix.tar.gz 
   tar -xf artifact.php\?name=neo4j-community-2.2.6-unix.tar.gz
   mv neo4j-community-2.2.6 neo4j
   cd neo4j
   ./bin/neo4j start
   ./bin/neo4j stop
   cd ..
    
   //This will set the environnement variable
    
   export NEO4J_HOME=\`pwd\`


Register the Gremlin plugin in the `$NEO4J_HOME/conf/neo4j-server.properties` file. To do so, add this line:

`org.neo4j.server.thirdparty_jaxrs_classes=com.thinkaurelius.neo4j.plugins=/tp`

Gremlin plug-in
+++++++++++++++

This install [gremlin plug-in](https://github.com/thinkaurelius/neo4j-gremlin-plugin) for Neo4j.
  
First, in command line : 

* `git clone https://github.com/thinkaurelius/neo4j-gremlin-plugin.git gremlin-plugin`
* `cd gremlin-plugin`


Now, check the pom.xml file, and make sure that Maven finds the Gremlin-2.7-SNAPSHOT. Until Gremlin 2.7 hits the repositories, you can use this (add it in the pom.xml, below contributors section.) : 

:: 

    <repositories>
       <repository>
         <id>snapshots-repo</id>
         <url>https://oss.sonatype.org/content/repositories/snapshots</url>
         <releases><enabled>false</enabled></releases>
         <snapshots><enabled>true</enabled></snapshots>
       </repository>
     </repositories>


Then, finish the compilation : 
* `brew install maven` // If you haven't installed maven yet
* `mvn clean package`

`$NEO4J_HOME`  is the home of the neo4j server. It was installed just before. Use the path or set the variable.

* `unzip target/neo4j-gremlin-plugin-tp2-2.2.3-SNAPSHOT-server-plugin.zip -d $NEO4J_HOME/plugins/gremlin-plugin`
* `cd $NEO4J_HOME`
* `bin/neo4j start`

You may call check that the server has GremlinPlugin available with 
`$ curl -s -G http://localhost:7474/tp/gremlin/execute`

Result should be : ::

   {
       "success": true
   }

You may now removed the git repository for gremlin-plugin.

Various versions of PHP
#######################

You need one version of PHP (at least) to run exakat. This version needs the `curl` and `sqlite3` extensions.  

Extra PHP-CLI versions will bring your more checks on the code. 

We recommend running PHP 5.6.9 (or latest version).

* `brew install php56 php56-curl php56-sqlite3`

PHP versions 5.3 to 5.6
#######################

* `brew tap homebrew/dupes`
* `brew tap homebrew/versions`
* `brew tap homebrew/homebrew-php`
* `brew install php53`
* `brew install php54`
* `brew install php55`
* `brew install php56`


Zip
###
Install the zip utility

* `brew install libzip`
* `zip -help`

Exakat 
######

Download the `exakat.phar` archive and place it in the `exakat` folder.

Optional installation
#####################

By default, exakat works with Git repository for downloading code. You may also use 
* `composer`
* `svn`
* `hg`
if you have installed those binary.

Test
####

From the commandline, run `php exakat.phar doctor`.
This will check if all of the above has be correctly run and will report some diagnostic. 


Generic installation guide
--------------------------

This is a simplified installation guide for a non-descript OS. Installation was tested on Osx and Debian, both with specific instructions. 
If you have succeeded in installing exakat on another system, please report any tips.

pre-requisite
#############
* Java 1.8 (needed for Neo4j)
* Neo4j 2.2.*
* Gremlin plugin
* PHP (at least one version)
* exakat.phar

Java install
############
You need a recent version of Java : the recommended version is Java 8. 

[Java Se Download] (http://www.oracle.com/technetwork/java/javase/downloads/index.html) 

Neo4j
#####

Download Neo4j 2.2.* version (currently, 2.2.4). 
Version 2.1.\* should work, but they are not supported. Version 2.3.\* and up are not working yet (Gremlin plug-in is missing).

[Neo4j](http://neo4j.com/)

Register the Gremlin plugin in the `$NEO4J_HOME/conf/neo4j-server.properties` file. To do so, add this line:

`org.neo4j.server.thirdparty_jaxrs_classes=com.thinkaurelius.neo4j.plugins=/tp`

Gremlin plug-in
+++++++++++++++++++++++++++++

There is a [gremlin plug-in](https://github.com/thinkaurelius/neo4j-gremlin-plugin) for Neo4j. Follow the install instructions there. 

Various versions of PHP
+++++++++++++++++++++++++++++
You need one version of PHP (at least) to run exakat. This version needs the `curl` and `sqlite3` extensions.  

Extra PHP-CLI versions will bring your more checks on the code. 

We recommend running PHP 5.6.9 (or latest version) to run Exakat. We also recommend the installation of PHP versions 5.2, 5.3, 5.4, 5.5, 5.6 and 7.0-dev, as they may be used with exakat.

Zip
+++
Install the command zip utility.

Exakat 
++++++
Download the `exakat.phar` archive from [exakat.io](http://www.exakat.io/) and place it in the `exakat` folder.

Test
####

From the commandline, run `php exakat.phar doctor`.
This will check if all of the above has be correctly run and will report some diagnostic. 

