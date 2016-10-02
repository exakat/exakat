<?php

trait ut11 {
    function bar11() {}
}

trait ut {
    use ut11;
    
    function bar() {}
}

trait ut2 {
    function bar2() {}
}

trait t {
    use ut;
    
    function foo() {
        $this->bar11();
    }
}

trait t2 {
    use ut;
    
    function foo() {
        $this->bar();
        $this->bar2();
        $this->bar11();
    }
}

trait t3 {
    use ut;
    
    function foo() {
        $this->bar3();
        $this->bar2();
        $this->bar11();
    }
}


?>