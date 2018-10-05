<?php


class A implements i2 {
    protected $b = 2;
    
    function foo() {
        $this->b = 'a';
        $this->d(self::B);
    }
}

class B extends A implements i {
    protected $b = 2;
    
    const B = 1;

    function foo() {
        $this->b = 'b';
        $this->d(self::B, self::IA, self::IA2, static::C);
    }
    
    function d() {}
}

class C extends b {
    function foo() {
        $this->b = 'c';
        $this->d(self::B);
    }
}

interface i {
    const IA = 1;
}

interface i2 {
    const IA2 = 1;
}

?>