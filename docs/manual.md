<a href="http://thinkaurelius.com"><img src="http://www.exakat.com/wp-content/uploads/2014/05/logo-exakat.png" alt="Exakat Logo" width="275" /></a>

Exakat Static Analyzer
======================

Exakat is a static analyzer for PHP. It applies the [clear PHP rules](http://github.com/dseguy/clearPHP/) to PHP code and provides a report on violations. 

# Version
This manual is for Exakt version 0.2.0 (build 

# Install Exakat
* [Read the Install for Osx manual](./Installation.osx.md)
* [Read the Install for Osx manual](./Installation.debian.md)
* [Read the Generic install](./Installation.generic.md)

# Check Install

Once the prerequisite are installed, it is advised to run to check if all is found : 

`php exakat.phar doctor`

After this run, You may have to edit 'config/config.ini' to add some extra configuration. Most of the time, the default values will be OK.

# Usage

## A first test

A simple run for the report : 

```
$ php exakat.phar init -p sculpin -R https://github.com/sculpin/sculpin
```

This will init the project in the 'projects' folder, and clone the code with the provided repository. The name after `-p` will be reused later for all subsequent operations.

Then, you can run : 
```
$ php exakat.phar project -p sculpin 
```

This will run the whole analysis. 

Once it is finished, you may find the result in `projects/sculpin/report`. Simply open the 'index.html' file in a browser (Note that Safari or Chrome have a security feature that will prevent them from loading directly the report. To avoid this, put the report on a webserver and open it again via http). 


# Licenses

* Exakat - [GNU Affero General Public License](http://www.exakat.io/exakat-licence)
* Neo4j Gremlin Plugin - [Apache2](https://github.com/neo4j-contrib/gremlin-plugin/blob/master/LICENSE.txt)
* Neo4j - [Dual free software/commercial license](http://www.neo4j.org/learn/licensing)

- - -

Exakat is maintained by [Exakat](http://exakat.io/).

<a href="http://thinkaurelius.com"><img src="http://www.exakat.com/wp-content/uploads/2014/05/logo-exakat.png" alt="Exakat Logo" width="275" /></a>