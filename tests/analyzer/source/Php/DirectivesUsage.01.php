<?php

ini_set('always_populate_raw_post_data',1);
\ini_get('asp_tags');
ini_alter('open_basedir', 3);
ini_restore('user_dir');
\ini_get('jsp_tags');

$a->ini_restore('error_prepend_string');
A::ini_restore('always_populate_raw_post_data');

?>