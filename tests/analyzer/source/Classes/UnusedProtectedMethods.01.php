<?php

class B2 {
    protected function ma21($b) {}
    protected function ma22($b) {}
    protected function ma231($b) {}
    protected function ma232($b) {}
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
        b2::ma21($b);
    }
}

class A2 extends A1 {
    public function foo() {
        a2::ma2();
        b2::ma22($b);
    }
}

class A31 extends A2 {
    public function foo() {
        a31::ma31();
        b2::ma231($b);
    }
}

class A32 extends A2 {
    public function foo() {
        a32::ma32();
        a32::ma321(); // Do not exists
        b2::ma232($b);
    }
}

?>