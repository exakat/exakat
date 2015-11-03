<?php

define('a', 1);
define('b', a + 2);
define('c', b + a);
define('d', strtolower(c) + a);

$a = 1;
define('e', 1 + $a);

?>