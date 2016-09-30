# Exakat

The Exakat Engine is an automated code reviewing engine for PHP. 

## Installation

### Installation with the phar

Phar is the recommended installation process.

The Exakat engine is [distributed as a phar archive](http://www.exakat.io/download-exakat/), that contains all the needed PHP code. 
The rest of the installation (Neo4j, gremlin, and dependencies) is detailled in the [documentation](https://github.com/exakat/exakat/blob/master/docs/Installation.rst#generic-installation-guide).

Once the installation is finished, you may check it with 'doctor'.

```bash
$ php exakat.phar doctor
```

### Installation from github

```bash
$ git clone https://github.com/exakat/exakat.git

$ cd exakat 

```

There, you may proceed with the rest of the installation (Neo4j, gremlin, and dependencies) is detailled in the [documentation](https://github.com/exakat/exakat/blob/master/docs/Installation.rst#generic-installation-guide).
You can also immediately use the PHAR after you have downloaded it, of course:

```bash
$ php exakat doctor
```

### Run online

Projects smaller than 1k lines of code may be [tested online](http://www.exakat.io/free-trial/), with the most recent version of exakat. 

## Contribute

See [CONTRIBUTING.md](https://github.com/exakat/exakat/blob/master/CONTRIBUTING.md) for information on how to contribute to the Exakat engine.

## Changelog

See [ChangeLog.txt](https://github.com/exakat/exakat/blob/master/ChangeLog.txt) for information on how to contribute to the Exakat engine.

