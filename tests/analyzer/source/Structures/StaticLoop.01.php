<?php

foreach($code as $k => &$v) {
    $v = strtolower($v);
}

foreach($code as $k => $w) {
    $vvv = &$w;
}

foreach($code as $k => $v2) {
    $v2 = &$v2 + 2;
}

foreach($code as $k => $v3) {
    $v = strtolower($v);
}

?>