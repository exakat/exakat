<?php

$expected     = array('new $classe( )',
                      'new $classe[1]',
                      'new $object->property',
                      'new Classe::$staticproperty',
                     );

$expected_not = array('new class { /**/ } ',
                      'new stdClass( )',
                     );

?>