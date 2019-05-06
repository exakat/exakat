<?php

$expected     = array('$a21++',
                      '$a31++', 
                      '$a41++', 
                      '$a51++', 
                     );

$expected_not = array('<?php  /**/  ?>', 
                       'function foo1( ) { /**/ } ', 
                       'function foo2( ) { /**/ } ', 
                       '$a11++', 
                       '$b11++', 
                       '$b21++',
                       '$b31++', 
                       '$b41++', 
                       '$b51++', 
                     );

?>