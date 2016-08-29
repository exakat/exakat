<?php

ini_set('session.hash_function',1);
\ini_get('session.hash_bits_per_charactor');
ini_alter('session.entropy_file', 3);
ini_restore('session.entropy_length');
\ini_restore('session.auto_start');

?>