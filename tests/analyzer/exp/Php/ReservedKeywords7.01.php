<?php

$expected     = array('false\\null',
                      'int\\float',
                      'class bool { /**/ } ',
                      'interface true { /**/ } ',
                      'trait string { /**/ } ',
                     );

$expected_not = array('function null( ) { /**/ }',
                     );

?>