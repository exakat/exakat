<?php

$expected     = array('class y { /**/ } ',
                     );

$expected_not = array('class x { /**/ } ',
                      'class a implements stringable { /**/ } ',
                      'class zz extends z { /**/ } ', 
                      'class z implements stringable { /**/ } ',
                     );

?>