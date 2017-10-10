<?php

class x implements ArrayAccess {

    function init() {
        $names = range('a', 'g');
        
        foreach($names as $n) {
            $this[$n];
        }

        $this[] = 3;
        $this->f;
    }
}

class x2 {

    function init() {
        $this[3] = 4;
    }
}

$X = new x();
$X->init();
print_r($X);

?>