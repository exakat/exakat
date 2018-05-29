<?php

class B2 {
    protected function Ma2() {}
}

class A1 {
    protected function Ma32() {}
    protected function Ma31() {}
    protected function Ma2() {}
    protected function Ma1() {}
    
    private function pMa1() {}
    public function puMa1() {}

    protected function unused() {}
}

class A2 extends A1 {
    public function foo() {
        a2::ma2();
        b2::ma2();
    }
}

class A31 extends A2 {
    public function foo() {
        a31::ma31();
        b2::ma2();
    }
}

class A32 extends A2 {
    public function foo() {
        a32::ma32();
        a32::ma321(); // Do not exists
        b2::ma2();
    }
}

?>