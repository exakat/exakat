<?php

$expected     = array('d::normald( )',
                      'd::normala( )',
                      'a::normala( )',
                      'z\a::normala( )',
                      'parent::normalb( )',
                      'e::normale( )');

$expected_not = array('d::statica( )',
                      'z\a::statica( )',
                      );

?>