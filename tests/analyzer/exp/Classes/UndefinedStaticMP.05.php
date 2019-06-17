<?php

$expected     = array('static::$pundefined',
                      'static::undefined( )',
                     );

$expected_not = array('static::$pdefinedinStatic',
                      'static::definedinStatic( )',
                      'static::definedInParent( )',
                      'static::definedInParentParent( )',
                      'static::$publicdefinedInParent',
                      'static::$pdefinedInParentParent',
                      'static::$pdefinedInParent',
                     );

?>