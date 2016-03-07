<?php

namespace A {
    $used_once++;

    function x ($a, $b = 1, Stdclass $c, Stdclass $d = null) {
        $used_once_in_x++;
    
        $used_twice++;
        $used_twice--;
    }

    class x {
        function x2 ($a, $b = 1, Stdclass $c, Stdclass $d = null) {
            $used_once_in_x2++;
    
            $used_twice++;
            $used_twice--;
        }
    }
}
?>