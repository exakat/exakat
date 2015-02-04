<?php

function __autoload($classname) {}

class x {
    public function __autoload($classname2) {}
}

abstract class y {
    abstract function __autoload($classname3);
}

abstract class t {
    function __autoload($classname4) {}
}

?>