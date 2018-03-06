<?php

$expected     = array('xxtea_encrypt($str, $key)',
                      'xxtea_decrypt($encrypt_data, $key)',
                     );

$expected_not = array('xxtea_unserialize($encrypt_data)',
                     );

?>