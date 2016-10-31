<?php

// PHP 5.5+ empty() usage
if (empty(strtolower($b0 . $c0))) {
    doSomethingWithoutA();
}

// Compatible empty() usage
$a = strtolower($b . $c);
if (empty($a)) {
    doSomethingWithoutA();
}

// $a is reused
$a2 = strtolower($b . $c);
if (empty($a2)) {
    doSomethingWithoutA();
} else {
    echo $a2;
}

?>