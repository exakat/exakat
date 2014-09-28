<?php

class x {
    static function define() {}
}

$X->define('a', 2); // can't be found
x::define('b', 4); // can't be found
$x::define('c', 6); // can't be found
define('d',7); // must be found

?>