<?php

$config = new Zend\Config\Config($configArray);

$config = new Zend\Config\StandaloneReaderPluginManager();

$config = new Zend\Config\NotZendConfig($configArray);

?>