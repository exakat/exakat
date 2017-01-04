<?php

namespace a {
    $o = new class {
        public $aa = 1;
        
        function foo() {
            return A::class;
        }
    };

    $o = new A;
    
    class A {
        public $aa = 1;
    }
}

?>