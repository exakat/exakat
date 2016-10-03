<?php

class B2 {
    protected function ma2() {}
}

class A1 {
    protected function ma32() {}
    protected function ma31() {}
    protected function ma2() {}
    protected function ma1() {}
    
    private function pma1() {}
    public function puma1() {}

    protected function unused() {}

    public function foo() {
        a2::ma1();
        b2::ma1();
    }
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