<?php
if (version_compare($version, $lower) >= 0) {
    $a = true;
    $b = 2;
} else {
    $a = false;
    $c++;
}

if (version_compare($version, $lower) >= 1) {
    $b = $d;
    $a = true;
} else {
    $b = false;
    $c++;
}

if (version_compare($version, $lower) >= 2)
    $a = true;
 else 
    $a = false;

?>