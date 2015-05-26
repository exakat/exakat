<a href="http://thinkaurelius.com"><img src="http://www.exakat.com/wp-content/uploads/2014/05/logo-exakat.png" alt="Exakat Logo" width="275" /></a>

Exakat Static Analyzer
======================

Exakat is a static analyzer for PHP. It applies the [clear PHP rules](http://github.com/dseguy/clearPHP/) to PHP code and provides a report on violations. 

# Install Exakat
## Install For Osx
[Read the Install for Osx manual](./Installation.osx.md)

## Install For Debian
[Read the Install for Osx manual](./Installation.debian.md)

## Other Installs
[Read the Generic install](./Installation.generic.md)


# Usage

## A first tests using curl

A simple run for the report : 

```
$ php exakat init -p sculpin -R https://github.com/sculpin/sculpin
```
This will init the project in the 'projects' folder, and clone the code with the provided repository. The name after `-p` will be reused later for all subsequent operations.

Then, you can run : 
```
$ php exakat project -p sculpin 
```

This will run the whole analyzis. 

Once it is finished, you may find the result in `projects/sculpin/report`. Simply open the 'index.html' file in Firefox (Note that Safari or Chrome has a security feature that will prevent them from using directly the report. To avoid this, put the report on a webserver and open it again via http). 

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