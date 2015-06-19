<?php

class a0 {    private function b0() {} }
class a1 extends a0 {    function b1() {} }
class a2 extends a1 {    private function b2() {} }
class a3 extends a2 {    function b3() {} 
    function c3() {
        parent::b3(); // exist only in a3
        parent::b2();
        parent::b1();
        parent::b0();
        parent::b100(); // do not exists
    }
}

?>