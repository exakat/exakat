<?php

$expected     = array('Phalcon\\Mvc\\Application',
                      'Phalcon\\Di\\FactoryDefault',
                      'Phalcon\\Loader',
                      'Phalcon\\Mvc\\View',
                      'Loader( )',
                      'View( )',
                      'FactoryDefault( )',
                      'Application($di)',
                     );

$expected_not = array('registerDirs',
                     );

?>