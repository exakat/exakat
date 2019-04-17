<?php

namespace A {
    use function A\dirname as split;
    
    function dirname($a, $b) { return __FUNCTION__; }
    
    echo dirname('/a/b/c');
    echo split('a', 'b');
    
    echo \dirname('/a/b/c');
}

?>