<?php

echo date('r');
echo 'Date : '.date('c');

if (rand(1,2) > 3) {
    print 'Yes';
}

class x {
    function foo() {
        $a = rand(0, 10);
        return 2 * $a;
    }
}

?>