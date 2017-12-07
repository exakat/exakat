<?php

$expected     = array('d::normald( )',
                      'd::normala( )',
                      'a::normala( )',
                      'z\\a::normala( )',
                      'e::normale( )',
                     );

$expected_not = array('d::statica( )',
                      'parent::normalb( )',
                      'z\\a::statica( )',
                     );

?>