<?php

$expected     = array('interface unusedInterface { /**/ } ', 
                      'interface unusedInterface2 { /**/ } ', 
                      'interface a extends usedInterface2 { /**/ } ', 
                     );

$expected_not = array('interface usedInterface { /**/ } ', 
                     );

?>