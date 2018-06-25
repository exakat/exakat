<?php

$expected     = array('$p1',
                      '$p2',
                      '$p3',
                      '$p4',
                      '$p5',
                      '$p6',
                      '$p7',
                      '$p11 = 1', 
                      '$p12 = 2', 
                      '$p13 = 3', 
                      '$p14 = 4', 
                      '$p15 = 5', 
                      '$p16 = 6'
                      '$p17 = 7', 
                     );

$expected_not = array('mcrypt_create_iv',
                      'mcrypt_enc_is_block_algorithm_mode',
                     );

?>