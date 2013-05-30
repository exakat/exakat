Neo4j 1.9.M04
=======================================

Welcome to Neo4j release 1.9.M04, a high-performance graph database.
This is the community distribution of Neo4j, including everything you need to
start building applications that can model, persist and explore graph-like data.

In the box
----------

Neo4j runs as a server application, exposing a Web-based management
interface and RESTful endpoints for data access.

Here in the installation directory, you'll find:

* bin - scripts and other executables
* conf - server configuration
* data - database, log, and other variable files
* doc - more light reading
* lib - core libraries
* plugins - user extensions
* system - super-secret server stuff

Make it go
----------

To get started with Neo4j, let's start the server and take a
look at the web interface...

1. open a console and navigate to the install directory
2. start the server
   * Windows: use `bin\Neo4j.bat`
   * Linux/Mac: use `bin/neo4j start`
3. in a browser, open [webadmin](http://localhost:7474/webadmin/)
4. from any REST client or browser, open (http://localhost:7474/db/data) 
   in order to get a REST starting point, e.g.
   `curl -v http://localhost:7474/db/data`
5. shutdown the server
   * Windows: type Ctrl-C to terminate the batch script
   * Linux/Mac: use `bin/neo4j stop`

Learn more
----------

There is a manual available in the `doc` directory, which includes tutorials
and reference material.

Out on the internets, you'll find:

* [Neo4j Home](http://neo4j.org)
* [Getting Started](http://docs.neo4j.org/chunked/1.9.M04/introduction.html)
* [The Neo4j Manual (online)](http://docs.neo4j.org/chunked/1.9.M04/)
* [Neo4j Components](http://components.neo4j.org)

For more links, a handy [guide post](doc/guide-post.html) in the `doc` 
directory will point you in the right direction.

License(s)
----------
Various licenses apply. Please refer to the LICENSE and NOTICE files for more
detailed information.

