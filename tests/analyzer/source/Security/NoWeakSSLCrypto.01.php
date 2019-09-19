<?php

stream_socket_enable_crypto($stream, true, STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT);
stream_socket_enable_crypto($stream, true, STREAM_CRYPTO_METHOD_SSLv3_CLIENT);

curl_setopt(CURLOPT_SSLVERSION, \CURL_SSLVERSION_TLSv1);

fsockopen('sslv2://www.example.com/', 1);


?>