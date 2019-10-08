<?php

mb_check_encoding($x, 'utf8');
mb_check_encoding($x, 'UTF8');
mb_check_encoding($x, 'UTF9');
mb_check_encoding($x, "UTF8");
mb_check_encoding($x, "UT"."F8");
mb_check_encoding($x, PHP_VERSION == '7.3' ? 'us' : 'ISO-8859-144');
?>