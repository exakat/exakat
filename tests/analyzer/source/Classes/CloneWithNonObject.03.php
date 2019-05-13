<?php

class y {
    function foo() {
        clone $this;
        clone True;
        clone 'a' . 'b';
        clone clone new stdclass();
        
    }
}

?>