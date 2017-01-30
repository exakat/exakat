<?php

//Don't do that
function __autoload($a) { return true; }

// shouldn't be found
class x {
    function __autoload($b) { return true; }
}

// shouldn't be found
trait t {
    function __autoload($c) { return true; }
}

?>