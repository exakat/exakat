<?php

$expected     = array('abstract_protected',
                      'abstract_static_protected',
                      'abstract_static', 
                      'abstract_protected_static',
                      'protected_abstract_static',
                      'abstract_alone', 
                      'protected_abstract',
                      'static_abstract', 
                      'x');

$expected_not = array('CONSTANT_DE_CLASSE',
                      'y',
                      '$private_property');

?>