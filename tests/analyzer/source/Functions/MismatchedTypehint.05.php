<?php

class x {
    function bar( Y $a) {}
}

class x2 {
    function bar2(stdclass $a) {}
}

function bar(Y $ay1, Y $ay2) {
    $x = new x;
    $x->bar($ay1);

    $y2 = new x2;
    $y2->bar2($ay2);

}

?>