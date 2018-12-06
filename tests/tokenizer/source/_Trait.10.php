<?php


trait a { function a() { echo __TRAIT__.PHP_EOL;}}
trait b { function a() { echo __TRAIT__.PHP_EOL;}}
trait c { function a() { echo __TRAIT__.PHP_EOL;}}

class x {
    use a, b, c{ a::a insteadof b,a,a;
                 c::a insteadof a;
                 a as d;
                 }
                 
    function bar(){
        $this->a();
    }
}

(new x)->bar();