<?php

// normal usage of mcrypt
mcrypt_module_get_algo_key_size($c);


// no mcrypt usage
$mcrypt_create_iv = 2;
$x = 'mcrypt_enc_is_block_algorithm_mode';

?>