<?php

// 2.7 only
new Zend\Cache\Service\StoragePluginManagerFactory();

// 2.5 to 2.7
new Zend\Cache\Storage\Adapter\XCache;

// Not zend
new Zend\Cache\Storage\Adapter\NotZend;
?>