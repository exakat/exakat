<?php

trait t {
    private function t1() {}
    private function t2() {}

    function foo() {
        return __METHOD__;
    }

}

class useT1 {
    use t; //{t::foo insteadof useT1; }
    
    function foo() {
        return __METHOD__;
        return $this->t1();
    }
}

class useT2 {
    use t;
    
    function foo() {
        return __METHOD__;
        return $this->t2();
    }
}

class useT12 {
    use t;
    
    function foo() {
        return $this->t2() + $this->t1();
    }
}

class useNoT {
    use t;
    
    function foo() {
        return $this->c1();
    }

    function c1() {
        return 3;
    }
}

class useNothing {
    function foo() {
        return $this->c1();
    }

    function c1() {
        return 3;
    }
}

?>