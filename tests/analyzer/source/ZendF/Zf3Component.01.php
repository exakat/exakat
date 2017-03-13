<?php

// An array of configuration data is given
$configArray = [
    'webhost'  => 'www.example.com',
    'database' => [
        'adapter' => 'pdo_mysql',
        'params'  => [
            'host'     => 'db.example.com',
            'username' => 'dbuser',
            'password' => 'secret',
            'dbname'   => 'mydatabase',
        ],
    ],
];

// Create the object-oriented wrapper using the configuration data
$config = new Zend\Config\Config($configArray);

// Print a configuration datum (results in 'www.example.com')
echo $config->webhost;

$config = new Zend\Config\NotAZendClass($configArray);

?>