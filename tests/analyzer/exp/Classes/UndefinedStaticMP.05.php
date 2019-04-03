<?php

$expected     = array('static::$pdefinedinStatic', 
                      'static::$pundefined', 
                      'static::$pdefinedInParentParent', 
                      'static::$pdefinedInParent', 
                      'static::undefined( )',
                     );

$expected_not = array('static::definedinStatic( )',
                      'static::definedInParent( )',
                      'static::definedInParentParent( )',
                      'static::$publicdefinedInParent',
                     );

?>