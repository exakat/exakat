<?php

namespace A {
    use B\D;
    use B\E;

    class C {
	    public function x() {
    		return new C();
	    }
    }
}

namespace B {
    class E {}
}

?>