<?php

$expected     = array('mcrypt_module_get_algo_key_size($c)',
                     );

$expected_not = array('mcrypt_create_iv',
                      'mcrypt_enc_is_block_algorithm_mode',
                     );

?>