<?php

$expected     = array('a::$inAA',
                      'a::$inAAA',
                      'a::$inAAAA',
                     );

$expected_not = array('a::$inA',
                      'a::$inB',
                      'a::$inTrait',
                      'a::$nowhere',
                      'c::$inC',
                     );

?>