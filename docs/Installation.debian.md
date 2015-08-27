#Installation guide for Debian

This is a specific installation guide for a debian server.

##pre-requisite
* Java 1.8
* Neo4j 2.2.*
* Gremlin plugin
* PHP (at least one version)
* exakat.phar

## Debian install

### apt-get

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

### Neo4j

Download Neo4j 2.2.* version (currently, 2.2.4).

[Neo4j](http://neo4j.com/)

`wget http://dist.neo4j.org/neo4j-community-2.2.4-unix.tar.gz
tar -xvf neo4j-community-2.2.4-unix.tar.gz 
mv neo4j-community-2.2.4 neo4j
`

### Gremlin plug-in

There is a [gremlin plug-in](https://github.com/thinkaurelius/neo4j-gremlin-plugin) for Neo4j. Follow the install instructions there. 

Check the pom.xml file, and make sure that Maven finds the Gremlin-2.7-SNAPSHOT. Until Gremlin 2.7 hits the repositories, you can use this (add it in the pom.xml, below contributors.) : 
`
    <repositories>
       <repository>
         <id>snapshots-repo</id>
         <url>https://oss.sonatype.org/content/repositories/snapshots</url>
         <releases><enabled>false</enabled></releases>
         <snapshots><enabled>true</enabled></snapshots>
       </repository>
     </repositories>
  `
  
  Then, in command line : 

`git clone https://github.com/neo4j-contrib/gremlin-plugin.git gremlin
cd gremlin
mvn clean package
unzip target/neo4j-gremlin-plugin-2.1-SNAPSHOT-server-plugin.zip -d ../neo4j/plugins/gremlin-plugin
cd ../neo4j
bin/neo4j restart`

### Various versions of PHP
You need one version of PHP (at least) to run exakat. This version needs the `curl` and `sqlite3` extensions.  

Extra PHP-CLI versions will bring your more checks on the code. 

We recommend running PHP 5.6.9 (or latest version) to run Exakat. We also recommend the installation of PHP versions 5.2, 5.3, 5.4, 5.5, 5.6 and 7.0-dev, as they may be used with exakat.

To install easily various versions of PHP, use the dotdeb repository. Follow the instruction [here](https://www.dotdeb.org/instructions/).

### Zip
Install the command zip utility.

### Exakat 
Download the `exakat.phar` archive from [exakat.io](http://www.exakat.io/) and place it in the `exakat` folder.

## Test

From the commandline, run `php exakat.phar doctor`.
This will check if all of the above has be correctly run and will report some diagnostic. 

