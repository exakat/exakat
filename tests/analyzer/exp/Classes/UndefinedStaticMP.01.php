<?php

$expected     = array('static::undefined( )',
                      'static::$pundefined',
                     );

$expected_not = array('static::definedinStatic( )',
                      'static::definedInParent( )',
                      'static::definedInParentParent( )',
                      'static::$pdefinedinStatic',
                      'static::$pdefinedInParent',
                      'static::$pdefinedInParentParent',
                     );

?>