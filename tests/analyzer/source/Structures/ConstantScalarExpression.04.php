<?php

const EXTERNAL = 1;

class x {
    public    $puReal = 4 + 4;

    public    $puDefault = 3;

    public    $puBoth = 3 + EXTERNAL;
    
    function foo($a) {
        $this->puDefault = 3 + $a;
        $this->puBoth = 3 + $a;
    }
}
?>
