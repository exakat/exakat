<?php

$expected     = array('d::normald( )',
                      'd::normala( )',
                      'e::normale( )');

$expected_not = array('d::statica( )',
                      'parent::normalb( )',
                      'a::normala( )',
                      'z\a::statica( )',
                      'z\a::normala( )'
                      );

?>