<?php

// No 4th arg
mb_eregi_replace("\w", $a, $b);

// With a variable as first : no results
mb_ereg_replace("$delim\({})(".$x.")\ie", $a, $b);

// With a $ as first : no results
mb_ereg_replace("\(".$delim.")(".$x.")\is", $a1, $b, "msre");
mb_ereg_replace("\(".$delim.")(".$x.")\is", $a2, $b, "mser");
mb_ereg_replace("\(".$delim.")(".$x.")\is", $a3, $b, "msere");

?>