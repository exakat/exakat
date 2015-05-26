#Installation guide for Osx

##pre-requisite
* Xcode
* homebrew
* git
* Java 1.7 (needed for Neo4j)
* Neo4j 2.1.*
* Gremlin plugin
* zip
* PHP version (at least one)
* exakat
* composer (Optional)

## OSX install

You need to use the Terminal, which is always installed with OSX.

You need [xcode](https://developer.apple.com/xcode/) installed, with the command line tools. Xcode is available for free in the App store. 

Create a folder for exakat. It will contain four elements : `neo4j` folder, the `exakat.phar` and the projects folder `projects`. 

### homebrew
Homebew is a package manager for OSX. It will speed up the installation if you install it now. You may do also without it (or using Fink or macport) : we just didn't test it yet.

* `ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"`

If brew is installed, it is a good moment to check the updates and then the doctor. 
* `brew update; brew upgrade`
* `brew doctor`

### git
Git should be available as soon as you have installed Homebrew.

### Java install
Install Java(TM) JDK 1.7. Neo4j recommends using Java 1.7, but is currently reported to work correctly with Java 1.8. 

Warning : the gremlin plug-in for neo4j will only be compiled with Java 1.7, so you'll need to install at least this one. 

* Go to [Java Se Download] (http://www.oracle.com/technetwork/java/javase/downloads/index.html) and follow the instructions
* Check with `java -version`
* `export JAVA_HOME='/Library/Java/JavaVirtualMachines/jdk1.7.0_75.jdk/Contents/Home'`

### Neo4j

* Go to [Neo4j Releases](http://neo4j.com/download/other-releases/) and download the Community edition for Linux/Mac.
As of today (may 2015), version 2.1.8 have been tested successfully. Version 2.2.0 won't work, as no gremlin plug-in is available. 

`curl -O http://neo4j.com/artifact.php?name=neo4j-community-2.1.8-unix.tar.gz 
tar -xf artifact.php\?name=neo4j-community-2.1.7-unix.tar.gz
tar -xf artifact.php\?name=neo4j-community-2.1.7-unix.tar.gz
mv neo4j-community-2.1.7 neo4j
cd neo4j
./bin/neo4j start
./bin/neo4j stop
cd ..`


### Gremlin plug-in

This will install [gremlin plug-in](https://github.com/neo4j-contrib/gremlin-plugin) for Neo4j

* `git clone https://github.com/neo4j-contrib/gremlin-plugin.git`
* `cd gremlin-plugin`
* `brew install maven`
* `mvn clean package`

`$NEO4J_HOME`  is the home of the neo4j server. It was installed just before. Use the path or set the variable.

* `unzip target/neo4j-gremlin-plugin-2.1-SNAPSHOT-server-plugin.zip -d $NEO4J_HOME/plugins/gremlin-plugin`
* `cd $NEO4J_HOME`
* `bin/neo4j start`

You may call `curl localhost:7474/db/data/` and check that the server has GremlinPlugin available with `curl localhost:7474/db/data/`

You may now removed the git repository for gremlin-plugin, and eventually java 1.7.

### batch-import

This installs the batch-import utility, to quickly import nodes into neo4j. Install this in the same folder as neo4j. 

* `git clone https://github.com/jexp/batch-import.git`
* `cd batch-import`
* `git checkout 2.1`
* `mvn clean compile assembly:single`

### Various versions of PHP
You need one version of PHP (at least) to run exakat. This version needs the `curl` and `sqlite3` extensions.  

Extra PHP-CLI versions will bring your more checks on the code. 

We recommend running PHP 5.6.9 (or latest version).

* `brew install php56 php56-curl php56-sqlite3`

####PHP versions 5.3 to 5.6

* `brew tap homebrew/dupes`
* `brew tap homebrew/versions`
* `brew tap homebrew/homebrew-php`
* `brew install php53`
* `brew install php54`
* `brew install php55`
* `brew install php56`


### Zip
Install the zip utility

* `brew install libzip`
* `zip -help`

### Exakat 
Download the `exakat.phar` archive and place it in the `exakat` folder.

## Optional installation

By default, exakat works with Git repository for downloading code. You may also use 
* `composer`
* `svn`
* `hg`
if you have installed those binary.

## Test

From the commandline, run `php exakat.phar doctor`.
This will check if all of the above has be correctly run and will report some diagnostic. 

