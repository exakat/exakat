<?php

class x {
    function foo(&$a, $b) {}

    static function bar(&$a, $b) {}
    
}

(new x)->foo(1, 2);
(new x)->foo($a, $b);

(new x())::bar(1, 2);
(new x())::bar($a, $b);

?>