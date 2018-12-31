<?php

$expected     = array('function null( ) { /**/ } ',
                      '$this->die( )',
                      '$this->exit( )',
                      '$or',
                     );

$expected_not = array('function',
                      'print',
                      'define',
                      'class true { /**/ } ',
                     );

?>