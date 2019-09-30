<?php

$expected     = array('\'sslv2://www.example.com/\'',
                      '\\CURL_SSLVERSION_TLSv1',
                      'stream_socket_enable_crypto($stream, true, STREAM_CRYPTO_METHOD_SSLv3_CLIENT)',
                     );

$expected_not = array('STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT',
                      '',
                     );

?>