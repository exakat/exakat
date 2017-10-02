<?php

use \arrayaccess as a;

class x {

    function init() {
        $names = range('a', 'g');
        
        foreach($names as $n) {
            $this[$n];
        }

        $this[] = 3;
    }
}

class xx implements a {

    function init() {
        $names = range('a', 'g');
        
        foreach($names as $n) {
            $this[3];
        }

        $this[] = 4;
    }
}

$X = new x();
$X->init();
print_r($X);

?>