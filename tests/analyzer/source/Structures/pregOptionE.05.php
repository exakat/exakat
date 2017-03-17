<?php

preg_replace("/({$delim})(".$x.")/ie", $a, $b);
mb_eregi_replace("/(".$delim.")(".$x.")/es", $a, $b);
mb_eregi_replace("/(".$delim.")(".$x.")/es", $a, $b, 'e');

// With a variable as first : no results
preg_replace("$delim/({})(".$x.")/ie", $a, $b);

// With a $ as first : no results
preg_replace("$/({})(".$x.")$"."ie", $a, $b);

preg_replace("/(".$delim.")(".$x.")/is", $a, $b);
mb_ereg_replace("/(".$delim.")(".$x.")/is", $a, $b);
mb_ereg_replace("/(".$delim.")(".$x.")/is", $a, $b, 'msr');
mb_ereg_replace("/(".$delim.")(".$x.")/is", $a, $b, $d);

?>