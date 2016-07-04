<?php

preg_match("|.+/$|", $dir);
preg_match("|$a1+/$|", $dir);
preg_match("|".$b1."+/$|", $dir);

preg_match("|.2+/$|v", $dir);
preg_match("|$a2+/$|v", $dir);
preg_match("|".$b2."+/$|v", $dir);

preg_match("|.3+/$|s", $dir);
preg_match("|$a3+/$|s", $dir);
preg_match("|".$b3."+/$|s", $dir);

preg_replace('/^\//', '', $url);
preg_replace("/$b1^\//", '', $url);
preg_replace("/^".$a1."\//", '', $url);


preg_replace('/^\//a', '', $url);
preg_replace("/$b2^\//a", '', $url);
preg_replace("/^".$a2."\//a", '', $url);

preg_replace('/^3\//s', '', $url);
preg_replace("/$b3^\//i", '', $url);
preg_replace("/^".$a3."\//i", '', $url);

?>