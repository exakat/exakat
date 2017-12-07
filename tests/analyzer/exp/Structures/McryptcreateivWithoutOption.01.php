<?php

$expected     = array('mcrypt_create_iv(1)',
                     );

$expected_not = array('mcrypt_create_iv(1, 2)',
                      'mcrypt_create_iv(1, 2, 3)',
                      'mcrypt_create_iv(4)',
                      'mcrypt_create_iv(5)',
                     );

?>