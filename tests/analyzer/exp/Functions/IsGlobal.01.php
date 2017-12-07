<?php

$expected     = array('fglobal($x)',
                     );

$expected_not = array('functioncallInMethod( )',
                      'functioncallInTrait( )',
                      'functioncallInFunction( )',
                     );

?>