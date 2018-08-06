<?php

// Handles arrays with callback
$uppercase = array_map('strtoupper', $source);

// Handles arrays with foreach
foreach($source as &$s) {
    $s = uppercase($s);
}

?>