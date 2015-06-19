<?php

foreach($am1 as $im1 => $bm1) {
    $am1[$im1] = $bm1;
}

// no, source is modified but somewherelese
foreach($anm1 as $inm1 => $bnm1) {
    $anm1[$j] = $bnm1;
}

foreach($anm2 as $bnm2) {
    $am2[] = $bm2;
}

// no, source is not modified but read
foreach($anm3 as $inm3 => $bnm3) {
    $x = $anm3[$inm3];
}

?>