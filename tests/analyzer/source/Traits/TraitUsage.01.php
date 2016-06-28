<?php

use non\_trait\_use as a;

trait G {
    function H() {  }
}

trait B {
    function C() {  }
    function D() {  }
}

class E extends F {
    use G;
    
}

class H extends I {
    use B, G;
    
}
?>