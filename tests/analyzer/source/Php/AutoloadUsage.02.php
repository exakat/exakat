<?php

function __autoload($class) { }

class x {
    function __autoload($classC) { }
}

interface i {
    function __autoload($classI);
}

trait t {
    function __autoload($classT) { }
}

?>