<?php

use MyNamespace\MyClass;

use Zend\Uri\UriFactory;
use Zend\Uri\UriFactoryNotZend;

$uri = Zend\Uri\Mailto::factory('http:');

UriFactory::registerScheme('ftp', MyClass::class);

// HTTP is a zend-uri class, but here it is not in the right namespace
$ftpUri = Http::factory(
    'ftp://user@ftp.example.com/path/file'
);
?>