<?php

// Reaching $source[$key] via $value is faster
foreach($source as $key => $value1) {
    $coordinates = array('x' => $value1[0],
                         'y' => $value1[1]);
}

// Reaching $source[$key] via $source is slow
foreach($source as $key => $value2) {
    $coordinates = array('x' => $source[$key][0],
                         'y' => $source[$key][1]);
}

// This doesn't apply to writing
foreach($source as $key => $value3) {
    $source[$key] = array('x' => $value3[0],
                          'y' => $value3[1]);
}

?>