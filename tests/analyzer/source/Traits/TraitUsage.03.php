<?php

trait G {
    function H() {  }
}

trait B {
    function C() {  }
    function D() {  }
}

$a = new class {
    use G;
};

class H extends I {
    use B, G;
    
}
?>