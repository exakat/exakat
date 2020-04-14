<?php

class A3 extends A2 {
    const cA3 = 3;
    
    function foo()  : void {
        echo self::cA1;
        echo self::cA2;
        echo self::cA3;
        echo self::cA4;
    }
}

?>