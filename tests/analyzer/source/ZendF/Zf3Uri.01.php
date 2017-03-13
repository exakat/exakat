<?php

use MyNamespace\MyClass;
use Zend\Uri\UriFactory;
use Zend\Uri\UriFactoryNotZend;

$uri = Zend\Uri\UriFactory::factory('http:');

UriFactory::registerScheme('ftp', MyClass::class);

$ftpUri = UriFactory::factory(
    'ftp://user@ftp.example.com/path/file'
);
?>