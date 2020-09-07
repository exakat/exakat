<?php

class y extends x {
    public function a(b $c) : ?string {}
    public function a2(b $c) : ?int {}

    private function foo($a):array {}
    protected function foo2($a) {}
}

abstract class x {
    abstract public function a(b $c) : ?string ;
    abstract public function a2(b $c) : ?string ;

    private function foo() {}
    protected function foo2($a):array {}
}

new y;

?>