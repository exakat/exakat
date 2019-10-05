<?php

class B2 {
    protected function ma2($b) {}
}

class A1 {
    protected function ma32() {}
    protected function ma31() {}
    protected function ma2() {}
    protected function ma1() {}
    
    private function pma1() {}
    public function puma1() {}

    protected function unused() {}
}

class A2 extends A1 {
    public function foo() {
        $this->ma2();
        $b2->ma2();
    }
}

class A31 extends A2 {
    public function foo() {
        $this->ma31();
        $b2->ma2();
    }
}

class A32 extends A2 {
    public function foo() {
        $this->ma32();
        $this->ma321(); // Do not exists
        $b2->ma2();
    }
}

?>