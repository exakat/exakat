<?php

$expected     = array('class a extends Exception { /**/ } ',
                      'class b extends RuntimeException { /**/ } ',
                      'class c extends a { /**/ } ',
                     );

$expected_not = array('class d extends e { /**/ }',
                     );

?>