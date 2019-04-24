#!/bin/bash

# We need to install dependencies only for Docker
[[ ! -e /.dockerenv ]] && exit 0

set -xe

# Make place ready for Java installation
mkdir -p /usr/share/man/man1mkdir -p /usr/share/man/man1

echo "memory_limit=-1" >> /usr/local/etc/php/php.ini
export TERM="xterm"
export JAVA_OPTIONS="-Xms32m -Xmx2512m"

# Install git (the php image doesn't have it) which is required by composer
apt-get update -yqq
apt-get install default-jre apt-utils git lsof unzip -yqq

# Install phpunit, the tool that we will use for testing
curl --location --output /usr/local/bin/phpunit https://phar.phpunit.de/phpunit.phar
chmod +x /usr/local/bin/phpunit

#    apt-get install -y --no-install-recommends git subversion mercurial bzr lsof unzip zip && \

curl -sS https://getcomposer.org/installer -o composer-setup.php
HASH="$(curl --silent -o - https://composer.github.io/installer.sig)"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer

composer  install --no-plugins --no-scripts 

curl --silent -o apache-tinkerpop-gremlin-server-3.3.6-bin.zip http://dist.exakat.io/apache-tinkerpop-gremlin-server-3.3.6-bin.zip
unzip -qq apache-tinkerpop-gremlin-server-3.3.6-bin.zip
mv apache-tinkerpop-gremlin-server-3.3.6 tinkergraph
rm -rf apache-tinkerpop-gremlin-server-3.3.6-bin.zip
cd tinkergraph
bin/gremlin-server.sh install org.apache.tinkerpop neo4j-gremlin 3.3.6
cd .. 

echo "graphdb = 'gsneo4j';

; where is neo4j inside a gremlin server host
gsneo4j_host     = '127.0.0.1';
gsneo4j_port     = '8182';
gsneo4j_folder   = 'tinkergraph';

phpversion = 7.3

php73 = /usr/local/bin/php

" >> config/exakat.ini


pwd
ls -hla 
ls -hla tinkergraph
ls -hla tinkergraph/ext
ls -hla tinkergraph/ext/neo4j-gremlin
more config/exakat.ini

php exakat doctor

exit 1;


php exakat doctor

php exakat cleandb -start

php exakat doctor

# Install mysql driver
# Here you can install any other extension that you need
#docker-php-ext-install sqlite3
