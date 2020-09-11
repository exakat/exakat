<?php

$expected     = array('d::normald( )',
                      'd::normala( )',
                      'e::normale( )',
                     );

$expected_not = array('d::statica( )',
                      'a::normala( )',
                      'z\\a::normala( )',
                      'parent::normalb( )',
                      'z\\a::statica( )',
                      'c::normala( )',
                      'c::normale( )',
                     );

?>