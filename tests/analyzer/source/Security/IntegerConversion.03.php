<?php


if ((int) $_GET['x'] === 2) {
    echo (int) $_GET['x'];
}

// Using (int) for validation
$c = 2;
if ((int) $_GET['xc'] === $c) {
    echo $_GET['xc'];
}

$d = "abc";
if ((int) $_GET['xd'] === $d) {
    echo $_GET['xd'];
}

if (rand(1,2)) {
    $e = "abc";
} else {
    $e = 34;
}

if ((int) $_GET['xe'] === $e) {
    echo $_GET['xe'];
}

?>