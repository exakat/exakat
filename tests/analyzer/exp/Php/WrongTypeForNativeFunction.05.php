<?php

$expected     = array('substr(self::$pInt, 0, 1)',
                     );

$expected_not = array('substr(self::$pString, 0, 1)',
                      'substr(self::$pVoid, 0, 1)',
                     );

?>