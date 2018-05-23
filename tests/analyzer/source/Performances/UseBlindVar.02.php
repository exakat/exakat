<?php

// Check on properties
foreach($source['a'] as $key => $value) {
    $coordinates = array('x' => $source['a'][$key][0],
                         'y' => $source['a'][$key][1]);
}

// Reaching $source[$key] via $source is slow
foreach($source->a as $key => $value) {
    $coordinates = array('x' => $source->a[$key][0],
                         'y' => $source->a[$key][1]);
}

// Check on PHPvariables
foreach($_POST as $key => $value) {
    $source[$key] = array('x' => $_POST[$key][0],
                          'y' => $_POST[$key][1]);
}

// Check on functioncall
foreach(foo() as $key => $value) {
    $source[$key] = array('x' => foo()[$key][0],
                          'y' => foo()[$key][1]);
}

// Check on functioncall
foreach($foo as $key => $value) {
    $d = $foo + $key + $value;
}

?>