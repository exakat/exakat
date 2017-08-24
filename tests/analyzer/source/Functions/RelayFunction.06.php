<?php

class foo {

    function notARelay(stdclass $a) {
        return 1;
    }
    
    function aRelay(stdclass $a) {
        return notARelay($a);
    }
    
    function aRelay2(stdclass $a) {
        return aRelay($a);
    }
    
    function notARelay2(stdclass $a) {
        x();
        return aRelay($a);
    }
    
    function notARelay3(stdclass $a) {
        $b = x($a);
        return aRelay($b);
    }

}
?>