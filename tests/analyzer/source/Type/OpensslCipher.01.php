<?php

openssl_open($sealed, $open, $env_key, $pkeyid, "aes-128-gcm");

$ciphertext = openssl_encrypt($plaintext, "aes-128-cbc", $key, $options=0, $iv, $tag);

openssl_digest($sealed, 'not a cipher', $env_key, $pkeyid);

?>