<?php

$expected     = array('openssl_pkey_get_private("file://src/openssl-0.9.6/demos/sign/key.pem")',
                      'openssl_sign($data, $signature, $pkeyid)',
                      'openssl_free_key($pkeyid)',
                     );

$expected_not = array(
                     );

?>