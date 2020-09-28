<?php

$expected     = array('hash_hmac_file($algo1, $filename, $key) == 0',
                      'foo($algo2, $filename, $key) == 0',
                     );

$expected_not = array('foo($algo3, $filename, $key)',
                     );

?>