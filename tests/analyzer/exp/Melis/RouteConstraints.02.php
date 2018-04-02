<?php

$expected     = array('array(\'route\' => \'api[/:api_key][/:module]/service[/:service_alias[/:service_METHOD]]\', \'constraint\' => array(\'api_key\' => \'[a-zA-Z0-9_\-\=\$\@]*\', \'module\' => \'[A-Z][a-zA-Z0-9_-]*\', \'service_alias\' => \'[A-Z][a-zA-Z0-9_-]*\', \'service_METHOD\' => \'[a-zA-Z][a-zA-Z0-9_-]*\',  ))', 
                     );

$expected_not = array('array(\'route\' => \'api[/:api_key][/:module]/service[/:service_alias[/:service123]]\', \'constraint\' => array(\'api_key\' => \'[a-zA-Z0-9_\-\=\$\@]*\', \'module\' => \'[A-Z][a-zA-Z0-9_-]*\', \'service_alias\' => \'[A-Z][a-zA-Z0-9_-]*\', \'service_method\' => \'[a-zA-Z][a-zA-Z0-9_-]*\',  ))',
                     );

?>