<?php

foreach($f->g as $h0) {
    $a->b['c'][] = $h0->e;
}

foreach($f->g as $h1) {
    $a->b['c'][] = $h1['e'];
}

// Includes a if() test
$bColumn = array();
foreach($a as $k => $v) {
    if (isset($v['b'])) {
        $bColumn[] = $v['b'];
    }
}

// Not a array_column, more a array_pad
foreach($f->g as $h2) {
    $a->b['c'][] = $h2;
}

?>