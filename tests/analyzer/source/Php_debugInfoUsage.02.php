<?php

function __debugInfo() { }

class y {
    public function __DEBUGINFO() { 
        return array('a' => 'b');
    }
}

$y = new y();
var_dump($y);

?>