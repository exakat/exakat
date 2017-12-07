<?php

$expected     = array('sodium_hex2bin($hex)',
                      'sodium_bin2hex($bin)',
                      'sodium_randombytes_buf(sodium_randombytes_uniform(1000))',
                      'sodium_randombytes_uniform(1000)',
                      'sodium_hex2bin(\'[\' . $hex . \']\', \'[]\')',
                     );

$expected_not = array('sodium_lib( )',
                     );

?>