<?php

$expected     = array('Zend\\Mvc\\Router\\Http\\Regex',
                     );

$expected_not = array('Zend\\Mvc\\Service\\ViewFactory( )',
                      'Zend\\Mvc\\MiddlewareListener( )',
                      'Zend\\Mvc\\Router\\Http\\RegexButNotZendClass( )',
                     );

?>