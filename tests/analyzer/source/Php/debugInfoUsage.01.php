<?php

function __debugInfo( $a ) { }

class y {
    public function __debugInfo() { 
        return array('a' => 'b');
    }
}

$y = new y();
var_dump($y);

?>