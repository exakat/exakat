#Generic installation guide

This is a simplified installation guide for a non-descript OS. Installation was tested on Osx and Debian, with specific instructions. If you have succeeded in installing exakat on another system, free free to submit a PR. 

##pre-requisite
* Java 1.8 (needed for Neo4j)
* Neo4j 2.2.*
* Gremlin plugin
* PHP (at least one version)
* exakat.phar

## OSX install

### Java install
You need a recent version of Java : the recommended version is Java 8. 

[Java Se Download] (http://www.oracle.com/technetwork/java/javase/downloads/index.html) 

### Neo4j

Download Neo4j 2.2.* version (currently, 2.2.4). 
Version 2.1.\* should work, but they are not supported. Version 2.3.\* and up are not working yet (Gremlin plug-in is missing).

[Neo4j](http://neo4j.com/)

Register the Gremlin plugin in the `$NEO4J_HOME/conf/neo4j-server.properties` file. To do so, add this line:

`org.neo4j.server.thirdparty_jaxrs_classes=com.thinkaurelius.neo4j.plugins=/tp`

### Gremlin plug-in

There is a [gremlin plug-in](https://github.com/thinkaurelius/neo4j-gremlin-plugin) for Neo4j. Follow the install instructions there. 

### Various versions of PHP
You need one version of PHP (at least) to run exakat. This version needs the `curl` and `sqlite3` extensions.  

Extra PHP-CLI versions will bring your more checks on the code. 

We recommend running PHP 5.6.9 (or latest version) to run Exakat. We also recommend the installation of PHP versions 5.2, 5.3, 5.4, 5.5, 5.6 and 7.0-dev, as they may be used with exakat.

### Zip
Install the command zip utility.

### Exakat 
Download the `exakat.phar` archive from [exakat.io](http://www.exakat.io/) and place it in the `exakat` folder.

## Test

From the commandline, run `php exakat.phar doctor`.
This will check if all of the above has be correctly run and will report some diagnostic. 

