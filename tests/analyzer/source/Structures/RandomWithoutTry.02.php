<?php
openssl_random_pseudo_bytes(1);

try{
openssl_random_pseudo_bytes(0);
} catch (Throwable $e) {
    echo $e->getMessage();
}
?>