<?php
class x {
    function foo() {
        $this->a = 1; // virtual property
    }
    
    use t;
}

class x2 {
    use t2;

    function foo() {
        $this->a = 1; // virtual property
    }
    
}
?>