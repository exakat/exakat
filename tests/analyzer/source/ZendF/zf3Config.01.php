<?php

// Create the config object
$config = new Zend\Config\Config([], true);
$config->production = [];

$config->production->webhost = 'www.example.com';
$config->production->database = [];
$config->production->database->params = [];
$config->production->database->params->host = 'localhost';
$config->production->database->params->username = 'production';
$config->production->database->params->password = 'secret';
$config->production->database->params->dbname = 'dbproduction';

$writer = new Zend\Config\Writer\Ini();
echo $writer->toString($config);

$config = new Zend\Config\NOT_ZEND();
$generator = new \Zend\Code\ParameterGenerator();

?>
