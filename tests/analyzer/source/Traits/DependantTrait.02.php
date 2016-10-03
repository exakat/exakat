<?php

trait ut {
    function bar() {}
}

trait t {
    use ut;
    
    function foo() {
        $this->bar();
    }
}

trait t2 {
    use ut;
    
    function foo() {
        $this->bar();
        $this->bar2();
    }
}


?>