<?php

(unset) $this;

unset($this);

function foo() {
    (unset) $this;
    
    unset($this);
}

class c {
    function foo() {
        (unset) $this;
        
        unset($this);
        
        $a->unset($this);
    }
}


trait t {
    function foo() {
        (unset) $this;
        
        unset($this);
        
        A::unset($this);
    }
}

?>