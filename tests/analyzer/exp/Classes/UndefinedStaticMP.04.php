<?php

$expected     = array('static::$pundefined',
                      'static::$pdefinedInParentParent',
                      'static::$pdefinedInParent',
                      'static::undefined( )',
                     );

$expected_not = array('static::$pdefinedinStatic',
                      'static::definedinStatic( )',
                      'static::definedInParent( )',
                      'static::definedInParentParent( )',
                      'static::$publicdefinedInParent',
                     );

?>