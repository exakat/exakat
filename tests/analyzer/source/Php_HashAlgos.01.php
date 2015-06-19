<?php

hash('crc32b', 1);
hash('fnv132', 2);
hash('fnv164', 3);
hash('fnv1a32', 4);

hash('unknown', 5);

hash($variable, 6);

hash(globalConstant, 7);

$hash = hash_pbkdf2('sha1', 'password', 'salt', 1, 0);

hash_hmac('sha256', 'admin', 'blog');
?>