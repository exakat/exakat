<?php
$array = [0, 1, 2];
foreach ($array as $val) {
    var_dump(current($array)); // $val is not a reference
}

foreach ($array2 as $val) {
    var_dump(\end($array2)); // $val is not a reference
}

foreach ($array3 as $key => $val) {
    var_dump(next($array));  // $val is not a reference 
}

foreach ($array4 as $val) {
    var_dump($x->next($array4)); // next is not a function
}

foreach ($array5 as $key => &$val) {
    var_dump(\next($array5));  // Real error
}

?>