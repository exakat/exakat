<?php

preg_replace("/({$delim})(".$x.")/ie", $a, $b);
mb_eregi("/(".$delim.")(".$x.")/es", $a, $b);

// With a variable as first : no results
preg_replace("$delim/({})(".$x.")/ie", $a, $b);

// With a $ as first : no results
preg_replace("$/({})(".$x.")$"."ie", $a, $b);

preg_replace("/(".$delim.")(".$x.")/is", $a, $b);
mb_ereg("/(".$delim.")(".$x.")/is", $a, $b);

?>