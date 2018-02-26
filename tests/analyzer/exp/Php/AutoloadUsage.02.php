<?php

$expected     = array('function __autoload($class) { /**/ } ',
                     );

$expected_not = array('function __autoload($classC) { /**/ } ',
                      'function __autoload($classI) { /**/ } ',
                      'function __autoload($classT) { /**/ } ',
                     );

?>