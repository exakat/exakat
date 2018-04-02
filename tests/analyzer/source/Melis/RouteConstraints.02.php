<?php
array('route' => 'api[/:api_key][/:module]/service[/:service_alias[/:service123]]', 
     'constraints' => array('api_key' => '[a-zA-Z0-9_\-\=\$\@]*', 
                           'module' => '[A-Z][a-zA-Z0-9_-]*', 
                           'service_alias' => '[A-Z][a-zA-Z0-9_-]*', 
                           'service_method' => '[a-zA-Z][a-zA-Z0-9_-]*',  
                        )
                    );

array('route' => 'api[/:api_key][/:module]/service[/:service_alias[/:service_METHOD]]', 
     'constraints' => array('api_key' => '[a-zA-Z0-9_\-\=\$\@]*', 
                           'module' => '[A-Z][a-zA-Z0-9_-]*', 
                           'service_alias' => '[A-Z][a-zA-Z0-9_-]*', 
                           'service_METHOD' => '[a-zA-Z][a-zA-Z0-9_-]*',  
                        )
                    );
	
?>