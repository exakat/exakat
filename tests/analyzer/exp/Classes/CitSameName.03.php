<?php

$expected     = array('interface T { /**/ } ',
                      'interface T { /**/ } ',
                      'class T { /**/ } ',
                      'trait T { /**/ } ',
                      'trait T { /**/ } ',
                      );

$expected_not = array('function T( ) { /**/ } ',
                      'trait T2 { /**/ } ',
);

?>