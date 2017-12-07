<?php

$expected     = array('function __autoload($classname) { /**/ } ',
                     );

$expected_not = array('function __autoload($classname1) { /**/ } ',
                      'function __autoload($classname2) { /**/ } ',
                      'function __autoload($classname3) { /**/ } ',
                     );

?>