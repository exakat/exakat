<?php

$expected     = array('crypt(1)',
                     );

$expected_not = array('crypt(1, 2)',
                      'crypt(1, 2, 3)',
                      'crypt(4)',
                      'crypt(5)',
                     );

?>