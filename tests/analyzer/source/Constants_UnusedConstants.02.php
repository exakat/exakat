<?php

class x {
    static function define() {}
}

$X->define(1, 2); // can't be found
x::define(3, 4); // can't be found
$x::define(5, 6); // can't be found
define(6,7); // must be found

?>