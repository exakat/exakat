<?php

namespace A {

use A\B\C;

class X {
    use \A\T;
    
    function w() {
        new T();
        new C();
    }
}

trait T {

}

}

?>