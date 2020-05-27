<?php

class x {

      const CERT_CONFIG = [
        "private_key_bits" => 3,
        "private_key_type" => OPENSSL_KEYTYPE_RSA,
    ];
}

new class {
        protected $private_key_bits = 3;
        protected $private_key_type = OPENSSL_KEYTYPE_RSA;
};

trait t {
        protected $private_key_bits = 3485;
        protected $private_key_type = OPENSSL_KEYTYPE_DH;
}
