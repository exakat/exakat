<?php

ini_set('always_populate_raw_post_data',1);
\ini_get('asp_tags');
ini_alter('xsl.security_prefs', 3);
ini_restore('xsl.security_prefs');
\ini_get('jsp_tags');

$a->ini_restore('xsl.security_prefs');
A::ini_restore('xsl.security_prefs');

?>