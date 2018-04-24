<?php

foreach($a as $b1) {
    $c .= $b1;
    preg_match('/a/', $c);
}

foreach($a as $k => $b2) {
    $c .= $b2;
    preg_match('/a/', $c);
}

foreach($a as $k3 => $b3) {
    $c .= $k3;
    preg_match('/a/', $c);
}

foreach($a as $k4 => $b4) {
    $c .= $k4;
    preg_replace('/a/', $c);
}

foreach($a as $k5 => $b5) {
    $c .= $k5;
    a('/a/', $c);
}

foreach($a as $k => $b6) {
    $c = $k6;
    preg_replace('/a/', $c);
}

?>