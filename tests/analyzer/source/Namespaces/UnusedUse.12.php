<?php

namespace D {
    use A\B;
    use D\E;
    use F\G;

    class E {
        public function __construct() {
            print __METHOD__."\n";
        }
    }
    
    $d = new B\C([]);
    
    $d = new E();
}

namespace A\B {
    class C {
        public function __construct() {
            print __METHOD__."\n";
        }
    }
}

?>