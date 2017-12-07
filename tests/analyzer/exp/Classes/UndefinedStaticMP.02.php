<?php

$expected     = array('SELF::undefined( )',
                      'SELF::$pundefined',
                     );

$expected_not = array('SELF::definedinStatic( )',
                      'SELF::definedInParent( )',
                      'SELF::definedInParentParent( )',
                      'SELF::$pdefinedinStatic',
                      'SELF::$pdefinedInParent',
                      'SELF::$pdefinedInParentParent',
                     );

?>