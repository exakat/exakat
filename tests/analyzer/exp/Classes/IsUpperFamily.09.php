<?php

$expected     = array('self::inAAMethod( )',
                      'self::$inAAProperty',
                      'self::inAAConst',
                     );

$expected_not = array('$a::inAAMethod( )',
                      '$a::$inAAProperty',
                      '$a::inAAConst',
                     );

?>