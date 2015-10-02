<?php

class A extends B {

    public function nonStaticMethod() {
        self::nonStaticMethod();
        self::staticMethod();

        parent::nonStaticMethod();
        parent::staticMethod();

        static::nonStaticMethod();
        static::staticMethod();
    }
    
    public static function staticMethod() {}
}

A::nonStaticMethod();
A::staticMethod();

?>