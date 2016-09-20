<?php

$expected     = array('fopen(\'jackalope://\' . $token . \'@\' . $this->session->getRegistryKey( ) . \':\' . $i . $this->path, \'rwb+\')', 
                      'fopen(\'file://\' . $token . \'@\' . $this->session->getRegistryKey( ) . \':\' . $i . $this->path, \'rwb+\')', 
                      'fopen(\'jackalope://asd.txt\', \'rwb+\')', 
                      'fopen(\'file://tmp/temp.file.txt\', \'rwb+\')', 
                      'fopen(\'ogg://some/file.ogg\', \'rwb+\')', 
                      'fopen(\'phar://some/archive.phar\', \'rwb+\')');

$expected_not = array();

?>