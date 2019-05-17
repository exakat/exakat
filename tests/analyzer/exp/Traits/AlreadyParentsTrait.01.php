<?php

$expected     = array('class b extends a { /**/ } ', 
                      'class e extends d { /**/ } ', 
                     );

$expected_not = array('class a { /**/ } ', 
                      'class c extends b { /**/ } ', 
                      'class f extends e { /**/ } '
                     );

?>