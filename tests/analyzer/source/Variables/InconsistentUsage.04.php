<?php
class A {
    protected $a;
    protected $a2 = 1;
    protected $a3 = null;
    

    public function b($a, $a2, $a3) {
        $this->a = $a;
        $this->a2 = $a2;
        $this->a3 = $a3;
    }
}
