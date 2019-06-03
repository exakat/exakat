<?php

interface i {
    function i(); 
}

// i is not implemented and declared
class foo {
    function i() {}
    function j() {}
}

// i is implemented and declared
class foo2 implements i {
    function i() {}
    function j() {}
}

// i is not implemented 
class foo3 {
    public function I() {}
    function j() {}
}

// i is private
class foo4 {
    private function i() {}
    function j() {}
}

// i is private
class foo5 {
    protected function i() {}
    function j() {}
}

// i is static
class foo6 implements j {
    static function i() {}
    function j() {}
}

?>