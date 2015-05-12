<a href="http://thinkaurelius.com"><img src="http://www.exakat.com/wp-content/uploads/2014/05/logo-exakat.png" alt="Exakat Logo" width="275" /></a>

Exakat Static Analyzer
======================

Exakat is a static analyzer for PHP. It applies the [clear PHP rules](http://github.com/dseguy/clearPHP/) to PHP code and provides a report on violations. 

# Install Exakat
## Install For Osx
## Install For Debian
## Other Installs


# Usage


```sh
mvn clean package
# for TP3 use: mvn clean package -Dtp.version=3
unzip target/neo4j-gremlin-plugin-*-server-plugin.zip -d $NEO4J_HOME/plugins/gremlin-plugin
$NEO4J_HOME/bin/neo4j restart
```

## A first tests using curl

If everything went well, you should already see an empty success message when you access the Gremln REST endpoint.

```
$ curl -s -G http://localhost:7474/tp/gremlin/execute
{
    "success": true
}
```

## Parameters

| parameter  | format                          | description                                                |
| ---------- | ------------------------------- | ---------------------------------------------------------- |
| **script** | String                          | the Gremlin script to be evaluated                         |
| **params** | JSON object                     | a map of parameters to bind to the script engine           |
| **load**   | comma-separated list of Strings | a list of Gremlin scripts to execute prior to the 'script' |




# Licenses

* Neo4j Gremlin Plugin - Apache2
* TinkerPop2 - [BSD](https://github.com/tinkerpop/gremlin/blob/master/LICENSE.txt)
* Neo4j - [Dual free software/commercial license](http://www.neo4j.org/learn/licensing)

- - -

Exakat is maintained by [Exakat](http://exakat.io/).

<a href="http://thinkaurelius.com"><img src="http://www.exakat.com/wp-content/uploads/2014/05/logo-exakat.png" alt="Exakat Logo" width="275" /></a>