<?php

trait a { private function a() { echo __TRAIT__.PHP_EOL;}}
trait b { public function a() { echo __TRAIT__.PHP_EOL;}}

class xprivate {
    use a, b { a::a insteadof b;}
}

class yprivate extends xprivate {
    function bar(){
        $this->a();
    }
}

class xpublic {
    use a, b { a::a insteadof a;}
}

class ypublic extends xpublic {
    function bar(){
        $this->A();
    }
}

(new x)->bar();
