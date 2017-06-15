<?php

namespace A {
    class A {}
    class B {}
}

namespace B {
    // Use is after
    new A();
    use A\A;
    
    // Use is before
    use A\B;
    new B();
}
?>