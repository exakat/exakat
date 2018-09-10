<?php

$expected     = array('parent::definedMethod( )',
                      'parent::constant1',
                      'parent::$property2',
                      'parent::$property3',
                     );

$expected_not = array('parent::undefinedMethod( )',
                      'parent::class',
                      'parent::$property1',
                      'parent::constant2',
                     );

?>