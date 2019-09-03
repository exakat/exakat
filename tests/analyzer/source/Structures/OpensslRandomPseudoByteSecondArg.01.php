<?php
    // PHP 7.4 way to check on random number generation
    try {
        $bytes = openssl_random_pseudo_bytes($i);
    } catch(\Exception $e) {
        die("Error while loading random number");
    }

    // Old way to check on random number generation
    $bytes = openssl_random_pseudo_bytes($i, $cstrong);
    if ($cstrong === false) {
        die("Error while loading random number");
    }
?>