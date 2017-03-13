<?php

$config = new Zend\Config\Config($configArray);

// New in 3.0
$config = new Zend\Config\StandaloneReaderPluginManager();

$config = new Zend\Config\NotZendConfig($configArray);

?>