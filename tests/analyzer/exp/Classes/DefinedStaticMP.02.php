<?php

$expected     = array('self::definedinStatic( )',
                      'self::definedInParent( )',
                      'self::definedInParentParent( )',
                      'self::$pdefinedinStatic',
                      'self::$pdefinedInParent',
                      'self::$pdefinedInParentParent',
                     );

$expected_not = array('self::definedinStatic( )',
                      'self::$definedinStatic',
                     );

?>