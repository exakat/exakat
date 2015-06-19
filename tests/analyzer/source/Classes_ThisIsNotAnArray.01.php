<?php

class x {

    function init() {
        $names = range('a', 'g');
        
        foreach($names as $n) {
            $this[$n];
        }

        $this[] = 3;
    }
}

$X = new x();
$X->init();
print_r($X);

?>