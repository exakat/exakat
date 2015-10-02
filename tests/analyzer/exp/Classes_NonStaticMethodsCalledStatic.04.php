<?php

$expected     = array('d::normald( )',
                      'd::normala( )',
                      'e::normale( )',
                      'parent::normalb( )',
                      'a::normala( )', 
                      'z\a::normala( )');

$expected_not = array('z\a::statica( )',
                      'd::statica( )',
                      );

?>