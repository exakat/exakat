<?php
$array = [0, 1, 2];
foreach ($array as &$val) {
    var_dump(current($array));
}

foreach ($array2 as &$val) {
    var_dump(\end($array2));
}

foreach ($array3 as $key => &$val) {
    var_dump(next($array3));
}

foreach ($array4 as &$val) {
    var_dump($x->next($array4));
}

foreach ($array5 as $key => &$val) {
    var_dump(\array_merge($array5));
}

?>