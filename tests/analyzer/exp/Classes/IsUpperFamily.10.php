<?php

$expected     = array('static::inAAMethod( )',
                      'static::$inAAProperty',
                      'static::inAAConst',
                     );

$expected_not = array('$a::inAAMethod( )',
                      '$a::$inAAProperty',
                      '$a::inAAConst',
                     );

?>