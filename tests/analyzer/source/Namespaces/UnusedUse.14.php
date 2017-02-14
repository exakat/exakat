<?php

namespace D {
    use A\B;
    use D\E;
    use F\G;

    class E extends B\C {
        public function __construct() {
            print __METHOD__."\n";
        }
    }
    
    $d = new E();
}

namespace A\B {
    interface C {
        public function __construct();
    }
}

?>