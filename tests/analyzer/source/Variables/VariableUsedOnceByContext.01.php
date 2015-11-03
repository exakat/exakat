<?php

namespace A {
    $used_once++;

    function x () {
        $used_once_in_x++;
    
        $used_twice++;
        $used_twice--;
    }

    class x {
        function x2 () {
            $used_once_in_x2++;
    
            $used_twice++;
            $used_twice--;
        }
    }
}
?>