<?php

$expected     = array('import_request_variables($a->b( )->c( ))', 
                      '\import_request_variables($_GET)');

$expected_not = array('C::import_request_variables($a->b( )->c( ))',
                      '$c->import_request_variables($_POST)'
);

?>