<?php

namespace A {
    use B\D as E;

    class C {
        public function __construct() { print __METHOD__."\n"; }
	    public function x() {
    		return new D();
	    }
    }
    
    new D();
}

namespace A {
    use B\C as Z;

    class D {
        public function __construct() { print __METHOD__."\n"; }
	    public function x() {
    		return new C();
	    }
    }
    
    new C();
}

namespace B {
    class D {
        public function __construct() { print __METHOD__."\n"; }
    }
}
?>