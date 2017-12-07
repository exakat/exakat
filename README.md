# Exakat

The Exakat Engine is an automated code reviewing engine for PHP. 

## Installation

### Installation with the phar

Phar is the recommended installation process.

The Exakat engine is [distributed as a phar archive](http://www.exakat.io/download-exakat/), that contains all the needed PHP code. 
The rest of the installation (NGremlin-server) is detailled in the [documentation](https://exakat.readthedocs.io/en/latest/).

The quick installation guide is the following (command line, MacOS. See docs for more options): 

```bash
mkdir exakat
cd exakat
curl -o exakat.phar http://dist.exakat.io/index.php?file=latest
curl -o apache-tinkerpop-gremlin-server-3.2.6-bin.zip http://ftp.tudelft.nl/apache/tinkerpop/3.2.6/apache-tinkerpop-gremlin-server-3.2.6-bin.zip
unzip apache-tinkerpop-gremlin-server-3.2.6-bin.zip
mv apache-tinkerpop-gremlin-server-3.2.6 tinkergraph
rm -rf apache-tinkerpop-gremlin-server-3.2.6-bin.zip

# Optional : install neo4j engine.
cd tinkergraph
bin/gremlin-server.sh -i org.apache.tinkerpop neo4j-gremlin 3.2.6
cd ..

php exakat.phar doctor
```

### Run online

Projects smaller than 1k lines of code may be [tested online](http://www.exakat.io/free-trial/), with the most recent version of exakat. 

## Contribute

See [CONTRIBUTING.md](https://github.com/exakat/exakat/blob/master/CONTRIBUTING.md) for information on how to contribute to the Exakat engine.

## Changelog

See [Changelog.txt](https://github.com/exakat/exakat/blob/master/ChangeLog.txt) for information on how to contribute to the Exakat engine.

