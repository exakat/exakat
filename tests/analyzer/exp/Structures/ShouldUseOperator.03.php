<?php

$expected     = array('chr(54)', 
                      'chr(C)',
                      'chr(CD)',
                     );

$expected_not = array('chr($c)',
                      'chr($c + 3)',
                     );

?>