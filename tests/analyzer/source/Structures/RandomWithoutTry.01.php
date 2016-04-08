<?php

$salt = random_int(0, 2);

try {
    $salt = random_bytes($length);
} catch (TypeError $e) {
    // Error while reading the provided parameter
} catch (Exception $e) {
    // Insufficient randome data generated
} catch (Error $e) {
    // Error with the provided parameter : <= 0
}

$a->random_int(3,4);

?>
