<?php

function x() {
    $y = array(1=>2, 3=>4);
    foreach($y as $k => $v) {
        yield $k => $v;
    }
}

foreach(x() as $k => $v) {
    print "$k => $v\n";
}

?>