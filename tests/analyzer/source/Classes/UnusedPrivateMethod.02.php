<?php

class x {
    private function foo1() {}
    private function foo2() {}
    private function foo3() {}
    private function foo4() {
        $this->foo1()
             ->foo2()
             ->foo3()
             ->foo4();
             
        $a->foo5();
    }
    private function foo5() {}
    private function foo6() {}
}
?>