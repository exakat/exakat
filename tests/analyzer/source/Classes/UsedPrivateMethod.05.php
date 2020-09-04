<?php

class x {
    protected function foo($x1) {}
    protected static function foo2($x1) {}
    public function foo3() {
        static::foo2();
        $this->foo();
    }
}

class x2 extends x {
    protected function foo($x2) {}
    protected static function foo2($x2) {}
    public function foo3() {
        static::foo2();
        $this->foo();
    }
}

class x3 {
    protected function foo($x3) {}
    protected static function foo2($x3) {}
    public function foo3() {
        static::foo2();
        $this->foo();
    }
}

?>