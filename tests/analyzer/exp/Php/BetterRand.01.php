<?php

$expected     = array('rand(1, 2)',
                      '\\mt_rand(3, 5)',
                     );

$expected_not = array('openssl_random_pseudo_bytes( )',
                     );

?>