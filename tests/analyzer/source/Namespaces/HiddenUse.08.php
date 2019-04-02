<?php

class x {
    use a;
    private $y = 3;
    use b;
    
    function foo() {
        $this->x = 3;
    }
}
?>