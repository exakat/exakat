<?php

$expected     = array('if(defined(\'C\')) { /**/ } ',
                      'if(defined(\'B\'))  /**/  ',
                      'if(defined(\'A\'))  /**/  ',
                     );

$expected_not = array('if(defined(\'D\'))  /**/  ',
                     );

?>