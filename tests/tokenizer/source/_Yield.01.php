<?php
// define a simple range generator
function generateRange($start, $end, $step = 1) {
    for ($i = $start; $i < $end; $i += $step) {
        // yield one result at a time
        yield $i;
    }
}

function generateRange2($start, $end, $step = 1) {
    for ($i = $start; $i < $end; $i += $step) {
        // yield one result at a time
        yield $i[1];
        yield $i->y ? 'd' : 2 + 2;
    }
}
 
foreach (generateRange(0, 1000000) as $number) {
    echo $number;
}

?>