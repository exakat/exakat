<?php

namespace A {
trait G {
    function H() {  }
}

trait B {
    function C() {  }
    function D() {  }
}
}

namespace B {
class E extends F {
    use A\G;
    use UndefinedTrait;
}

class H extends I {
    use A\B, A\G;
    
}
}
?>