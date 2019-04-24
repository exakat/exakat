#!/bin/bash

# We need to install dependencies only for Docker
[[ ! -e /.dockerenv ]] && exit 0

set -xe

# Install git (the php image doesn't have it) which is required by composer
apt-get update -yqq
apt-get install default-jre apt-utils git lsof unzip -yqq

# Install phpunit, the tool that we will use for testing
curl --location --output /usr/local/bin/phpunit https://phar.phpunit.de/phpunit.phar
chmod +x /usr/local/bin/phpunit

#    apt-get install -y --no-install-recommends git subversion mercurial bzr lsof unzip zip && \

#    \
#    echo "===> Composer"  && \
#    curl -sS https://getcomposer.org/installer -o composer-setup.php && \
#    HASH="$(curl --silent -o - https://composer.github.io/installer.sig)" && \
#    php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" && \
#    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
#    \

curl --silent -o apache-tinkerpop-gremlin-server-3.3.6-bin.zip http://dist.exakat.io/apache-tinkerpop-gremlin-server-3.3.6-bin.zip
unzip apache-tinkerpop-gremlin-server-3.3.6-bin.zip
mv apache-tinkerpop-gremlin-server-3.3.6 tinkergraph
rm -rf apache-tinkerpop-gremlin-server-3.3.6-bin.zip
cd tinkergraph
#bin/gremlin-server.sh install org.apache.tinkerpop neo4j-gremlin 3.3.6
cd .. 

php exakat doctor

# Install mysql driver
# Here you can install any other extension that you need
#docker-php-ext-install sqlite3