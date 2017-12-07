<?php

$expected     = array('static::definedinStatic( )',
                      'static::definedInParent( )',
                      'static::definedInParentParent( )',
                      'static::$pdefinedinStatic',
                      'static::$pdefinedInParent',
                      'static::$pdefinedInParentParent',
                     );

$expected_not = array('static::$definedinStatic',
                      'static::undefined()',
                     );

?>