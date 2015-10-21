#Installation guide for Osx

##pre-requisite
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

## OSX install

You need to use the Terminal, which is always installed with OSX.

You need [xcode](https://developer.apple.com/xcode/) installed, with the command line tools. Xcode is available for free in the App store. 

Create a folder for exakat. It will contain four elements : `neo4j` folder, the `exakat.phar` and the projects folder `projects`. Other folders will be created along the way.

### homebrew
Homebew is a package manager for OSX. It will speed up the installation if you install it now. You may do also without it (or using Fink or macport) : we are just confortable with brew.

* `ruby -e "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install)"`

If brew is installed, it is a good moment to check the updates and then the doctor. 
* `brew update; brew upgrade`
* `brew doctor`

### git
Git should be available as soon as you have installed Homebrew.

### Java install
Install Java(TM) JDK 1.8. Neo4j recommends using Java 1.7, but is currently reported to work correctly with Java 1.8. 

* Go to [Java Se Download] (http://www.oracle.com/technetwork/java/javase/downloads/index.html) and follow the instructions
* Check with `java -version`
* `echo $JAVA_HOME` (Should be set to the path of Java 1.8)
* `export JAVA_HOME='/Library/Java/JavaVirtualMachines/jdk1.8.0_60.jdk/Contents/Home'` (Note that 1.8.0_60 may differ on your installation. Check the path)

### Neo4j

* Go to [Neo4j Releases](http://neo4j.com/download/other-releases/) and download the Community edition for Linux/Mac.
As of today (august 2015), version 2.2.4 have been tested successfully. Versions 2.1.\* might work, though they are not supported. Neo4j 2.3.\* won't work yet (The gremlin plug-in doesn't work yet). 

`curl -O http://neo4j.com/artifact.php?name=neo4j-community-2.2.6-unix.tar.gz 
tar -xf artifact.php\?name=neo4j-community-2.2.6-unix.tar.gz
mv neo4j-community-2.2.6 neo4j
cd neo4j
./bin/neo4j start
./bin/neo4j stop
cd ..

//This will set the environnement variable

export NEO4J_HOME=\`pwd\`
`

Register the Gremlin plugin in the `$NEO4J_HOME/conf/neo4j-server.properties` file. To do so, add this line:

`org.neo4j.server.thirdparty_jaxrs_classes=com.thinkaurelius.neo4j.plugins=/tp`

### Gremlin plug-in

This install [gremlin plug-in](https://github.com/thinkaurelius/neo4j-gremlin-plugin) for Neo4j.
  
First, in command line : 

* `git clone https://github.com/thinkaurelius/neo4j-gremlin-plugin.git gremlin-plugin`
* `cd gremlin-plugin`


Now, check the pom.xml file, and make sure that Maven finds the Gremlin-2.7-SNAPSHOT. Until Gremlin 2.7 hits the repositories, you can use this (add it in the pom.xml, below contributors section.) : 

```code
    <repositories>
       <repository>
         <id>snapshots-repo</id>
         <url>https://oss.sonatype.org/content/repositories/snapshots</url>
         <releases><enabled>false</enabled></releases>
         <snapshots><enabled>true</enabled></snapshots>
       </repository>
     </repositories>
```

Then, finish the compilation : 
* `brew install maven` // If you haven't installed maven yet
* `mvn clean package`

`$NEO4J_HOME`  is the home of the neo4j server. It was installed just before. Use the path or set the variable.

* `unzip target/neo4j-gremlin-plugin-tp2-2.2.3-SNAPSHOT-server-plugin.zip -d $NEO4J_HOME/plugins/gremlin-plugin`
* `cd $NEO4J_HOME`
* `bin/neo4j start`

You may call check that the server has GremlinPlugin available with 
`$ curl -s -G http://localhost:7474/tp/gremlin/execute`

Result should be : 
`{
    "success": true
}`

You may now removed the git repository for gremlin-plugin.

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

