<?php

preg_match_all('/a/', 'b', $a);
preg_match_all('/a/o', 'b', $a);
preg_match_all('/a/s', 'b', $a);
preg_match_all('/a/f', 'b', $a);
preg_match_all('/a/t', 'b', $a);
preg_match_all('/a/u', 'b', $a);
preg_match_all('/a/', 'b', $a);
preg_match_all('/a/', 'b', $a);
preg_match_all('/a/', 'b', $a);
preg_replace('/a/', 'b', $a);
preg_replace_callback('/a/', 'b', $a);
preg_replace_callback_array('/a/', 'b', $a);
preg_match_all('/a/', 'b', $a);
preg_match_all('/a/', 'b', $a);

preg_match_all('#a#', 'b', $a);
preg_match_all('&a&', 'b', $a);

preg_match_all('!a!', 'b', $a);
preg_match_all("[$a]", 'b', $a);

?>